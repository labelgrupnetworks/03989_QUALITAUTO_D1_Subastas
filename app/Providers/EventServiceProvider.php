<?php

namespace App\Providers;

use App\Actions\Observability\RecipientsNotificationList;
use App\Events\user\UserNewsletterSubscribed;
use App\Listeners\user\NotifySubscriptionToClientWebService;
use App\Listeners\user\NotifySubscriptionToMailingService;
use App\Listeners\user\SendSubscriptionConfirmationEmail;
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
		UserNewsletterSubscribed::class => [
			SendSubscriptionConfirmationEmail::class,
			NotifySubscriptionToClientWebService::class,
			NotifySubscriptionToMailingService::class,
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
			$recipients = (new RecipientsNotificationList)->getDebugTeam();
			Notification::route('mail', $recipients)->notify(new BusyJobs($event));
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
