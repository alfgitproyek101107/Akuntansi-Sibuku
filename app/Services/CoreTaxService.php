<?php

namespace App\Services;

use App\Models\TaxSetting;
use App\Models\TaxInvoice;
use App\Models\TaxLog;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CoreTaxService
{
    protected $baseUrl;
    protected $apiToken;
    protected $timeout = 30; // seconds

    public function __construct()
    {
        $taxSettings = TaxSetting::getForCurrentBranch();

        $this->baseUrl = $taxSettings ? $taxSettings->coretax_base_url : 'https://api.coretax.com';
        $this->apiToken = $taxSettings ? $taxSettings->coretax_api_token : null;
    }

    /**
     * Validate NPWP with CoreTax
     */
    public function validateNPWP(string $npwp): array
    {
        try {
            $log = $this->createLog('validate_npwp', ['npwp' => $npwp]);

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiToken,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . '/validate-npwp', [
                    'npwp' => $npwp,
                ]);

            $result = $response->json();

            if ($response->successful()) {
                $log->markAsSuccess($result);
                return [
                    'success' => true,
                    'data' => $result,
                    'message' => 'NPWP valid'
                ];
            } else {
                $log->markAsFailed('Validation failed: ' . ($result['message'] ?? 'Unknown error'));
                return [
                    'success' => false,
                    'message' => $result['message'] ?? 'NPWP validation failed'
                ];
            }

        } catch (\Exception $e) {
            $log->markAsFailed('Exception: ' . $e->getMessage());
            Log::error('CoreTax NPWP validation error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Unable to validate NPWP at this time'
            ];
        }
    }

    /**
     * Create tax invoice with CoreTax
     */
    public function createTaxInvoice(TaxInvoice $taxInvoice): array
    {
        try {
            $log = $this->createLog('create_invoice', [
                'tax_invoice_id' => $taxInvoice->id,
                'invoice_number' => $taxInvoice->invoice_number
            ]);

            $payload = $this->buildInvoicePayload($taxInvoice);

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiToken,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . '/invoices', $payload);

            $result = $response->json();

            if ($response->successful()) {
                // Update tax invoice with CoreTax data
                $taxInvoice->update([
                    'coretax_invoice_id' => $result['invoice_id'] ?? null,
                    'coretax_qr_code' => $result['qr_code'] ?? null,
                    'coretax_serial_number' => $result['serial_number'] ?? null,
                    'coretax_status' => 'sent',
                    'coretax_sent_at' => now(),
                    'coretax_response' => $result,
                    'status' => 'sent'
                ]);

                $log->markAsSuccess($result);
                return [
                    'success' => true,
                    'data' => $result,
                    'message' => 'Tax invoice created successfully'
                ];
            } else {
                $taxInvoice->update([
                    'coretax_status' => 'rejected',
                    'status' => 'failed'
                ]);

                $log->markAsFailed('API Error: ' . ($result['message'] ?? 'Unknown error'));
                return [
                    'success' => false,
                    'message' => $result['message'] ?? 'Failed to create tax invoice'
                ];
            }

        } catch (\Exception $e) {
            $taxInvoice->update([
                'coretax_status' => 'rejected',
                'status' => 'failed'
            ]);

            $log->markAsFailed('Exception: ' . $e->getMessage());
            Log::error('CoreTax invoice creation error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Unable to create tax invoice at this time'
            ];
        }
    }

    /**
     * Check tax invoice status with CoreTax
     */
    public function checkInvoiceStatus(TaxInvoice $taxInvoice): array
    {
        try {
            if (!$taxInvoice->coretax_invoice_id) {
                return [
                    'success' => false,
                    'message' => 'No CoreTax invoice ID available'
                ];
            }

            $log = $this->createLog('check_status', [
                'tax_invoice_id' => $taxInvoice->id,
                'coretax_invoice_id' => $taxInvoice->coretax_invoice_id
            ]);

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiToken,
                    'Content-Type' => 'application/json',
                ])
                ->get($this->baseUrl . '/invoices/' . $taxInvoice->coretax_invoice_id . '/status');

            $result = $response->json();

            if ($response->successful()) {
                // Update status based on CoreTax response
                $status = $result['status'] ?? 'unknown';
                $taxInvoice->update([
                    'coretax_status' => $status,
                    'status' => $this->mapCoreTaxStatus($status),
                    'coretax_approved_at' => ($status === 'approved') ? now() : null,
                ]);

                $log->markAsSuccess($result);
                return [
                    'success' => true,
                    'status' => $status,
                    'data' => $result,
                    'message' => 'Status checked successfully'
                ];
            } else {
                $log->markAsFailed('API Error: ' . ($result['message'] ?? 'Unknown error'));
                return [
                    'success' => false,
                    'message' => $result['message'] ?? 'Failed to check invoice status'
                ];
            }

        } catch (\Exception $e) {
            $log->markAsFailed('Exception: ' . $e->getMessage());
            Log::error('CoreTax status check error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Unable to check invoice status at this time'
            ];
        }
    }

    /**
     * Sync data with CoreTax
     */
    public function syncData(array $data): array
    {
        try {
            $log = $this->createLog('sync_data', $data);

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiToken,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . '/sync', $data);

            $result = $response->json();

            if ($response->successful()) {
                $log->markAsSuccess($result);
                return [
                    'success' => true,
                    'data' => $result,
                    'message' => 'Data synced successfully'
                ];
            } else {
                $log->markAsFailed('Sync failed: ' . ($result['message'] ?? 'Unknown error'));
                return [
                    'success' => false,
                    'message' => $result['message'] ?? 'Data sync failed'
                ];
            }

        } catch (\Exception $e) {
            $log->markAsFailed('Exception: ' . $e->getMessage());
            Log::error('CoreTax sync error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Unable to sync data at this time'
            ];
        }
    }

    /**
     * Build invoice payload for CoreTax API
     */
    private function buildInvoicePayload(TaxInvoice $taxInvoice): array
    {
        $transaction = $taxInvoice->transaction;

        return [
            'invoice_number' => $taxInvoice->invoice_number,
            'invoice_date' => $taxInvoice->invoice_date->format('Y-m-d'),
            'tax_type' => $taxInvoice->tax_type,
            'tax_rate' => $taxInvoice->tax_rate,
            'subtotal' => $taxInvoice->subtotal,
            'tax_amount' => $taxInvoice->tax_amount,
            'total_amount' => $taxInvoice->total_amount,

            // Customer information
            'customer' => [
                'name' => $taxInvoice->customer_name,
                'npwp' => $taxInvoice->customer_npwp,
                'nik' => $taxInvoice->customer_nik,
                'address' => $taxInvoice->customer_address,
                'type' => $taxInvoice->customer_type,
            ],

            // Company information
            'company' => [
                'name' => $taxInvoice->branch->name ?? 'Unknown',
                'npwp' => $taxInvoice->branch->npwp ?? null,
                'address' => $taxInvoice->branch->address ?? null,
            ],

            // Transaction details
            'transaction_id' => $transaction->id,
            'items' => $taxInvoice->items ?? [],

            // Additional metadata
            'branch_code' => $taxInvoice->branch_id,
            'external_reference' => $transaction->id,
        ];
    }

    /**
     * Map CoreTax status to internal status
     */
    private function mapCoreTaxStatus(string $coretaxStatus): string
    {
        return match($coretaxStatus) {
            'approved', 'success' => 'approved',
            'rejected', 'failed' => 'rejected',
            'pending', 'processing' => 'pending',
            default => 'unknown',
        };
    }

    /**
     * Create a tax log entry
     */
    private function createLog(string $action, array $requestPayload = null): TaxLog
    {
        $branchId = session('active_branch') ?? (auth()->user()->branch_id ?? null);
        $userId = auth()->id();

        return TaxLog::create([
            'branch_id' => $branchId,
            'user_id' => $userId,
            'endpoint' => $this->baseUrl . '/' . str_replace('_', '-', $action),
            'method' => 'POST',
            'action' => $action,
            'request_payload' => $requestPayload,
            'status' => 'pending',
        ]);
    }

    /**
     * Check if CoreTax integration is available
     */
    public function isAvailable(): bool
    {
        return !empty($this->apiToken);
    }

    /**
     * Get API configuration
     */
    public function getConfig(): array
    {
        return [
            'base_url' => $this->baseUrl,
            'has_token' => !empty($this->apiToken),
            'timeout' => $this->timeout,
        ];
    }
}