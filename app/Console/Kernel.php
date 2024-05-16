<?php

namespace App\Console;

use App\Actions\Observability\CheckCertificateAction;
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
		$schedule->command("queue:monitor database:$queueEnv --max=3")
			->name('Monitorizar jobs')
			->hourly()
			->between('8:00', '18:00')
			->days([Schedule::MONDAY, Schedule::TUESDAY, Schedule::WEDNESDAY, Schedule::THURSDAY, Schedule::FRIDAY]);
			//si necesitamos guardar la salida en un archivo
			//->sendOutputTo(storage_path('logs/queue-monitor.log'));

		$schedule->call(new CheckFailedJobsAction)
			->name('Comprobar jobs fallidos')
			->days([Schedule::MONDAY, Schedule::TUESDAY, Schedule::WEDNESDAY, Schedule::THURSDAY, Schedule::FRIDAY])
			->dailyAt('9:00');

		$schedule->call(new HasAuctionAction, ['when' => 'week'])
			->name('Comprobar si subasta en una semana')
			->days([Schedule::MONDAY, Schedule::TUESDAY, Schedule::WEDNESDAY, Schedule::THURSDAY, Schedule::FRIDAY])
			->dailyAt('10:00');

		$schedule->call(new HasAuctionAction, ['when' => 'day'])
			->name('Comprobar si subasta hoy')
			->days([Schedule::MONDAY, Schedule::TUESDAY, Schedule::WEDNESDAY, Schedule::THURSDAY, Schedule::FRIDAY])
			->dailyAt('9:00');

		// activar cuando se pueda intstalar el paquete "spatie/ssl-certificate": "2.4"
		// $schedule->call(new CheckCertificateAction)
		// 	->name('Comprobar certificado')
		// 	->dailyAt('9:30');
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
