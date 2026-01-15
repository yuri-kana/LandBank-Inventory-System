<?php
// database/migrations/xxxx_add_email_verification_fields_to_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add these columns if they don't exist
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'verification_token')) {
                $table->string('verification_token')->nullable();
            }
            if (!Schema::hasColumn('users', 'verification_sent_at')) {
                $table->timestamp('verification_sent_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'verification_required')) {
                $table->boolean('verification_required')->default(true);
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove columns if rolling back
            $table->dropColumn(['email_verified_at', 'verification_token', 'verification_sent_at', 'verification_required']);
        });
    }
};