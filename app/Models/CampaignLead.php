<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignLead extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'campaign_id',
        'tenant_id',
        'contact_id',
        'assigned_agent_id',
        'call_id',
        'status',
        'attempts',
        'last_called_at',
        'next_callback_at',
        'outcome',
    ];

    protected $casts = [
        'attempts' => 'integer',
        'last_called_at' => 'datetime',
        'next_callback_at' => 'datetime',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_agent_id');
    }

    public function call(): BelongsTo
    {
        return $this->belongsTo(Call::class);
    }
}
