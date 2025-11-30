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
        Schema::create('tax_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tax_invoice_id')->nullable()->constrained()->onDelete('cascade');

            // API Call Details
            $table->string('endpoint');
            $table->string('method')->default('POST'); // GET, POST, PUT, etc.
            $table->string('action'); // validate_npwp, create_invoice, sync_data, etc.

            // Request/Response Data
            $table->json('request_payload')->nullable();
            $table->json('response_data')->nullable();
            $table->integer('http_status_code')->nullable();

            // Status and Error Handling
            $table->string('status')->default('pending'); // pending, success, failed, retry
            $table->text('error_message')->nullable();
            $table->string('error_code')->nullable();

            // Retry Logic
            $table->integer('attempt_number')->default(1);
            $table->integer('max_attempts')->default(3);
            $table->timestamp('next_retry_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Additional Context
            $table->string('external_reference')->nullable(); // CoreTax reference ID
            $table->decimal('processing_time', 8, 3)->nullable(); // in seconds
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['branch_id', 'status']);
            $table->index(['tax_invoice_id']);
            $table->index(['status', 'next_retry_at']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_logs');
    }
};
