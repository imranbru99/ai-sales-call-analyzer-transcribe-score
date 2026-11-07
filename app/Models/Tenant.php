<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'domain',
        'business_type',
        'status',
        'plan_id',
        'trial_ends_at',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
        'trial_ends_at' => 'datetime',
    ];

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function headOffice(): HasOne
    {
        return $this->hasOne(Branch::class)->where('is_head_office', true);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * @return HasMany<TenantModule, $this>
     */
    public function tenantModules(): HasMany
    {
        return $this->hasMany(TenantModule::class);
    }

    public function calls(): HasMany
    {
        return $this->hasMany(Call::class);
    }

    public function isModuleEnabled(string $moduleKey): bool
    {
        // Core modules are always enabled
        $config = config("modules.modules.{$moduleKey}");
        if ($config && ! empty($config['is_core'])) {
            return true;
        }

        $record = $this->tenantModules()->where('module_key', $moduleKey)->first();

        return $record ? (bool) $record->enabled : false;
    }

    public function enableModule(string $moduleKey, array $settings = []): void
    {
        $this->tenantModules()->updateOrCreate(
            ['module_key' => $moduleKey],
            [
                'enabled' => true,
                'settings' => $settings,
                'enabled_at' => now(),
            ]
        );
    }

    public function disableModule(string $moduleKey): void
    {
        $this->tenantModules()->updateOrCreate(
            ['module_key' => $moduleKey],
            ['enabled' => false]
        );
    }
}
