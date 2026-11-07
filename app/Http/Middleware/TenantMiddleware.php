<?php

namespace App\Http\Middleware;

use App\Models\Branch;
use App\Models\Tenant;
use App\Models\User;
use App\Services\Tenancy\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. From Authenticated User
        $user = auth()->user();
        if ($user instanceof User && $user->tenant instanceof Tenant) {
            TenantContext::setTenant($user->tenant);
            TenantContext::setBranch($user->branch instanceof Branch ? $user->branch : null);

            return $next($request);
        }

        // 2. From Subdomain or Custom Domain
        $host = $request->getHost();
        $tenant = Tenant::where('domain', $host)->first();

        if (! $tenant && str_contains($host, '.')) {
            $subdomain = explode('.', $host)[0];
            $tenant = Tenant::where('slug', $subdomain)->first();
        }

        // 3. From Request Header (API)
        if (! $tenant && $request->hasHeader('X-Tenant-ID')) {
            $tenant = Tenant::find($request->header('X-Tenant-ID'));
        }

        if ($tenant) {
            TenantContext::setTenant($tenant);
        }

        return $next($request);
    }
}
