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
        // Add branch_id to categories table (nullable for existing data)
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->index(['branch_id', 'type']);
        });

        // Add branch_id to transfers table (nullable for existing data)
        Schema::table('transfers', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->index(['branch_id', 'date']);
        });

        // Add branch_id to recurring_templates table (nullable for existing data)
        Schema::table('recurring_templates', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->index(['branch_id', 'is_active']);
        });

        // Add branch_id to transactions table
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->after('id')->constrained()->onDelete('cascade');
        });

        // Add status field to transactions for approval workflow
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('status')->default('posted')->after('amount'); // draft, pending_approval, approved, posted
            $table->foreignId('approved_by')->nullable()->after('status')->constrained('users');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->index(['status', 'branch_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropForeign(['approved_by']);
            $table->dropColumn('approved_by');
            $table->dropColumn('approved_at');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });

        Schema::table('transfers', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });

        Schema::table('recurring_templates', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });
    }
};
