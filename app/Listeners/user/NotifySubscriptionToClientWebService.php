<?php

namespace App\Listeners\user;

use App\Events\user\UserNewsletterSubscribed;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class NotifySubscriptionToClientWebService
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
		if(!Config::get('app.WebServiceClientNewsletter')) {
			return;
		}

		$theme = Config::get('app.theme');
		$rutaClientcontroller = "App\Http\Controllers\\externalws\\$theme\ClientController";

		if (!class_exists($rutaClientcontroller)) {
			return;
		}

		$clientController = new $rutaClientcontroller();

		//check if the method exists
		if (!method_exists($clientController, 'addSuscription')) {
			return;
		}

		$clientController->addSuscription($event->email);
	}
}
