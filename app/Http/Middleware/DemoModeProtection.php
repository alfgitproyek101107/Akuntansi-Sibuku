<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DemoModeProtection
{
    /**
     * Operations that are blocked in demo mode
     */
    private const BLOCKED_OPERATIONS = [
        'DELETE' => [
            'users.destroy',
            'branches.destroy',
            'accounts.destroy',
            'categories.destroy',
            'products.destroy',
            'customers.destroy',
        ],
        'POST' => [
            'settings.backup',
            'settings.clearLogs',
            'settings.system/backup',
            'settings.system/clear-logs',
        ],
        'PUT' => [
            'settings.updateSystem',
            'settings.system',
        ],
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Skip if no authenticated user
        if (!$user) {
            return $next($request);
        }

        // Check if user is in demo mode
        if ($user->demo_mode) {
            // Check for blocked operations
            if ($this->isBlockedOperation($request)) {
                return $this->blockDemoOperation($request);
            }

            // Check for real business connections
            if ($this->isRealBusinessConnection($request)) {
                return $this->handleRealBusinessConnection();
            }

            // Add demo mode header for frontend
            $request->headers->set('X-Demo-Mode', 'true');
        }

        return $next($request);
    }

    /**
     * Check if the current operation is blocked in demo mode
     */
    private function isBlockedOperation(Request $request): bool
    {
        $method = $request->method();
        $routeName = $request->route() ? $request->route()->getName() : null;

        // Check route-based blocking
        if (isset(self::BLOCKED_OPERATIONS[$method]) && in_array($routeName, self::BLOCKED_OPERATIONS[$method])) {
            return true;
        }

        // Check URL-based blocking for system operations
        $path = $request->path();
        $blockedPaths = [
            'settings/system/backup',
            'settings/system/clear-logs',
            'settings/system/maintenance',
        ];

        foreach ($blockedPaths as $blockedPath) {
            if (str_contains($path, $blockedPath)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Block demo operation with appropriate response
     */
    private function blockDemoOperation(Request $request): Response
    {
        // Log the blocked attempt
        Log::warning('Demo mode operation blocked', [
            'user_id' => Auth::id(),
            'route' => $request->route() ? $request->route()->getName() : $request->path(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Return JSON response for AJAX requests
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Operasi ini tidak diizinkan dalam mode demo.',
                'demo_mode' => true,
                'error_code' => 'DEMO_MODE_BLOCKED'
            ], 403);
        }

        // Return redirect with error for regular requests
        return redirect()->back()->with('error',
            'Operasi ini tidak diizinkan dalam mode demo. ' .
            'Data demo akan direset secara otomatis setiap 24 jam.'
        );
    }

    /**
     * Check if this looks like a real business connection
     */
    private function isRealBusinessConnection(Request $request): bool
    {
        // Check for multiple branch connections (real businesses often have multiple branches)
        $user = Auth::user();
        $branchCount = $user->branches()->count();

        if ($branchCount > 3) {
            return true;
        }

        // Check for high volume of transactions (real businesses have more data)
        $transactionCount = $user->transactions()->count();
        if ($transactionCount > 1000) {
            return true;
        }

        // Check for custom settings (real businesses customize their setup)
        // Note: AppSettings are global, so we check total settings count instead
        $totalSettings = \App\Models\AppSetting::count();
        if ($totalSettings > 50) { // Higher threshold since settings are global
            return true;
        }

        // Check for uploaded files (real businesses upload their own documents)
        $uploadedFiles = \Illuminate\Support\Facades\Storage::disk('public')
            ->files("uploads/{$user->id}");
        if (count($uploadedFiles) > 5) {
            return true;
        }

        return false;
    }

    /**
     * Handle real business connection in demo mode
     */
    private function handleRealBusinessConnection(): Response
    {
        // Log the real business connection attempt
        Log::warning('Real business connection detected in demo mode', [
            'user_id' => Auth::id(),
            'email' => Auth::user()->email,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Force logout
        Auth::logout();

        // Clear session
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        // Return to login with warning
        return redirect('/login')->with('warning',
            'Akun demo Anda telah terdeteksi memiliki data bisnis nyata. ' .
            'Untuk keamanan, Anda telah dilogout. ' .
            'Silakan daftar akun baru untuk bisnis Anda.'
        );
    }
}
