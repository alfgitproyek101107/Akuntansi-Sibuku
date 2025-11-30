<?php

namespace App\Http\Controllers;

use App\Models\ReportSchedule;
use App\Jobs\SendWhatsAppReport;
use App\Services\WhatsAppService;
use App\Services\ReportGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class WhatsAppReportController extends Controller
{
    protected WhatsAppService $whatsApp;
    protected ReportGeneratorService $reportGenerator;

    public function __construct(WhatsAppService $whatsApp, ReportGeneratorService $reportGenerator)
    {
        $this->whatsApp = $whatsApp;
        $this->reportGenerator = $reportGenerator;
    }

    /**
     * Display report schedules management page
     */
    public function index()
    {
        $schedules = ReportSchedule::where('user_id', Auth::id())
            ->with('branch')
            ->orderBy('created_at', 'desc')
            ->get();

        $availableProviders = WhatsAppService::getAvailableProviders();

        return view('reports.whatsapp.index', compact('schedules', 'availableProviders'));
    }

    /**
     * Store a new report schedule
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report_type' => 'required|in:daily,weekly,monthly',
            'scheduled_time' => 'required|date_format:H:i',
            'whatsapp_number' => 'required|string|regex:/^[0-9+\-\s()]+$/',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Validate WhatsApp number format
        if (!$this->whatsApp->validatePhoneNumber($request->whatsapp_number)) {
            return response()->json([
                'success' => false,
                'message' => 'Format nomor WhatsApp tidak valid'
            ], 422);
        }

        // Check if user has access to the selected branch
        if ($request->branch_id) {
            $user = Auth::user();
            $hasBranchAccess = $user->branches()->where('branches.id', $request->branch_id)->exists();

            if (!$user->hasRole('super-admin') && !$hasBranchAccess) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke cabang tersebut'
                ], 403);
            }
        }

        $schedule = ReportSchedule::create([
            'user_id' => Auth::id(),
            'branch_id' => $request->branch_id,
            'report_type' => $request->report_type,
            'scheduled_time' => $request->scheduled_time,
            'whatsapp_number' => $request->whatsapp_number,
            'is_active' => $request->boolean('is_active', true),
            'settings' => $request->settings ?? [],
        ]);

        Log::info('WhatsApp report schedule created', [
            'schedule_id' => $schedule->id,
            'user_id' => Auth::id(),
            'report_type' => $request->report_type
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal laporan WhatsApp berhasil dibuat',
            'schedule' => $schedule->load('branch')
        ]);
    }

    /**
     * Show a report schedule (for editing)
     */
    public function show(ReportSchedule $schedule)
    {
        if ($schedule->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'schedule' => $schedule->load('branch')
        ]);
    }

    /**
     * Update a report schedule
     */
    public function update(Request $request, ReportSchedule $schedule)
    {
        // Check ownership
        if ($schedule->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'report_type' => 'required|in:daily,weekly,monthly',
            'scheduled_time' => 'required|date_format:H:i',
            'whatsapp_number' => 'required|string|regex:/^[0-9+\-\s()]+$/',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Validate WhatsApp number format
        if (!$this->whatsApp->validatePhoneNumber($request->whatsapp_number)) {
            return response()->json([
                'success' => false,
                'message' => 'Format nomor WhatsApp tidak valid'
            ], 422);
        }

        $schedule->update([
            'report_type' => $request->report_type,
            'scheduled_time' => $request->scheduled_time,
            'whatsapp_number' => $request->whatsapp_number,
            'branch_id' => $request->branch_id,
            'is_active' => $request->boolean('is_active', true),
            'settings' => $request->settings ?? [],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal laporan WhatsApp berhasil diperbarui',
            'schedule' => $schedule->load('branch')
        ]);
    }

    /**
     * Toggle active status of a report schedule
     */
    public function toggle(ReportSchedule $schedule)
    {
        if ($schedule->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $schedule->update(['is_active' => !$schedule->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Status jadwal berhasil diubah',
            'is_active' => $schedule->is_active
        ]);
    }

    /**
     * Delete a report schedule
     */
    public function destroy(ReportSchedule $schedule)
    {
        if ($schedule->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $schedule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal laporan WhatsApp berhasil dihapus'
        ]);
    }

    /**
     * Send manual report
     */
    public function sendManual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report_type' => 'required|in:daily,weekly,monthly,custom',
            'branch_id' => 'nullable|string',
            'custom_date' => 'nullable|date',
            'custom_start_date' => 'required_if:report_type,custom|date',
            'custom_end_date' => 'required_if:report_type,custom|date|after_or_equal:custom_start_date',
            'whatsapp_number' => 'required|string|regex:/^[0-9+\-\s()]+$/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Validate WhatsApp number
        if (!$this->whatsApp->validatePhoneNumber($request->whatsapp_number)) {
            return response()->json([
                'success' => false,
                'message' => 'Format nomor WhatsApp tidak valid'
            ], 422);
        }

        // Check branch access
        if ($request->branch_id && $request->branch_id !== 'all') {
            $user = Auth::user();
            $hasBranchAccess = $user->branches()->where('branches.id', $request->branch_id)->exists();

            if (!$user->hasRole('super-admin') && !$hasBranchAccess && $request->branch_id !== session('active_branch')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke cabang tersebut'
                ], 403);
            }
        }

        // Create temporary schedule for manual sending
        $schedule = new ReportSchedule([
            'user_id' => Auth::id(),
            'branch_id' => $request->branch_id === 'all' ? null : $request->branch_id,
            'report_type' => $request->report_type,
            'scheduled_time' => now()->format('H:i'),
            'whatsapp_number' => $request->whatsapp_number,
            'is_active' => true,
            'user' => Auth::user(), // Attach user for demo mode check
        ]);

        // Determine date for report
        $customDate = null;
        if ($request->report_type === 'custom') {
            $customDate = $request->custom_end_date; // Use end date for custom reports
        } elseif ($request->custom_date) {
            $customDate = $request->custom_date;
        }

        // Dispatch job
        SendWhatsAppReport::dispatch($schedule, $customDate, $request->branch_id, true);

        Log::info('Manual WhatsApp report queued', [
            'user_id' => Auth::id(),
            'report_type' => $request->report_type,
            'branch_id' => $request->branch_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan WhatsApp sedang dikirim. Anda akan menerima pesan dalam beberapa saat.'
        ]);
    }

    /**
     * Test WhatsApp connection
     */
    public function testConnection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'whatsapp_number' => 'required|string|regex:/^[0-9+\-\s()]+$/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Send test message
        $result = $this->whatsApp->sendMessage(
            $request->whatsapp_number,
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
     * Get available branches for user
     */
    public function getBranches()
    {
        $user = Auth::user();

        if ($user->hasRole('super-admin')) {
            $branches = \App\Models\Branch::all();
        } else {
            $branches = $user->branches;
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
    }
}