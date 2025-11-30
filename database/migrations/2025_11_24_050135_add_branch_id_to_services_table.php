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
        Schema::table('services', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->after('user_id')->constrained('branches')->onDelete('cascade');
            $table->index(['branch_id', 'is_active']);
        });

        // Populate existing services with user's default branch
        DB::statement("
            UPDATE services
            SET branch_id = (
                SELECT branch_id FROM users WHERE users.id = services.user_id
            )
            WHERE branch_id IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropIndex(['branch_id', 'is_active']);
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });
    }
};
