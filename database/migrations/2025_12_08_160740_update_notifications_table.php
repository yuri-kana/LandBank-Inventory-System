<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // First, check if table exists
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->json('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        } else {
            // Table exists, add missing columns
            Schema::table('notifications', function (Blueprint $table) {
                if (!Schema::hasColumn('notifications', 'data')) {
                    $table->json('data')->after('type');
                }
                if (!Schema::hasColumn('notifications', 'read_at')) {
                    $table->timestamp('read_at')->nullable()->after('data');
                }
                if (!Schema::hasColumn('notifications', 'notifiable_type')) {
                    $table->string('notifiable_type');
                }
                if (!Schema::hasColumn('notifications', 'notifiable_id')) {
                    $table->unsignedBigInteger('notifiable_id');
                }
            });
        }
    }

    public function down()
    {
        // You can drop the table if you want, but be careful
        // Schema::dropIfExists('notifications');
    }
};