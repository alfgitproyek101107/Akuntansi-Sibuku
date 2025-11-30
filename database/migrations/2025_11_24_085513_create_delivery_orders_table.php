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
        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('do_number')->unique();
            $table->string('reference_number')->nullable();
            $table->date('do_date');
            $table->date('delivery_date');
            $table->date('expected_delivery_date');

            // Status & Workflow
            $table->enum('status', ['draft', 'confirmed', 'in_transit', 'delivered', 'cancelled'])->default('draft');
            $table->enum('shipping_method', ['pickup', 'delivery', 'courier', 'truck'])->default('delivery');

            // Relationships
            $table->foreignId('sales_order_id')->constrained('sales_orders')->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('set null');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Created by

            // Delivery Details
            $table->text('shipping_address');
            $table->string('shipping_contact');
            $table->string('shipping_phone');
            $table->string('shipping_email')->nullable();

            // Logistics
            $table->string('courier_name')->nullable();
            $table->string('tracking_number')->nullable();
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->text('shipping_notes')->nullable();

            // Delivery Tracking
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->string('delivered_by')->nullable(); // Person who received
            $table->text('delivery_notes')->nullable();
            $table->text('recipient_signature')->nullable(); // Could be base64 image

            // Quantities
            $table->integer('total_items')->default(0);
            $table->decimal('total_quantity', 10, 2)->default(0);
            $table->decimal('delivered_quantity', 10, 2)->default(0);
            $table->decimal('remaining_quantity', 10, 2)->default(0);

            // Approval & Audit
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();

            // Quality Control
            $table->enum('quality_check', ['pending', 'passed', 'failed', 'partial'])->default('pending');
            $table->text('quality_notes')->nullable();
            $table->foreignId('quality_checked_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('quality_checked_at')->nullable();

            // Metadata
            $table->json('metadata')->nullable();
            $table->text('internal_notes')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['sales_order_id', 'status']);
            $table->index(['customer_id', 'status']);
            $table->index(['branch_id', 'do_date']);
            $table->index(['status', 'delivery_date']);
            $table->index('do_number');
            $table->index('user_id');
            $table->index('tracking_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_orders');
    }
};
