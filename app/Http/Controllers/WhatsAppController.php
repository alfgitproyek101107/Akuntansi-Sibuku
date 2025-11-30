<?php

namespace App\Http\Controllers;

use App\Models\WhatsAppLog;
use App\Services\WhatsAppService;
use App\Services\WhatsAppReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

/**
 * WhatsApp Controller - Unified controller for all WhatsApp functionality
 *
 * Handles:
 * - Settings management (/settings/whatsapp)
 * - Report monitoring (/reports/whatsapp)
 * - Manual sending
 * - Test connections
 * - Log viewing
 */
class WhatsAppController extends Controller
{
    protected WhatsAppService $whatsAppService;
    protected WhatsAppReportService $reportService;

    public function __construct(
        WhatsAppService $whatsAppService,
        WhatsAppReportService $reportService
    ) {
        $this->whatsAppService = $whatsAppService;
        $this->reportService = $reportService;
    }

    // ===============================
    // SETTINGS MANAGEMENT (/settings/whatsapp)
    // ===============================

    /**
     * Display WhatsApp settings page
     */
    public function settings()
    {
        $settings = [
            'whatsapp_owner_number' => config('app_settings.whatsapp_owner_number', ''),
            'whatsapp_api_key' => config('app_settings.whatsapp_api_key', ''),
            'whatsapp_country_code' => config('app_settings.whatsapp_country_code', '62'),
            'whatsapp_reports_enabled' => config('app_settings.whatsapp_reports_enabled', false),
            'whatsapp_daily_time' => config('app_settings.whatsapp_daily_time', '08:00'),
            'whatsapp_weekly_day' => config('app_settings.whatsapp_weekly_day', '1'),
            'whatsapp_weekly_time' => config('app_settings.whatsapp_weekly_time', '08:00'),
            'whatsapp_monthly_day' => config('app_settings.whatsapp_monthly_day', '1'),
            'whatsapp_monthly_time' => config('app_settings.whatsapp_monthly_time', '08:00'),
            'whatsapp_report_format' => config('app_settings.whatsapp_report_format', 'detailed'),
        ];

        // Get recent logs for monitoring
        $recentLogs = WhatsAppLog::latest()->take(10)->get();

        return view('settings.whatsapp', compact('settings', 'recentLogs'));
    }

    /**
     * Update WhatsApp settings
     */
    public function updateSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'whatsapp_owner_number' => 'required|string|regex:/^[0-9+\-\s()]+$/',
            'whatsapp_api_key' => 'required|string|min:10',
            'whatsapp_country_code' => 'required|string|regex:/^[0-9]+$/|max:3',
            'whatsapp_reports_enabled' => 'boolean',
            'whatsapp_daily_time' => 'required_if:whatsapp_reports_enabled,true|date_format:H:i',
            'whatsapp_weekly_day' => 'required_if:whatsapp_reports_enabled,true|integer|between:0,6',
            'whatsapp_weekly_time' => 'required_if:whatsapp_reports_enabled,true|date_format:H:i',
            'whatsapp_monthly_day' => 'required_if:whatsapp_reports_enabled,true|integer|between:1,31',
            'whatsapp_monthly_time' => 'required_if:whatsapp_reports_enabled,true|date_format:H:i',
            'whatsapp_report_format' => 'required|in:simple,detailed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Save settings using updateOrCreate
            $settings = [
                'whatsapp_owner_number' => $request->whatsapp_owner_number,
                'whatsapp_api_key' => $request->whatsapp_api_key,
                'whatsapp_country_code' => $request->whatsapp_country_code,
                'whatsapp_reports_enabled' => $request->boolean('whatsapp_reports_enabled'),
                'whatsapp_daily_time' => $request->whatsapp_daily_time,
                'whatsapp_weekly_day' => $request->whatsapp_weekly_day,
                'whatsapp_weekly_time' => $request->whatsapp_weekly_time,
                'whatsapp_monthly_day' => $request->whatsapp_monthly_day,
                'whatsapp_monthly_time' => $request->whatsapp_monthly_time,
                'whatsapp_report_format' => $request->whatsapp_report_format,
            ];

