<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Task;
use App\Models\TaskResponse;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
| Here you may define all of your Closure based console commands.
| These commands are loaded by the framework and run via Artisan.
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
| Laravel 12 Scheduler (NO Kernel.php)
|--------------------------------------------------------------------------
*/


Schedule::call(function () {
    Task::whereNotNull('task_end')
        ->where('task_end', '<', now())
        ->whereDoesntHave('responses')
        ->each(function ($task) {
            TaskResponse::create([
                'task_id'          => $task->id,
                'teacher_id'       => $task->teacher_id,
                'response_type'    => 'not_complete',
                'd_married_points' => $task->d_married_points,
                'desc'             => 'Auto marked not complete (no response till deadline)',
            ]);
        });
})
->everyMinute()
->name('auto-mark-tasks-not-complete')  // Pehle name set karo
->withoutOverlapping();                  // Phir withoutOverlapping
