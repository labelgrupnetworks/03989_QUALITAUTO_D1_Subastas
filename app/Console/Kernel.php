<?php

namespace App\Console;

use App\Actions\Observability\CheckFailedJobsAction;
use App\Actions\Observability\HasAuctionAction;
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
		$queueEnv = Config::get('app.queue_env');
		$schedule->command("queue:monitor database:$queueEnv --max=1")
			->name('Monitorizar jobs')
			->everyTwoMinutes();
			//->hourly();
		//->sendOutputTo(storage_path('logs/queue-monitor.log'));

		$schedule->call(new CheckFailedJobsAction)
			->name('Comprobar jobs fallidos')
			//->dailyAt('9:00');
			->everyTwoMinutes();

		$schedule->call(new HasAuctionAction, ['when' => 'week'])
			->name('Comprobar si subasta en una semana')
			->dailyAt('9:00');
			//->everyMinute();

		$schedule->call(new HasAuctionAction, ['when' => 'day'])
			->name('Comprobar si subasta hoy')
			->dailyAt('9:00');
			//->everyMinute();
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
