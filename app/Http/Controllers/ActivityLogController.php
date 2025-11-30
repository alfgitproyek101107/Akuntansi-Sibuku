<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Branch;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        // Only super admin and admin can access activity logs
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            $isAuthorized = $user->hasRole('super-admin') ||
                           $user->hasRole('superadmin') ||
                           $user->hasRole('admin') ||
                           ($user->userRole && in_array($user->userRole->name, ['super_admin', 'super-admin', 'superadmin', 'admin']));

            if (!$isAuthorized) {
                abort(403, 'Unauthorized access to activity logs');
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of activity logs
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with(['user', 'branch'])
            ->orderBy('occurred_at', 'desc');

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action_type')) {
            $query->where('action_type', $request->action_type);
        }

        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('date_from')) {
            $query->where('occurred_at', '>=', $request->date_from . ' 00:00:00');
        }

        if ($request->filled('date_to')) {
            $query->where('occurred_at', '<=', $request->date_to . ' 23:59:59');
        }

        // Super admin can see all logs, admin can see their branch logs
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('super-admin') ||
                       $user->hasRole('superadmin') ||
                       ($user->userRole && in_array($user->userRole->name, ['super_admin', 'super-admin', 'superadmin']));

        if (!$isSuperAdmin) {
            $currentBranchId = session('active_branch') ?? $user->branch_id;
            if ($currentBranchId) {
                $query->where('branch_id', $currentBranchId);
            }
        }

        $activityLogs = $query->paginate(50);

        // Get filter options
        $users = User::select('id', 'name')->orderBy('name')->get();
        $branches = Branch::select('id', 'name')->orderBy('name')->get();
        $actionTypes = ActivityLog::select('action_type')
            ->distinct()
            ->orderBy('action_type')
            ->pluck('action_type');
        $modelTypes = ActivityLog::select('model_type')
            ->distinct()
            ->whereNotNull('model_type')
            ->orderBy('model_type')
            ->pluck('model_type');

        return view('activity-logs.index', compact(
            'activityLogs',
            'users',
            'branches',
            'actionTypes',
            'modelTypes'
        ));
    }

    /**
     * Display the specified activity log
     */
    public function show($id)
    {
        $activityLog = ActivityLog::with(['user', 'branch'])->findOrFail($id);

        // Check access permissions
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('super-admin') ||
                       $user->hasRole('superadmin') ||
                       ($user->userRole && in_array($user->userRole->name, ['super_admin', 'super-admin', 'superadmin']));

        if (!$isSuperAdmin) {
            $currentBranchId = session('active_branch') ?? $user->branch_id;
            if ($activityLog->branch_id !== $currentBranchId) {
                abort(403, 'Unauthorized access to this activity log');
            }
        }

        return view('activity-logs.show', compact('activityLog'));
    }

    /**
     * Get activity summary for dashboard
     */
    public function summary(Request $request)
    {
        $days = $request->get('days', 7);
        $summary = ActivityLogService::getActivitySummary($days);

        return response()->json($summary);
    }

    /**
     * Export activity logs
     */
    public function export(Request $request)
    {
        // Log the export activity
        ActivityLogService::logExport(Auth::user(), 'activity_logs', 0, $request->all());

        // Build query with same filters as index
        $query = ActivityLog::with(['user', 'branch'])
            ->orderBy('occurred_at', 'desc');

        // Apply same filters as index method
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action_type')) {
            $query->where('action_type', $request->action_type);
        }

        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('date_from')) {
            $query->where('occurred_at', '>=', $request->date_from . ' 00:00:00');
        }

        if ($request->filled('date_to')) {
            $query->where('occurred_at', '<=', $request->date_to . ' 23:59:59');
        }

        $activityLogs = $query->get();

        // Generate CSV content
        $csvContent = "Date,Time,User,Branch,Action,Model,Description,IP Address\n";

        foreach ($activityLogs as $log) {
            $csvContent .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s,%s\n",
                $log->occurred_at->format('Y-m-d'),
                $log->occurred_at->format('H:i:s'),
                $log->user_name ?? 'System',
                $log->branch_name ?? 'N/A',
                $log->action_type,
                $log->model_name ?? 'N/A',
                str_replace('"', '""', $log->description ?? ''),
                $log->ip_address ?? 'N/A'
            );
        }

        $filename = 'activity_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Clean old activity logs (admin only)
     */
    public function clean(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('super-admin') ||
                       $user->hasRole('superadmin') ||
                       ($user->userRole && in_array($user->userRole->name, ['super_admin', 'super-admin', 'superadmin']));

        if (!$isSuperAdmin) {
            abort(403, 'Only super admin can clean activity logs');
        }

        $daysToKeep = $request->get('days', 365);
        $deletedCount = ActivityLogService::cleanOldLogs($daysToKeep);

        // Log the cleanup activity
        ActivityLogService::log(
            'system_maintenance',
            "Cleaned up {$deletedCount} old activity log records (kept {$daysToKeep} days)",
            ['operation' => 'cleanup', 'records_deleted' => $deletedCount, 'days_kept' => $daysToKeep]
        );

        return redirect()->back()->with('success', "Successfully cleaned up {$deletedCount} old activity log records.");
    }
}
