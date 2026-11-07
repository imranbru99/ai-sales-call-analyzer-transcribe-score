<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Script extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'title',
        'slug',
        'call_type',
        'version',
        'content',
        'objection_flow',
        'is_active',
    ];

    protected $casts = [
        'objection_flow' => 'array',
        'is_active' => 'boolean',
    ];
}
