<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;

class FailedJobs extends Notification implements ShouldQueue
{
	use Queueable;

	private $queueEnviorment;
	private $numberToFailedJobs;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct($numberToFailedJobs)
	{
		$this->numberToFailedJobs = $numberToFailedJobs;
		$this->queueEnviorment = Config::get('app.queue_env');
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
			->subject("🧐 [$theme] - Failed Jobs")
			->greeting('Psst!')
			->line("Se han detectado jobs fallidos en la cola **{$this->queueEnviorment}**.")
			->line("Existen **{$this->numberToFailedJobs}** jobs fallidos")
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
