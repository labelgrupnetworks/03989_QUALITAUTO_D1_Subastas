<?php

namespace App\Http\Controllers\admin\bi;

use App\Http\Controllers\Controller;
use App\libs\FormLib;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgCsub;
use App\Models\V5\FgLicit;
use App\Models\V5\FgOrtsec0;
use App\Models\V5\FgSub;
use App\Models\V5\FxCliWeb;
use App\Providers\ToolsServiceProvider;
use Illuminate\Http\Request;

class AdminBiController extends Controller
{

	function __construct()
	{
		view()->share(['menu' => 'bi']);
	}

	public function index(Request $request)
	{

		/* DB::listen(function ($query) {
			dump($query->sql);
			dump($query->bindings);
		}); */

		//$this->lotsAwardForCategory($request);
		$formulario = $this->formFilters($request);
		return view('admin::pages.bi.index', compact('formulario'));
	}

	private function formFilters(Request $request)
	{
		$defaultYear = date('Y');

		$subastasFilters = FgSub::joinSessionSub()->orderBy('session_start', 'desc')->get();

		$years = $subastasFilters->pluck('session_start')->map(function ($date) {
			return date("Y", strtotime($date));
		})->unique()->toArray();

		$years = array_combine($years, $years);

		$months = $subastasFilters->pluck('session_start')->map(function ($date) {
			return date("M", strtotime($date));
		})->unique();

		$monthsNumber = $months->map(function ($date) {
			return date("m", strtotime($date));
		})->toArray();

		$months = array_combine($monthsNumber, $months->toArray());
		ksort($months);

		$auctionsTypes = (new FgSub())->getTipoSubTypes();

		return (object)[
			'years' => FormLib::Select("years[]", 0, "$defaultYear-01-01", $years, 'multiple', '', false),
			'months' => FormLib::Select("months[]", 0, "", $months, 'multiple', '', false),
			'tipo_subs' => FormLib::Select("tipo_subs[]", 0, "", $auctionsTypes, 'multiple', '', false),
			'auctions' => FormLib::Select("auctions[]", 0, "", [], 'multiple', '', false),
			//'lin_ortsec0' => FormLib::Select("lin_ortsec0[]", 0, "", [], 'multiple', '', false),
		];
	}


	public function lotsInfo(Request $request)
	{
		$subastas = $this->auctions($request)->get()
			->relationWith('lotes', [['sub_asigl0', '=', 'cod_sub'], ['ref_asigl0', '>=', 'init_lot'], ['ref_asigl0', '<=', 'end_lot']], function () {
				return FgAsigl0::query()
					->select('fgasigl0.ref_asigl0', 'fgasigl0.sub_asigl0', 'cerrado_asigl0', 'fghces1.implic_hces1')
					->joinFghces1Asigl0();
			})
			->relationWith('licitadores', ['sub_licit' => 'cod_sub'], function () {
				return FgLicit::query();
			});

		$subastas = $subastas->filter(function ($subasta) {
			return $subasta->lotes->count();
		});

		$auctionsForMonth = $this->auctionsForMonth($subastas);


		/* $infoTotales = $subastas->reduce(function ($totals, $subasta) {

			$totals['lotesCount'] = $subasta->lotes->count() + ($totals['lotesCount'] ?? 0);
			$totals['licitadores'] = $subasta->licitadores->count() + ($totals['licitadores'] ?? 0);

			if ($subasta->lotes->count() > 0) {

				$totals['vendidos'] = $subasta->lotes->reduce(function ($totales, $lote) {
					return 	$totales + ($lote->isAwarded ? 1 : 0);
				}) + ($totals['vendidos'] ?? 0);

				$totals['adjudicado'] = $subasta->lotes->sum(function ($lote) {
					return ($lote->isAwarded ? $lote->implic_hces1 : 0);
				}) + ($totals['adjudicado'] ?? 0);
			}

			return $totals;
		}); */

		$info = [
			'auctionsForMonth' => $auctionsForMonth
		];

		return response(compact('subastas', 'info'));
	}

	public function auctionsForMonth($auctions)
	{
		$auctionsForMonthArray = $auctions->groupBy(function ($auction) {
			return intval(ToolsServiceProvider::getDateFormat($auction->session_start, 'Y-m-d H:i:s', 'm'));
		});

		$auctionsForMonth = $auctionsForMonthArray->map(function ($actionsGroup) {
			return [
				'count' => $actionsGroup->count(),
				'awardValue' => $actionsGroup->map(function ($auction) {
					return $auction->lotes->sum(function ($lote) {
						return ($lote->isAwarded ? $lote->implic_hces1 : 0);
					});
				})->sum()
			];
		});


		return $auctionsForMonth;
	}

