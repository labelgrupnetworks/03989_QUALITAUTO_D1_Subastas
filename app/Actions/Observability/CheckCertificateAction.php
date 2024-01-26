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

	public function __invoke()
	{
		$urls = [
			Config::get('app.url'),
			Config::get('app.node_url')
		];

		$recipients = (new RecipientsNotificationList)->getWebAlerts();

		foreach ($urls as $url) {
			$daysToFinishCertificate = $this->checkCertificate($url);

			foreach ($recipients as $recipient) {

				$notification = Notification::route('mail', $recipient);
				if ($daysToFinishCertificate === 0) {
					$this->sendNotification($notification, new ExpiredCertificate($url));
				} elseif ($daysToFinishCertificate <= 30) {
					$this->sendNotification($notification, new TimeToFinishCertificate($url, $daysToFinishCertificate));
				}
			}
		}
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
