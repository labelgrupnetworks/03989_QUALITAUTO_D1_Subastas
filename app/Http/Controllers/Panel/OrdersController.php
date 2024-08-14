<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Subasta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class OrdersController extends Controller
{
	public function orderbidsList(Request $request)
	{
		if (!Session::has('user')) {
			$url =  Config::get('app.url') . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) . '?view_login=true';
			$data['data'] = trans_choice(Config::get('app.theme') . '-app.user_panel.not-logged', 1, ['url' => $url]);
			$seo = new \Stdclass();
			$seo->noindex_follow = true;
			if (config('app.seo_notlogged_page', 0)) {
				$seo->meta_title = trans(Config::get('app.theme') . '-app.metas.title_no_logged');
				$seo->meta_description = trans(Config::get('app.theme') . '-app.metas.description__no_logged');
			}

			$data['seo'] = $seo;
			return View::make('front::pages.not-logged',  $data);
		}

		//El orderBidsListLots falla la vista, revisar antes de desactivar la agrupación de subastas,
		//Solo lo estaba usando Gutinvest, y lo he desactivado porque fallaba
		if (!Config::get('app.user_panel_group_subasta', 1)) {
			return $this->orderbidsListLots($request);
		}

		return $this->orderbidsListSubastas($request);
	}

	private function orderbidsListSubastas(Request $request)
	{
		$favorites = null;
		if (!empty($request->input('favorites'))) {
			$favorites = true;
			$data['favorites'] = true;
			# Lista de códigos de licitacion del usuario en sesion

			$data['codigos_licitador'] = self::getLicitCodes();
		}
		$sub = new Subasta();
		$sub->licit = Session::get('user.cod');

		$subastas_active = $favorites
			? $sub->getActiveAuctionsUserHasFavorites()
			: $sub->getActiveAuctionsUserHasBids();

		$auctionsAvailables = Arr::pluck($subastas_active, 'des_sub', 'cod_sub');

		$sub->page  = 'all';
		$sub->cod = null;
		$all_pujas = array();

		$filters = [
			'cods_sub' => request('cods_sub', []),
		];

		if (!empty(Config::get('app.lots_closed_inpanel'))) {
			$all_pujas_temp = $sub->getAllBidsAndOrders($favorites, true, $filters);
		} else {
			$all_pujas_temp = $sub->getAllBidsAndOrders($favorites, false, $filters);
		}

		foreach ($all_pujas_temp as $temp_pujas) {
			$all_pujas[$temp_pujas->cod_sub]['lotes'][] = $temp_pujas;
		}
		foreach ($all_pujas as $key_inf => $value) {
			$sub->cod = $key_inf;
			$all_pujas[$key_inf]['inf'] = $sub->getInfSubasta();
		}

		$data['auctionsAvailables'] = $auctionsAvailables;
		$data['values'] = $all_pujas;
		$data['seo'] = new \stdClass();
		$data['seo']->noindex_follow = true;
		return View::make('front::pages.panel.orders', array('data' => $data));
	}

	private function orderbidsListLots(Request $request)
	{
		$sub = new Subasta();
		$sub->licit = Session::get('user.cod');
		$data = array();
		$page = Route::current()->parameter('page');

		$sub->page  = 'all';

		if (!empty($request->input('order')) && $request->input('order') == 'desc') {
			$sub->where_filter = 'desc';
		} else {
		}

		$queryValues = $sub->getAllBidsAndOrders();
		$totalItems = count($queryValues);


		$itemsPerPage = $sub->itemsPerPage = 10;
		//$urlPattern     = \Routing::slug('user/panel/orders').'/page/(:num)';

		if (empty($page) or $page == 1) {
			$currentPage    = 1;
		} else {
			$currentPage    = $page;
		}

		$path = $request->fullUrlWithoutQuery(['page']);
		$paginator = new Paginator($queryValues, $totalItems, $itemsPerPage, $currentPage, ['path' => $path]);

		$sub->page = $currentPage;
		//$paginator->numPages = ($paginator->numPages -1);

		$data['values'] = $queryValues;
		$data['paginator'] = $paginator;
		$data['currency'] = $sub->getCurrency();
		//}
		$data['seo'] = new \stdClass();
		$data['seo']->noindex_follow = true;

		return View::make('front::pages.panel.orders', array('data' => $data));
	}

	# Lista en formato string de codigos de licitador del usuario en sesión
	public static function getLicitCodes()
	{
		$User = new User();

		$User->cod_cli = Session::get('user.cod');
		$codigos_licitador = array();
		foreach ($User->getLicitCodes() as $key) {
			$codigos_licitador[$key->sub_licit] = $key->cod_licit;
		}

		return $codigos_licitador;
	}
}