            foreach ($settings as $key => $value) {
                \App\Models\AppSetting::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => (string) $value,
                        'type' => is_bool($value) ? 'boolean' : (is_int($value) ? 'integer' : 'string'),
                        'group' => 'whatsapp',
                        'label' => ucwords(str_replace('_', ' ', $key)),
                        'description' => 'WhatsApp setting: ' . $key,
                        'is_public' => false,
                    ]
                );
            }

            Log::info('WhatsApp settings updated', [
                'user_id' => auth()->id(),
                'settings_count' => count($settings)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengaturan WhatsApp berhasil disimpan'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update WhatsApp settings', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan pengaturan: ' . $e->getMessage()
            ], 500);
        }
    }

    // ===============================
    // REPORTS MANAGEMENT (/reports/whatsapp)
    // ===============================

    /**
     * Display WhatsApp reports monitoring page
     */
    public function reports()
    {
        // Get recent logs for monitoring
        $recentLogs = WhatsAppLog::with(['user', 'branch'])
            ->latest()
            ->take(20)
            ->get();

        // Get statistics
        $stats = [
            'total_sent' => WhatsAppLog::where('status', 'success')->count(),
            'total_failed' => WhatsAppLog::where('status', 'failed')->count(),
            'today_sent' => WhatsAppLog::where('status', 'success')
                ->whereDate('created_at', today())
                ->count(),
        ];

        return view('reports.whatsapp', compact('recentLogs', 'stats'));
    }

    // ===============================
    // MANUAL SENDING FUNCTIONALITY
    // ===============================

    /**
     * Send manual report (from settings page)
     */
    public function sendManualReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report_type' => 'required|in:daily,weekly,monthly',
            'phone_number' => 'required|string|regex:/^[0-9+\-\s()]+$/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Send report
            $result = $this->reportService->sendReport(
                $request->report_type,
                $request->phone_number,
                session('active_branch'),
                auth()->id(),
                'manual'
            );

            return response()->json([
                'success' => $result['success'],
                'message' => $result['message']
            ]);

        } catch (\Exception $e) {
            Log::error('Manual report send failed', [
                'error' => $e->getMessage(),
                'report_type' => $request->report_type,
                'phone_number' => $request->phone_number,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim laporan: ' . $e->getMessage()
            ], 500);
        }
    }

    // ===============================
    // CONNECTION TESTING
    // ===============================

    /**
     * Test WhatsApp connection
     */
    public function testConnection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_number' => 'required|string|regex:/^[0-9+\-\s()]+$/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Send test message
            $result = $this->whatsAppService->sendMessage(
                $request->test_number,
                "🧪 *TEST PESAN*\n\nIni adalah pesan test dari sistem laporan WhatsApp.\n\n🕒 " . now()->format('d/m/Y H:i')
            );

            // Log test result
            WhatsAppLog::create([
                'report_type' => 'test',
                'phone_number' => $request->test_number,
                'status' => $result['success'] ? 'success' : 'failed',
                'message' => $result['success'] ? 'Test message sent successfully' : ($result['message'] ?? 'Test failed'),
                'response_data' => $result,
                'error_message' => $result['success'] ? null : ($result['message'] ?? 'Unknown error'),
                'user_id' => auth()->id(),
                'sent_at' => now(),
            ]);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesan test berhasil dikirim!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim pesan test: ' . ($result['message'] ?? 'Unknown error')
                ], 422);
            }

        } catch (\Exception $e) {
            Log::error('WhatsApp test connection failed', [
                'error' => $e->getMessage(),
                'test_number' => $request->test_number,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Test koneksi gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    // ===============================
    // LOG MANAGEMENT
    // ===============================

    /**
     * Get WhatsApp logs with filtering
     */
    public function getLogs(Request $request)
    {
        try {
            $query = WhatsAppLog::with(['user', 'branch'])
                ->orderBy('created_at', 'desc');

            // Apply filters
            if ($request->has('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }

            if ($request->has('report_type') && $request->report_type !== '') {
                $query->where('report_type', $request->report_type);
            }

            if ($request->has('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $logs = $query->paginate(20);

            return response()->json([
                'success' => true,
                'logs' => $logs
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to fetch WhatsApp logs', [
                'error' => $e->getMessage(),
                'filters' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data logs'
            ], 500);
        }
    }

    // ===============================
    // UTILITY METHODS
    // ===============================

    /**
     * Get log detail for modal
     */
    public function getLogDetail($logId)
    {
        try {
            $log = WhatsAppLog::with(['user', 'branch'])->findOrFail($logId);

            // Check if user owns this log
            if ($log->user_id !== auth()->id() && !auth()->user()->hasRole('super-admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'log' => $log
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to fetch log detail', [
                'error' => $e->getMessage(),
                'log_id' => $logId,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail log'
            ], 500);
        }
    }

    /**
     * Get logs table HTML (for AJAX loading)
     */
    public function getLogsTable(Request $request)
    {
        try {
            $query = WhatsAppLog::with(['user', 'branch'])
                ->orderBy('created_at', 'desc');

            // Apply filters
            if ($request->has('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }

            if ($request->has('report_type') && $request->report_type !== '') {
                $query->where('report_type', $request->report_type);
            }

            if ($request->has('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $logs = $query->paginate(20);

            return view('reports.whatsapp._logs_table', compact('logs'));

        } catch (\Exception $e) {
            Log::error('Failed to load logs table', [
                'error' => $e->getMessage(),
                'filters' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat tabel logs'
            ], 500);
        }
    }

    /**
     * Get available branches for current user
     */
    public function getBranches()
    {
        try {
            $user = auth()->user();

            if ($user->hasRole('super-admin')) {
                $branches = \App\Models\Branch::all();
            } else {
                $branches = $user->branches ?? collect();
            }

            return response()->json([
                'success' => true,
                'branches' => $branches->map(function ($branch) {
                    return [
                        'id' => $branch->id,
                        'name' => $branch->name,
                    ];
                })
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to fetch branches', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data cabang'
            ], 500);
        }
    }
}