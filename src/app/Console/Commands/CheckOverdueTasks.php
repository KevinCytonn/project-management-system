<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\Notification;
use Carbon\Carbon;

class CheckOverdueTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-overdue-tasks';
    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
          $now = Carbon::now();

        $tasks = Task::whereNotNull('due_date')
            ->where('status', '!=', 'complete')
            ->where('due_date', '<', $now)
            ->get();

        foreach ($tasks as $task) {
           
            $exists = Notification::where('task_id', $task->id)
                ->where('type', Notification::TYPE_TASK_OVERDUE)
                ->whereDate('created_at', $now->toDateString())
                ->exists();

            if (!$exists && $task->assigned_to) {
                Notification::create([
                    'user_id' => $task->assigned_to,
                    'project_id' => $task->project_id,
                    'task_id' => $task->id,
                    'type' => Notification::TYPE_TASK_OVERDUE,
                    'message' => "Task '{$task->title}' is overdue (was due {$task->due_date->toDateString()}).",
                    'is_read' => false,
                ]);
            }
        }

        $this->info('Overdue check completed. Found ' . $tasks->count() . ' tasks.');
        return 0;
    }
}
