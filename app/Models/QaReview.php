<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QaReview extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'call_id',
        'tenant_id',
        'analyst_id',
        'calibration_session_id',
        'status',
        'score',
        'notes',
        'feedback_to_agent',
        'completed_at',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    public function call(): BelongsTo
    {
        return $this->belongsTo(Call::class);
    }

    public function analyst(): BelongsTo
    {
        return $this->belongsTo(User::class, 'analyst_id');
    }
}
