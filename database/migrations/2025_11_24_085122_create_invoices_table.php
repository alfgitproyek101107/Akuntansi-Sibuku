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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('invoice_number')->unique();
            $table->string('reference_number')->nullable(); // External reference
            $table->date('invoice_date');
            $table->date('due_date');
            $table->date('payment_date')->nullable();

            // Status & Workflow
            $table->enum('status', ['draft', 'sent', 'viewed', 'partial', 'paid', 'overdue', 'cancelled'])->default('draft');
            $table->enum('payment_terms', ['cash', 'credit_7', 'credit_14', 'credit_30', 'credit_60', 'credit_90'])->default('cash');

            // Relationships
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Invoice creator
            $table->foreignId('sales_order_id')->nullable()->constrained('sales_orders')->onDelete('set null');

            // Financial
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2)->default(0);

            // Payment tracking
            $table->integer('payment_count')->default(0);
            $table->decimal('last_payment_amount', 15, 2)->nullable();
            $table->date('last_payment_date')->nullable();

            // Customer & Billing Info
            $table->text('billing_address')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('billing_contact')->nullable();
            $table->string('billing_phone')->nullable();
            $table->string('billing_email')->nullable();

            // Notes & Terms
            $table->text('notes')->nullable();
            $table->text('terms_and_conditions')->nullable();
            $table->text('footer_text')->nullable();

            // Approval & Audit
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();

            // Email & Communication
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->integer('email_count')->default(0);
            $table->timestamp('last_email_sent_at')->nullable();

            // Recurring & Templates
            $table->boolean('is_recurring')->default(false);
            $table->string('recurring_frequency')->nullable(); // monthly, quarterly, etc.
            $table->date('next_invoice_date')->nullable();

            // Metadata
            $table->json('metadata')->nullable(); // For additional custom fields
            $table->string('currency', 3)->default('IDR');

            $table->timestamps();

            // Indexes
            $table->index(['customer_id', 'status']);
            $table->index(['branch_id', 'invoice_date']);
            $table->index(['status', 'due_date']);
            $table->index(['status', 'invoice_date']);
            $table->index('invoice_number');
            $table->index('user_id');
            $table->index('sales_order_id');
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
