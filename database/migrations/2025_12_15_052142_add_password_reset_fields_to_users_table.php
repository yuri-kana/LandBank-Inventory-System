<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Only add columns that don't exist
            if (!Schema::hasColumn('users', 'reset_token')) {
                $table->string('reset_token', 255)->nullable();
            }
            if (!Schema::hasColumn('users', 'reset_token_expires_at')) {
                $table->timestamp('reset_token_expires_at')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['reset_token', 'reset_token_expires_at', 'otp_code', 'otp_expires_at']);
        });
    }
};