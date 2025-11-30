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
        Schema::create('user_branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->string('role_name')->default('staff'); // staff, supervisor, manager, admin
            $table->boolean('is_default')->default(false); // Default branch for user
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Unique constraint: one user can only have one role per branch
            $table->unique(['user_id', 'branch_id']);

            $table->index(['user_id', 'is_active']);
            $table->index(['branch_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_branches');
    }
};
