<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoachingAction extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'coaching_plan_id',
        'tenant_id',
        'agent_id',
        'call_id',
        'title',
        'description',
        'due_date',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(CoachingPlan::class, 'coaching_plan_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function call(): BelongsTo
    {
        return $this->belongsTo(Call::class);
    }
}
