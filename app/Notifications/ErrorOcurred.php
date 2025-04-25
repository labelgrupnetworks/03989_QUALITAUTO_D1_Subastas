<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Throwable;

class ErrorOcurred extends Notification
{
	use Queueable;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct(
		private Throwable $exception,
		private int $count = 0
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
		$logViewer = Config::get('app.url', null) . '/admin/log-viewer';
		return (new MailMessage)->mailer('log_mail')
			->subject("🥵 [$theme] - Ha ocurrido un error")
			->level('error')
			->greeting('Psst!')
			->line("La excepción **{$this->exception->getMessage()}** ocurrió")
			->when($this->count, function ($message) {
				$message->line("El error se ha producido **{$this->count} veces** en la última hora");
			})
			->line("Archivo: **{$this->exception->getFile()}**")
			->line("Línea: **{$this->exception->getLine()}**")
			//->line("Stack trace: **{$this->exception->getTraceAsString()}**")
			->action('Revisar', $logViewer)
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
}
