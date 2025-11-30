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
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('price', 'sale_price');
            $table->renameColumn('cost_price', 'purchase_price');
            $table->foreignId('tax_rule_id')->nullable()->constrained('tax_rules');
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->boolean('is_tax_included')->default(false);
            $table->enum('tax_type', ['ppn', 'non_pajak', 'ppn_0', 'bebas_pajak'])->default('ppn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['tax_rule_id']);
            $table->dropColumn(['tax_rule_id', 'tax_percentage', 'is_tax_included', 'tax_type']);
            $table->renameColumn('sale_price', 'price');
            $table->renameColumn('purchase_price', 'cost_price');
        });
    }
};
