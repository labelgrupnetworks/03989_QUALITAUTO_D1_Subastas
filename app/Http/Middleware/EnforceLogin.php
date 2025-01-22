<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class EnforceLogin
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function handle(Request $request, Closure $next)
	{
		// Verifica si la aplicación requiere autenticación
		$isForceLogin = Config::get('app.enforce_login', false);
		if(!$isForceLogin) {
			return $next($request);
		}

		$excludedRoutes = [
			'home.redirect',
			'home',
			'user.login-page',
			'user.login_post_ajax',
			'user.password_recovery',
			'user.send_password_recovery',
			'user.ajax_send_password_recovery',
			'user.email-recovery',
			'user.change-passw',
			'api.action.subasta',
			'contact_page'
		];

		// Verifica si la ruta actual está excluida de la autenticación forzada
		$isExcluded = $request->route() && in_array($request->route()->getName(), $excludedRoutes);
		if($isExcluded) {
			return $next($request);
		}

		//!Auth::check()
		if (!Session::has('user')) {
			return redirect()->route('user.login-page');
		}

		return $next($request);
	}
}
