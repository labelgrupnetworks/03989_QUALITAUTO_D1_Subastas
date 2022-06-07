<?php

namespace App\Http\Controllers\admin\subasta;

use App\Http\Controllers\Controller;
use App\libs\FormLib;
use App\Models\V5\FgCsub;
use Illuminate\Http\Request;
use App\Providers\ToolsServiceProvider;
use Illuminate\Support\Str;
use App\Exports\WinnersExport;

class AdminWinnerController extends Controller
{

	/*index, create, show, edit, store, update, destroy*/

	/**
	 * Mostrar página incial
	 * */
	function index(Request $request, $idauction, $resource_name){

		$ganadores = FgCsub::query();
		$isSub = false;

			if ($request->ref_csub) {
				$ganadores->where('upper(ref_csub)', '=',  $request->ref_csub);
			}
			if ($request->descweb_hces1) {
				$ganadores->where('upper(descweb_hces1)', 'like', "%" . mb_strtoupper($request->descweb_hces1) . "%");
			}
			if ($request->cod2_cli) {
				$ganadores->where('cod2_cli', '=', $request->cod2_cli);
			}
			if ($request->clifac_csub) {
				$ganadores->where('clifac_csub', '=', $request->clifac_csub);
			}
			if ($request->rsoc_cli) {
				$ganadores->where('upper(rsoc_cli)', 'like', "%" . mb_strtoupper($request->rsoc_cli) . "%");
			}
			if ($request->licit_csub) {
				$ganadores->where('licit_csub', '=', $request->licit_csub);
			}
			if ($request->fec_asigl1) {
				$ganadores->where('fec_asigl1', '>=', ToolsServiceProvider::getDateFormat($request->fec_asigl1, 'Y-m-d', 'Y/m/d') . ' 00:00:00');
			}
			if ($request->himp_csub) {
				$import = Str::replaceFirst(',', '.', $request->himp_csub);
				$ganadores->where('himp_csub', '=', $import);
			}



		$ganadores = $ganadores->select('ref_csub', 'cod2_cli', 'rsoc_cli', 'licit_csub', 'fec_asigl1', 'himp_csub', 'clifac_csub', 'fghces1.descweb_hces1')
			->joinWinnerBid()
			->joinCli()
			->joinAsigl0()
			->joinFghces1()
			->where('sub_csub', $idauction)
			->orderby(request('order_winner', 'FgCsub.fecfra_csub'), request('order_winner_dir', 'desc'))
			->paginate(20)
			->keyBy('ref_csub');



		$filter = (object)[

			'ref_csub' => FormLib::text("ref_csub", 0, $request->ref_csub),
			'descweb_hces1' => FormLib::text("descweb_hces1", 0, $request->descweb_hces1),
			'cod2_cli' => FormLib::text("cod2_cli", 0, $request->cod2_cli),
			'clifac_csub' => FormLib::text("clifac_csub", 0, $request->clifac_csub),
			'rsoc_cli' => FormLib::text('rsoc_cli', 0, $request->rsoc_cli),
			'licit_csub' => FormLib::text('licit_csub', 0, $request->licit_csub),
			'fec_asigl1' => FormLib::Date('fec_asigl1', 0, $request->fec_asigl1),
			'himp_csub' => FormLib::text('himp_csub', 0, $request->himp_csub),

		];

		return \View::make('admin::pages.subasta.subastas._nav_ganadores', compact('ganadores', 'filter', 'isSub', 'idauction', 'resource_name'))->render();
	}

	function winnersExport($cod_sub){
		return (new WinnersExport($cod_sub))->download("ganadores_subasta_$cod_sub" . "_" . date("Ymd") . ".xlsx");
	}



}
