<?php

namespace App\Services;

class DemoAiDriver
{
    /**
     * Generate deterministic analysis for a call.
     */
    public function analyze(string $title, int $durationSeconds = 240, ?string $callType = 'sales_demo'): array
    {
        $callType = $callType ?: 'sales_demo';
        $talkRatio = 1.15; // Agent spoke 53%, customer 47%
        $agentSec = (int) ($durationSeconds * 0.53);
        $customerSec = (int) ($durationSeconds * 0.42);
        $silenceSec = $durationSeconds - $agentSec - $customerSec;

        $overallScore = 88.5;
        $categoryBreakdown = [
            'greeting' => ['score' => 95, 'max' => 100, 'weight' => 10, 'notes' => 'Crisp introduction and agenda setting.'],
            'discovery' => ['score' => 88, 'max' => 100, 'weight' => 20, 'notes' => 'Uncovered budget and primary churn bottleneck.'],
            'active_listening' => ['score' => 90, 'max' => 100, 'weight' => 15, 'notes' => 'Summarized customer pain points accurately before presenting solution.'],
            'objection_handling' => ['score' => 85, 'max' => 100, 'weight' => 20, 'notes' => 'Successfully countered pricing objection with ROI calculation.'],
            'pitch_quality' => ['score' => 92, 'max' => 100, 'weight' => 15, 'notes' => 'Highlighted relevant enterprise case studies.'],
            'compliance' => ['score' => 100, 'max' => 100, 'weight' => 10, 'notes' => 'Recorded line disclosure stated within first 15 seconds.'],
            'closing' => ['score' => 82, 'max' => 100, 'weight' => 10, 'notes' => 'Solidified demo date for VP of Operations next Tuesday.'],
        ];

        $methodologies = [
            'BANT' => [
                'Budget' => 'Confirmed ($45k annual allocation reserved for Q4)',
                'Authority' => 'Decision maker (VP Sales) present with technical lead',
                'Need' => 'Needs 100% transcript search and QA scoring automation',
                'Timeline' => 'Deployment targeted before end of November',
            ],
            'MEDDIC' => [
                'Metrics' => 'Aims to reduce QA review time from 3 hours to 20 minutes',
                'Economic Buyer' => 'CFO approval pending final security questionnaire',
                'Decision Criteria' => 'Compliance with SOC2 and local data residency',
                'Decision Process' => 'Pilot demo -> Legal review -> Contract sign',
                'Identify Pain' => 'Losing deals due to slow agent response and script drift',
                'Champion' => 'Sales Enablement Director advocating for immediate rollout',
            ],
            'SPIN' => [
                'Situation' => 'Currently handling 8,000 monthly calls across 40 reps manually.',
                'Problem' => 'Only 2% of calls are QA-reviewed; compliance breaches go unnoticed.',
                'Implication' => 'Risk of regulatory fines and inconsistent closing percentages.',
                'Need-Payoff' => 'Automated AI scoring would cover 100% of calls instantly.',
            ],
        ];

        $actionItems = [
            ['task' => 'Send SOC2 compliance package and data encryption whitepaper', 'assignee' => 'Agent', 'due' => 'Tomorrow, 5:00 PM'],
            ['task' => 'Provide tiered enterprise pricing quote for 50 agents', 'assignee' => 'Agent', 'due' => 'Friday, 12:00 PM'],
            ['task' => 'Confirm calendar invite with VP of Sales for technical walkthrough', 'assignee' => 'Customer', 'due' => 'Next Monday'],
        ];

        $keyMoments = [
            ['timestamp' => '00:15', 'type' => 'compliance', 'title' => 'Recording Consent Stated', 'description' => 'Agent confirmed recording notice.'],
            ['timestamp' => '01:30', 'type' => 'pain_point', 'title' => 'Core Bottleneck Identified', 'description' => 'Customer reported 3-week QA review lag.'],
            ['timestamp' => '02:45', 'type' => 'objection', 'title' => 'Pricing Pushback Handled', 'description' => 'Addressed enterprise license budget concern.'],
            ['timestamp' => '03:40', 'type' => 'buying_signal', 'title' => 'Champion Commitment', 'description' => 'Customer asked for immediate sandbox access.'],
        ];

        $objections = [
            [
                'objection' => 'We currently have an existing contract with our PBX vendor for 6 more months.',
                'counter' => 'Highlighted native SIP & API connector that overlays existing telephony without contract termination.',
                'outcome' => 'Resolved satisfactorily.',
            ],
        ];

        $buyingSignals = [
            'How quickly can our 50 reps be onboarded into the platform?',
            'Does your team assist with custom scorecard migration?',
            'We have budget approved for implementation this quarter.',
        ];

        $summary = 'The prospect expressed urgent dissatisfaction with their current manual QA sampling process (under 2% review coverage). The agent effectively demonstrated how AI Sales Call Analyzer processes 100% of calls in minutes, detects compliance violations in real time, and integrates directly with their CRM. The prospect agreed to schedule a pilot demo with their VP next Tuesday.';

        $executiveSummary = 'High-probability enterprise deal ($45k ARR). Key decision makers engaged. Recommended action: Deliver custom pricing proposal and security docs by tomorrow.';

        return [
            'overall_score' => $overallScore,
            'sentiment_label' => 'positive',
            'sentiment_score' => 0.84,
            'outcome' => 'interested',
            'deal_likelihood_score' => 88,
            'talk_time_agent_sec' => $agentSec,
            'talk_time_customer_sec' => $customerSec,
            'silence_time_sec' => $silenceSec,
            'talk_listen_ratio' => $talkRatio,
            'words_per_minute' => 148,
            'summary' => $summary,
            'executive_summary' => $executiveSummary,
            'action_items' => $actionItems,
            'key_moments' => $keyMoments,
            'topics' => ['QA Automation', 'Compliance Disclosures', 'CRM Integration', 'Pricing & ROI'],
            'buying_signals' => $buyingSignals,
            'churn_risks' => [],
            'objections' => $objections,
            'questions_asked' => [
                'What is your target timeline for automating your QA process?',
                'Which telephony and CRM systems are currently in place?',
            ],
            'competitor_mentions' => ['Gong', 'Chorus'],
            'pricing_mentions' => ['$45,000 / year', 'per-seat license'],
            'methodology_scores' => $methodologies,
            'script_adherence_score' => 94.00,
            'scorecard_breakdown' => $categoryBreakdown,
            'ai_provider_used' => 'Demo AI Intelligence Engine',
            'ai_cost' => 0.0125,
        ];
    }

