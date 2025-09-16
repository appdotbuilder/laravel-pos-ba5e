<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Owner has access to everything
        if ($user->isOwner()) {
            return $next($request);
        }

        // Check specific role access
        switch ($role) {
            case 'admin':
                if (!$user->hasAdminAccess()) {
                    abort(403, 'Access denied. Admin privileges required.');
                }
                break;
            case 'kasir':
                if (!in_array($user->role, ['owner', 'admin', 'kasir'])) {
                    abort(403, 'Access denied. Insufficient privileges.');
                }
                break;
            default:
                abort(403, 'Access denied.');
        }

        return $next($request);
    }
}