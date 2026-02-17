<?php

namespace App\Http\Middleware;

use App\Models\SystemSetting;
use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->routeIs('login') || $request->routeIs('logout')) {
            return $next($request);
        }

        $user = $request->user();

        if ($user && ($user->isSuperAdmin() || $user->isAdmin())) {
            return $next($request);
        }

        $systemSetting = SystemSetting::first();

        if ($systemSetting->maintenance_mode) {
            return redirect()->route('maintenance.index');
        }

        return $next($request);
    }
}
