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
        Schema::table('transactions', function (Blueprint $table) {
            // Tax-related fields
            $table->string('tax_type')->nullable()->after('tax_amount'); // ppn, pph_21, pph_23, etc.
            $table->boolean('is_taxable')->default(true)->after('tax_type');
            $table->foreignId('tax_invoice_id')->nullable()->constrained()->onDelete('set null')->after('is_taxable');

            // Customer tax information for invoice generation
            $table->string('customer_name')->nullable()->after('tax_invoice_id');
            $table->string('customer_npwp')->nullable()->after('customer_name');
            $table->string('customer_nik')->nullable()->after('customer_npwp');
            $table->text('customer_address')->nullable()->after('customer_nik');
            $table->string('customer_type')->default('company')->after('customer_address'); // company, personal

            // Tax calculation flags
            $table->boolean('tax_included_in_price')->default(false)->after('customer_type'); // Price includes tax
            $table->boolean('generate_tax_invoice')->default(false)->after('tax_included_in_price');

            // Indexes
            $table->index(['tax_type', 'branch_id']);
            $table->index(['is_taxable', 'tax_invoice_id']);
            $table->index(['generate_tax_invoice', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['tax_invoice_id']);
            $table->dropColumn([
                'tax_type',
                'is_taxable',
                'tax_invoice_id',
                'customer_name',
                'customer_npwp',
                'customer_nik',
                'customer_address',
                'customer_type',
                'tax_included_in_price',
                'generate_tax_invoice'
            ]);
        });
    }
};
