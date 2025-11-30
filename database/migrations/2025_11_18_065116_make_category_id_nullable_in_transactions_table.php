<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if category_id is already nullable
        $columnInfo = DB::select("SHOW COLUMNS FROM transactions WHERE Field = 'category_id'");

        if (!empty($columnInfo) && strpos($columnInfo[0]->Type, 'NOT NULL') !== false) {
            // Column is NOT NULL, need to make it nullable
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropForeign(['category_id']);
                $table->integer('category_id')->nullable()->change();
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            });
        }
        // If already nullable, do nothing
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the process - make category_id NOT NULL again
        Schema::table('transactions', function (Blueprint $table) {
            // Drop the existing foreign key constraint first
            $table->dropForeign(['category_id']);
            // Make category_id NOT nullable
            $table->integer('category_id')->nullable(false)->change();
            // Re-add the foreign key constraint
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }
};
