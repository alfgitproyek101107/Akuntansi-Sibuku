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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // User information
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable();

            // Branch information
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('branch_name')->nullable();

            // Activity details
            $table->string('action_type'); // create, update, delete, login, logout, export, etc.
            $table->string('model_type')->nullable(); // App\Models\User, App\Models\Transaction, etc.
            $table->unsignedBigInteger('model_id')->nullable(); // ID of the affected record
            $table->string('model_name')->nullable(); // Human-readable name of the affected record

            // Detailed change tracking
            $table->json('old_values')->nullable(); // Previous values (before change)
            $table->json('new_values')->nullable(); // New values (after change)
            $table->json('changed_fields')->nullable(); // List of fields that were changed

            // Context information
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('session_id')->nullable();
            $table->json('request_data')->nullable(); // Additional request context

            // Description and notes
            $table->text('description')->nullable();
            $table->text('notes')->nullable();

            // Status and metadata
            $table->string('status')->default('completed'); // completed, failed, pending
            $table->json('metadata')->nullable(); // Additional structured data
            $table->timestamp('occurred_at')->useCurrent();

            // Indexes for performance
            $table->index(['user_id', 'occurred_at']);
            $table->index(['model_type', 'model_id']);
            $table->index(['action_type', 'occurred_at']);
            $table->index(['branch_id', 'occurred_at']);
            $table->index('occurred_at');

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
