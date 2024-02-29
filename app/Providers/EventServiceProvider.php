<?php

namespace App\Providers;

use App\Actions\Observability\RecipientsNotificationList;
use App\Notifications\BusyJobs;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Queue\Events\QueueBusy;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class EventServiceProvider extends ServiceProvider
{
	/**
	 * The event listener mappings for the application.
	 *
	 * @var array<class-string, array<int, class-string>>
	 */
	protected $listen = [
		Registered::class => [
			SendEmailVerificationNotification::class,
		],
	];

	/**
	 * Register any events for your application.
	 *
	 * @return void
	 */
	public function boot()
	{
		Event::listen(function (QueueBusy $event) {
			$recipients = (new RecipientsNotificationList)->getWebAlerts();
			foreach ($recipients as $recipient) {
				Notification::route('mail', $recipient)
					->notify(new BusyJobs($event));
			}

			Log::warning("Queue is busy", ['event' => $event]);
		});
	}

	/**
	 * Determine if events and listeners should be automatically discovered.
	 *
	 * @return bool
	 */
	public function shouldDiscoverEvents()
	{
		return false;
	}
}
