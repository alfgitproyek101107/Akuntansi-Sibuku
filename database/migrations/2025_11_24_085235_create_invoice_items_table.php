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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('sales_order_item_id')->nullable()->constrained('sales_order_items')->onDelete('set null');

            // Product Info (snapshot for historical accuracy)
            $table->string('product_name');
            $table->string('product_sku')->nullable();
            $table->string('product_unit')->default('pcs');
            $table->text('product_description')->nullable();

            // Quantity & Pricing
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price', 15, 2);
            $table->decimal('cost_price', 15, 2)->nullable(); // For margin calculation
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(11.00); // Default PPN rate
            $table->decimal('tax_amount', 15, 2)->default(0);

            // Line totals
            $table->decimal('subtotal', 15, 2); // quantity * unit_price
            $table->decimal('total_amount', 15, 2); // subtotal - discount + tax

            // Delivery tracking (for invoiced items)
            $table->decimal('delivered_quantity', 10, 2)->default(0);
            $table->decimal('invoiced_quantity', 10, 2); // Quantity being invoiced
            $table->decimal('remaining_quantity', 10, 2); // For partial invoicing

            // Notes & Custom fields
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable(); // For custom fields

            // Sort order
            $table->integer('sort_order')->default(0);

            $table->timestamps();

            // Indexes
            $table->index(['invoice_id', 'product_id']);
            $table->index('product_id');
            $table->index('sales_order_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
