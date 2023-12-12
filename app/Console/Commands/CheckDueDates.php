<?php

namespace App\Console\Commands;

use App\Models\notifications;
use App\Models\todo;
use Illuminate\Console\Command;

class CheckDueDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

     protected $signature = 'check:due-dates';

     protected $description = 'Check for expired due dates and create notifications.';

    /**
     * The console command description.
     *
     * @var string
     */

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $todos = todo::whereDate('due', '<', now())->get();
        foreach ($todos as $todo) {
            // Create a notification for each expired todo
            $check = notifications::where('refID', $todo->refID)->count();
            if($check == 0){
                notifications::create([
                    'warehouseID' => $todo->warehouseID,
                    'content' => $todo->title,
                    'date' => $todo->due,
                    'level' => $todo->level,
                    'refID' => $todo->refID,
                ]);
            }

        }

        $this->info('Due date check completed.');
    }
}
