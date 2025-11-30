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
        Schema::create('lock_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->string('period_type')->default('monthly'); // monthly, quarterly, yearly
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_locked')->default(false);
            $table->foreignId('locked_by')->nullable()->constrained('users');
            $table->timestamp('locked_at')->nullable();
            $table->text('lock_reason')->nullable();
            $table->json('allowed_modules')->nullable(); // Which modules are locked
            $table->timestamps();

            $table->unique(['branch_id', 'start_date', 'end_date']);
            $table->index(['branch_id', 'is_locked', 'period_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lock_periods');
    }
};
