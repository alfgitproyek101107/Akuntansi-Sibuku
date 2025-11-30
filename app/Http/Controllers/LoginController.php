<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $maxLoginAttempts = 5;
    protected $lockoutDuration = 15; // minutes

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        // Check if user exists and is locked
        if ($user && $user->isLocked()) {
            $remainingTime = $user->getRemainingLockoutTime();
            return back()->withErrors([
                'email' => "Akun terkunci. Coba lagi dalam {$remainingTime} menit."
            ])->withInput(['email' => $request->email]);
        }

        // Check if user exists and has exceeded max attempts
        if ($user && $user->hasExceededMaxLoginAttempts($this->maxLoginAttempts)) {
            $user->lockAccount($this->lockoutDuration);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login gagal. Akun terkunci selama {$this->lockoutDuration} menit."
            ])->withInput(['email' => $request->email]);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Reset failed attempts on successful login
            if ($user) {
                $user->resetFailedLoginAttempts();
            }

            // Log successful login
            ActivityLog::logLogin($user, 'success');

            $request->session()->regenerate();

            // Set default branch for user if not already set
            if (!session()->has('active_branch')) {
                $defaultBranch = \App\Http\Middleware\BranchIsolation::getUserDefaultBranch($user);
                if ($defaultBranch) {
                    session(['active_branch' => $defaultBranch->id]);
                }
            }

            return redirect()->intended('/dashboard');
        }

        // Increment failed attempts
        if ($user) {
            $user->incrementFailedLoginAttempts();

            // Log failed login attempt
            ActivityLog::logLogin($user, 'failed');

            // Check if this attempt triggers lockout
            if ($user->hasExceededMaxLoginAttempts($this->maxLoginAttempts)) {
                $user->lockAccount($this->lockoutDuration);
                return back()->withErrors([
                    'email' => "Terlalu banyak percobaan login gagal. Akun terkunci selama {$this->lockoutDuration} menit."
                ])->withInput(['email' => $request->email]);
            }
        } else {
            // Log failed login for non-existent user
            ActivityLog::log('login_failed', 'Failed login attempt for non-existent user', [
                'attempted_email' => $request->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.'
        ])->withInput(['email' => $request->email]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        // Log logout before destroying session
        if ($user) {
            ActivityLog::logLogout($user);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
