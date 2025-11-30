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
        Schema::table('income_items', function (Blueprint $table) {
            $table->decimal('tax_rate', 5, 2)->default(0)->after('subtotal');
            $table->decimal('tax_amount', 15, 2)->default(0)->after('tax_rate');
            $table->string('tax_type', 50)->nullable()->after('tax_amount');
            $table->decimal('total_with_tax', 15, 2)->default(0)->after('tax_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('income_items', function (Blueprint $table) {
            $table->dropColumn(['tax_rate', 'tax_amount', 'tax_type', 'total_with_tax']);
        });
    }
};
