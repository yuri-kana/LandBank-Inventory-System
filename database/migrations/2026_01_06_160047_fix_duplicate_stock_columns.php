<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            // First check which columns already exist
            $columns = [
                'available_stock',
                'reserved_stock', 
                'beginning_stock_30d',
                'total_requested_30d',
                'total_claimed_30d',
                'total_restocked_30d',
                // Skip minimum_stock since it already exists
                'monthly_requested',
                'monthly_claimed',
                'monthly_restocked'
            ];
            
            $existingColumns = Schema::getColumnListing('items');
            
            // Add only the columns that don't exist
            if (!in_array('available_stock', $existingColumns)) {
                $table->integer('available_stock')->default(0)->after('quantity');
            }
            
            if (!in_array('reserved_stock', $existingColumns)) {
                $table->integer('reserved_stock')->default(0)->after('available_stock');
            }
            
            if (!in_array('beginning_stock_30d', $existingColumns)) {
                $table->integer('beginning_stock_30d')->default(0)->comment('Stock 30 days ago');
            }
            
            if (!in_array('total_requested_30d', $existingColumns)) {
                $table->integer('total_requested_30d')->default(0)->comment('Total approved requests in last 30 days');
            }
            
            if (!in_array('total_claimed_30d', $existingColumns)) {
                $table->integer('total_claimed_30d')->default(0)->comment('Total taken in last 30 days');
            }
            
            if (!in_array('total_restocked_30d', $existingColumns)) {
                $table->integer('total_restocked_30d')->default(0)->comment('Total added in last 30 days');
            }
            
            if (!in_array('monthly_requested', $existingColumns)) {
                $table->integer('monthly_requested')->default(0);
            }
            
            if (!in_array('monthly_claimed', $existingColumns)) {
                $table->integer('monthly_claimed')->default(0);
            }
            
            if (!in_array('monthly_restocked', $existingColumns)) {
                $table->integer('monthly_restocked')->default(0);
            }
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $columnsToDrop = [
                'available_stock',
                'reserved_stock',
                'beginning_stock_30d',
                'total_requested_30d',
                'total_claimed_30d',
                'total_restocked_30d',
                'monthly_requested',
                'monthly_claimed',
                'monthly_restocked'
            ];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('items', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};