<?php

namespace App\Listeners\user;

use App\libs\EmailLib;

class SendSubscriptionConfirmationEmail
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
	public function handle($event)
	{
		//Soporte Concursal quiere que se le envié notificación al usuario
		$email = new EmailLib('USER_NEWSLETTER');
		if (!empty($email->email)) {
			$email->setTo(strtolower($event->email));
			$email->send_email();
		}
	}
}
