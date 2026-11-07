<?php

namespace App\Http\Middleware;

use App\Facades\Module;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureModuleEnabled
{
    public function handle(Request $request, Closure $next, string $moduleKey): Response
    {
        if (! Module::enabled($moduleKey)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "The module '{$moduleKey}' is disabled for this organization.",
                    'module' => $moduleKey,
                ], 403);
            }

            abort(403, "The requested feature ({$moduleKey}) is currently disabled in your plan or business settings.");
        }

        return $next($request);
    }
}
