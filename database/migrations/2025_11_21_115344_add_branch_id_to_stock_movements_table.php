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
        // Step 1: Add branch_id column as nullable
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->after('user_id')->constrained('branches')->onDelete('cascade');
            $table->index(['branch_id', 'product_id', 'date']);
        });

        // Step 2: Populate existing stock_movements with product's branch
        DB::statement("
            UPDATE stock_movements
            SET branch_id = (
                SELECT branch_id FROM products WHERE products.id = stock_movements.product_id
            )
            WHERE branch_id IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });
    }
};
