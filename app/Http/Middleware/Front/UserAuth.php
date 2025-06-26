<?php

namespace App\Http\Middleware\Front;

use App\Providers\RoutingServiceProvider;
use Closure;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class UserAuth
{

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (Session::has('user')) {
			return $next($request);
		}

		if (Request::ajax()) {
			$res = array(
				'status' => 'error',
				'redirect' => RoutingServiceProvider::slug('login'),
				'msg' => trans("admin-app.login.session_timeout")
			);
			die(json_encode($res));
		}

		// Mostrar página de inicio de sesión - (Para Tauler)
		$notUserAndIsPanelConfig = !Session::has('user') && Config::get('app.notlogged_page_inpanel', false);
		if ($notUserAndIsPanelConfig) {
			return $this->notLoggedView();
		}

		// Redirigir a la página de registro o inicio de sesión
		$view_login = "";
		if (Config::get('app.modal_login')) {
			$view_login = "?view_login=true";
		}

		return Redirect::to(RoutingServiceProvider::slug('login') . $view_login);
	}

	/**
     * Muestra la vista cuando el usuario no está logueado
     */
    private function notLoggedView()
    {
        $seo = (object)[
            'noindex_follow' => true,
        ];

        $currentUrl = URL::current();
        $queryString = http_build_query([
            'view_login' => 'true'
        ]);

        return response()->view('front::pages.not-logged', [
            'data' => trans('web.user_panel.not-logged', ['url' => "$currentUrl?$queryString"]),
            'seo' => $seo,
            'openLogin' => true,
        ]);
    }
}
