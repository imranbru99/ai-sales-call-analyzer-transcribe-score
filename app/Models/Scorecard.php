<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scorecard extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'call_type',
        'description',
        'is_active',
        'pass_threshold',
        'criteria',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'pass_threshold' => 'decimal:2',
        'criteria' => 'array',
    ];

    public function calls(): HasMany
    {
        return $this->hasMany(Call::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(CallScore::class);
    }
}
