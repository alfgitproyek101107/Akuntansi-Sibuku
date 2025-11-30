<?php

namespace App\Services;

use App\Models\TaxInvoice;
use App\Models\TaxSetting;
use App\Models\Transaction;
use App\Models\Branch;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TaxInvoiceService
{
    protected $coreTaxService;

    public function __construct(CoreTaxService $coreTaxService)
    {
        $this->coreTaxService = $coreTaxService;
    }

    /**
     * Generate tax invoice from transaction
     */
    public function generateFromTransaction(Transaction $transaction): ?TaxInvoice
    {
        // Check if transaction should generate tax invoice
        if (!$transaction->shouldGenerateTaxInvoice()) {
            return null;
        }

        // Check if tax invoice already exists
        if ($transaction->taxInvoice) {
            return $transaction->taxInvoice;
        }

        // Get tax settings
        $taxSettings = TaxSetting::getForBranch($transaction->branch_id);
        if (!$taxSettings) {
            throw new \Exception('Tax settings not configured for this branch');
        }

        // Build invoice data
        $invoiceData = $this->buildInvoiceData($transaction, $taxSettings);

        // Create tax invoice
        $taxInvoice = TaxInvoice::create($invoiceData);

        // Update transaction with tax invoice reference
        $transaction->update(['tax_invoice_id' => $taxInvoice->id]);

        return $taxInvoice;
    }

    /**
     * Send tax invoice to CoreTax
     */
    public function sendToCoreTax(TaxInvoice $taxInvoice): array
    {
        if (!$this->coreTaxService->isAvailable()) {
            return [
                'success' => false,
                'message' => 'CoreTax integration not configured'
            ];
        }

        return $this->coreTaxService->createTaxInvoice($taxInvoice);
    }

    /**
     * Generate invoice number
     */
    public function generateInvoiceNumber(Branch $branch, string $taxType = 'ppn', Carbon $date = null): string
    {
        $date = $date ?: now();
        $year = $date->format('Y');
        $month = $date->format('m');

        // Get branch code (first 3 letters uppercase)
        $branchCode = strtoupper(substr($branch->name, 0, 3));

        // Get tax type code
        $taxCode = match($taxType) {
            'ppn' => 'PPN',
            'ppn_umkm' => 'PPU',
            'pph_21' => 'PH21',
            'pph_22' => 'PH22',
            'pph_23' => 'PH23',
            default => 'TAX',
        };

        // Count existing invoices for this month
        $count = TaxInvoice::where('branch_id', $branch->id)
            ->whereYear('invoice_date', $year)
            ->whereMonth('invoice_date', $month)
            ->where('tax_type', $taxType)
            ->count() + 1;

        // Format: BRANCH-TAXCODE-YYYYMM-XXXX
        return sprintf(
            '%s-%s-%s%02d-%04d',
            $branchCode,
            $taxCode,
            $year,
            $month,
            $count
        );
    }

    /**
     * Build invoice data from transaction
     */
    private function buildInvoiceData(Transaction $transaction, TaxSetting $taxSettings): array
    {
        $branch = $transaction->branch;
        $taxRate = $transaction->tax_rate;
        $taxAmount = $transaction->tax_amount;

        // Calculate amounts
        $subtotal = $transaction->getTaxableAmount();
        $totalAmount = $transaction->getTotalAmountWithTax();

        // Build items array
        $items = [];
        if ($transaction->product) {
            $items[] = [
                'name' => $transaction->product->name,
                'sku' => $transaction->product->sku,
                'quantity' => 1, // Assuming single item transaction
                'unit_price' => $subtotal,
                'total' => $subtotal,
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
            ];
        } elseif ($transaction->service) {
            $items[] = [
                'name' => $transaction->service->name,
                'description' => $transaction->service->description,
                'quantity' => 1,
                'unit_price' => $subtotal,
                'total' => $subtotal,
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
            ];
        } else {
            // Manual transaction
            $items[] = [
                'name' => $transaction->description ?: 'Transaksi Manual',
                'quantity' => 1,
                'unit_price' => $subtotal,
                'total' => $subtotal,
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
            ];
        }

        return [
            'transaction_id' => $transaction->id,
            'branch_id' => $transaction->branch_id,
            'user_id' => $transaction->user_id,

            // Invoice details
            'invoice_number' => $this->generateInvoiceNumber($branch, $transaction->tax_type, $transaction->date),
            'invoice_date' => $transaction->date,
            'invoice_type' => $transaction->tax_type,
            'tax_type' => $transaction->isPPN() ? 'output' : 'input',

            // Amounts
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'tax_rate' => $taxRate,

            // Customer information
            'customer_name' => $transaction->customer_name ?: 'Customer',
            'customer_npwp' => $transaction->customer_npwp,
            'customer_nik' => $transaction->customer_nik,
            'customer_address' => $transaction->customer_address,
            'customer_type' => $transaction->customer_type,

            // Status
            'status' => 'generated',
            'items' => $items,
        ];
    }

    /**
     * Validate tax invoice data
     */
    public function validateInvoiceData(array $data): array
    {
        $errors = [];

        // Required fields
        $required = ['invoice_number', 'invoice_date', 'subtotal', 'tax_amount', 'total_amount'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $errors[] = "Field {$field} is required";
            }
        }

        // Amount validations
        if (isset($data['subtotal']) && isset($data['tax_amount']) && isset($data['total_amount'])) {
            $calculatedTotal = $data['subtotal'] + $data['tax_amount'];
            if (abs($calculatedTotal - $data['total_amount']) > 0.01) {
                $errors[] = 'Total amount does not match subtotal + tax amount';
            }
        }

        // Tax rate validation
        if (isset($data['tax_rate']) && ($data['tax_rate'] < 0 || $data['tax_rate'] > 100)) {
            $errors[] = 'Tax rate must be between 0 and 100';
        }

        // Invoice number uniqueness
        if (isset($data['invoice_number'])) {
            $exists = TaxInvoice::where('invoice_number', $data['invoice_number'])
                ->when(isset($data['id']), fn($q) => $q->where('id', '!=', $data['id']))
                ->exists();

            if ($exists) {
                $errors[] = 'Invoice number already exists';
            }
        }

        return $errors;
    }

    /**
     * Get tax summary for a period
     */
    public function getTaxSummary(int $branchId, Carbon $startDate, Carbon $endDate): array
    {
        $invoices = TaxInvoice::where('branch_id', $branchId)
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->get();

        $summary = [
            'total_invoices' => $invoices->count(),
            'total_subtotal' => $invoices->sum('subtotal'),
            'total_tax_amount' => $invoices->sum('tax_amount'),
            'total_amount' => $invoices->sum('total_amount'),
            'by_tax_type' => [],
            'by_status' => [],
        ];

        // Group by tax type
        foreach ($invoices->groupBy('tax_type') as $taxType => $typeInvoices) {
            $summary['by_tax_type'][$taxType] = [
                'count' => $typeInvoices->count(),
                'subtotal' => $typeInvoices->sum('subtotal'),
                'tax_amount' => $typeInvoices->sum('tax_amount'),
                'total' => $typeInvoices->sum('total_amount'),
            ];
        }

        // Group by status
        foreach ($invoices->groupBy('status') as $status => $statusInvoices) {
            $summary['by_status'][$status] = $statusInvoices->count();
        }

        return $summary;
    }

    /**
     * Bulk send pending invoices to CoreTax
     */
    public function bulkSendToCoreTax(int $branchId, int $limit = 10): array
    {
        $pendingInvoices = TaxInvoice::where('branch_id', $branchId)
            ->where('status', 'generated')
            ->whereNull('coretax_sent_at')
            ->limit($limit)
            ->get();

        $results = [
            'total' => $pendingInvoices->count(),
            'successful' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        foreach ($pendingInvoices as $invoice) {
            $result = $this->sendToCoreTax($invoice);

            if ($result['success']) {
                $results['successful']++;
            } else {
                $results['failed']++;
                $results['errors'][] = [
                    'invoice_number' => $invoice->invoice_number,
                    'error' => $result['message']
                ];
            }
        }

        return $results;
    }
}