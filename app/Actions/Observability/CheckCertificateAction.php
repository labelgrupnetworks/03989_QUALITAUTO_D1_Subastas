<?php

namespace App\Actions\Observability;

use App\Actions\Traits\ActionNotificable;
use App\Notifications\ExpiredCertificate;
use App\Notifications\TimeToFinishCertificate;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Spatie\SslCertificate\SslCertificate;

class CheckCertificateAction
{
	use ActionNotificable;

	private const DAYS_TO_FINISH = 30;

	public function __invoke()
	{
		$urls = [
			Config::get('app.url'),
			Config::get('app.node_url'),
		];

		$recipients = (new RecipientsNotificationList)->getWebAlerts();

		foreach ($urls as $url) {
			$daysToFinishCertificate = $this->checkCertificate($url);
			$notificationToSend = $this->getNotificationToExpired($daysToFinishCertificate);

			if(!$notificationToSend) {
				continue;
			}

			foreach ($recipients as $recipient) {
				$notification = Notification::route('mail', $recipient);
				$this->sendNotification($notification, new $notificationToSend($url, $daysToFinishCertificate));
			}
		}
	}

	private function getNotificationToExpired($daysToFinishCertificate)
	{
		return match (true) {
			$daysToFinishCertificate === 0 => ExpiredCertificate::class,
			$daysToFinishCertificate <= self::DAYS_TO_FINISH => TimeToFinishCertificate::class,
			default => null,
		};
	}

	private function checkCertificate($url)
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
