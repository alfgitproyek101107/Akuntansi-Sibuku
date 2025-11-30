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
        Schema::table('transfers', function (Blueprint $table) {
            $table->boolean('is_demo')->default(false)->after('branch_id');
            $table->decimal('fee', 15, 2)->default(0)->after('amount');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('completed')->after('fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->dropColumn(['is_demo', 'fee', 'status']);
        });
    }
};
