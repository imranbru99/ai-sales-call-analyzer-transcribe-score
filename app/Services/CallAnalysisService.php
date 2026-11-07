<?php

namespace App\Services;

use App\Models\Call;
use App\Models\CallScore;
use App\Models\ComplianceRule;
use App\Models\ComplianceViolation;
use App\Models\Scorecard;
use App\Models\User;
use ImranDevBd\AiHub\Facades\AIHub;

class CallAnalysisService
{
    public function __construct(
        protected DemoAiDriver $demoAiDriver
    ) {}

    /**
     * Run full end-to-end analysis on a call.
     */
    public function analyze(Call $call): Call
    {
        // 1. Fetch deterministic or live AI analysis
        $analysis = $this->resolveAnalysis($call);

        // 2. Score against scorecard if available
        $scorecard = $call->scorecard ?? Scorecard::where('tenant_id', $call->tenant_id)->where('is_active', true)->first();
        if ($scorecard instanceof Scorecard) {
            $call->scorecard_id = $scorecard->id;
            $this->evaluateScorecard($call, $scorecard, $analysis);
        }

        // 3. Update Call with intelligence metrics
        $call->update([
            'status' => 'scored',
            'overall_score' => $analysis['overall_score'],
            'sentiment_label' => $analysis['sentiment_label'],
            'sentiment_score' => $analysis['sentiment_score'],
            'outcome' => $analysis['outcome'],
            'deal_likelihood_score' => $analysis['deal_likelihood_score'],
            'talk_time_agent_sec' => $analysis['talk_time_agent_sec'],
            'talk_time_customer_sec' => $analysis['talk_time_customer_sec'],
            'silence_time_sec' => $analysis['silence_time_sec'],
            'talk_listen_ratio' => $analysis['talk_listen_ratio'],
            'words_per_minute' => $analysis['words_per_minute'],
            'summary' => $analysis['summary'],
            'executive_summary' => $analysis['executive_summary'],
            'action_items' => $analysis['action_items'],
            'key_moments' => $analysis['key_moments'],
            'topics' => $analysis['topics'],
            'buying_signals' => $analysis['buying_signals'],
            'churn_risks' => $analysis['churn_risks'],
            'objections' => $analysis['objections'],
            'questions_asked' => $analysis['questions_asked'],
            'competitor_mentions' => $analysis['competitor_mentions'],
            'pricing_mentions' => $analysis['pricing_mentions'],
            'methodology_scores' => $analysis['methodology_scores'],
            'script_adherence_score' => $analysis['script_adherence_score'],
            'scorecard_breakdown' => $analysis['scorecard_breakdown'],
            'ai_provider_used' => $analysis['ai_provider_used'],
            'ai_cost' => $analysis['ai_cost'],
        ]);

        // 4. Scan transcript for compliance violations
        $this->scanCompliance($call);

        return $call->fresh();
    }

    protected function resolveAnalysis(Call $call): array
    {
        // Check if an AI provider has an active key configured
        $hasKey = config('ai-hub.providers.openai.api_key') || config('ai-hub.providers.gemini.api_key');

        if ($hasKey && ! app()->environment('testing')) {
            try {
                $agentName = $call->agent instanceof User ? $call->agent->name : 'Sales Agent';
                $prompt = "Analyze this sales call: Title '{$call->title}', Agent '{$agentName}'. Provide overall score (0-100), summary, key objections, and sentiment.";
                $response = AIHub::prompt($prompt)->asJsonObject()->generate();
                $data = $response->json();

                if (is_array($data) && isset($data['overall_score'])) {
                    return array_merge($this->demoAiDriver->analyze($call->title, $call->duration_seconds), $data);
                }
            } catch (\Throwable $e) {
                // Graceful fallback to deterministic engine
            }
        }

        return $this->demoAiDriver->analyze($call->title, $call->duration_seconds ?: 180, $call->call_type ?? 'sales_demo');
    }

    protected function evaluateScorecard(Call $call, Scorecard $scorecard, array $analysis): CallScore
    {
        $criteria = $scorecard->criteria ?? [];
        $totalEarned = 0;
        $totalWeight = 0;
        $hasCriticalFail = false;
        $answers = [];

        foreach ($criteria as $item) {
            $key = $item['key'] ?? 'general';
            $weight = (float) ($item['weight'] ?? 10);
            $breakdown = $analysis['scorecard_breakdown'][$key] ?? null;
            $earned = $breakdown ? (float) $breakdown['score'] : 85.0;

            if (! empty($item['is_critical']) && $earned < 50.0) {
                $hasCriticalFail = true;
            }

            $totalEarned += ($earned * ($weight / 100));
            $totalWeight += $weight;

            $answers[] = [
                'criterion' => $item['title'] ?? ucfirst($key),
                'score' => $earned,
                'weight' => $weight,
                'feedback' => $breakdown['notes'] ?? 'Good execution.',
            ];
        }

        $finalScore = $hasCriticalFail ? 0.00 : round($totalEarned, 2);
        $passed = $finalScore >= (float) $scorecard->pass_threshold;

        return CallScore::updateOrCreate(
            ['call_id' => $call->id, 'scorecard_id' => $scorecard->id],
            [
                'tenant_id' => $call->tenant_id,
                'scored_by' => 'ai',
                'total_score' => $finalScore,
                'passed' => $passed,
                'has_critical_failure' => $hasCriticalFail,
                'answers' => $answers,
            ]
        );
    }

    protected function scanCompliance(Call $call): void
    {
        $rules = ComplianceRule::where('tenant_id', $call->tenant_id)->where('is_active', true)->get();
        if ($rules->isEmpty()) {
            return;
        }

        $segments = $call->segments;
        if ($segments->isEmpty()) {
            return;
        }

        $fullTranscript = $segments->pluck('text')->implode(' ');

        foreach ($rules as $rule) {
            $phrases = $rule->phrases ?? [];
            if ($rule->rule_type === 'forbidden_phrase') {
                foreach ($phrases as $phrase) {
                    if (stripos($fullTranscript, $phrase) !== false) {
                        ComplianceViolation::firstOrCreate(
                            ['call_id' => $call->id, 'compliance_rule_id' => $rule->id],
                            [
                                'tenant_id' => $call->tenant_id,
                                'agent_id' => $call->agent_id,
                                'severity' => $rule->severity,
                                'quote' => "Forbidden phrase detected: '{$phrase}'",
                                'remediation_status' => 'flagged',
                            ]
                        );
                    }
                }
            } elseif ($rule->rule_type === 'required_phrase' || $rule->rule_type === 'mandatory_disclosure') {
                $found = false;
                foreach ($phrases as $phrase) {
                    if (stripos($fullTranscript, $phrase) !== false) {
                        $found = true;
                        break;
                    }
                }

                if (! $found) {
                    ComplianceViolation::firstOrCreate(
                        ['call_id' => $call->id, 'compliance_rule_id' => $rule->id],
                        [
                            'tenant_id' => $call->tenant_id,
                            'agent_id' => $call->agent_id,
                            'severity' => $rule->severity,
                            'quote' => 'Mandatory disclosure omitted from call opening.',
                            'remediation_status' => 'flagged',
                        ]
                    );
                }
            }
        }
    }
}
