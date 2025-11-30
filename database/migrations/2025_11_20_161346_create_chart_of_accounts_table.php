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
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Account code like 1001, 2001, etc.
            $table->string('name');
            $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->enum('category', [
                'current_asset', 'fixed_asset', 'current_liability', 'long_term_liability',
                'owner_equity', 'retained_earnings', 'sales_revenue', 'other_revenue',
                'cost_of_goods_sold', 'operating_expense', 'other_expense'
            ]);
            $table->foreignId('parent_id')->nullable()->constrained('chart_of_accounts')->onDelete('cascade');
            $table->decimal('balance', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->integer('level')->default(1); // Hierarchy level
            $table->string('normal_balance')->default('debit'); // 'debit' or 'credit'
            $table->timestamps();

            $table->index(['type', 'category']);
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chart_of_accounts');
    }
};
