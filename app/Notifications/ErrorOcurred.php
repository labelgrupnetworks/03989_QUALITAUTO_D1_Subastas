<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use NotificationChannels\MicrosoftTeams\Actions\ActionOpenUrl;
use NotificationChannels\MicrosoftTeams\ContentBlocks\TextBlock;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsAdaptiveCard;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsChannel;
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
		$routes = array_keys($notifiable->routes);
		if (in_array('NotificationChannels\MicrosoftTeams\MicrosoftTeamsChannel', $routes)) {
			return [MicrosoftTeamsChannel::class];
		}

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
	 * Get the Microsoft Teams representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @see https://adaptivecards.microsoft.com/designer
	 * @return \NotificationChannels\MicrosoftTeams\MicrosoftTeamsAdaptiveCard
	 */
	public function toMicrosoftTeams($notifiable)
    {
		$to = collect(data_get($notifiable, 'routes', []))->first();
		$theme = mb_strtoupper(Config::get('app.theme'));

		$title = "🥵 [$theme] - Ha ocurrido un error";
		if($this->count > 0) {
			$title .= " ({$this->count} veces en la última hora)";
		}

		$logViewer = Config::get('app.url', null) . '/admin/log-viewer';

		return MicrosoftTeamsAdaptiveCard::create()
			->to($to)
			->title($title)
			->content([
        		TextBlock::create()
					->setText("La excepción **{$this->exception->getMessage()}** ocurrió")
					->setWeight('Default')
					->setSize('Medium')
					->setColor('Attention'),
				TextBlock::create()
					->setText("Archivo: **{$this->exception->getFile()}**")
					->setWeight('Default')
					->setSize('Small'),
				TextBlock::create()
					->setText("Línea: **{$this->exception->getLine()}**")
					->setWeight('Default')
					->setSize('Small'),

			])
			->actions([
				ActionOpenUrl::create()
					->setTitle('Revisar')
					->setUrl($logViewer),
			]);
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
