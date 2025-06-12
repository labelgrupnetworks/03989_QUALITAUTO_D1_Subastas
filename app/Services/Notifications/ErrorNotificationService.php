<?php

namespace App\Services\Notifications;

use App\Notifications\ErrorOcurred;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsChannel;
use Throwable;

class ErrorNotificationService
{
	/**
	 * Dirección de correo electrónico para recibir las notificaciones.
	 */
	protected const NOTIFICATION_EMAIL = 'enadal@labelgrup.com';

	/**
	 * Procesa la excepción y determina si enviar una notificación.
	 *
	 * @param Throwable $exception
	 * @return void
	 */
	public function processException(Throwable $exception): void
	{
		// Verifica si se deben enviar notificaciones
		if (!$this->checkConditionsToSendNotification()) {
			return;
		}

		try {
			$this->checkThresholdAndAlert($exception);
		} catch (Throwable $e) {
			Log::error('Error al enviar el email de error: ' . $e->getMessage());
		}
	}

	protected function checkConditionsToSendNotification(): bool
	{
		// Aquí puedes agregar condiciones adicionales para enviar notificaciones
		// No enviar notificaciones en entornos de desarrollo o pruebas
		// Solo enviar en producción
		if (in_array(Config::get('app.env'), ['local', 'testing'])) {
			return false;
		}

		$dayOfWeek = date('N'); // 1 (lunes) a 7 (domingo)
		if ($dayOfWeek >= 6) { // 6 es sábado, 7 es domingo
			return false;
		}

		if (!Config::get('app.notification_exceptions', false)) {
			return false;
		}

		return true;
	}

	/**
	 * Verifica los umbrales para enviar alertas sobre excepciones.
	 */
	protected function checkThresholdAndAlert(Throwable $exception): void
	{
		// 1) Crea una clave única para esta excepción
		$key = $this->generateExceptionKey($exception);

		$this->notifyFirstTime($exception, $key);
		$this->notifyWhenMaxAttemptsExceeded($exception, $key);
	}

	/**
	 * Genera una clave única para la excepción.
	 */
	protected function generateExceptionKey(Throwable $exception): string
	{
		return 'error:' . sha1(
			get_class($exception)
				. '|' . $exception->getMessage()
				. '|' . $exception->getFile()
				. '|' . $exception->getLine()
		);
	}

	/**
	 * Notificar la primera vez que ocurre un error.
	 */
	private function notifyFirstTime(Throwable $exception, string $key): void
	{
		$firstRateKey = $key . ':first';
		if (RateLimiter::tooManyAttempts($firstRateKey, 1)) {
			return;
		}

		$oneDayTime = 60 * 60 * 24;
		$this->sendNotification($exception);

		// Bloquea la notificación "first-time" durante 24 horas
		RateLimiter::hit($firstRateKey, $oneDayTime);
	}

	/**
	 * Notificar cuando se excede el número máximo de intentos.
	 */
	private function notifyWhenMaxAttemptsExceeded(Throwable $exception, string $key): void
	{
		$maxAttempts = 5; // Número máximo de veces
		$decayMinutes = 15; // Ventana en minutos

		RateLimiter::hit($key, $decayMinutes * 60);
		if (!RateLimiter::tooManyAttempts($key, $maxAttempts)) {
			return;
		}

		// Limpia para no volver a notificar inmediatamente
		RateLimiter::clear($key);

		// Envía el email con información del número de intentos
		$this->sendNotification($exception, $maxAttempts);
	}

	/**
	 * Envía la notificación de error.
	 *
	 * @param Throwable $exception La excepción ocurrida
	 * @param int|null $attempts Número de intentos (si aplica)
	 * @return void
	 */
	private function sendNotification(Throwable $exception, ?int $attempts = 0): void
	{
		$notify = new ErrorOcurred($exception, $attempts);

		$notification = Config::get('services.microsoft_teams.webhook_url', null)
			? Notification::route(MicrosoftTeamsChannel::class, Config::get('services.microsoft_teams.webhook_url'))
			: Notification::route('mail', self::NOTIFICATION_EMAIL);

		Config::get('app.debug', false)
			? $notification->notifyNow($notify) // Sin colas
			: $notification->notify($notify);   // Con colas
	}
}
