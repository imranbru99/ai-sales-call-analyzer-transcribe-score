<?php

namespace App\Services\Tenancy;

use App\Models\Branch;
use App\Models\Tenant;
use App\Models\User;

class TenantContext
{
    protected static ?Tenant $currentTenant = null;

    protected static ?Branch $currentBranch = null;

    public static function setTenant(?Tenant $tenant): void
    {
        static::$currentTenant = $tenant;
    }

    public static function getTenant(): ?Tenant
    {
        if (static::$currentTenant !== null) {
            return static::$currentTenant;
        }

        $user = auth()->user();
        if ($user instanceof User && $user->tenant instanceof Tenant) {
            static::$currentTenant = $user->tenant;

            return static::$currentTenant;
        }

        return null;
    }

    public static function getTenantId(): ?int
    {
        return static::getTenant()?->id;
    }

    public static function setBranch(?Branch $branch): void
    {
        static::$currentBranch = $branch;
    }

    public static function getBranch(): ?Branch
    {
        if (static::$currentBranch !== null) {
            return static::$currentBranch;
        }

        $user = auth()->user();
        if ($user instanceof User && $user->branch instanceof Branch) {
            static::$currentBranch = $user->branch;

            return static::$currentBranch;
        }

        return null;
    }

    public static function getBranchId(): ?int
    {
        return static::getBranch()?->id;
    }

    public static function clear(): void
    {
        static::$currentTenant = null;
        static::$currentBranch = null;
    }
}
