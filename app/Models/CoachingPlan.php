<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CoachingPlan extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'agent_id',
        'coach_id',
        'title',
        'status',
        'strengths',
        'gaps',
        'focus_areas',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'focus_areas' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function actions(): HasMany
    {
        return $this->hasMany(CoachingAction::class);
    }
}
