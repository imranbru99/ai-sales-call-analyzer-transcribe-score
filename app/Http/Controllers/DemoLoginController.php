<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Tenant;
use App\Models\User;
use App\Services\Tenancy\TenantContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class DemoLoginController extends Controller
{
    /**
     * One-click demo login for all 12 roles.
     */
    public function loginAs(string $role): RedirectResponse
    {
        $roleMap = [
            'super_admin' => 'superadmin@demo.test',
            'tenant_owner' => 'owner@demo.test',
            'branch_manager' => 'branch.manager@demo.test',
            'sales_manager' => 'manager@demo.test',
            'agent' => 'agent@demo.test',
            'qa_analyst' => 'qa@demo.test',
            'trainer' => 'trainer@demo.test',
            'compliance_officer' => 'compliance@demo.test',
            'finance' => 'finance@demo.test',
            'hr_officer' => 'hr@demo.test',
            'customer' => 'customer@demo.test',
            'reseller' => 'reseller@demo.test',
        ];

        $email = $roleMap[$role] ?? null;
        if (! $email) {
            abort(404, "Unknown demo role: {$role}");
        }

        $user = User::where('email', $email)->first();
        if (! $user) {
            abort(404, "Demo user for role '{$role}' not found. Please run seeders first.");
        }

        Auth::login($user);
        if ($user->tenant instanceof Tenant) {
            TenantContext::setTenant($user->tenant);
        }
        if ($user->branch instanceof Branch) {
            TenantContext::setBranch($user->branch);
        }

        $redirectUrl = match ($user->role) {
            'super_admin' => '/platform',
            'agent' => '/agent',
            'customer', 'reseller' => '/portal',
            default => '/app',
        };

        return redirect($redirectUrl);
    }
}
