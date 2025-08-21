<?php

namespace App\Http\Controllers;

use App\Http\Controllers\V5\LotListController;
use App\Models\V5\FgAsigl0;
use App\Providers\ToolsServiceProvider;
use App\Services\Content\BlockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;


class ContentController extends Controller
{
	public $lang;
	public $id;

	public function getAjaxStaticCarousel()
	{
		$pathFile = public_path(request('path', ''));

		if (!file_exists($pathFile) || !is_file($pathFile)) {
			return response()->json(['message' => 'Not Found'], 404);
		}

		$lots = json_decode(file_get_contents($pathFile));
		$content = "";

		foreach ($lots as $lot) {
			$content .= view('includes.static_carousel', ['lot' => $lot])->render();
		}

		return $content;
	}

	function getAjaxCarousel(Request $request)
	{
		$contents = "";

		$replaces = $request->input('replace');
		if (empty($replaces) || !is_array($replaces) || !$request->has('key')) {
			return $contents;
		}

		$replaces = array_map(function ($replace) {
			return ToolsServiceProvider::replaceDangerqueryCharacter($replace);
		}, $replaces);


		$banner = (new BlockService)->getResultBlockByKeyname($request->input('key'), $replaces);
		if (empty($banner)) {
			return;
		}

		foreach ($banner as $item) {
			if (isset($item->impsalhces_asigl0)) {
				$item->no_formated_impsalhces_asigl0 = $item->impsalhces_asigl0;
				$item->impsalhces_asigl0 = ToolsServiceProvider::moneyFormat($item->impsalhces_asigl0);
			}
		}
		if (!empty($_POST['replace'])) {
			$lang_temp = $_POST['replace']['lang'];
			$locales = Config::get('app.language_complete');
			foreach ($locales as $key => $value) {
				if ($value == $lang_temp) {
					App::setLocale($key);
				}
			}
		}
		$img = new \App\Models\Subasta;
		if (!empty($banner)) {
			foreach ($banner as $bann) {
				$contents .= view('includes.carousel', array('bann' => $bann, 'img' => $img))->render();
			}
		}


		if (!empty($_POST['size'])) {
			$data = array('contents' => $contents, 'size' => count($banner));
			return $data;
		}

		return $contents;
	}

	function getAjaxLotGrid(Request $request)
	{
		$itemsForPage = 15;
		$contents = "";

		$replaces = $request->input('replace');
		$replaces = array_map(function ($replace) {
			return ToolsServiceProvider::replaceDangerqueryCharacter($replace);
		}, $replaces);


		$banner = (new BlockService)->getResultBlockByKeyname($request->input('key'), $replaces);
		if (empty($banner)) {
			return;
		}

		foreach ($banner as $item) {
			if (isset($item->impsalhces_asigl0)) {
				$item->no_formated_impsalhces_asigl0 = $item->impsalhces_asigl0;
				$item->impsalhces_asigl0 = ToolsServiceProvider::moneyFormat($item->impsalhces_asigl0);
			}
		}
		if (!empty($_POST['replace'])) {
			$lang_temp = $_POST['replace']['lang'];
			$locales = Config::get('app.language_complete');
			foreach ($locales as $key => $value) {
				if ($value == $lang_temp) {
					App::setLocale($key);
				}
			}
		}

		$img = new \App\Models\Subasta;
		if (!empty($banner)) {
			$banner_chunked = array_chunk($banner, $itemsForPage);
			foreach ($banner_chunked as $key => $bann_list) {
				$contents .= view('includes.lot_grid_ajax', array('bann_list' => $bann_list, 'img' => $img, 'page' => $key + 1))->render();
			}
		}

		if (!empty($_POST['size'])) {
			$data = array('contents' => $contents, 'size' => count($banner));
			return $data;
		}

		return $contents;
	}

