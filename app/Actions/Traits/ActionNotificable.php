<?php
namespace App\Actions\Traits;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;

trait ActionNotificable
{
	protected function sendNotification(AnonymousNotifiable $notification, Notification $notificationToSend)
	{
		if (Config::get('app.debug')) {
			$notification->notifyNow($notificationToSend); //sin colas
		} else {
			$notification->notify($notificationToSend); //con colas
		}
	}
}
