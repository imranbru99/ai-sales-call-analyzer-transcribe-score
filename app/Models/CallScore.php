<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CallScore extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'call_id',
        'scorecard_id',
        'tenant_id',
        'scored_by',
        'evaluator_id',
        'total_score',
        'passed',
        'has_critical_failure',
        'answers',
        'dispute_status',
        'dispute_comment',
    ];

    protected $casts = [
        'total_score' => 'decimal:2',
        'passed' => 'boolean',
        'has_critical_failure' => 'boolean',
        'answers' => 'array',
    ];

    public function call(): BelongsTo
    {
        return $this->belongsTo(Call::class);
    }

    public function scorecard(): BelongsTo
    {
        return $this->belongsTo(Scorecard::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }
}
