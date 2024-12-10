<?php

namespace App\Http\Controllers\admin\b2b;

use App\Http\Controllers\Controller;
use App\libs\FormLib;
use App\Models\V5\FgAsigl1;
use App\Models\V5\FgSub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminB2BBidsController extends Controller
{
	public function index(Request $request)
	{
		$ownerCod = Session::get('user.cod');
		$auctionsCods = FgSub::query()
			->where('agrsub_sub', $ownerCod)
			->pluck('cod_sub');

		$pujrepsArray = FgAsigl1::pujrepTypes();
		$typesArray = FgAsigl1::types();

		$bids = FgAsigl1::query()
			->select([
				'ref_asigl1',
				'lin_asigl1',
				'pujrep_asigl1',
				'type_asigl1',
				"licit_asigl1",
				"imp_asigl1",
				"fec_asigl1",
				"hora_asigl1",
				"FXCLI.nom_cli",
				"FGHCES1.descweb_hces1",
				"FXCLI.cod_cli",
			])
			->leftJoinCli()
			->joinFghces1Asigl0()
			->whereIn('sub_asigl1', $auctionsCods)
			->orderby($request->input('order_pujas') ?? 'fec_asigl1', $request->input('order_pujas_dir', 'desc'))
			->paginate(20);

		$bids->each(function ($bid) use ($pujrepsArray, $typesArray) {
			$bid->pujrep_asigl1 = $pujrepsArray[$bid->pujrep_asigl1] ?? '';
			$bid->type_asigl1 = $typesArray[$bid->type_asigl1] ?? '';
		});

		$formulario = (object)[
			'ref_asigl1' => FormLib::text("ref_asigl1", 0, $request->ref_asigl1),
			'lin_asigl1' => FormLib::text("lin_asigl1", 0, $request->lin_asigl1),
			'pujrep_asigl1' => FormLib::Select('pujrep_asigl1', 0, $request->pujrep_asigl1, $pujrepsArray),
			'type_asigl1' => FormLib::Select('type_asigl1', 0, $request->type_asigl1, $typesArray),
			'descweb_hces1' => FormLib::text("descweb_hces1", 0, $request->descweb_hces1),
			'nom_cli' => FormLib::text('nom_cli', 0, $request->nom_cli),
			'cod_cli' => FormLib::text("cod2_cli", 0, $request->cod_cli),
			'imp_asigl1' => FormLib::text('imp_asigl1', 0, $request->imp_asigl1),
		];

		$tableParams = [
			'ref_asigl1' => 1,
			'descweb_hces1' => 1,
			'lin_asigl1' => 1,
			'pujrep_asigl1' => 1,
			'type_asigl1' => 1,
			'imp_asigl1' => 1,
			'fec_asigl1' => 1,
			'nom_cli' => 1,
			'cod_cli' => 1,
		];

		$numberOfColumns = count(array_filter($tableParams));

		$data = [
			'bids' => $bids,
			'formulario' => $formulario,
			'pujrepsArray' => $pujrepsArray,
			'typesArray' => $typesArray,
			'tableParams' => $tableParams,
			'numberOfColumns' => $numberOfColumns
		];

		return view('admin::pages.b2b.bids.index', $data);
	}
}
