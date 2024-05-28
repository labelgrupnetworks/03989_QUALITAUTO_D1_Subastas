<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class BlockMaliciousIP
{
	/**
	 * Handle an incoming request.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{

		$isValid = $this->isValidSessionCookie($request);
		if (!$isValid) {
			$ip = $request->ip();
			Log::warning("IP {$ip} sospechosa de modificar cookies.");

			// Bloquear la IP o tomar otra acción
			//abort(403, "Acceso denegado");
		}

		return $next($request);
	}

	private function isValidSessionCookie($request)
	{
		$cookie = $request->cookie(
			Str::slug(env('APP_NAME', 'laravel'), '_') . '_session'
		);

		try {
			$decoded = base64_decode($cookie, true);
			if ($decoded === false || !is_string($decoded)) {
				return false;
			}
		} catch (\Throwable $th) {
			return false;
		}

		return true;
	}
}
