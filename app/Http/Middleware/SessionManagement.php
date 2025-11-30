<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SessionManagement
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only apply to authenticated users
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Check if user account is locked
        if ($user->isLocked()) {
            Auth::logout();
            Session::flush();
            return redirect('/login')->withErrors([
                'email' => 'Akun Anda terkunci karena terlalu banyak percobaan login gagal.'
            ]);
        }

        // Check for password change - invalidate other sessions
        $this->checkPasswordChange($user);

        // Check session timeout (30 minutes of inactivity)
        $this->checkSessionTimeout($request);

        // Update last activity timestamp
        Session::put('last_activity', now()->timestamp);

        return $next($request);
    }

    /**
     * Check if password has been changed and invalidate other sessions
     *
     * @param \App\Models\User $user
     * @return void
     */
    private function checkPasswordChange($user): void
    {
        $sessionPasswordHash = Session::get('password_hash');

        if (!$sessionPasswordHash) {
            // Store current password hash in session
            Session::put('password_hash', $user->password);
        } elseif ($sessionPasswordHash !== $user->password) {
            // Password has been changed, invalidate this session
            Auth::logout();
            Session::flush();
            abort(redirect('/login')->withErrors([
                'email' => 'Password telah diubah. Silakan login kembali.'
            ]));
        }
    }

    /**
     * Check for session timeout based on inactivity
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    private function checkSessionTimeout(Request $request): void
    {
        $lastActivity = Session::get('last_activity');
        $timeout = config('session.lifetime', 120) * 60; // Convert to seconds

        if ($lastActivity && (now()->timestamp - $lastActivity) > $timeout) {
            // Session has expired due to inactivity
            Auth::logout();
            Session::flush();
            abort(redirect('/login')->withErrors([
                'email' => 'Sesi telah berakhir karena tidak aktif. Silakan login kembali.'
            ]));
        }
    }
}
