<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\Events\QueueBusy;
use Illuminate\Support\Facades\Config;

class BusyJobs extends Notification
{
	use Queueable;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct(
		private QueueBusy $event
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
			->subject("🥵 [$theme] - Muchos Jobs en cola")
			->level('error')
			->greeting('Psst!')
			->line("Se han detectado muchos jobs en espera en la cola **{$this->event->queue}**.")
			->line("Existen **{$this->event->size}** jobs en cola")
			->line("Revisar si la cola esta en funcionamiento o si hay algún job que no se este procesando")
			->action('Revisar', route('admin.jobs.index'))
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
