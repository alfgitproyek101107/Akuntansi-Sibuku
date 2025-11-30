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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // approval_request, due_date, low_balance, etc.
            $table->string('title');
            $table->text('message');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('notifiable_type')->nullable(); // Related model
            $table->unsignedBigInteger('notifiable_id')->nullable();
            $table->json('data')->nullable(); // Additional data
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('priority')->default('normal'); // low, normal, high, urgent
            $table->string('channel')->default('database'); // database, email, sms
            $table->timestamps();

            $table->index(['user_id', 'is_read']);
            $table->index(['type', 'branch_id']);
            $table->index(['priority', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
