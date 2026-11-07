<?php

namespace App\Models;

use App\Models\Concerns\BelongsToBranch;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

/**
 * @property User|null $agent
 * @property Scorecard|null $scorecard
 */
class Call extends Model
{
    use BelongsToBranch, BelongsToTenant, HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'tenant_id',
        'branch_id',
        'team_id',
        'agent_id',
        'customer_id',
        'deal_id',
        'campaign_id',
        'scorecard_id',
        'title',
        'call_type',
        'direction',
        'source',
        'caller_number',
        'callee_number',
        'caller_name',
        'callee_name',
        'status',
        'duration_seconds',
        'audio_url',
        'audio_path',
        'audio_disk',
        'audio_format',
        'audio_size_bytes',
        'waveform_data',
        'consent_recorded',
        'jurisdiction',
        'external_call_id',
        'language',
        'overall_score',
        'sentiment_label',
        'sentiment_score',
        'outcome',
        'deal_likelihood_score',
        'talk_time_agent_sec',
        'talk_time_customer_sec',
        'silence_time_sec',
        'talk_listen_ratio',
        'words_per_minute',
        'summary',
        'executive_summary',
        'action_items',
        'key_moments',
        'topics',
        'buying_signals',
        'churn_risks',
        'objections',
        'questions_asked',
        'competitor_mentions',
        'pricing_mentions',
        'methodology_scores',
        'script_adherence_score',
        'scorecard_breakdown',
        'ai_provider_used',
        'ai_cost',
        'pii_redacted',
        'retention_until',
        'is_starred',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'audio_size_bytes' => 'integer',
        'consent_recorded' => 'boolean',
        'overall_score' => 'decimal:2',
        'sentiment_score' => 'decimal:2',
        'deal_likelihood_score' => 'integer',
        'talk_time_agent_sec' => 'integer',
        'talk_time_customer_sec' => 'integer',
        'silence_time_sec' => 'integer',
        'talk_listen_ratio' => 'decimal:2',
        'words_per_minute' => 'integer',
        'script_adherence_score' => 'decimal:2',
        'ai_cost' => 'decimal:4',
        'pii_redacted' => 'boolean',
        'is_starred' => 'boolean',
        'retention_until' => 'datetime',
        'waveform_data' => 'array',
        'action_items' => 'array',
        'key_moments' => 'array',
        'topics' => 'array',
        'buying_signals' => 'array',
        'churn_risks' => 'array',
        'objections' => 'array',
        'questions_asked' => 'array',
        'competitor_mentions' => 'array',
        'pricing_mentions' => 'array',
        'methodology_scores' => 'array',
        'scorecard_breakdown' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (Call $call) {
            if (empty($call->uuid)) {
                $call->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return BelongsTo<Scorecard, $this>
     */
    public function scorecard(): BelongsTo
    {
        return $this->belongsTo(Scorecard::class);
    }

    public function segments(): HasMany
    {
        return $this->hasMany(TranscriptSegment::class)->orderBy('start_time');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(CallScore::class);
    }

    public function annotations(): HasMany
    {
        return $this->hasMany(CallAnnotation::class)->orderBy('timestamp_sec');
    }

    public function complianceViolations(): HasMany
    {
        return $this->hasMany(ComplianceViolation::class);
    }

    public function getAudioStreamUrl(): string
    {
        if (! empty($this->audio_url)) {
            return $this->audio_url;
        }

        if (! empty($this->audio_path)) {
            // Generate temporary signed route for private audio stream
            return URL::temporarySignedRoute(
                'calls.stream-audio',
                now()->addHours(2),
                ['call' => $this->id]
            );
        }

        return '';
    }

    public function isPassing(): bool
    {
        if ($this->overall_score === null) {
            return false;
        }

        $threshold = $this->scorecard ? (float) $this->scorecard->pass_threshold : 70.0;

        return $this->overall_score >= $threshold;
    }
}