	function getAjaxNewCarousel(Request $request)
	{
		$langkey = array_search($request->input('replace.lang', 'es-ES'), Config::get('app.language_complete'));
		Config::set('app.locale', $langkey);

		$key = $request->input('key');
		$replaces = $request->input('replace');

		if (empty($replaces) || !is_array($replaces) || !$key) {
			return "";
		}

		$lots = null;
		$replaces = $request->input('replace');
		$replaces = array_map(function ($replace) {
			return ToolsServiceProvider::replaceDangerqueryCharacter($replace);
		}, $replaces);


		$lotsQuery = (new BlockService)->getResultBlockByKeyname($key, $replaces);

		$lotlistcontroller = new LotListController();
		$lotlist = $lotlistcontroller->setRef($lotsQuery);

		#cargamos los datos de los lotes
		if (!empty($lotlist) && !empty($lotlist->refLots)) {

			$fgasigl0 = new FgAsigl0();
			$lots = $fgasigl0->GetLotsByRefAsigl0($lotlist->refLots)
				->when(request('order'), function ($query, $order) {
					return $query->orderBy($order);
				})
				->when(request('orders'), function ($query, $ordersString) {
					$orders = explode(',', $ordersString);
					foreach ($orders as $order) {
						$query->orderBy($order);
					}
					return $query;
				})
				->get();

			#seteamos las variables para la blade
			$lots = $lotlistcontroller->setVarsLot($lots);
		}

		if (empty($lots)) {
			return "";
		}

		return View::make('front::includes.new_carrousel', ["lots"  => $lots]);
	}

	function getAjaxGridLotesDestacados(Request $request)
	{
		Config::set('app.locale', request('lang', 'es'));
		$lots = null;

		$replaces = $request->input('replace');
		$replaces = array_map(function ($replace) {
			return ToolsServiceProvider::replaceDangerqueryCharacter($replace);
		}, $replaces);

		$lotsQuery = (new BlockService)->getResultBlockByKeyname($request->input('key'), $replaces);

		$lotlistcontroller = new LotListController();
		$lotlist = $lotlistcontroller->setRef($lotsQuery);

		#cargamos los datos de los lotes
		if (!empty($lotlist) && !empty($lotlist->refLots)) {

			$fgasigl0 = new FgAsigl0();
			$lots = $fgasigl0->GetLotsByRefAsigl0($lotlist->refLots)
				->when(request('order'), function ($query, $order) {
					return $query->orderBy($order);
				})
				->when(request('orders'), function ($query, $ordersString) {
					$orders = explode(',', $ordersString);
					foreach ($orders as $order) {
						$query->orderBy($order);
					}
					return $query;
				})
				->get();

			#seteamos las variables para la blade
			$lots = $lotlistcontroller->setVarsLot($lots);
		}

		if (empty($lots)) {
			return "";
		}

		return View::make('front::includes.grid.lots', ["lots"  => $lots]);
	}

	public function rematesDestacados($codSub)
	{
		abort_if(!View::exists('pages.remates_destacados'), 404);

		$lots = FgAsigl0::activeLotAsigl0()
			->select('REF_ASIGL0, NUM_HCES1, LIN_HCES1, DESCWEB_HCES1, DESC_HCES1, IMPSALHCES_ASIGL0, IMPLIC_HCES1, COD_SUB, "id_auc_sessions", "name", WEBFRIEND_HCES1, DES_SUB ')
			->where("SUB_ASIGL0", $codSub)
			->where("cerrado_asigl0", "S")
			->where("IMPLIC_HCES1", ">", 0)
			->where("DESTACADO_ASIGL0", "S")
			->orderby('"start", ref_asigl0')
			->get();

		$sessions = array();
		foreach ($lots as $lot) {
			if (empty($sessions[$lot->name])) {
				$sessions[$lot->name] = array();
			}
			$sessions[$lot->name][] = $lot;
		}

		return View::make('pages.remates_destacados', compact("sessions"));
	}
}
