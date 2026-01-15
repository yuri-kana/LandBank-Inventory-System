<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CheckDatabaseSchema extends Command
{
    protected $signature = 'db:check {table?}';
    protected $description = 'Check database table structure';

    public function handle()
    {
        $tableName = $this->argument('table') ?? 'notifications';

        $this->info("📊 Checking table: {$tableName}");

        if (!Schema::hasTable($tableName)) {
            $this->error("❌ Table '{$tableName}' does not exist!");
            
            $this->info("Available tables:");
            $tables = DB::select('SHOW TABLES');
            foreach ($tables as $table) {
                $tableName = array_values((array)$table)[0];
                $this->line("  - {$tableName}");
            }
            return 1;
        }

        $columns = Schema::getColumnListing($tableName);
        $columnDetails = [];

        foreach ($columns as $column) {
            $type = DB::getSchemaBuilder()->getColumnType($tableName, $column);
            $columnDetails[] = [
                'Column' => $column,
                'Type' => $type,
                'Nullable' => Schema::getConnection()->getDoctrineColumn($tableName, $column)->getNotnull() ? 'NO' : 'YES',
                'Default' => Schema::getConnection()->getDoctrineColumn($tableName, $column)->getDefault() ?? 'NULL',
            ];
        }

        $this->info("✅ Table '{$tableName}' exists with " . count($columns) . " columns");
        $this->table(['Column', 'Type', 'Nullable', 'Default'], $columnDetails);

        // Row count
        $rowCount = DB::table($tableName)->count();
        $this->info("📈 Total rows: {$rowCount}");

        if ($rowCount > 0) {
            $this->info("\n📝 Sample data (first 5 rows):");
            $sampleData = DB::table($tableName)->take(5)->get();
            
            foreach ($sampleData as $index => $row) {
                $this->line("\nRow #" . ($index + 1) . ":");
                foreach ((array)$row as $key => $value) {
                    $displayValue = is_string($value) && strlen($value) > 50 
                        ? substr($value, 0, 50) . '...' 
                        : $value;
                    $this->line("  {$key}: {$displayValue}");
                }
            }
        }

        return 0;
    }
}