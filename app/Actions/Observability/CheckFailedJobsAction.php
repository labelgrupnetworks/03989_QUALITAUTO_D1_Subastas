<?php

namespace App\Actions\Observability;

use App\Actions\Traits\ActionNotificable;
use App\Notifications\FailedJobs;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class CheckFailedJobsAction
{
	use ActionNotificable;

	public function __invoke()
	{
		Log::info("Test para comprovar que la tarea se ejecuta y guarda el log en el lugar correcto.");
		$failedJobs = DB::table('FAILED_JOBS')->get();

		//no podemos hacer el where sobre la tabla poque el campo queue es clob...
		$numberToFailedJobs = $failedJobs->where('queue', Config::get('app.queue_env'))
			->count();

		if ($numberToFailedJobs === 0) {
			return;
		}

		$recipients = (new RecipientsNotificationList)->getWebTeam();

		foreach ($recipients as $recipient) {
			$notification = Notification::route('mail', $recipient);
			$this->sendNotification($notification, new FailedJobs($numberToFailedJobs));
		}
	}
}
