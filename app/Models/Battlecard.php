<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Battlecard extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'competitor_name',
        'slug',
        'competitor_strengths',
        'our_advantages',
        'counter_points',
        'pricing_intel',
        'auto_researched_at',
    ];

    protected $casts = [
        'counter_points' => 'array',
        'auto_researched_at' => 'datetime',
    ];
}
