<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('password_changed_at')->nullable();
            $table->boolean('password_change_required')->default(false);
            $table->integer('login_count')->default(0);
            $table->timestamp('first_login_at')->nullable();
            $table->timestamp('last_password_reset_request')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'password_changed_at',
                'password_change_required',
                'login_count',
                'first_login_at',
                'last_password_reset_request'
            ]);
        });
    }
};