<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Spatie\Backup\BackupDestination\Backup;
use Spatie\Backup\BackupDestination\BackupDestination;
use Spatie\Backup\Tasks\Backup\BackupJobFactory;

class BackupController extends Controller
{
    public function createBackup()
    {
         // Generate the backup
         Artisan::call('backup:run --only-db');

         // Get the path to the latest backup file
         $backupPath = Storage::disk('local')->path(last(Storage::disk('local')->files('/laravel/')));

         // Stream the backup file to the browser as a download
         return response()->download($backupPath)->deleteFileAfterSend(true);
    }

   
}
