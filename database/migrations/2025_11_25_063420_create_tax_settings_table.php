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
        Schema::create('tax_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Company Tax Profile
            $table->string('company_name');
            $table->string('npwp')->nullable();
            $table->text('company_address')->nullable();
            $table->boolean('is_pkp')->default(false);

            // Tax Rates
            $table->decimal('ppn_rate', 5, 2)->default(11.00); // 11%
            $table->decimal('ppn_umkm_rate', 5, 2)->default(1.10); // 1.1%
            $table->decimal('pph_21_rate', 5, 2)->default(5.00); // 5%
            $table->decimal('pph_22_rate', 5, 2)->default(1.50); // 1.5%
            $table->decimal('pph_23_rate', 5, 2)->default(2.00); // 2%

            // CoreTax API Settings
            $table->string('coretax_api_token')->nullable();
            $table->string('coretax_base_url')->default('https://api.coretax.com');
            $table->boolean('auto_sync_enabled')->default(false);
            $table->integer('sync_retry_attempts')->default(3);

            // Tax Calculation Preferences
            $table->boolean('include_tax_in_price')->default(false); // Price includes tax or not
            $table->boolean('auto_calculate_tax')->default(true);
            $table->boolean('require_tax_invoice')->default(false);
            $table->string('default_tax_type')->default('ppn'); // ppn, pph, none

            // Branch-specific settings
            $table->boolean('enable_branch_tax')->default(true);
            $table->json('tax_exempt_products')->nullable(); // Product IDs that are tax-exempt

            $table->timestamps();

            $table->index(['branch_id', 'user_id']);
            $table->index(['npwp']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_settings');
    }
};