	public function lotsAwardForCategory(Request $request)
	{
		$subFamilies = FgOrtsec0::select('sec_ortsec1', 'des_sec')
			->getAllFgOrtsec0()
			->joinOrtsec1FgOrtsec0()
			->joinSecOrtsec0()
			->when($request->lin_ortsec0, function ($query, $lin_ortsec) {
				return $query->whereIn('lin_ortsec0', $lin_ortsec);
			})
			->get()
			->relationWith('lotes', [['sec_hces1', '=', 'sec_ortsec1']], function () use ($request) {
				$lotes = FgAsigl0::query()
					->select('fgasigl0.ref_asigl0', 'fgasigl0.sub_asigl0', 'cerrado_asigl0', 'impsalhces_asigl0', 'fghces1.implic_hces1', 'fghces1.sec_hces1', 'auc."start"', 'auc."id_auc_sessions"')
					->joinFghces1Asigl0()
					->joinSessionAsigl0()
					->joinSubastaAsigl0()
					->whereIn('SUBC_SUB', [FgSub::SUBC_SUB_ACTIVO, FgSub::SUBC_SUB_HISTORICO]);
				/* ->where([
						['cerrado_asigl0', 'S'],
						['lic_hces1', 'S']
					]); */

				return $this->whereFilters($request, $lotes);
			})
			->filter(function ($category) {
				return $category->lotes->count() > 0;
			})->values();

		return response($subFamilies);
	}

	private function auctions(Request $request)
	{
		$auctions = FgSub::joinSessionSub()
			->addSelect('"auc_sessions"."init_lot"', '"auc_sessions"."end_lot"')
			->whereIn('SUBC_SUB', [FgSub::SUBC_SUB_ACTIVO, FgSub::SUBC_SUB_HISTORICO]);


		$auctions =	$this->whereFilters($request, $auctions)
			->orderBy('"start"');
		//->get();

		return $auctions;
	}

	private function whereFilters(Request $request, $query)
	{
		$defaultYear = date('Y');

		return $query->when($request->years, function ($query, $years) {
			return $query->whereIn('to_char("start", \'yyyy\')', $years);
		}, function ($query) use ($defaultYear, $request) {

			if (!empty($request->all())) {
				return $query;
			}
			return $query->where('"start"', '>=', "$defaultYear/01/01");
		})

			->when($request->months, function ($query, $months) {
				return $query->whereIn('to_char("start", \'mm\')', $months);
			})

			->when($request->tipo_subs, function ($query, $tipo_subs) {
				return $query->whereIn('tipo_sub', $tipo_subs);
			})

			->when($request->auctions, function ($query, $auctions) {
				return $query->whereIn('"id_auc_sessions"', $auctions);
			});
	}

	public function getAuctionInfo(Request $request)
	{

		if (!$request->id_auc_sessions) {
			return abort(404);
		}

		$auction = $this->auctions($request)
			->where('"id_auc_sessions"', $request->id_auc_sessions)
			->get()
			->relationWith('adjudicaciones', [['sub_csub', '=', 'cod_sub'], ['ref_asigl0', '>=', 'init_lot'], ['ref_asigl0', '<=', 'end_lot']], function () {
				return FgCsub::joinAsigl0()->leftJoinCli()->joinFghces1()
					->select('himp_csub', 'sub_csub', 'licit_csub', 'impsalhces_asigl0', 'ref_asigl0', 'cod_cli', 'nom_cli', 'fghces1.descweb_hces1');
			})
			->first();

		$topCompradores = $auction->adjudicaciones->groupBy('licit_csub')
			->map(function ($awards, $key) {
				return [
					'nom_cli' => $awards->first()->nom_cli,
					'himp_csub' => $awards->reduce(function ($carry, $item) {
						return $carry + $item->himp_csub;
					}, 0),
					'lots' => $awards->count()
				];
			})
			->sortByDesc('himp_csub');

		//dump($topCompradores);

		$topVentas = $auction->adjudicaciones->sortByDesc('himp_csub')->values();
		//dump($topVentas);

		$topIncremento = $auction->adjudicaciones->sortByDesc(function ($award) {
			return ($award->himp_csub - $award->impsalhces_asigl0) / $award->impsalhces_asigl0;
		})->values();
		//dd($topIncremento);

		/**
		 * @todo Cambiar respuesta, crear las tres vistas de las tablas para cargarlas directamente en
		 * sus tabs
		 */

		return response(view('admin::pages.bi._modal', [])->render());

		//(awardedValue - startingAwardedPrice) / startingAwardedPrice * 100;

	}

	private function clientsRegiter()
	{
		$clientes = FxCliWeb::orderby('fecalta_cliweb')->get();
		$clientesForDate = $clientes->groupBy(function ($client, $key) {
			return \Tools::getDateFormat($client->fecalta_cliweb, 'Y-m-d H:i:s', 'm-Y');
		})->map(function ($clientes, $key) {
			return $clientes->count();
		});
	}
}
