<?php

namespace App\Models;

use App\Models\Concerns\BelongsToBranch;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use BelongsToBranch, BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'name',
        'dialer_mode',
        'status',
        'caller_id',
        'total_leads',
        'completed_leads',
        'connection_rate',
        'conversion_rate',
        'settings',
    ];

    protected $casts = [
        'connection_rate' => 'decimal:2',
        'conversion_rate' => 'decimal:2',
        'settings' => 'array',
    ];

    public function leads(): HasMany
    {
        return $this->hasMany(CampaignLead::class);
    }
}
