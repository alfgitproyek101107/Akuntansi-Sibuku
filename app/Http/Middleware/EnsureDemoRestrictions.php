<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureDemoRestrictions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user || !$user->demo_mode) {
            return $next($request);
        }

        // Demo user restrictions
        if ($this->isRestrictedAction($request)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'This action is not allowed in demo mode'
                ], 403);
            }

            return redirect()->back()->with('error', 'This action is not allowed in demo mode');
        }

        return $next($request);
    }

    /**
     * Check if the current request is a restricted action for demo users
     */
    protected function isRestrictedAction(Request $request): bool
    {
        $routeName = $request->route() ? $request->route()->getName() : null;
        $method = $request->method();

        // Restricted routes for demo users
        $restrictedRoutes = [
            // Branch management
            'branches.create',
            'branches.store',
            'branches.edit',
            'branches.update',
            'branches.destroy',

            // Company settings
            'settings.company',
            'settings.company.update',

            // User management (except self) - allow viewing users list
            'users.create',
            'users.store',
            'users.edit',
            'users.update',
            'users.destroy',

            // System settings
            'settings.system',
            'settings.system.update',

            // Export actions
            'reports.export',
            'transactions.export',
            'accounts.export',

            // System logs
            'logs.index',
            'logs.show',

            // Password changes for other users
            'users.change-password',
        ];

        // Check if route is restricted
        if (in_array($routeName, $restrictedRoutes)) {
            return true;
        }

        // Additional checks for specific actions
        if ($this->isDataModificationOutsideDemoBranch($request)) {
            return true;
        }

        return false;
    }

    /**
     * Check if user is trying to modify data outside demo branch
     */
    protected function isDataModificationOutsideDemoBranch(Request $request): bool
    {
        $user = Auth::user();
        $demoBranchId = 999; // Demo branch ID

        // Check if request contains branch_id parameter
        if ($request->has('branch_id') && $request->input('branch_id') != $demoBranchId) {
            return true;
        }

        // Check route parameters for branch
        $route = $request->route();
        if ($route && $route->hasParameter('branch') && $route->parameter('branch') != $demoBranchId) {
            return true;
        }

        // For API requests, check X-Branch-ID header
        if ($request->hasHeader('X-Branch-ID') && $request->header('X-Branch-ID') != $demoBranchId) {
            return true;
        }

        return false;
    }
}