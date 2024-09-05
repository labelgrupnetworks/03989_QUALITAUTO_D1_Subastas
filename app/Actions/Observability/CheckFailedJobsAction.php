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
		$failedJobs = DB::table('FAILED_JOBS')->get();

		//no podemos hacer el where sobre la tabla poque el campo queue es clob...
		$numberToFailedJobs = $failedJobs->where('queue', Config::get('app.queue_env'))
			->count();

		if ($numberToFailedJobs === 0) {
			return;
		}

		$recipients = (new RecipientsNotificationList)->getDebugTeam();
		$notification = Notification::route('mail', $recipients);
		$this->sendNotification($notification, new FailedJobs($numberToFailedJobs));
	}
}
