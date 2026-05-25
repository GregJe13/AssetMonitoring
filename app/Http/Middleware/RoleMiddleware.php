<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Role hierarchy from highest to lowest.
     * A higher role automatically inherits all access from lower roles.
     * 
     * Modify this array to add/reorder roles.
     */
    private const ROLE_HIERARCHY = [
        'admin',    // Highest — can access everything
        'manager',  // Can access manager + worker + guest pages
        'worker',   // Can access worker + guest pages
        'guest',    // Lowest — view only (dashboard & assets)
    ];

    /**
     * Handle an incoming request.
     * 
     * Usage in routes:
     *   middleware('role:user')    → user, manager, and admin can access
     *   middleware('role:manager') → only manager and admin can access
     *   middleware('role:admin')   → only admin can access
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Minimum required roles (any one match grants access)
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $userLevel = $this->getRoleLevel($user->role);

        // User passes if their role level is <= any of the required role levels
        // (lower number = higher authority)
        foreach ($roles as $role) {
            $requiredLevel = $this->getRoleLevel($role);
            if ($userLevel <= $requiredLevel) {
                return $next($request);
            }
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }

    /**
     * Get the hierarchy level of a role (lower = more authority).
     */
    private function getRoleLevel(string $role): int
    {
        $index = array_search($role, self::ROLE_HIERARCHY);
        
        // Unknown roles get the lowest priority (placed at bottom)
        return $index !== false ? $index : count(self::ROLE_HIERARCHY);
    }
}

