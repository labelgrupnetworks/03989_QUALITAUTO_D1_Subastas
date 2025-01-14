<?php

namespace App\Listeners\user;

use App\Events\user\UserNewsletterSubscribed;
use App\Http\Controllers\externalws\mailing\ExternalMailingController;
use App\Models\Newsletter;
use Illuminate\Support\Facades\Log;

class NotifySubscriptionToMailingService
{
	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  object  $event
	 * @return void
	 */
	public function handle(UserNewsletterSubscribed $event)
	{
		(new Newsletter)->subscribeToExternalService($event->email);
	}
}
