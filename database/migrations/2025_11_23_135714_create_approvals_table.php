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
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();

            // Link to workflow
            $table->unsignedBigInteger('workflow_id');
            $table->string('workflow_name');

            // The item being approved
            $table->string('approvable_type'); // App\Models\Transaction, App\Models\Transfer, etc.
            $table->unsignedBigInteger('approvable_id');
            $table->string('approvable_title'); // Human-readable title

            // Approval details
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('currency', 3)->default('IDR');
            $table->text('description')->nullable();
            $table->json('item_details')->nullable(); // Store snapshot of the item

            // Current approval status
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled', 'expired'])->default('pending');
            $table->integer('current_level')->default(1); // Current approval level
            $table->integer('total_levels')->default(1); // Total approval levels required

            // Approval progress
            $table->json('level_progress'); // [{"level": 1, "required": 2, "approved": 1, "rejected": 0}, ...]
            $table->json('approver_history'); // [{"user_id": 1, "action": "approved", "level": 1, "timestamp": "..."}, ...]

            // Assigned approvers
            $table->json('current_approvers'); // Users who can approve at current level
            $table->json('all_assigned_approvers'); // All users assigned to this approval

            // Timing
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('due_date')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('expired_at')->nullable();

            // Requestor info
            $table->unsignedBigInteger('requested_by');
            $table->string('requested_by_name');
            $table->unsignedBigInteger('branch_id')->nullable();

            // Rejection info
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->string('rejected_by_name')->nullable();

            // Urgency and priority
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->boolean('is_urgent')->default(false);

            // Auto-approval
            $table->boolean('auto_approved')->default(false);
            $table->text('auto_approval_reason')->nullable();

            // Metadata
            $table->json('metadata')->nullable();
            $table->text('notes')->nullable();

            // Indexes
            $table->index(['approvable_type', 'approvable_id']);
            $table->index(['workflow_id', 'status']);
            $table->index(['requested_by', 'status']);
            $table->index(['branch_id', 'status']);
            $table->index(['status', 'requested_at']);
            $table->index(['due_date', 'status']);
            $table->index('current_level');

            // Foreign keys
            $table->foreign('workflow_id')->references('id')->on('approval_workflows')->onDelete('cascade');
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};
