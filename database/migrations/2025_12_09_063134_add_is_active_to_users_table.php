<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Check and add missing columns
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('verification_required');
            }
            
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->unique()->after('name');
            }
            
            // Make sure verification_required has default value
            if (Schema::hasColumn('users', 'verification_required')) {
                // Update existing null values to false
                \DB::table('users')
                    ->whereNull('verification_required')
                    ->update(['verification_required' => false]);
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Only drop if you want to rollback
            // $table->dropColumn(['is_active', 'username']);
        });
    }
};