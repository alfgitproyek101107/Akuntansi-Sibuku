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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('quotation_number')->unique();
            $table->string('reference_number')->nullable();
            $table->date('quotation_date');
            $table->date('valid_until');
            $table->date('expected_delivery_date')->nullable();

            // Status & Workflow
            $table->enum('status', ['draft', 'sent', 'viewed', 'accepted', 'rejected', 'expired', 'cancelled'])->default('draft');
            $table->enum('payment_terms', ['cash', 'credit_7', 'credit_14', 'credit_30', 'credit_60', 'credit_90'])->default('cash');
            $table->enum('shipping_method', ['pickup', 'delivery', 'courier', 'truck'])->default('pickup');

            // Relationships
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Sales person

            // Financial
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);

            // Customer & Shipping Info
            $table->text('customer_notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('shipping_contact')->nullable();
            $table->string('shipping_phone')->nullable();

            // Terms & Conditions
            $table->text('terms_and_conditions')->nullable();
            $table->text('warranty_info')->nullable();
            $table->text('payment_terms_text')->nullable();

            // Response Tracking
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->enum('response_type', ['accepted', 'rejected', 'modified', 'pending'])->nullable();
            $table->text('response_notes')->nullable();
            $table->foreignId('responded_by')->nullable()->constrained('users')->onDelete('set null');

            // Approval & Audit
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();

            // Follow-up
            $table->timestamp('next_follow_up')->nullable();
            $table->text('follow_up_notes')->nullable();
            $table->integer('follow_up_count')->default(0);

            // Metadata
            $table->json('metadata')->nullable();
            $table->string('currency', 3)->default('IDR');

            $table->timestamps();

            // Indexes
            $table->index(['customer_id', 'status']);
            $table->index(['branch_id', 'quotation_date']);
            $table->index(['status', 'valid_until']);
            $table->index(['status', 'quotation_date']);
            $table->index('quotation_number');
            $table->index('user_id');
            $table->index('valid_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
