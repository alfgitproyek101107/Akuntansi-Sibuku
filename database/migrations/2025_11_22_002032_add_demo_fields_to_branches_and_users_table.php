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
        Schema::table('branches', function (Blueprint $table) {
            $table->boolean('is_demo')->default(false)->after('email');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('demo_mode')->default(false)->after('locked_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn('is_demo');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('demo_mode');
        });
    }
};
