<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Make sure we have both columns as they're being used
            if (!Schema::hasColumn('notifications', 'message')) {
                $table->text('message')->nullable()->after('type');
            }
            
            if (!Schema::hasColumn('notifications', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('data');
            }
            
            // Ensure data column can store JSON properly
            if (Schema::hasColumn('notifications', 'data')) {
                // Convert existing data if needed
                $table->json('data')->nullable()->change();
            }
        });
        
        // Update existing notifications to have proper data
        $notifications = DB::table('notifications')->get();
        
        foreach ($notifications as $notification) {
            // If data is empty but message exists, populate data from message
            if (empty($notification->data) && !empty($notification->message)) {
                $messageData = is_string($notification->message) ? $notification->message : json_encode($notification->message);
                
                DB::table('notifications')
                    ->where('id', $notification->id)
                    ->update([
                        'data' => json_encode([
                            'message' => $messageData,
                            'type' => $notification->type,
                            'created_at' => $notification->created_at,
                        ]),
                        'updated_at' => now(),
                    ]);
            }
        }
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Don't drop columns in rollback to prevent data loss
            // $table->dropColumn(['message', 'is_read']);
        });
    }
};