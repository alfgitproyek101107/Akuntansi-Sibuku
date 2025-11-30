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
        // Only create tables that don't exist
        if (!Schema::hasTable('invoice_lines')) {
            // Invoice Lines table (if invoices table exists)
            Schema::create('invoice_lines', function (Blueprint $table) {
                $table->id();
                $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
                $table->string('description');
                $table->integer('quantity')->default(1);
                $table->decimal('unit_price', 15, 2)->default(0);
                $table->decimal('line_total', 15, 2)->default(0);
                $table->decimal('tax_rate', 5, 2)->default(0);
                $table->decimal('tax_amount', 15, 2)->default(0);
                $table->integer('line_number');
                $table->json('metadata')->nullable(); // Product info, etc.
                $table->timestamps();

                $table->index(['invoice_id', 'line_number']);
            });
        }

        // Purchase Bills table (AP - Accounts Payable)
        if (!Schema::hasTable('bills')) {
            Schema::create('bills', function (Blueprint $table) {
                $table->id();
                $table->string('bill_number')->unique(); // Auto-generated
                $table->date('bill_date');
                $table->date('due_date');
                $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
                $table->string('vendor_name'); // Cached for performance
                $table->text('billing_address');
                $table->text('notes')->nullable();
                $table->decimal('subtotal', 15, 2)->default(0);
                $table->decimal('tax_amount', 15, 2)->default(0);
                $table->decimal('discount_amount', 15, 2)->default(0);
                $table->decimal('total_amount', 15, 2)->default(0);
                $table->decimal('paid_amount', 15, 2)->default(0);
                $table->decimal('balance', 15, 2)->default(0);
                $table->enum('status', ['draft', 'received', 'partially_paid', 'paid', 'overdue', 'cancelled'])->default('draft');
                $table->enum('payment_terms', ['cash', 'net_15', 'net_30', 'net_45', 'net_60'])->default('net_30');
                $table->foreignId('journal_entry_id')->nullable()->constrained()->onDelete('set null');
                $table->foreignId('created_by')->constrained('users');
                $table->foreignId('branch_id')->constrained()->onDelete('cascade');
                $table->timestamps();

                // Indexes
                $table->index(['branch_id', 'bill_date']);
                $table->index(['vendor_id', 'status']);
                $table->index(['due_date', 'status']);
                $table->index(['status', 'branch_id']);
                $table->index(['bill_number']);
            });
        }

        // Bill Lines table
        if (!Schema::hasTable('bill_lines')) {
            Schema::create('bill_lines', function (Blueprint $table) {
                $table->id();
                $table->foreignId('bill_id')->constrained()->onDelete('cascade');
                $table->string('description');
                $table->integer('quantity')->default(1);
                $table->decimal('unit_price', 15, 2)->default(0);
                $table->decimal('line_total', 15, 2)->default(0);
                $table->decimal('tax_rate', 5, 2)->default(0);
                $table->decimal('tax_amount', 15, 2)->default(0);
                $table->integer('line_number');
                $table->json('metadata')->nullable(); // Product info, etc.
                $table->timestamps();

                $table->index(['bill_id', 'line_number']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_lines');
        Schema::dropIfExists('bills');
        Schema::dropIfExists('invoice_lines');
        Schema::dropIfExists('invoices');
    }
};
