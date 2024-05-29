<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;

class TimeToFinishCertificate extends Notification implements ShouldQueue
{
	use Queueable;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct(
		private string $url,
		private int $daysToFinish
	) {
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param  mixed  $notifiable
	 * @return array
	 */
	public function via($notifiable)
	{
		return ['mail'];
	}

	/**
	 * Get the mail representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($notifiable)
	{
		$theme = mb_strtoupper(Config::get('app.theme'));
		return (new MailMessage)->mailer('log_mail')
			->subject("💣 [$theme] - El certificado caduca pronto")
			->greeting('Ojo!')
			->line("El certificado de **{$this->url}** caduca en **{$this->daysToFinish}** días.")
			->line("Por favor, renovar el certificado.")
			->line("PD: Este mensaje se enviará cada día hasta que se renueve el certificado.")
			->salutation('Saludos');
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return array
	 */
	public function toArray($notifiable)
	{
		return [
			//
		];
	}

	/**
	 * Determine which queues should be used for each notification channel.
	 *
	 * @return array
	 */
	public function viaQueues()
	{
		return [
			'mail' => Config::get('app.queue_env'),
		];
	}
}
