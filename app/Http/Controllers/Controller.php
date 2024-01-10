<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Session;
use Request;
use App\Models\User;
use App\libs\SeoLib;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    var $userLoged;

    public function __construct(){

        //header("Cache-Control: no-cache, must-revalidate");
        //header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        if( env('APP_DEBUG') || (!empty($_GET) && !empty($_GET['querylog']) && $_GET['querylog'] == 'active_log')){
            \DB::enableQueryLog();
        }

        $this->validateUserSession();
		$this->UTMSession();
		SeoLib::KeywordsSearch();

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
                    Session::flush();
                }

            }
             return $next($request);
        });
    }

	function UTMSession(){

			# se debe crear la sesion siempre la primera vez que entras en la web, ya que estos valores no se pueden alterar durante la navegación por la web
            if (!Session::has('UTM') ) # && ( !empty(Request::header('referer')) || !empty(request("UTM_SOURCE")) || !empty(request("UTM_MEDIUM")) || !empty(request("UTM_CAMPAIGN"))  || !empty(request("UTM_TYPE")) )
            {
				Session::put('UTM.source', request("UTM_SOURCE")); /* el origen del tráfico, es decir, de qué sitio, anunciante o publicación vino el usuario */
				Session::put('UTM.medium', request("UTM_MEDIUM")); /*  los medios de publicidad o marketing utilizados para llegar a su sitio (ejemplos: banner, cpc, newsletter). */
				Session::put('UTM.campaign', request("UTM_CAMPAIGN")); /* el nombre de la campaña que define determinado contexto de marketing (ejemplos: natal, lanzamiento, promo01).  */
				Session::put('UTM.type', request("UTM_TYPE"));
				Session::put('UTM.referer', Request::header('referer'));
            }


	}
}
