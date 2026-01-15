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
        Schema::table('monthly_reports', function (Blueprint $table) {
            // Add total_claimed column if it doesn't exist
            if (!Schema::hasColumn('monthly_reports', 'total_claimed')) {
                $table->integer('total_claimed')->default(0)->after('total_restocked');
            }
            
            // Add report_generated_at column if it doesn't exist
            if (!Schema::hasColumn('monthly_reports', 'report_generated_at')) {
                $table->timestamp('report_generated_at')->nullable()->after('fast_depleting_items');
            }
            
            // Add is_finalized column if it doesn't exist
            if (!Schema::hasColumn('monthly_reports', 'is_finalized')) {
                $table->boolean('is_finalized')->default(false)->after('report_generated_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monthly_reports', function (Blueprint $table) {
            $table->dropColumn(['total_claimed', 'report_generated_at', 'is_finalized']);
        });
    }
};