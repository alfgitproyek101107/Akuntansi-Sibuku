<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tax_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Invoice Details
            $table->string('invoice_number')->unique();
            $table->date('invoice_date');
            $table->string('invoice_type')->default('ppn'); // ppn, pph, etc.
            $table->string('tax_type')->default('output'); // output (penjualan), input (pembelian)

            // Transaction Amounts
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax_amount', 15, 2);
            $table->decimal('total_amount', 15, 2);
            $table->decimal('tax_rate', 5, 2); // 11.00, 1.10, etc.

            // Customer/Vendor Tax Info
            $table->string('customer_name');
            $table->string('customer_npwp')->nullable();
            $table->string('customer_nik')->nullable();
            $table->text('customer_address')->nullable();
            $table->string('customer_type')->default('company'); // company, personal

            // CoreTax Integration
            $table->string('coretax_invoice_id')->nullable();
            $table->string('coretax_qr_code')->nullable();
            $table->string('coretax_serial_number')->nullable();
            $table->string('coretax_status')->default('draft'); // draft, sent, approved, rejected
            $table->timestamp('coretax_sent_at')->nullable();
            $table->timestamp('coretax_approved_at')->nullable();
            $table->json('coretax_response')->nullable(); // Store full API response

            // Status and Notes
            $table->string('status')->default('draft'); // draft, generated, sent, approved
            $table->text('notes')->nullable();
            $table->json('items')->nullable(); // Store invoice items as JSON

            $table->timestamps();

            $table->index(['branch_id', 'invoice_date']);
            $table->index(['transaction_id']);
            $table->index(['coretax_status']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_invoices');
    }
};
