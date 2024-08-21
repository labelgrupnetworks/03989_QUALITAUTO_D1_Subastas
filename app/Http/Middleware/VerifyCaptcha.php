<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VerifyCaptcha
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
		if (Config::get('app.codRecaptchaEmail', false) || Config::get('app.captcha_v3', false)) {

			$token = Config::get('app.captcha_v3', false)
				? $request->input('captcha_token')
				: $request->input('g-recaptcha-response');

			$ip = $request->getClientIp();
			$email = $request->input('email');

			if (!$this->captchaIsValid($token, $ip, $email)) {
				return response()->json(['error' => 'recaptcha_incorrect', 'message' => 'recaptcha_incorrect', 'status' => 'error'], 422);
			}
		}

		return $next($request);
	}

	/**
	 * @param string|null $token Token de recaptcha v3
	 * @param string|null $ip IP del usuario
	 * @param string|null $email string Email del usuario
	 */
	private function captchaIsValid($token, $ip, $email)
	{
		return Config::get('app.captcha_v3', false)
			? $this->validateRecaptchaV3($token, $ip, $email)
			: $this->validateRecaptcha($token, $ip, $email);
	}

	private function validateRecaptcha($token, $ip, $email)
	{
		if (empty($token)) {
			return false;
		}

		$privateKey = Config::get('app.codRecaptchaEmail');

		//get verify response data
		$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $privateKey . '&response=' . $token);
		$responseData = json_decode($verifyResponse);
		if(empty($responseData) || $responseData->success !== true) {
			Log::warning('Recaptcha v2 failed', ['response' => $responseData, 'email' => $email, 'ip' => $ip]);
			return false;
		}

		return true;
	}

	private function validateRecaptchaV3($token, $ip, $email)
	{
		$privateKey = Config::get('app.captcha_v3_private', '');

		$response = Http::asForm()
		->post('https://www.google.com/recaptcha/api/siteverify', [
			'secret' => $privateKey,
			'response' => $token,
			'remoteip' => $ip,
		]);

		if($response->failed()) {
			return false;
		}

		$responseObject = $response->object();
		if($responseObject->success == false || $responseObject->score < config('app.captcha_v3_severity', '0.5')) {
			Log::warning('Recaptcha failed', ['response' => $response->json(), 'email' => $email, 'ip' => $ip]);
			return false;
		}

		return true;
	}

}
