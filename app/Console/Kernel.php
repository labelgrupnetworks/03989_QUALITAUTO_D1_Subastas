<?php

namespace App\Console;

use App\Models\V5\Web_Scheduled_Task;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		Web_Scheduled_Task::whereActive()->get()->each(function ($task) use ($schedule) {
			$schedule->command($task->command)
				->name($task->task_name)
				->cron($task->cron_expression);
		});
	}

	/**
	 * Register the commands for the application.
	 *
	 * @return void
	 */
	protected function commands()
	{
		$this->load(__DIR__ . '/Commands');

		require base_path('routes/console.php');
	}
}
