<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Check if user has admin or super admin role
     */
    private function isAdminOrSuperAdmin($user = null)
    {
        $user = $user ?? Auth::user();
        if (!$user || !$user->userRole) {
            return false;
        }

        $roleName = $user->userRole->name;
        $normalizedRole = strtolower(str_replace([' ', '_'], '', $roleName));

        return in_array($normalizedRole, ['superadmin', 'admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $isAuthorized = $this->isAdminOrSuperAdmin($user);

        // Log for debugging
        $roleName = $user->userRole ? $user->userRole->name : 'NO_ROLE';
        \Log::info('UserController@index - User: ' . $user->email . ', Role: "' . $roleName . '", Authorized: ' . ($isAuthorized ? 'YES' : 'NO'));

        // Only super admin or admin can view users
        if (!$isAuthorized) {
            // For demo users, allow access to users page
            if ($user->demo_mode) {
                // Allow demo user to view users page
            } else {
                abort(403, 'Unauthorized - Role: ' . $roleName);
            }
        }

        $users = User::with(['userRole', 'branch'])->paginate(10);

        // Separate demo and regular users for better organization
        $demoUsers = $users->filter(function ($user) {
            return $user->demo_mode;
        });

        $regularUsers = $users->filter(function ($user) {
            return !$user->demo_mode;
        });

        return view('users.index', compact('users', 'demoUsers', 'regularUsers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only super admin or admin can create users
        if (!$this->isAdminOrSuperAdmin()) {
            abort(403, 'Unauthorized');
        }

        $roles = UserRole::all();
        $branches = \App\Models\Branch::all();

        return view('users.create', compact('roles', 'branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Only super admin or admin can create users
        if (!$this->isAdminOrSuperAdmin()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'user_role_id' => ['required', 'exists:user_roles,id'],
            'branch_id' => ['nullable', 'exists:branches,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_role_id' => $request->user_role_id,
            'branch_id' => $request->branch_id,
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Only super admin, admin, or the user themselves can view
        if (!$this->isAdminOrSuperAdmin() && Auth::id() !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $user->load(['userRole', 'branch', 'accounts', 'categories', 'transactions']);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Only super admin, admin, or the user themselves can edit
        if (!$this->isAdminOrSuperAdmin() && Auth::id() !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Regular users cannot change their own role
        $canEditRole = $this->isAdminOrSuperAdmin();

        $roles = UserRole::all();
        $branches = \App\Models\Branch::all();

        return view('users.edit', compact('user', 'roles', 'branches', 'canEditRole'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Only super admin, admin, or the user themselves can update
        if (!Auth::user()->userRole ||
            (!in_array(Auth::user()->userRole->name, ['super_admin', 'admin']) && Auth::id() !== $user->id)) {
            abort(403, 'Unauthorized');
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ];

        // Only admins can change role and branch
        if ($this->isAdminOrSuperAdmin()) {
            $rules['user_role_id'] = ['required', 'exists:user_roles,id'];
            $rules['branch_id'] = ['nullable', 'exists:branches,id'];
        }

        // Password is optional for updates
        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        }

        $request->validate($rules);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Only admins can update role and branch
        if ($this->isAdminOrSuperAdmin()) {
            $updateData['user_role_id'] = $request->user_role_id;
            $updateData['branch_id'] = $request->branch_id;
        }

        // Update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        $message = Auth::id() === $user->id ? 'Profil berhasil diperbarui' : 'Pengguna berhasil diperbarui';

        return redirect()->route('users.index')->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Only super admin can delete users
        $currentUser = Auth::user();
        if (!$currentUser->userRole || strtolower(str_replace([' ', '_'], '', $currentUser->userRole->name)) !== 'superadmin') {
            abort(403, 'Unauthorized');
        }

        // Cannot delete self
        if (Auth::id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus');
    }

    /**
     * Show profile of current user
     */
    public function profile()
    {
        $user = Auth::user()->load(['userRole', 'branch']);

        return view('users.profile', compact('user'));
    }

    /**
     * Update profile of current user
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Change password of current user
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password berhasil diubah');
    }
}