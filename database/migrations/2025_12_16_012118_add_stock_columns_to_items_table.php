<?php
// database/migrations/xxxx_add_stock_columns_to_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->integer('available_stock')->default(0)->after('quantity');
            $table->integer('reserved_stock')->default(0)->after('available_stock');
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['available_stock', 'reserved_stock']);
        });
    }
};