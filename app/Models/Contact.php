<?php

namespace App\Models;

use App\Models\Concerns\BelongsToBranch;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    use BelongsToBranch, BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'assigned_agent_id',
        'name',
        'email',
        'phone',
        'company_name',
        'job_title',
        'status',
        'lead_score',
        'enrichment_data',
        'custom_fields',
    ];

    protected $casts = [
        'lead_score' => 'integer',
        'enrichment_data' => 'array',
        'custom_fields' => 'array',
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_agent_id');
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }
}
