<?php

namespace App\Http\Controllers;

use App\libs\SeoLib;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    var $userLoged;

    public function __construct(){

        //header("Cache-Control: no-cache, must-revalidate");
        //header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        if( env('APP_DEBUG') || (!empty($_GET) && !empty($_GET['querylog']) && $_GET['querylog'] == 'active_log')){
            DB::enableQueryLog();
        }

        $this->validateUserSession();
		$this->UTMSession();


    }

    /*Comprovamos que el usuario tenga session el usaurio exista, si no existe eliminamos session*/
    function validateUserSession(){
        $this->middleware(function ($request, $next) {
            if (Session::has('user'))
            {
                $user                = new User();
                $user->cod_cli       = Session::get('user.cod');
                $this->userLoged     = $user->getUser();

                if(empty($this->userLoged)){
					Session::forget('user');
					Session::forget('_token');
                }

            }
             return $next($request);
        });
    }

	function UTMSession(){

		$this->middleware(function ($request, $next) {

			# se debe crear la sesion siempre la primera vez que entras en la web, ya que estos valores no se pueden alterar durante la navegación por la web
            if (!session()->has('UTM') )
            {

				session()->put('UTM.source', request("utm_source",request("UTM_SOURCE"))); // el origen del tráfico, es decir, de qué sitio, anunciante o publicación vino el usuario
				session()->put('UTM.medium', request("utm_medium",request("UTM_MEDIUM"))); //  los medios de publicidad o marketing utilizados para llegar a su sitio (ejemplos: banner, cpc, newsletter).
				session()->put('UTM.campaign', request("utm_campaign",request("UTM_CAMPAIGN"))); // el nombre de la campaña que define determinado contexto de marketing (ejemplos: natal, lanzamiento, promo01).
				session()->put('UTM.type', request("utm_type",request("UTM_TYPE")));
				session()->put('UTM.referer', parse_url(Request::header('referer'), PHP_URL_HOST));
				SeoLib::saveVisit();

            }

			return $next($request);
		});


	}
}
