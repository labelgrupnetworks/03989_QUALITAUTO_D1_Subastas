<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Subasta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
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
		$codCli = Session::get('user.cod');

		if (!empty($request->input('favorites'))) {
			$favorites = true;
			$data['favorites'] = true;
			$data['codigos_licitador'] = (new User)->getLicitCodesGroupBySub($codCli);
		}

		$sub = new Subasta();
		$sub->licit = $codCli;

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

		$showLotsClosed = Config::get('app.lots_closed_inpanel', false);
		$all_pujas_temp = $sub->getAllBidsAndOrders($favorites, $showLotsClosed, $filters);

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

		$sub->page = 'all';

		if (!empty($request->input('order')) && $request->input('order') == 'desc') {
			$sub->where_filter = 'desc';
		}

		$queryValues = $sub->getAllBidsAndOrders();
		$totalItems = count($queryValues);

		$itemsPerPage = $sub->itemsPerPage = 10;

		if (empty($page) or $page == 1) {
			$currentPage    = 1;
		} else {
			$currentPage    = $page;
		}

		$path = $request->fullUrlWithoutQuery(['page']);
		$paginator = new Paginator($queryValues, $totalItems, $itemsPerPage, $currentPage, ['path' => $path]);

		$sub->page = $currentPage;

		$data['values'] = $queryValues;
		$data['paginator'] = $paginator;
		$data['currency'] = $sub->getCurrency();
		$data['seo'] = new \stdClass();
		$data['seo']->noindex_follow = true;

		return View::make('front::pages.panel.orders', array('data' => $data));
	}

	public function ordersClient()
	{
		$subastaObj        = new Subasta();
		$data = array(
			"sub" => null,
		);

		if (!empty($_GET['sub'])) {
			$subasta = $_GET['sub'];
			$subastaObj->cod = $subasta;
			$inf_subasta = $subastaObj->getInfSubasta();

			if (!empty($inf_subasta) && strtotime("now") > strtotime($inf_subasta->orders_start)  &&   strtotime("now") < strtotime($inf_subasta->orders_end)) {

				$subastaObj->page = 'all';
				$subastaObj->licit = Session::get('user.cod');
				$subastas = $subastaObj->getAllSubastaLicitOrdenes($subasta);
				$data['sub'] = $subastas;
			}
		}

		$data['node']  = array(
			'ol'       => Config::get('app.url') . "/" . App::getLocale() . "/api/ol/subasta",
		);

		return View::make('front::pages.panel.ordenes_cli', array('data' => $data));
	}
}
