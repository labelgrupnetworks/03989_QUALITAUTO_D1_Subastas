<?php
namespace App\Http\Controllers;

use View;
use Config;
use Cookie;
use Request;


use App\Http\Controllers\UserController;
use App\Models\User;
use stdClass;

class HomeController extends Controller
{



	 //**************************************************************************************************/
	//
   //  index - Home de la web
  //
 //***************************************************************************************************/


    public function index()
    {
		$data['restricti_css_js'] = true;

		if(Config::get('app.seo_in_home', 0)){
			$data['seo'] = new \stdClass();
			$data['seo']->meta_title = trans(\Config::get('app.theme').'-app.metas.title_home');
			$data['seo']->meta_description = trans(\Config::get('app.theme').'-app.metas.description_home');
		}

        return View::make('front::pages.home', array('data' => $data));
    }




	   //**************************************************************************************************/
	  //
     //  accept_cookies - Guardamos cookie conforme ha aceptado la politica de cookies
	//
   // 	@cookie_law - nombre de la cookie
  //
 //***************************************************************************************************/


	public function accept_cookies() {

		$config = config('session');

		return response('OK')->cookie(
		    'cookie_law', '1', (60*24*365), $config['path'], $config['domain'], $config['secure'], true
		);

	}

}
