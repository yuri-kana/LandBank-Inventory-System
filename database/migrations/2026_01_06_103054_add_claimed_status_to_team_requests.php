<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // For MySQL: Modify ENUM values
        DB::statement("ALTER TABLE team_requests MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'claimed') DEFAULT 'pending'");
        
        // Alternatively, if the above doesn't work, change to string:
        // Schema::table('team_requests', function (Blueprint $table) {
        //     $table->string('status', 20)->default('pending')->change();
        // });
    }

    public function down()
    {
        // Revert back to original ENUM if needed
        DB::statement("ALTER TABLE team_requests MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
        
        // Or if changed to string:
        // DB::statement("ALTER TABLE team_requests MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
    }
};