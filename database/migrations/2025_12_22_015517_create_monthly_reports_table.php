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
        Schema::create('monthly_reports', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->tinyInteger('month'); // 1-12
            $table->integer('beginning_stock_value')->default(0);
            $table->integer('total_requests')->default(0);
            $table->integer('total_restocked')->default(0);
            $table->integer('total_claimed')->default(0); // ADDED: Items claimed
            $table->integer('ending_stock_value')->default(0);
            $table->json('most_requested_items')->nullable();
            $table->json('fast_depleting_items')->nullable();
            $table->timestamp('report_generated_at')->nullable(); // ADDED: When report was generated
            $table->boolean('is_finalized')->default(false); // ADDED: Mark as final
            $table->timestamps();
            
            // Add unique constraint for year-month combination
            $table->unique(['year', 'month']);
            
            // Add indexes for better performance
            $table->index(['year', 'month']);
            $table->index('is_finalized');
            $table->index('report_generated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_reports');
    }
};