<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add only profile_photo_path if it doesn't exist
            if (!Schema::hasColumn('users', 'profile_photo_path')) {
                $table->string('profile_photo_path')->nullable()->after('email');
            }
            
            // We already have reset_token and reset_token_expires_at from earlier migration
            // We DON'T need otp_code and otp_expires_at
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Only drop profile_photo_path if it exists
            if (Schema::hasColumn('users', 'profile_photo_path')) {
                $table->dropColumn('profile_photo_path');
            }
        });
    }
};