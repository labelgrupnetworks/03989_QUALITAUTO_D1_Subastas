<?php

namespace App\Exceptions;

use App\Services\Notifications\ErrorNotificationService;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Session;
use Throwable;

class Handler extends ExceptionHandler
{
	/**
	 * A list of exception types with their corresponding custom log levels.
	 *
	 * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
	 */
	protected $levels = [
		//
	];

	/**
	 * A list of the exception types that are not reported.
	 *
	 * @var array<int, class-string<\Throwable>>
	 */
	protected $dontReport = [
		//
	];

	/**
	 * A list of the inputs that are never flashed for validation exceptions.
	 *
	 * @var array<int, string>
	 */
	protected $dontFlash = [
		'current_password',
		'password',
		'password_confirmation',
		'confirm_password'
	];

	/**
	 * Register the exception handling callbacks for the application.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->reportable(function (Throwable $e) {
			//
		});
	}

	protected function context()
	{
		try {
			return array_merge(parent::context(), array_filter([
				'route' => request()->url() ?? '',
				'query' => request()->query() ?? '',
				'post' => array_filter(request()->post() ?? [], fn($value, $key) => !in_array($key, $this->dontFlash)),
				'userId' => Session::has('user') ? Session::get('user')['cod'] : null,
				'referer' => request()->headers->get('referer') ?? '',
			]));
		} catch (Throwable $e) {
			return [];
		}
	}

	public function report(Throwable $exception)
	{
		parent::report($exception);

		if ($this->shouldAlert($exception)) {
			(new ErrorNotificationService)->processException($exception);
		}
	}

	/**
     * Define qué excepciones quieres monitorizar.
	 * Las excepciones inlcuidas aquí no se mostraran.
     */
    private function shouldAlert(Throwable $exception): bool
    {
        return ! ($exception instanceof \Illuminate\Validation\ValidationException)
            && ! ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException)
			&& ! ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException)
			&& ! ($exception instanceof \Illuminate\Session\TokenMismatchException);
    }
}
