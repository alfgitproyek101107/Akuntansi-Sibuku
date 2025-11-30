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
        // Only create payments table if it doesn't exist
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->string('payment_number')->unique(); // Auto-generated
                $table->date('payment_date');
                $table->enum('payment_type', ['received', 'made']); // Payment received (AR) or payment made (AP)
                $table->enum('payment_method', ['cash', 'bank_transfer', 'check', 'credit_card', 'debit_card', 'online_payment', 'other']);
                $table->string('reference_number')->nullable(); // Bank reference, check number, etc.
                $table->decimal('amount', 15, 2)->default(0);
                $table->string('currency', 3)->default('IDR');
                $table->decimal('exchange_rate', 10, 6)->default(1); // For multi-currency
                $table->text('notes')->nullable();
                $table->string('receipt_number')->nullable(); // For payment receipts

                // Relationships
                $table->foreignId('customer_id')->nullable()->constrained()->onDelete('cascade'); // For payments received
                $table->foreignId('vendor_id')->nullable()->constrained()->onDelete('cascade'); // For payments made
                $table->foreignId('account_id')->constrained()->onDelete('cascade'); // Bank/cash account used
                $table->foreignId('journal_entry_id')->nullable()->constrained()->onDelete('set null');
                $table->foreignId('created_by')->constrained('users');
                $table->foreignId('approved_by')->nullable()->constrained('users');
                $table->timestamp('approved_at')->nullable();
                $table->foreignId('branch_id')->constrained()->onDelete('cascade');

                $table->timestamps();

                // Indexes
                $table->index(['branch_id', 'payment_date']);
                $table->index(['payment_type', 'branch_id']);
                $table->index(['customer_id', 'payment_date']);
                $table->index(['vendor_id', 'payment_date']);
                $table->index(['account_id']);
                $table->index(['payment_number']);
            });
        }

        // Payment Allocations table (for partial payments against invoices/bills)
        if (!Schema::hasTable('payment_allocations')) {
            Schema::create('payment_allocations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('payment_id')->constrained()->onDelete('cascade');
                $table->morphs('allocatable'); // invoice or bill
                $table->decimal('allocated_amount', 15, 2)->default(0);
                $table->decimal('discount_amount', 15, 2)->default(0); // Early payment discount
                $table->text('notes')->nullable();
                $table->timestamps();

            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
