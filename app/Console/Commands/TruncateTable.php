<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TruncateTable extends Command
{
    protected $signature = 'table:truncate {table}';

    protected $description = 'Truncate a specific table';

    public function handle()
    {
        $table = $this->argument('table');

        try {
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // Truncate the table
            DB::table($table)->truncate();

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            $this->info("Table '$table' has been truncated.");
        } catch (\Exception $e) {
            $this->error("Failed to truncate table '$table': " . $e->getMessage());
        }
    }
}
