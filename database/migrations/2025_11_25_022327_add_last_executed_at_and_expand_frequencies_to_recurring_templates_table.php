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
        Schema::table('recurring_templates', function (Blueprint $table) {
            $table->timestamp('last_executed_at')->nullable()->after('next_date');
            $table->dropColumn('frequency');
        });

        Schema::table('recurring_templates', function (Blueprint $table) {
            $table->enum('frequency', ['daily', 'weekly', 'biweekly', 'monthly', 'quarterly', 'yearly'])->default('monthly')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recurring_templates', function (Blueprint $table) {
            $table->dropColumn('last_executed_at');
            $table->dropColumn('frequency');
        });

        Schema::table('recurring_templates', function (Blueprint $table) {
            $table->enum('frequency', ['daily', 'weekly', 'monthly', 'yearly'])->default('monthly')->after('description');
        });
    }
};
