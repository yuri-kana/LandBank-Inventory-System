// database/migrations/xxxx_xx_xx_xxxxxx_add_is_read_to_notifications_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('notifications', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('data');
            }
            
            if (!Schema::hasColumn('notifications', 'message')) {
                $table->text('message')->nullable()->after('type');
            }
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['is_read', 'message']);
        });
    }
};