<?php

namespace App\Http\Controllers\admin\subasta;

use App\Exports\BidsExport;
use App\Http\Controllers\Controller;
use App\libs\FormLib;
use App\Models\V5\FgAsigl1;
use App\Services\admin\Auction\BidsService;
use Illuminate\Http\Request;

class AdminBidsController extends Controller
{
	private $perPage = 30;

	/**
	 * Mostrar página incial
	 * */
	function index(Request $request, BidsService $bidsService)
	{
		$pujas = $bidsService->getBidsQueryFromFilters($request);

		if ($request->input('export', false)) {
			$fileName = "pujas" . "_" . date('d-m-Y_H-i-s');

			//por el momento descargamos el excel directamente.
			//Pero dejo la segunda opción que nos sirve para hacer y guardar el excel en segundo plano
			return (new BidsExport($request->all()))->download("$fileName.xlsx");

			//if count($pujas) > 20000
			// Excel::queue(new QueryExport($request->all()), "$fileName.xlsx");
			// return  redirect()->back()
			// 	->with(['success' => [0 => 'Exportando pujas, le avisaremos cuando termine']]);
		}

		//añadir array con los tipos de puja (pujrep y type) para poder utilizar en el select del filtro y para mostrar el nombre en la tabla
		$pujrepsArray = FgAsigl1::pujrepTypes();

		//En caso de querer mostrar solamente unos tipos de pujas, los obtenemos de un config
		if (config('app.admin_type_bids', false)) {
			$pujrepsArray = collect($pujrepsArray)->filter(function ($type, $key) {
				return in_array($key, explode(',', config('app.admin_type_bids')));
			});
		}

		$typesArray = FgAsigl1::types();

		$filter = (object)[
			'sub_asigl1' => FormLib::text("sub_asigl1", 0, $request->sub_asigl1),
			'ref_asigl1' => FormLib::text("ref_asigl1", 0, $request->ref_asigl1),
			'idorigen_asigl0' => FormLib::text("idorigen_asigl0", 0, $request->idorigen_asigl0),
			'lin_asigl1' => FormLib::text("lin_asigl1", 0, $request->lin_asigl1),
			'pujrep_asigl1' => FormLib::Select('pujrep_asigl1', 0, $request->pujrep_asigl1, $pujrepsArray),
			'type_asigl1' => FormLib::Select('type_asigl1', 0, $request->type_asigl1, $typesArray),
			'descweb_hces1' => FormLib::text("descweb_hces1", 0, $request->descweb_hces1),
			'nom_cli' => FormLib::text('nom_cli', 0, $request->nom_cli),
			'licit_asigl1' => FormLib::text("licit_asigl1", 0, $request->licit_asigl1),
			'cod2_cli' => FormLib::text("cod2_cli", 0, $request->cod2_cli),
			'fec_asigl1' => [
				'from_fec_asigl1' => FormLib::Date('from_fec_asigl1', 0, $request->from_fec_asigl1),
				'to_fec_asigl1' => FormLib::Date('to_fec_asigl1', 0, $request->to_fec_asigl1),
			],
			'imp_asigl1' => FormLib::text('imp_asigl1', 0, $request->imp_asigl1),
			'retirado_asigl0' => FormLib::text('retirado_asigl0', 0, $request->retirado_asigl0),
			'ffin_asigl0' => FormLib::Date('ffin_asigl0', 0, $request->ffin_asigl0),
		];

		$tableParams = [
			'sub_asigl1' => 1,
			'ref_asigl1' => 1,
			'idorigen_asigl0' => 0,
			'lin_asigl1' => 1,
			'pujrep_asigl1' => 1,
			'type_asigl1' => 1,
			'descweb_hces1' => 0,
			'nom_cli' => 1,
			'licit_asigl1' => 1,
			'cod2_cli' => 0,
			'fec_asigl1' => 1,
			'imp_asigl1' => 1,
			'retirado_asigl0' => 0,
			'ffin_asigl0' => 0
		];

		$data = [
			'bids' => $pujas->paginate($this->perPage),
			'formulario' => $filter,
			'tableParams' => $tableParams,
			'pujrepsArray' => $pujrepsArray,
			'typesArray' => $typesArray,
		];

		return view('admin::pages.subasta.pujas.index', $data);
	}

}
