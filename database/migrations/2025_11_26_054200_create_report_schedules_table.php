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
        Schema::create('report_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('report_type', ['daily', 'weekly', 'monthly']);
            $table->time('scheduled_time');
            $table->string('whatsapp_number');
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable(); // Additional settings like report format, filters, etc.
            $table->timestamp('last_sent_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'branch_id']);
            $table->index(['report_type', 'is_active']);
            $table->index('scheduled_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_schedules');
    }
};