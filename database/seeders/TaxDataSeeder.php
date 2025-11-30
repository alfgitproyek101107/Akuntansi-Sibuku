<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TaxSetting;
use App\Models\TaxInvoice;
use App\Models\TaxLog;
use App\Models\Branch;
use App\Models\User;
use App\Models\Transaction;

class TaxDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing branches and users
        $branches = Branch::all();
        $users = User::all();

        if ($branches->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No branches or users found. Please run other seeders first.');
            return;
        }

        // Create Tax Settings for each branch
        foreach ($branches as $branch) {
            $user = $users->where('branch_id', $branch->id)->first() ?? $users->first();

            // Create PPN 11%
            TaxSetting::create([
                'user_id' => $user->id,
                'name' => 'PPN 11%',
                'rate' => 11.00,
                'type' => 'percent',
                'branch_id' => $branch->id,
            ]);

            // Create PPh 21 5%
            TaxSetting::create([
                'user_id' => $user->id,
                'name' => 'PPh 21 5%',
                'rate' => 5.00,
                'type' => 'percent',
                'branch_id' => $branch->id,
            ]);

            // Create PPh 23 2%
            TaxSetting::create([
                'user_id' => $user->id,
                'name' => 'PPh 23 2%',
                'rate' => 2.00,
                'type' => 'percent',
                'branch_id' => $branch->id,
            ]);
        }

        // Get some transactions to create tax invoices for
        $transactions = Transaction::where('type', 'income')
            ->where('amount', '>', 1000000) // Only for transactions over 1M
            ->limit(10)
            ->get();

        if ($transactions->isNotEmpty()) {
            foreach ($transactions as $transaction) {
                $taxSetting = TaxSetting::where('branch_id', $transaction->branch_id)->first();
                if (!$taxSetting) continue;

                $subtotal = $transaction->amount / 1.11; // Assuming 11% PPN
                $taxAmount = $transaction->amount - $subtotal;

                TaxInvoice::create([
                    'transaction_id' => $transaction->id,
                    'branch_id' => $transaction->branch_id,
                    'user_id' => $transaction->user_id,
                    'invoice_number' => $this->generateInvoiceNumber(),
                    'invoice_date' => $transaction->date,
                    'invoice_type' => 'ppn',
                    'tax_type' => 'output',
                    'subtotal' => $subtotal,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $transaction->amount,
                    'tax_rate' => 11.00,
                    'customer_name' => 'PT Customer ' . rand(1, 100),
                    'customer_npwp' => $this->generateNPWP(),
                    'customer_address' => 'Jl. Customer No. ' . rand(1, 50),
                    'customer_type' => collect(['company', 'personal'])->random(),
                    'coretax_invoice_id' => 'CT' . rand(100000, 999999),
                    'coretax_qr_code' => 'QR' . strtoupper(substr(md5(rand()), 0, 16)),
                    'coretax_serial_number' => 'SN' . rand(10000000, 99999999),
                    'coretax_status' => collect(['draft', 'sent', 'approved', 'rejected'])->random(),
                    'coretax_sent_at' => rand(0, 1) ? now()->subDays(rand(1, 30)) : null,
                    'coretax_approved_at' => rand(0, 1) ? now()->subDays(rand(1, 15)) : null,
                    'status' => collect(['draft', 'generated', 'sent', 'approved'])->random(),
                    'notes' => 'Faktur pajak untuk transaksi ' . $transaction->description,
                    'items' => json_encode([
                        [
                            'name' => 'Jasa ' . rand(1, 10),
                            'quantity' => rand(1, 5),
                            'price' => $subtotal / rand(1, 5),
                            'total' => $subtotal
                        ]
                    ]),
                ]);
            }
        }

        // Create Tax Logs
        $taxInvoices = TaxInvoice::all();
        $actions = ['validate_npwp', 'create_invoice', 'sync_data', 'check_status', 'update_invoice'];
        $statuses = ['pending', 'success', 'failed', 'retry'];

        for ($i = 0; $i < 20; $i++) {
            $branch = $branches->random();
            $user = $users->where('branch_id', $branch->id)->first() ?? $users->first();
            $taxInvoice = $taxInvoices->random();

            TaxLog::create([
                'branch_id' => $branch->id,
                'user_id' => $user->id,
                'tax_invoice_id' => rand(0, 1) ? $taxInvoice->id : null,
                'endpoint' => '/api/v1/' . collect($actions)->random(),
                'method' => collect(['GET', 'POST', 'PUT', 'PATCH'])->random(),
                'action' => collect($actions)->random(),
                'request_payload' => json_encode([
                    'invoice_number' => $taxInvoice->invoice_number ?? 'TEST' . rand(1000, 9999),
                    'amount' => rand(1000000, 10000000),
                    'tax_rate' => 11.00
                ]),
                'response_data' => json_encode([
                    'status' => 'success',
                    'message' => 'Invoice processed successfully',
                    'reference_id' => 'REF' . rand(100000, 999999)
                ]),
                'http_status_code' => collect([200, 201, 400, 401, 500])->random(),
                'status' => collect($statuses)->random(),
                'error_message' => rand(0, 1) ? 'Connection timeout' : null,
                'error_code' => rand(0, 1) ? 'TIMEOUT' : null,
                'attempt_number' => rand(1, 3),
                'max_attempts' => 3,
                'next_retry_at' => rand(0, 1) ? now()->addMinutes(rand(5, 60)) : null,
                'completed_at' => rand(0, 1) ? now()->subMinutes(rand(1, 60)) : null,
                'external_reference' => 'EXT' . rand(100000, 999999),
                'processing_time' => rand(100, 5000) / 1000, // 0.1 to 5 seconds
                'notes' => rand(0, 1) ? 'API call completed successfully' : null,
            ]);
        }

        $this->command->info('Tax data seeded successfully!');
        $this->command->info('Created ' . $branches->count() . ' tax settings');
        $this->command->info('Created ' . $taxInvoices->count() . ' tax invoices');
        $this->command->info('Created 20 tax logs');
    }

    /**
     * Generate a random NPWP number
     */
    private function generateNPWP(): string
    {
        return sprintf(
            '%02d.%03d.%03d.%01d-%03d.%03d',
            rand(10, 99), // Branch code
            rand(100, 999), // Serial number part 1
            rand(100, 999), // Serial number part 2
            rand(0, 9), // Check digit
            rand(100, 999), // Tax office code
            rand(100, 999) // Registration number
        );
    }

    /**
     * Generate a unique invoice number
     */
    private function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $month = date('m');
        $sequence = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);

        return "FP{$year}{$month}{$sequence}";
    }
}