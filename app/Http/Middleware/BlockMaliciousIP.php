<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class BlockMaliciousIP
{

	protected $maxAttempts = 2;
    protected $decayMinutes = 10;
    protected $blockDurationMinutes = 60;

	/**
	 * Handle an incoming request.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$ip = $request->ip();
		$blockedKey = 'blocked_ip_' . $ip;
		$attemptsKey = 'ip_attempts_' . $ip;

        if (Cache::has($blockedKey)) {
            return response('Your IP is temporarily blocked due to suspicious activity.', 403);
        }

        return $next($request);

		$isValid = $this->isValidSessionCookie($request);
		if (!$isValid) {

			$attempts = Cache::get($attemptsKey, 0) + 1;
            Cache::put($attemptsKey, $attempts, $this->decayMinutes * 60);

			if ($attempts > $this->maxAttempts) {
                Cache::put($blockedKey, true, $this->blockDurationMinutes * 60);
                Log::warning('Blocked IP ' . $ip . ' due to suspicious activity.');
            }

		}

		return $next($request);
	}

	private function isValidSessionCookie($request)
	{
		$cookie = $request->cookie(
			Config::get('session.cookie')
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
