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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Vendor code (auto-generated)
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Indonesia');
            $table->string('tax_id')->nullable(); // NPWP
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->enum('vendor_type', ['individual', 'company'])->default('company');
            $table->enum('payment_terms', ['cash', 'net_15', 'net_30', 'net_45', 'net_60'])->default('net_30');
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0); // Outstanding balance
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Indexes
            $table->index(['branch_id', 'is_active']);
            $table->index(['code', 'branch_id']);
            $table->index(['name', 'branch_id']);
            $table->index(['email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
