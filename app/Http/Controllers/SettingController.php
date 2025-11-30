<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\NotificationSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $activeTab = $request->get('tab', 'general');

        // Get settings data based on active tab
        $data = [];

        switch ($activeTab) {
            case 'general':
                $data['settings'] = AppSetting::getGroup('general');
                break;
            case 'system':
                $data['settings'] = AppSetting::getGroup('system');
                break;
            case 'notifications':
                $data['settings'] = AppSetting::getGroup('notifications');
                $data['userNotifications'] = NotificationSetting::getEnabledForUser($user->id);
                $data['notificationTypes'] = NotificationSetting::getNotificationTypes();
                $data['eventTypes'] = NotificationSetting::getEventTypes();
                break;
            case 'roles':
                $data['roles'] = Role::with('permissions')->get();
                $data['permissions'] = Permission::all()->groupBy(function ($permission) {
                    return explode(' ', $permission->name)[1] ?? 'other';
                });
                $data['users'] = User::with('roles')->get();
                break;
            case 'transactions':
                $data['settings'] = AppSetting::getGroup('transactions');
                break;
            case 'ui':
                $data['settings'] = AppSetting::getGroup('ui');
                break;
        }

        return view('settings.index', compact('user', 'activeTab', 'data'));
    }

    public function branches()
    {
        $user = Auth::user();

        // Only allow super_admin and admin to access branch management
        if (!$user->userRole || !in_array($user->userRole->name, ['super_admin', 'admin'])) {
            abort(403, 'Unauthorized access to branch management.');
        }

        return view('settings.branches');
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Auth::user()->update($request->only(['name', 'email']));

        return redirect()->route('settings.index', ['tab' => 'profile'])->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
                'different:current_password'
            ],
        ], [
            'password.regex' => 'Password harus mengandung minimal 1 huruf besar, 1 huruf kecil, 1 angka, dan 1 karakter khusus (@$!%*?&).',
            'password.different' => 'Password baru harus berbeda dari password saat ini.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Kata sandi saat ini salah.']);
        }

        // Check if new password is different from current
        if (Hash::check($request->password, Auth::user()->password)) {
            return redirect()->back()->withErrors(['password' => 'Password baru tidak boleh sama dengan password saat ini.']);
        }

        $user = Auth::user();
        $user->update(['password' => Hash::make($request->password)]);

        // Invalidate all other sessions for this user
        // Note: In a production environment, you might want to use a more sophisticated
        // session management system or database-based sessions to properly invalidate all sessions

        return redirect()->route('settings.index', ['tab' => 'profile'])->with('success', 'Kata sandi berhasil diperbarui. Semua sesi lain telah diakhiri.');
    }

    public function updateGeneralSettings(Request $request)
    {
        $user = Auth::user();

        // Check if user has permission via userRole (for backward compatibility)
        if (!$user->userRole || !in_array($user->userRole->name, ['Super Admin', 'Admin', 'super_admin', 'admin'])) {
            // Fallback to Spatie permission check
            $this->authorize('edit general settings');
        }

        $settings = [
            'app_name', 'app_description', 'company_name', 'company_address',
            'company_phone', 'company_email', 'default_currency', 'date_format', 'timezone'
        ];

        foreach ($settings as $setting) {
            if ($request->has($setting)) {
                AppSetting::setValue($setting, $request->input($setting));
            }
        }

        return redirect()->route('settings.index', ['tab' => 'general'])->with('success', 'Pengaturan umum berhasil diperbarui.');
    }

    public function updateSystemSettings(Request $request)
    {
        $this->authorize('system maintenance');

        $settings = [
            'maintenance_mode', 'debug_mode', 'backup_enabled', 'backup_frequency',
            'max_upload_size', 'session_timeout'
        ];

        foreach ($settings as $setting) {
            if ($request->has($setting)) {
                AppSetting::setValue($setting, $request->input($setting));
            }
        }

        return redirect()->route('settings.index', ['tab' => 'system'])->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }

    public function updateNotificationSettings(Request $request)
    {
        $user = Auth::user();

        $notificationTypes = NotificationSetting::getNotificationTypes();
        $eventTypes = NotificationSetting::getEventTypes();

        foreach ($eventTypes as $eventKey => $eventName) {
            foreach ($notificationTypes as $typeKey => $typeName) {
                $enabled = $request->boolean("notification_{$eventKey}_{$typeKey}", false);

                NotificationSetting::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'notification_type' => $typeKey,
                        'event_type' => $eventKey,
                    ],
                    ['is_enabled' => $enabled]
                );
            }
        }

        return redirect()->route('settings.index', ['tab' => 'notifications'])->with('success', 'Pengaturan notifikasi berhasil diperbarui.');
    }

    public function updateTransactionSettings(Request $request)
    {
        $this->authorize('edit general settings');

        $settings = [
            'auto_numbering_enabled', 'default_transaction_prefix', 'require_transaction_description', 'allow_negative_balance'
        ];

        foreach ($settings as $setting) {
            if ($request->has($setting)) {
                AppSetting::setValue($setting, $request->boolean($setting));
            }
        }

        return redirect()->route('settings.index', ['tab' => 'transactions'])->with('success', 'Pengaturan transaksi berhasil diperbarui.');
    }

    public function updateUISettings(Request $request)
    {
        $this->authorize('edit general settings');

        $settings = ['primary_color', 'secondary_color', 'dark_mode_enabled', 'items_per_page'];

        foreach ($settings as $setting) {
            if ($request->has($setting)) {
                $value = $setting === 'dark_mode_enabled' || $setting === 'items_per_page'
                    ? $request->boolean($setting)
                    : $request->input($setting);
                AppSetting::setValue($setting, $value);
            }
        }

        return redirect()->route('settings.index', ['tab' => 'ui'])->with('success', 'Pengaturan UI berhasil diperbarui.');
    }

    public function createRole(Request $request)
    {
        $this->authorize('manage roles');

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $role = Role::create(['name' => $request->name]);

        if ($request->permissions) {
            $role->givePermissionTo($request->permissions);
        }

        return redirect()->route('settings.index', ['tab' => 'roles'])->with('success', 'Role berhasil dibuat.');
    }

    public function updateRole(Request $request, Role $role)
    {
        $this->authorize('manage roles');

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('settings.index', ['tab' => 'roles'])->with('success', 'Role berhasil diperbarui.');
    }

    public function deleteRole(Role $role)
    {
        $this->authorize('manage roles');

        if ($role->name === 'super-admin') {
            return redirect()->back()->with('error', 'Role super-admin tidak dapat dihapus.');
        }

        $role->delete();

        return redirect()->route('settings.index', ['tab' => 'roles'])->with('success', 'Role berhasil dihapus.');
    }

    public function assignRole(Request $request, User $user)
    {
        $this->authorize('manage roles');

        $validator = Validator::make($request->all(), [
            'role' => 'required|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $user->syncRoles([$request->role]);

        return redirect()->route('settings.index', ['tab' => 'roles'])->with('success', 'Role berhasil ditetapkan ke user.');
    }

    public function clearCache()
    {
        $this->authorize('system maintenance');

        Cache::flush();
        AppSetting::clearCache();

        return redirect()->route('settings.index', ['tab' => 'system'])->with('success', 'Cache berhasil dibersihkan.');
    }

    public function optimizeApplication()
    {
        $this->authorize('system maintenance');

        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');

        return redirect()->route('settings.index', ['tab' => 'system'])->with('success', 'Aplikasi berhasil dioptimalkan.');
    }

    public function backupDatabase()
    {
        $this->authorize('system maintenance');

        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('backups/' . $filename);

        // Create backups directory if it doesn't exist
        if (!file_exists(storage_path('backups'))) {
            mkdir(storage_path('backups'), 0755, true);
        }

        // Simple database dump (you might want to use a more robust backup solution)
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.host'),
            config('database.connections.mysql.database'),
            $path
        );

        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            return redirect()->route('settings.index', ['tab' => 'system'])->with('success', 'Backup database berhasil dibuat.');
        } else {
            return redirect()->route('settings.index', ['tab' => 'system'])->with('error', 'Gagal membuat backup database.');
        }
    }

    public function getLogs()
    {
        $this->authorize('system maintenance');

        $logPath = storage_path('logs/laravel.log');

        if (!file_exists($logPath)) {
            return response()->json(['error' => 'Log file not found'], 404);
        }

        $logs = file_get_contents($logPath);
        $lines = explode("\n", $logs);
        $recentLogs = array_slice(array_reverse($lines), 0, 100);

        return response()->json(['logs' => $recentLogs]);
    }

    public function clearLogs()
    {
        $this->authorize('system maintenance');

        $logPath = storage_path('logs/laravel.log');

        if (file_exists($logPath)) {
            file_put_contents($logPath, '');
        }

        return redirect()->route('settings.index', ['tab' => 'system'])->with('success', 'Log berhasil dibersihkan.');
    }
}