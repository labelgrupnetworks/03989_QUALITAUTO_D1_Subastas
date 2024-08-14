<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Favorites;
use App\Models\Subasta;
use App\Models\User;
use App\Providers\RoutingServiceProvider;
use App\Providers\ToolsServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class FavoritesController extends Controller
{

	/**
	 * Obtener los favoritos por código de cliente, no por licitador
	 * No se esta utiliando (14/08/2024)
	 */
	public function getNewFavoritos()
	{
		//El config todos lo tienen a 1, por lo que no se esta utilizando
		if (!Config::get('app.user_panel_group_subasta', 1)) {
			return $this->getFavoritosLotsNew();
		}

		return $this->getFavoritosSubastasNew();
	}

	private function getFavoritosSubastasNew()
	{
		if (!Session::has('user')) {
			$favs = array();
			$paginator = "";
			$data = array(
				'favoritos'      => $favs,
				'paginator'      => $paginator,
			);

			return View::make('front::pages.panel.favoritos', array('data' => $data));
		}

		$favs = array();
		$fav  = new Favorites(false, false);
		$favs = $fav->getFavsNewByCodCli();

		$all_favorites = array();


		if (!isset($favs['status']) || $favs['status'] != 'error') {
			foreach ($favs as $lot) {
				$lot->url_img = ToolsServiceProvider::url_img("lote_small", $lot->num_hces1, $lot->lin_hces1);
				$url_friendly = str_slug($lot->titulo_hces1);
				$lot->url_lot = RoutingServiceProvider::translateSeo('lote') . $lot->id_sub . "-" . str_slug($lot->name) . '-' . $lot->id_auc_sessions . "/" . $lot->id_ref . '-' . $lot->num_hces1 . '-' . $url_friendly;

				$all_favorites[$lot->id_sub]['lotes'][] = $lot;
				$all_favorites[$lot->id_sub]['inf'] = [
					'cod_sub' => $lot->id_sub,
					'des_sub' => $lot->des_sub,
					'name' => $lot->name,
				];
			}
		}

		$data = array(
			'favoritos'      => $all_favorites
		);

		return View::make('front::pages.panel.new_favoritos', array('data' => $data));
	}

	private function getFavoritosLotsNew()
	{

		if (!Session::has('user')) {
			$favs = array();
			$data = array(
				'favoritos'      => $favs,
			);

			return View::make('front::pages.panel.favoritos', array('data' => $data));
		}

		$favs = array();
		$fav  = new Favorites(Session::get('user.cod'), false);
		$favs = $fav->getFavsNewByCodCli();

		$data = array(
			'favoritos'      => $favs,
		);

		return View::make('front::pages.panel.favoritos', array('data' => $data));
	}

	// lotes favoritos, por subasta o por lotes
	public function getFavoritos()
	{
		//El config todos lo tienen a 1, por lo que no se esta utilizando
		if (!Config::get('app.user_panel_group_subasta', 1)) {
			return $this->getFavoritosLots();
		}

		return $this->getFavoritosSubastas();
	}

	#Lotes favoritos
	public function getFavoritosSubastas()
	{
		$sub = new Subasta();

		if (!Session::has('user')) {
			$favs = array();
			$paginator = "";
			$data = array(
				'favoritos'      => $favs,
				'paginator'      => $paginator,
			);

			return View::make('front::pages.panel.favoritos', array('data' => $data));
		}

		# Lista de códigos de licitacion del usuario en sesion
		$codCli = Session::get('user.cod');
		$codigos_licitador = (new User)->getLicitCodesGroupBySub($codCli);
		# Obtenemos los codigos de licitador en formato string para el IN(xxx)

		$lista_codigos = '';
		$coma = '';
		foreach ($codigos_licitador as $key => $value) {
			$lista_codigos .= $coma . "'" . $key . "-" . $value . "'";
			$coma = ',';
		}
		$all_favorites = array();
		$favs = array();
		$fav  = new Favorites(false, false);
		if (!empty($lista_codigos)) {

			$fav->list_licit = $lista_codigos;

			$fav->page  = 'all';

			$favs = $fav->getFavsByLicits();
		}

		if (!empty($favs['data'])) {
			foreach ($favs['data'] as $favorites) {
				$all_favorites[$favorites->cod_sub]['lotes'][] = $favorites;
			}
			foreach ($all_favorites as $key_inf => $value) {
				$sub->cod = $key_inf;
				$all_favorites[$key_inf]['inf'] = $sub->getInfSubasta();
			}
		}


		$data = array(
			'favoritos'      => $all_favorites,
			'codigos_licitador' => $codigos_licitador
		);
		return View::make('front::pages.panel.favoritos', array('data' => $data));
	}

	public function getFavoritosLots()
	{

		if (!Session::has('user')) {
			$favs = array();
			$paginator = "";
			$data = array(
				'favoritos'      => $favs,
				'paginator'      => $paginator,
			);

			return View::make('front::pages.panel.favoritos', array('data' => $data));
		}
		# Lista de códigos de licitacion del usuario en sesion
		$codCli = Session::get('user.cod');
		$codigos_licitador = (new User)->getLicitCodesGroupBySub($codCli);
		# Obtenemos los codigos de licitador en formato string para el IN(xxx)

		$lista_codigos = '';
		$coma = '';
		foreach ($codigos_licitador as $key => $value) {
			$lista_codigos .= $coma . "'" . $key . "-" . $value . "'";
			$coma = ',';
		}

		$favs = array();
		$fav  = new Favorites(false, false);
		if (!empty($lista_codigos)) {

			$fav->list_licit = $lista_codigos;

			$fav->page  = 'all';

			$favs = $fav->getFavsByLicits();
		}

		if (!empty($favs) && isset($favs['data'])) {
			$favs = $favs['data'];
		}

		# Paginador #
		$page = Route::current()->parameter('page');


		$totalItems = count($favs);
		$itemsPerPage   = $fav->itemsPerPage = 10;

		if (empty($page) or $page == 1) {
			$currentPage    = 1;
		} else {
			$currentPage    = $page;
		}

		$path = request()->fullUrlWithoutQuery(['page']);
		$paginator = new Paginator($favs, $totalItems, $itemsPerPage, $currentPage, ['path' => $path]);

		$fav->page           = $currentPage;
		# end paginador #

		$favs = $fav->getFavsByLicits();

		if ($favs && isset($favs['data'])) {
			$favs = $favs['data'];
		} else {
			$favs = array();
		}

		$data = array(
			'favoritos'      => $favs,
			'paginator'      => $paginator,
			'codigos_licitador' => $codigos_licitador
		);

		return View::make('front::pages.panel.favoritos', array('data' => $data));
	}

	//Ver Temas Faoritos panel
	public function getTemaFavoritos()
	{
		$emp  = Config::get('app.emp');
		if (!Session::has('user')) {
			$favs = array();
			$paginator = "";
			$data = array(
				'favoritos'      => $favs,
				'paginator'      => $paginator,
			);

			return View::make('front::pages.panel.temas_favorites', array('data' => $data));
		}

		$user = new User();
		$cod_lic =  Session::get('user.cod');
		$data['favorites'] = $user->favorites();

		$data['fav'] = $user->fav_themes($emp, $cod_lic);

		return View::make('front::pages.panel.temas_favorites', array('data' => $data));
	}

	//Guardar Temas favoritos panel
	public function savedTemaFavoritos(Request $request)
	{
		$emp  = Config::get('app.emp');
		$user = new User();
		$cod_cli =  Session::get('user.cod');

		$user->deletefavorites($emp, $cod_cli);

		$data['favorites'] = $user->favorites();

		foreach ($data['favorites'] as $favorites) {
			$interest = $request->input("interest_" . $favorites->cod_tsec);
			if (!empty($interest)) {
				$user->addfavorites($emp, $cod_cli, $favorites->cod_tsec);
			}
		}
	}
}
