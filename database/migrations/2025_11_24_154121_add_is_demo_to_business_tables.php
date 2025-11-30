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
        // Add is_demo to tables that have branch_id
        $tablesWithBranchId = [
            'transfers',
            'transactions',
            'accounts',
            'categories',
            'products',
            'services',
            'customers',
            'stock_movements',
            'recurring_templates'
        ];

        foreach ($tablesWithBranchId as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->boolean('is_demo')->default(false)->after('branch_id');
                $table->index(['is_demo', 'branch_id']);
            });
        }

        // Add is_demo to tables without branch_id
        $tablesWithoutBranchId = [
            'product_categories',
            'journal_lines',
            'users'
        ];

        foreach ($tablesWithoutBranchId as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->boolean('is_demo')->default(false)->after('id');
                $table->index(['is_demo']);
            });
        }

        // Journal entries might not have branch_id yet, skip for now
        // Schema::table('journal_entries', function (Blueprint $table) {
        //     $table->boolean('is_demo')->default(false)->after('id');
        //     $table->index(['is_demo']);
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove is_demo from tables that have it
        $tables = [
            'transfers',
            'transactions',
            'accounts',
            'categories',
            'products',
            'product_categories',
            'services',
            'customers',
            'stock_movements',
            'recurring_templates',
            'journal_lines',
            'users'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('is_demo');
            });
        }
    }
};
