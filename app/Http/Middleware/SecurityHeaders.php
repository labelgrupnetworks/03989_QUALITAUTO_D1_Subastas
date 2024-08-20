<?php

namespace App\Http\Middleware;

use Closure;

class SecurityHeaders
{
	public function handle($request, Closure $next)
	{
		$response = $next($request);

		/**
		 * Content Security Policy
		 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy
		 */
		//$response->headers->set('Content-Security-Policy', "default-src 'self';");

		/**
		 * X-Frame-Options
		 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Frame-Options
		 */
		$response->headers->set('X-Frame-Options', 'DENY');

		/**
		 * X-Content-Type-Options
		 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Content-Type-Options
		 */
		$response->headers->set('X-Content-Type-Options', 'nosniff');

		/**
		 * Referrer Policy
		 * @see https://web.dev/articles/referrer-best-practices?hl=es or https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Referrer-Policy
		 */
		//$response->headers->set('Referrer-Policy', 'no-referrer');

		return $response;
	}
}
