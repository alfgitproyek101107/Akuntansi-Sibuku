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
        // For SQLite compatibility, the enum is stored as TEXT
        // No changes needed as 'transfer' can be stored as string
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse is not needed for this fix
    }
};
