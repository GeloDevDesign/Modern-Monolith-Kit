<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Check if specific roles are required
        if (! empty($roles)) {
            $roles = array_map('trim', $roles);
            $userRole = $user->type instanceof UserRole ? $user->type->value : $user->type;

            if (! in_array($userRole, $roles)) {
                return abort(403, 'Unauthorized action.');
            }
        }

        // If user is a standard User, enforce email verification
        if ($user->type === UserRole::TYPE_USER) {
            if (! $user->hasVerifiedEmail()) {
                return $request->expectsJson()
                    ? abort(403, 'Your email address is not verified.')
                    : Redirect::route('verification.notice');
            }
        }

        // Admin and Super Admin bypass verification check
        return $next($request);
    }
}
