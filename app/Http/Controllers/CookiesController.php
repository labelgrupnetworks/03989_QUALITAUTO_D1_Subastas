<?php
namespace App\Http\Controllers;

use App\libs\TradLib;
use App\Providers\ToolsServiceProvider;
use View;
use Config;
use Cookie;
use Redirect;
use Illuminate\Http\Request;

class CookiesController extends Controller
{

	public function getConfigCookies()
	{
		$config = config('session');
		$cookiesState = ToolsServiceProvider::cookies();
		$internalCookies = ['esentials' => [
				Config::get('app.theme').'_session' => trans(\Config::get('app.theme').'-app.cookies.cookie_description_session'),
				'XSRF-TOKEN' => trans(\Config::get('app.theme').'-app.cookies.cookie_description_token')
			],
			'preferences' => [
				'lot' => trans(\Config::get('app.theme').'-app.cookies.cookie_description_lot'),
				'cookie_config' => trans(\Config::get('app.theme').'-app.cookies.cookie_description_cookie_config')
			]
		];

		//if(empty($cookies)){
			//Cookie::queue('cookie_config', 'all=1;', (60*24*365), $config['path'], $config['domain'], $config['secure'], true);
		//}
		$data['seo']=new \Stdclass();
		$data['seo']->noindex_follow=true;

		$seoExist = TradLib::getWebTranslateWithStringKey('metas', 'title_cookies', config('app.locale', 'es'));
		if(!empty($seoExist)){
			$data['seo']->meta_title = trans(\Config::get('app.theme') . '-app.metas.title_cookies');
		}

		return View::make('front::pages.cookies', compact('cookiesState', 'internalCookies', 'data'));
	}

	public function setConfigCookies(Request $request)
	{
		$config = config('session');
		$cookie = "all=0;facebook=". $request->get('facebook', 0).";google=".$request->get('google', 0).";preferences=".$request->get('preferences', 1);

		if($request->cookie('facebook') && empty($request->get('facebook', 0))){
			Cookie::queue(Cookie::forget('_fbp'));
		}

		//Supuestamente elimina las cookies.
		//Aun así, se vuelven a eliminar por .js
		if($request->cookie('google') && empty($request->get('google', 0))){
			Cookie::queue(Cookie::forget('_ga'));
			Cookie::queue(Cookie::forget('_gid'));
			Cookie::queue(Cookie::forget('_gat_gtag_UA_112197559_1'));
		}

		if(($request->cookie('lot') || $request->cookie('cookie_config')) && empty($request->get('preferences', 1))){
			Cookie::queue(Cookie::forget('lot'));
			Cookie::queue(Cookie::forget('cookie_config'));
		}

		if($request->get('ajax', 0)){

			if(empty($request->get('preferences', 1))){
				return response('OK');
			}

			return response('OK')->cookie(
				'cookie_config', $cookie, (60*24*365), $config['path'], $config['domain'], $config['secure'], true
			);
		}

		if(empty($request->get('preferences', 1))){
			return Redirect::to('/');
		}

		Cookie::queue('cookie_config', $cookie, (60*24*365), $config['path'], $config['domain'], $config['secure'], true);
		return Redirect::to('/');
	}

	public function acceptAllCookies()
	{
		$config = config('session');

		return response('OK')->cookie(
		    'cookie_config', 'all=1;', (60*24*365), $config['path'], $config['domain'], $config['secure'], true
		);
	}


}
