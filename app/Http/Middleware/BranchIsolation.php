<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Branch;
use Symfony\Component\HttpFoundation\Response;

class BranchIsolation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        // Skip branch isolation for certain routes
        if ($this->shouldSkipBranchIsolation($request)) {
            return $next($request);
        }

        // Super admin bypass - allow access to all routes
        if ($this->isSuperAdmin($user)) {
            // Set a default branch for super admin if not set
            if (!session()->has('active_branch')) {
                $defaultBranch = $this->getUserDefaultBranch($user);
                if ($defaultBranch) {
                    session(['active_branch' => $defaultBranch->id]);
                }
            }
            return $next($request);
        }

        // Get current branch from session or request
        $currentBranchId = $this->getCurrentBranchId($request);

        if (!$currentBranchId) {
            // If no branch is set, try to auto-select for users with single branch
            $userBranches = $this->getUserBranches($user);

            if ($userBranches->count() === 1) {
                // Auto-select the only available branch
                $branch = $userBranches->first();
                $currentBranchId = $branch->id;
                session(['active_branch' => $currentBranchId]);
            } else {
                // Multiple branches available, redirect to selection
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Branch not selected'], 400);
                }
                return redirect()->route('branch.select')->with('warning', 'Silakan pilih cabang terlebih dahulu');
            }
        }

        // Check if user has access to this branch
        if (!$this->userHasBranchAccess($user, $currentBranchId)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized branch access'], 403);
            }
            abort(403, 'Anda tidak memiliki akses ke cabang ini');
        }

        // Set branch context for the request
        $request->merge(['active_branch' => $currentBranchId]);

        // Add branch to session for future requests
        session(['active_branch' => $currentBranchId]);

        return $next($request);
    }

    /**
     * Check if branch isolation should be skipped for this request
     */
    protected function shouldSkipBranchIsolation(Request $request): bool
    {
        $currentRoute = $request->route();

        // Skip by route name if available
        if ($currentRoute) {
            $routeName = $currentRoute->getName();
            $skipRoutes = [
                'branch.select',
                'branch.set',
                'logout',
            ];

            if (in_array($routeName, $skipRoutes)) {
                return true;
            }
        }

        // Skip by path pattern for routes that might not have names
        $path = $request->path();
        $skipPaths = [
            'logout',
            'login',
            'register',
        ];

        foreach ($skipPaths as $skipPath) {
            if (str_contains($path, $skipPath)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get current branch ID from request or session
     */
    protected function getCurrentBranchId(Request $request): ?int
    {
        // Check route parameter first
        if ($request->route('branch')) {
            return $request->route('branch');
        }

        // Check request parameter
        if ($request->has('branch_id')) {
            return $request->input('branch_id');
        }

        // Check session
        if (session()->has('active_branch')) {
            return session('active_branch');
        }

        // Check header (for API requests)
        if ($request->hasHeader('X-Branch-ID')) {
            return $request->header('X-Branch-ID');
        }

        return null;
    }

    /**
     * Check if user is super admin
     */
    protected function isSuperAdmin($user): bool
    {
        return $user->hasRole('super-admin') || ($user->userRole && $user->userRole->name === 'super_admin');
    }

    /**
     * Check if user has access to the branch
     */
    protected function userHasBranchAccess($user, $branchId): bool
    {
        // Super admin can access all branches
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        // Check if user is assigned to this branch
        return $user->branches()
            ->where('branches.id', $branchId)
            ->where('user_branches.is_active', true)
            ->exists();
    }

    /**
     * Get user's accessible branches
     */
    public static function getUserBranches($user)
    {
        if ($user->hasRole('super-admin') || ($user->userRole && $user->userRole->name === 'super_admin')) {
            return Branch::active()->get();
        }

        return $user->branches()
            ->where('user_branches.is_active', true)
            ->get();
    }

    /**
     * Get user's default branch
     */
    public static function getUserDefaultBranch($user)
    {
        if ($user->hasRole('super-admin') || ($user->userRole && $user->userRole->name === 'super_admin')) {
            return Branch::active()->where('is_head_office', true)->first()
                ?? Branch::active()->first();
        }

        return $user->branches()
            ->where('user_branches.is_active', true)
            ->where('user_branches.is_default', true)
            ->first()
            ?? $user->branches()
                ->where('user_branches.is_active', true)
                ->first();
    }
}
