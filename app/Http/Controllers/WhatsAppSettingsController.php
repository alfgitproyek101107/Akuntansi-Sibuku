<?php

namespace App\Http\Controllers;

use App\Models\WhatsAppLog;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WhatsAppSettingsController extends Controller
{
    protected WhatsAppService $whatsApp;

    public function __construct(WhatsAppService $whatsApp)
    {
        $this->whatsApp = $whatsApp;
    }

    /**
     * Display WhatsApp settings page
     */
    public function index()
    {
        $settings = [
            'whatsapp_owner_number' => config('app_settings.whatsapp_owner_number', ''),
            'whatsapp_api_key' => config('app_settings.whatsapp_api_key', ''),
            'whatsapp_country_code' => config('app_settings.whatsapp_country_code', '62'),
            'whatsapp_reports_enabled' => config('app_settings.whatsapp_reports_enabled', false),
            'whatsapp_daily_time' => config('app_settings.whatsapp_daily_time', '08:00'),
            'whatsapp_weekly_day' => config('app_settings.whatsapp_weekly_day', '1'), // Monday
            'whatsapp_weekly_time' => config('app_settings.whatsapp_weekly_time', '08:00'),
            'whatsapp_monthly_day' => config('app_settings.whatsapp_monthly_day', '1'),
            'whatsapp_monthly_time' => config('app_settings.whatsapp_monthly_time', '08:00'),
            'whatsapp_report_format' => config('app_settings.whatsapp_report_format', 'detailed'),
        ];

        // Get recent logs
        $recentLogs = WhatsAppLog::latest()->take(10)->get();

        return view('settings.whatsapp', compact('settings', 'recentLogs'));
    }

    /**
     * Update WhatsApp settings
     */
    public function update(Request $request)
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

        // Save settings
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

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan WhatsApp berhasil disimpan'
        ]);
    }

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

        // Test connection
        $result = $this->whatsApp->sendMessage(
            $request->test_number,
            "🧪 *TEST PESAN*\n\nIni adalah pesan test dari sistem laporan WhatsApp.\n\n🕒 " . now()->format('d/m/Y H:i')
        );

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
    }

    /**
     * Send manual report
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

        $result = $this->whatsApp->sendManualReport(
            $request->phone_number,
            $request->report_type,
            session('active_branch'),
            auth()->id()
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil dikirim!'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim laporan: ' . ($result['message'] ?? 'Unknown error')
            ], 422);
        }
    }

    /**
     * Get WhatsApp logs
     */
    public function getLogs(Request $request)
    {
        $query = WhatsAppLog::with(['user', 'branch'])
            ->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('report_type') && $request->report_type !== '') {
            $query->where('report_type', $request->report_type);
        }

        $logs = $query->paginate(20);

        return response()->json([
            'success' => true,
            'logs' => $logs
        ]);
    }
}
