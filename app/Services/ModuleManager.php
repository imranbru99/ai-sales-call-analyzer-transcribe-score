<?php

namespace App\Services;

use App\Models\Module;
use App\Models\Tenant;
use App\Models\TenantModule;
use App\Services\Tenancy\TenantContext;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ModuleManager
{
    /**
     * Determine if a module is enabled for a given tenant or the current tenant.
     */
    public function enabled(string $moduleKey, ?Tenant $tenant = null): bool
    {
        $tenant = $tenant ?? TenantContext::getTenant();

        // If no tenant context (e.g. platform admin or public guest), check core status
        $config = config("modules.modules.{$moduleKey}");
        if (! $config) {
            return false;
        }

        if (! empty($config['is_core'])) {
            return true;
        }

        if (! $tenant) {
            return true;
        }

        return Cache::remember("tenant_{$tenant->id}_module_{$moduleKey}", 300, function () use ($tenant, $moduleKey) {
            $tm = TenantModule::where('tenant_id', $tenant->id)
                ->where('module_key', $moduleKey)
                ->first();

            return $tm ? (bool) $tm->enabled : false;
        });
    }

    /**
     * Get all registered modules.
     */
    public function all(): Collection
    {
        return Module::orderBy('sort_order')->get();
    }

    /**
     * Enable a module for a tenant, auto-resolving dependencies.
     */
    public function enable(string $moduleKey, ?Tenant $tenant = null, array $settings = []): array
    {
        $tenant = $tenant ?? TenantContext::getTenant();
        if (! $tenant) {
            return [];
        }

        $config = config("modules.modules.{$moduleKey}");
        $autoEnabled = [];

        // Auto-enable missing dependencies
        if ($config && ! empty($config['dependencies'])) {
            foreach ($config['dependencies'] as $dependencyKey) {
                if (! $this->enabled($dependencyKey, $tenant)) {
                    $this->enable($dependencyKey, $tenant);
                    $autoEnabled[] = $dependencyKey;
                }
            }
        }

        TenantModule::updateOrCreate(
            ['tenant_id' => $tenant->id, 'module_key' => $moduleKey],
            [
                'enabled' => true,
                'settings' => $settings,
                'enabled_at' => now(),
            ]
        );

        Cache::forget("tenant_{$tenant->id}_module_{$moduleKey}");

        return $autoEnabled;
    }

    /**
     * Disable a module for a tenant and check for dependents.
     */
    public function disable(string $moduleKey, ?Tenant $tenant = null): array
    {
        $tenant = $tenant ?? TenantContext::getTenant();
        if (! $tenant) {
            return [];
        }

        $dependents = $this->getDependents($moduleKey, $tenant);

        TenantModule::updateOrCreate(
            ['tenant_id' => $tenant->id, 'module_key' => $moduleKey],
            ['enabled' => false]
        );

        Cache::forget("tenant_{$tenant->id}_module_{$moduleKey}");

        return $dependents;
    }

    /**
     * Return list of active modules depending on this module.
     */
    public function getDependents(string $moduleKey, ?Tenant $tenant = null): array
    {
        $tenant = $tenant ?? TenantContext::getTenant();
        $dependents = [];
        $modules = config('modules.modules', []);

        foreach ($modules as $key => $meta) {
            if (in_array($moduleKey, $meta['dependencies'] ?? [])) {
                if ($this->enabled($key, $tenant)) {
                    $dependents[] = $key;
                }
            }
        }

        return $dependents;
    }

    /**
     * Apply a preset configuration to a tenant.
     */
    public function applyPreset(string $presetKey, Tenant $tenant): void
    {
        $preset = config("modules.presets.{$presetKey}");
        if (! $preset) {
            return;
        }

        $allModules = config('modules.modules', []);
        $enabledKeys = $preset['modules'];

        foreach ($allModules as $key => $meta) {
            $isEnabled = in_array($key, $enabledKeys);
            TenantModule::updateOrCreate(
                ['tenant_id' => $tenant->id, 'module_key' => $key],
                [
                    'enabled' => $isEnabled,
                    'enabled_at' => $isEnabled ? now() : null,
                ]
            );
            Cache::forget("tenant_{$tenant->id}_module_{$key}");
        }

        $tenant->update(['business_type' => $presetKey]);
    }

    /**
     * Synchronize database table from config/modules.php.
     */
    public function syncCatalog(): void
    {
        $catalog = config('modules.modules', []);
        $order = 1;

        foreach ($catalog as $key => $data) {
            Module::updateOrCreate(
                ['key' => $key],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'category' => $data['category'],
                    'price' => $data['price'],
                    'dependencies' => $data['dependencies'],
                    'is_core' => $data['is_core'],
                    'icon' => $data['icon'] ?? null,
                    'sort_order' => $order++,
                ]
            );
        }
    }
}