    /**
     * Generate realistic transcript segments with speaker diarization.
     */
    public function generateTranscript(string $agentName = 'Sarah Jenkins', string $customerName = 'Michael Vance'): array
    {
        return [
            [
                'speaker' => 'agent',
                'speaker_name' => $agentName,
                'start_time' => 0.0,
                'end_time' => 8.5,
                'text' => 'Hello Michael! This is Sarah from AI Sales Call Analyzer. This call is recorded for quality assurance. How are you today?',
                'sentiment' => 'positive',
                'sentiment_score' => 0.75,
                'confidence' => 0.98,
            ],
            [
                'speaker' => 'customer',
                'speaker_name' => $customerName,
                'start_time' => 9.0,
                'end_time' => 18.2,
                'text' => "Hi Sarah, doing well thanks. We've been looking for an automated solution to transcribe our sales calls because our team is growing quickly.",
                'sentiment' => 'neutral',
                'sentiment_score' => 0.40,
                'confidence' => 0.97,
            ],
            [
                'speaker' => 'agent',
                'speaker_name' => $agentName,
                'start_time' => 19.0,
                'end_time' => 32.5,
                'text' => 'That makes complete sense. Most teams we work with find that manual QA only covers about 2% of calls. What is your current call review workflow like?',
                'sentiment' => 'positive',
                'sentiment_score' => 0.80,
                'confidence' => 0.99,
            ],
            [
                'speaker' => 'customer',
                'speaker_name' => $customerName,
                'start_time' => 33.0,
                'end_time' => 52.0,
                'text' => 'Exactly that. Our team leads listen to maybe 3 or 4 calls per rep a month. We miss coaching opportunities, and recently had an unannounced compliance issue on disclosures.',
                'sentiment' => 'negative',
                'sentiment_score' => -0.45,
                'confidence' => 0.96,
            ],
            [
                'speaker' => 'agent',
                'speaker_name' => $agentName,
                'start_time' => 53.0,
                'end_time' => 74.0,
                'text' => 'With our automated compliance engine, every call is evaluated in real time against your exact phrase rules, whether disclosures, forbidden promises, or GDPR notices, with instant alerts to management.',
                'sentiment' => 'positive',
                'sentiment_score' => 0.88,
                'confidence' => 0.98,
            ],
            [
                'speaker' => 'customer',
                'speaker_name' => $customerName,
                'start_time' => 75.0,
                'end_time' => 92.0,
                'text' => 'That sounds very promising. What kind of pricing structure do you offer for around 50 sales agents across two branch offices?',
                'sentiment' => 'positive',
                'sentiment_score' => 0.70,
                'confidence' => 0.97,
            ],
            [
                'speaker' => 'agent',
                'speaker_name' => $agentName,
                'start_time' => 93.0,
                'end_time' => 115.0,
                'text' => 'We provide tiered team plans with multi-branch management included. I will send over our complete ROI breakdown and invite your team for a live pilot demonstration next Tuesday.',
                'sentiment' => 'positive',
                'sentiment_score' => 0.92,
                'confidence' => 0.99,
            ],
            [
                'speaker' => 'customer',
                'speaker_name' => $customerName,
                'start_time' => 116.0,
                'end_time' => 125.0,
                'text' => 'Sounds great Sarah. Please send the calendar invitation and we will review it with our VP. Thanks!',
                'sentiment' => 'positive',
                'sentiment_score' => 0.90,
                'confidence' => 0.98,
            ],
        ];
    }
}
