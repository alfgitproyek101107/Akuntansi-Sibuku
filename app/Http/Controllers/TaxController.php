<?php

namespace App\Http\Controllers;

use App\Models\TaxSetting;
use App\Models\TaxInvoice;
use App\Models\TaxLog;
use App\Models\Branch;
use App\Services\CoreTaxService;
use App\Services\TaxInvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TaxController extends Controller
{
    protected $coreTaxService;
    protected $taxInvoiceService;

    public function __construct(CoreTaxService $coreTaxService, TaxInvoiceService $taxInvoiceService)
    {
        $this->coreTaxService = $coreTaxService;
        $this->taxInvoiceService = $taxInvoiceService;
    }

    /**
     * Display tax dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $branchId = session('active_branch') ?? ($user->branch_id ?? null);

        // Get tax settings for current branch
        $taxSettings = TaxSetting::getForBranch($branchId);

        // Get tax statistics
        $stats = $this->getTaxStats($branchId);

        // Get recent tax invoices
        $recentInvoices = TaxInvoice::where('branch_id', $branchId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get pending tax logs
        $pendingLogs = TaxLog::where('branch_id', $branchId)
            ->whereIn('status', ['pending', 'retry'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('tax.index', compact(
            'taxSettings',
            'stats',
            'recentInvoices',
            'pendingLogs'
        ));
    }

    /**
     * Show tax settings form
     */
    public function settings()
    {
        $user = Auth::user();
        $branchId = session('active_branch') ?? ($user->branch_id ?? null);

        $taxSettings = TaxSetting::getForBranch($branchId);
        $branches = $this->getAvailableBranches();

        return view('tax.settings', compact('taxSettings', 'branches'));
    }

    /**
     * Update tax settings
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        $branchId = session('active_branch') ?? ($user->branch_id ?? null);

        $request->validate([
            'company_name' => 'required|string|max:255',
            'npwp' => 'nullable|string|max:20',
            'company_address' => 'nullable|string',
            'is_pkp' => 'boolean',
            'ppn_rate' => 'required|numeric|min:0|max:100',
            'ppn_umkm_rate' => 'required|numeric|min:0|max:100',
            'pph_21_rate' => 'required|numeric|min:0|max:100',
            'pph_22_rate' => 'required|numeric|min:0|max:100',
            'pph_23_rate' => 'required|numeric|min:0|max:100',
            'coretax_api_token' => 'nullable|string',
            'coretax_base_url' => 'nullable|url',
            'auto_sync_enabled' => 'boolean',
            'sync_retry_attempts' => 'required|integer|min:1|max:10',
            'include_tax_in_price' => 'boolean',
            'auto_calculate_tax' => 'boolean',
            'require_tax_invoice' => 'boolean',
            'default_tax_type' => 'required|in:ppn,ppn_umkm,pph_21,pph_22,pph_23',
            'enable_branch_tax' => 'boolean',
            'tax_exempt_products' => 'nullable|array',
        ]);

        $data = $request->all();
        $data['branch_id'] = $branchId;
        $data['user_id'] = $user->id;

        TaxSetting::updateOrCreate(
            ['branch_id' => $branchId],
            $data
        );

        return redirect()->route('tax.settings')->with('success', 'Pengaturan pajak berhasil diperbarui');
    }

    /**
     * Display tax invoices
     */
    public function invoices(Request $request)
    {
        $user = Auth::user();
        $branchId = session('active_branch') ?? ($user->branch_id ?? null);

        $query = TaxInvoice::where('branch_id', $branchId)
            ->with(['transaction', 'user']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tax_type')) {
            $query->where('tax_type', $request->tax_type);
        }

        if ($request->filled('date_from')) {
            $query->where('invoice_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('invoice_date', '<=', $request->date_to);
        }

        $taxInvoices = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('tax.invoices', compact('taxInvoices'));
    }

    /**
     * Show tax invoice details
     */
    public function showInvoice(TaxInvoice $taxInvoice)
    {
        $this->authorizeTaxInvoice($taxInvoice);

        $taxInvoice->load(['transaction', 'branch', 'user']);
        $taxLogs = $taxInvoice->taxLogs()->orderBy('created_at', 'desc')->get();

        return view('tax.invoice-detail', compact('taxInvoice', 'taxLogs'));
    }

    /**
     * Send tax invoice to CoreTax
     */
    public function sendToCoreTax(TaxInvoice $taxInvoice)
    {
        $this->authorizeTaxInvoice($taxInvoice);

        if ($taxInvoice->isSentToCoreTax()) {
            return redirect()->back()->with('error', 'Faktur pajak sudah dikirim ke CoreTax');
        }

        $result = $this->taxInvoiceService->sendToCoreTax($taxInvoice);

        if ($result['success']) {
            return redirect()->back()->with('success', 'Faktur pajak berhasil dikirim ke CoreTax');
        } else {
            return redirect()->back()->with('error', 'Gagal mengirim faktur pajak: ' . $result['message']);
        }
    }

    /**
     * Check tax invoice status with CoreTax
     */
    public function checkInvoiceStatus(TaxInvoice $taxInvoice)
    {
        $this->authorizeTaxInvoice($taxInvoice);

        $result = $this->coreTaxService->checkInvoiceStatus($taxInvoice);

        if ($result['success']) {
            return redirect()->back()->with('success', 'Status faktur pajak berhasil diperbarui');
        } else {
            return redirect()->back()->with('error', 'Gagal memeriksa status faktur pajak');
        }
    }

    /**
     * Download tax invoice PDF
     */
    public function downloadInvoice(TaxInvoice $taxInvoice)
    {
        $this->authorizeTaxInvoice($taxInvoice);

        // For now, return a simple text response
        // In production, you'd generate a proper PDF
        $content = $this->generateInvoiceText($taxInvoice);

        $filename = 'faktur-pajak-' . $taxInvoice->invoice_number . '.txt';

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Display tax logs
     */
    public function logs(Request $request)
    {
        $user = Auth::user();
        $branchId = session('active_branch') ?? ($user->branch_id ?? null);

        $query = TaxLog::where('branch_id', $branchId);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $taxLogs = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('tax.logs', compact('taxLogs'));
    }

    /**
     * Validate NPWP
     */
    public function validateNpwp(Request $request)
    {
        $request->validate([
            'npwp' => 'required|string|size:15',
        ]);

        $result = $this->coreTaxService->validateNPWP($request->npwp);

        return response()->json($result);
    }

    /**
     * Get tax statistics
     */
    private function getTaxStats($branchId)
    {
        $currentMonth = Carbon::now();

        return [
            'total_invoices' => TaxInvoice::where('branch_id', $branchId)->count(),
            'pending_invoices' => TaxInvoice::where('branch_id', $branchId)->where('status', 'generated')->count(),
            'approved_invoices' => TaxInvoice::where('branch_id', $branchId)->where('status', 'approved')->count(),
            'total_tax_amount' => TaxInvoice::where('branch_id', $branchId)->sum('tax_amount'),
            'monthly_tax_amount' => TaxInvoice::where('branch_id', $branchId)
                ->whereYear('invoice_date', $currentMonth->year)
                ->whereMonth('invoice_date', $currentMonth->month)
                ->sum('tax_amount'),
            'failed_logs' => TaxLog::where('branch_id', $branchId)->where('status', 'failed')->count(),
        ];
    }

    /**
     * Get available branches for the user
     */
    private function getAvailableBranches()
    {
        $user = Auth::user();

        if ($user->hasRole('super-admin')) {
            return Branch::all();
        }

        // Get branches user has access to
        $branchIds = DB::table('user_branches')
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->pluck('branch_id')
            ->toArray();

        if (!empty($branchIds)) {
            return Branch::whereIn('id', $branchIds)->get();
        }

        // Fallback to user's default branch
        return Branch::where('id', $user->branch_id)->get();
    }

    /**
     * Authorize access to tax invoice
     */
    private function authorizeTaxInvoice(TaxInvoice $taxInvoice)
    {
        $user = Auth::user();
        $branchId = session('active_branch') ?? ($user->branch_id ?? null);

        if ($taxInvoice->branch_id !== $branchId) {
            abort(403, 'Unauthorized access to tax invoice');
        }
    }

    /**
     * Generate simple invoice text (placeholder for PDF generation)
     */
    private function generateInvoiceText(TaxInvoice $taxInvoice)
    {
        $text = "FAKTUR PAJAK\n";
        $text .= "================\n\n";
        $text .= "Nomor Faktur: {$taxInvoice->invoice_number}\n";
        $text .= "Tanggal: {$taxInvoice->invoice_date->format('d/m/Y')}\n";
        $text .= "Tipe Pajak: {$taxInvoice->getTaxTypeDisplayName()}\n\n";

        $text .= "PENJUAL:\n";
        $text .= "Nama: {$taxInvoice->branch->name}\n";
        $text .= "NPWP: " . ($taxInvoice->branch->npwp ?? 'N/A') . "\n";
        $text .= "Alamat: " . ($taxInvoice->branch->address ?? 'N/A') . "\n\n";

        $text .= "PEMBELI:\n";
        $text .= "Nama: {$taxInvoice->customer_name}\n";
        $text .= "NPWP: " . ($taxInvoice->customer_npwp ?? 'N/A') . "\n";
        $text .= "NIK: " . ($taxInvoice->customer_nik ?? 'N/A') . "\n";
        $text .= "Alamat: " . ($taxInvoice->customer_address ?? 'N/A') . "\n\n";

        $text .= "DETAIL BARANG/JASA:\n";
        if ($taxInvoice->items) {
            foreach ($taxInvoice->items as $item) {
                $text .= "- {$item['name']}: Rp " . number_format($item['total'], 0, ',', '.') . "\n";
            }
        }
        $text .= "\n";

        $text .= "SUBTOTAL: Rp " . number_format($taxInvoice->subtotal, 0, ',', '.') . "\n";
        $text .= "PAJAK ({$taxInvoice->tax_rate}%): Rp " . number_format($taxInvoice->tax_amount, 0, ',', '.') . "\n";
        $text .= "TOTAL: Rp " . number_format($taxInvoice->total_amount, 0, ',', '.') . "\n\n";

        $text .= "Status: {$taxInvoice->status}\n";
        if ($taxInvoice->coretax_invoice_id) {
            $text .= "CoreTax ID: {$taxInvoice->coretax_invoice_id}\n";
        }

        return $text;
    }
}