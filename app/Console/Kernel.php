<?php

namespace App\Console;

use App\Actions\Observability\CheckCertificateAction;
use App\Actions\Observability\CheckFailedJobsAction;
use App\Actions\Observability\HasAuctionAction;
use App\Models\V5\Web_Scheduled_Task;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Config;

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
			$schedule->command($task->command_scheduled_tasks)
				->name($task->task_name_scheduled_tasks)
				->cron($task->cron_expression_scheduled_tasks);
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
