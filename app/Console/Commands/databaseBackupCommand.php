<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Spatie\DbDumper\Databases\MySql;;

class databaseBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is to backup Database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = now();
       File::put('dump.sql', '');
        Mysql::create()
        ->setDbName(env('DB_DATABASE'))
        ->setUserName(env('DB_USERNAME'))
        ->setPassword(env('DB_PASSWORD'))
        ->setHost(env('DB_HOST'))
        ->setPort(env('DB_PORT'))
        ->dumpToFile(storage_path("/db-backups/$date.sql"));
    }
}
