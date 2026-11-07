<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'provider',
        'is_enabled',
        'credentials',
        'settings',
        'health_status',
        'last_synced_at',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'credentials' => 'array',
        'settings' => 'array',
        'last_synced_at' => 'datetime',
    ];
}
