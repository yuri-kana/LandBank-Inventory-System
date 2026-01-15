<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('team_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('claimed_by')->nullable()->after('status');
            $table->timestamp('claimed_at')->nullable()->after('claimed_by');
            $table->foreign('claimed_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('team_requests', function (Blueprint $table) {
            //
        });
    }
};
