<?php

namespace App\Http\Controllers;

use App\Models\database_backup;
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
         Artisan::call('backup:run --disable-notifications --only-db');

         // Get the path to the latest backup file
         $backupPath = Storage::disk('local')->path(last(Storage::disk('local')->files('/Laravel/')));

         $size = filesize($backupPath);
         database_backup::create(
            [
                'size' => $size,
                'path' => $backupPath
            ]
         );
         // Stream the backup file to the browser as a download
         return response()->json(['message' => 'Backup created successfully']);
    }


}
