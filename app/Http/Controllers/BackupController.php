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

    public function index()
    {
        $data = database_backup::all();

        return view('backup.index', compact('data'));
    }
    public function create()
    {
         // Generate the backup
         Artisan::call('database:backup');

         return back()->with('message', 'Database Backup Created');
    }

    public function delete($id)
    {
        $backup = database_backup::find($id);
        @unlink($backup->path);
        $backup->delete();

        return back()->with('error', 'Database Dackup Deleted');
    }

    public function downloadBackup($id)
    {
        $backup = database_backup::find($id);

        return response()->download($backup->path);
    }


}
