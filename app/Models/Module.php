<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'description',
        'category',
        'price',
        'dependencies',
        'is_core',
        'icon',
        'sort_order',
    ];

    protected $casts = [
        'dependencies' => 'array',
        'is_core' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function tenantModules(): HasMany
    {
        return $this->hasMany(TenantModule::class, 'module_key', 'key');
    }
}
