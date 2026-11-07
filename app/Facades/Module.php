<?php

namespace App\Facades;

use App\Services\ModuleManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static bool enabled(string $moduleKey, ?\App\Models\Tenant $tenant = null)
 * @method static \Illuminate\Support\Collection all()
 * @method static array enable(string $moduleKey, ?\App\Models\Tenant $tenant = null, array $settings = [])
 * @method static array disable(string $moduleKey, ?\App\Models\Tenant $tenant = null)
 * @method static array getDependents(string $moduleKey, ?\App\Models\Tenant $tenant = null)
 * @method static void applyPreset(string $presetKey, \App\Models\Tenant $tenant)
 * @method static void syncCatalog()
 *
 * @see ModuleManager
 */
class Module extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ModuleManager::class;
    }
}
