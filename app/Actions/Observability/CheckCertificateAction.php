<?php

namespace App\Actions\Observability;

use App\Actions\Traits\ActionNotificable;
use App\Notifications\ExpiredCertificate;
use App\Notifications\TimeToFinishCertificate;
use Illuminate\Notifications\Notification as NotificationClass;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Spatie\SslCertificate\SslCertificate;

class CheckCertificateAction
{
	use ActionNotificable;

	private const DAYS_TO_FINISH = 15;

	public function __invoke()
	{
		$urls = [
			Config::get('app.url'),
			Config::get('app.node_url'),
		];

		$recipients = (new RecipientsNotificationList)->getWebAlerts();

		foreach ($urls as $url) {
			$daysToFinishCertificate = $this->checkCertificate($url);
			$notificationToSend = $this->getNotificationAccordingToExpired($daysToFinishCertificate, $url);

			if(!$notificationToSend) {
				continue;
			}

			foreach ($recipients as $recipient) {
				$notification = Notification::route('mail', $recipient);
				$this->sendNotification($notification, $notificationToSend);
			}
		}
	}

	private function getNotificationAccordingToExpired(int $daysToFinishCertificate, string $url) : ?NotificationClass
	{
		Log::info('Días para finalizar el certificado: ' . $daysToFinishCertificate);
		$notification =  match (true) {
			$daysToFinishCertificate === 0 => new ExpiredCertificate($url),
			$daysToFinishCertificate <= self::DAYS_TO_FINISH => new TimeToFinishCertificate($url, $daysToFinishCertificate),
			default => null,
		};

		return $notification;
	}

	private function checkCertificate(string $url) : int
	{
		$dateToFinish = now();
		try {
			$certificate = SslCertificate::createForHostName($url);
			$dateToFinish = $certificate->expirationDate();
		} catch (\Exception $e) {
			Log::error('Error al obtener el certificado de ' . $url);
		}

		return $dateToFinish->diffInDays();
	}
}
