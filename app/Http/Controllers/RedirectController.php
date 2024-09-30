<?php

namespace App\Http\Controllers;

use App\Models\Subasta;
use App\Providers\RoutingServiceProvider as Routing;
use App\Providers\ToolsServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class RedirectController extends Controller
{

	public function __construct()
	{
		//si hemso definido que redirecciones oslo en un idioma , marcamos este idoma
		if (!empty(Config::get('app.force_language_redirect'))) {
			App::setLocale(Config::get('app.force_language_redirect'));
		}
	}

	public function redirect_lot()
	{
		$lote = Routing::find_redirect_lot();

		if (count($lote) > 0) {
			$lang = Request::segment(1);

			if (array_key_exists($lang, Config::get('app.locales'))) {
				App::setLocale(strtolower($lang));
			}

			$subasta        = new Subasta();
			$subasta->cod   = $lote[0]->sub_web_redirect_lots;
			//si es un lote redirigimos a lotes
			if (!empty($lote[0]->ref_web_redirect_lots)) {
				$subasta->lote   = $lote[0]->ref_web_redirect_lots;
				$subasta->id_auc_sessions = $subasta->getIdAucSessionslote($subasta->cod, $subasta->lote);
				if (empty($subasta->id_auc_sessions)) {
					return Redirect::to(URL::asset($lang), 301);
				}

				$lote_info = $subasta->getLote();

				if (empty($lote_info)) {
					// Log::info("cod_sub: ".$subasta->cod . " Lote:".$subasta->lote);
					return Redirect::to(URL::asset($lang), 301);
				}
				$item = $lote_info[0];
				$url_friendly =  ToolsServiceProvider::url_lot($item->cod_sub, $item->id_auc_sessions, $item->name, $item->ref_asigl0, $item->num_hces1, $item->webfriend_hces1, $item->titulo_hces1);

				return Redirect::to(URL::asset($url_friendly), 301);
			} else {

				$sesiones = $subasta->getSessiones();

				if (count($sesiones) > 0) {

					$url_friendly = Routing::translateSeo('subasta') . $subasta->cod . "-" . str_slug($sesiones[0]->name) . "-" . $sesiones[0]->id_auc_sessions;

					return Redirect::to(URL::asset($url_friendly), 301);
				} else {
					return Redirect::to(URL::asset($lang), 301);
				}
			}
		}
		exit(View::make('front::errors.404'));
	}

	public function redirect_page()
	{
		$page = Routing::find_redirect_page();

		if (count($page) > 0) {

			/* Codigo nuevo para identificar el idioma de la página  2019_01_31 antes era $lang = Config::get('app.locale');*/
			if (!empty(Request::segment(1))) {
				$lang_tmp = Request::segment(1);
				$locales = Config::get('app.locales');
				if (array_key_exists($lang_tmp, $locales)) {
					$lang = $lang_tmp;
				} else {
					$lang = Config::get('app.locale');
				}
			}
			/* fin codigo nuevo */
			$url_friendly = $lang . "/" . $page[0]->page_web_redirect_pages;

			return Redirect::to(URL::asset($url_friendly), 301);
		} else {
			exit(View::make('front::errors.404'));
		}
	}
}
