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
        Schema::create('approval_workflows', function (Blueprint $table) {
            $table->id();

            // Workflow basic info
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('module_type'); // transaction, transfer, journal_entry, etc.
            $table->string('trigger_condition'); // amount > 1000000, category = 'expense', etc.

            // Approval levels
            $table->json('approval_levels'); // [{"level": 1, "approvers": [1,2,3], "min_approvals": 1}, ...]

            // Conditions
            $table->decimal('min_amount', 15, 2)->nullable();
            $table->decimal('max_amount', 15, 2)->nullable();
            $table->json('category_ids')->nullable(); // Specific categories that require approval
            $table->json('user_ids')->nullable(); // Users who trigger this workflow
            $table->json('branch_ids')->nullable(); // Branches where this applies

            // Settings
            $table->boolean('is_active')->default(true);
            $table->boolean('require_all_levels')->default(false); // true = sequential, false = any level can approve
            $table->integer('auto_approve_after_days')->nullable(); // Auto-approve if no action
            $table->boolean('allow_self_approval')->default(false);

            // Metadata
            $table->json('metadata')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('branch_id')->nullable();

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index(['module_type', 'is_active']);
            $table->index(['branch_id', 'is_active']);
            $table->index('created_by');

            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_workflows');
    }
};
