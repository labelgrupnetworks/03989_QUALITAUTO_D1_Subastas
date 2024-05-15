<?php

namespace App\Notifications;

use App\Models\V5\FgSub;
use App\Providers\ToolsServiceProvider;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;

class AuctionInTime extends Notification implements ShouldQueue
{
	use Queueable;

	private $enviorment;
	private $queueEnviorment;
	private $numberToFailedJobs;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct(
		private FgSub $auction,
		private string $whenTime
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

		$url = ToolsServiceProvider::url_auction($this->auction->cod_sub, $this->auction->name, $this->auction->id_auc_sessions, $this->auction->reference);
		$mailMessage = (new MailMessage)->mailer('log_mail')
			->subject($this->whenTimeSubject($this->whenTime))
			->greeting('Aviso!')
			->line($this->whenTimeBody($this->whenTime, $this->auction))
			->action('Ver subasta', $url);

		if (Config::get('app.tr_show_streaming', false)) {
			$streaminUrl = ToolsServiceProvider::url_real_time_auction($this->auction->cod_sub, $this->auction->name, $this->auction->id_auc_sessions);
			$mailMessage->line("");
			$mailMessage->line("**Ojo!!** Esta subasta tiene streaming");
			$mailMessage->line("Url del [Streaming]({$streaminUrl})");
		}

		return $mailMessage->salutation('Saludos');
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

	private function whenTimeSubject(string $whenTime)
	{
		$theme = mb_strtoupper(Config::get('app.theme'));
		return match ($whenTime) {
			'week' => "⏳ [$theme] - Subasta en 1 semana",
			'day' => "⌛ [$theme] - Hoy tenemos subasta!!",
			'default' => "⏳ [$theme] - Subasta en 1 semana",
		};
	}

	private function whenTimeBody(string $whenTime, $auction)
	{
		return match ($whenTime) {
			'week' => "La subasta {$auction->name} se realizará en 1 semana",
			'day' => "La subasta {$auction->name} se realizará hoy a las {$auction->session_start}",
			'default' => "La subasta se realizará en 1 semana",
		};
	}
}
