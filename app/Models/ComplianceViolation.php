<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplianceViolation extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'call_id',
        'compliance_rule_id',
        'tenant_id',
        'agent_id',
        'severity',
        'quote',
        'timestamp_sec',
        'remediation_status',
        'remediation_notes',
    ];

    protected $casts = [
        'timestamp_sec' => 'decimal:2',
    ];

    public function call(): BelongsTo
    {
        return $this->belongsTo(Call::class);
    }

    public function rule(): BelongsTo
    {
        return $this->belongsTo(ComplianceRule::class, 'compliance_rule_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}
