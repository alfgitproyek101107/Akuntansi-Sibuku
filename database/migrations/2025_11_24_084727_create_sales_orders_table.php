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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('so_number')->unique();
            $table->date('order_date');
            $table->date('delivery_date')->nullable();
            $table->date('valid_until')->nullable();

            // Status & Workflow
            $table->enum('status', ['draft', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])->default('draft');
            $table->enum('payment_terms', ['cash', 'credit_7', 'credit_14', 'credit_30', 'credit_60', 'credit_90'])->default('cash');
            $table->enum('shipping_method', ['pickup', 'delivery', 'courier', 'truck'])->default('pickup');

            // Relationships
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Sales person
            $table->unsignedBigInteger('quotation_id')->nullable(); // Removed constraint to avoid dependency issues

            // Financial
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2)->default(0);

            // Customer & Shipping Info
            $table->text('customer_notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('shipping_contact')->nullable();
            $table->string('shipping_phone')->nullable();

            // Approval & Audit
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();

            // Metadata
            $table->json('metadata')->nullable(); // For additional custom fields
            $table->string('reference_number')->nullable(); // External reference

            $table->timestamps();

            // Indexes
            $table->index(['customer_id', 'status']);
            $table->index(['branch_id', 'order_date']);
            $table->index(['status', 'order_date']);
            $table->index('so_number');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
