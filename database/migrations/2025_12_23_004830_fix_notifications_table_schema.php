<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remove the message column if it exists
        if (Schema::hasColumn('notifications', 'message')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->dropColumn('message');
            });
        }
        
        // Also remove the is_read column (Laravel uses read_at)
        if (Schema::hasColumn('notifications', 'is_read')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->dropColumn('is_read');
            });
        }
        
        // Make sure data column exists and is json
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'data')) {
                $table->json('data')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Add back columns if needed
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'message')) {
                $table->text('message')->nullable()->after('type');
            }
            if (!Schema::hasColumn('notifications', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('read_at');
            }
        });
    }
};