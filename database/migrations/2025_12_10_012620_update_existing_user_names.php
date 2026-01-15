<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all team member users
        $users = User::where('role', 'team_member')->get();
        
        foreach ($users as $user) {
            // Get the team name for this user
            $team = DB::table('teams')->where('id', $user->team_id)->first();
            
            if ($team) {
                // Update the user's name to just the team name
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['name' => $team->name]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not reversible since we're modifying data
        // But we can leave it empty
    }
};