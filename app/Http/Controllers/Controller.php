<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Session;

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
}
