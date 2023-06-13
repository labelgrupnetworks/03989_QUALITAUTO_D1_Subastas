<?php

namespace App\Http\Controllers\admin\subasta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Models\V5\FgAsigl1_Aux;
use App\Models\V5\FgAsigl1;
use App\libs\FormLib;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgHces1;
use App\Providers\ToolsServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminPujasController extends Controller
{

	private $perPage = 30;

	/**
	 * Mostrar página incial
	 * */
	function index(Request $request, $cod_sub, $resource_name)
	{
		$pujas = $this->pujasInstance();
		# he añadido el campo asigl0_aux para que se sepa si viene de una puja auxiliar o de una normal y así pdoer borrar la que toca
		$pujas = $pujas->select("ref_asigl1", "lin_asigl1", 'pujrep_asigl1', 'type_asigl1', "licit_asigl1", "imp_asigl1", "fec_asigl1", "hora_asigl1", "FXCLI.nom_cli", "FGHCES1.descweb_hces1", "cod2_cli", "fgasigl0.idorigen_asigl0", "fgasigl0.retirado_asigl0", "fgasigl0.ffin_asigl0", "fgasigl0.hfin_asigl0");
		if (Config::get('app.lower_bids', false) || config('app.auxiliar_bids', false)){
			$pujas =$pujas->addselect( "asigl0_aux");

		}
		$pujas = $pujas->where('sub_asigl1', $cod_sub)

					->when($request->ref_asigl1, function($query, $ref_asigl1){
						$query->where('ref_asigl1', $ref_asigl1);
					})
					->when($request->lin_asigl1, function($query, $lin_asigl1){
						$query->where('lin_asigl1', $lin_asigl1);
					})
					->when($request->licit_asigl1, function($query, $licit_asigl1){
						$query->where('licit_asigl1', $licit_asigl1);
					})
					->when($request->imp_asigl1, function($query, $imp_asigl1){
						$query->where('imp_asigl1', $imp_asigl1);
					})
					->when($request->fec_asigl1, function($query, $fec_asigl1){
						$query->where('fec_asigl1', '>=', ToolsServiceProvider::getDateFormat($fec_asigl1, 'Y-m-d', 'Y/m/d') . ' 00:00:00');
					})
					->when($request->idorigen_asigl0, function($query, $idorigen_asigl0){
						$query->where('upper(idorigen_asigl0)', 'like', "%" . mb_strtoupper($idorigen_asigl0) . "%");
					})
					->when($request->nom_cli, function($query, $nom_cli){
						$query->where('upper(nom_cli)', 'like', "%" . mb_strtoupper($nom_cli) . "%");
					})
					->when($request->descweb_hces1, function($query, $descweb_hces1){
						$query->where('upper(descweb_hces1)', 'like', "%" . mb_strtoupper($descweb_hces1) . "%");
					})
					->when($request->cod2_cli, function($query, $cod2_cli){
						$query->where('upper(cod2_cli)', 'like', "%" . mb_strtoupper($cod2_cli) . "%");
					})
					->when($request->pujrep_asigl1, function($query, $pujrep_asigl1){
						$query->where('pujrep_asigl1', $pujrep_asigl1);
					})
					->when($request->type_asigl1, function($query, $type_asigl1){
						$query->where('type_asigl1', $type_asigl1);
					})
					->when($request->retirado_asigl0, function($query, $retirado_asigl0){
						$query->where('retirado_asigl0', $retirado_asigl0);
					})
					->when($request->ffin_asigl0, function($query, $ffin_asigl0){
						$query->where('ffin_asigl0', $ffin_asigl0);
					})
					->orderby(request('order_pujas', 'fec_asigl1'), request('order_pujas_dir', 'desc'))
					->paginate($this->perPage, ['*'], 'pujasPage');


		//añadir array con los tipos de puja (pujrep y type) para poder utilizar en el select del filtro y para mostrar el nombre en la tabla
		$pujrepsArray = FgAsigl1::pujrepTypes();

		//En caso de querer mostrar solamente unos tipos de pujas, los obtenemos de un config
		if(config('app.admin_type_bids', false)){
			$pujrepsArray = collect($pujrepsArray)->filter(function ($type, $key) {
				return in_array($key, explode(',', config('app.admin_type_bids')));
			});
		}

		$typesArray = FgAsigl1::types();

		$filter = (object)[
			'ref_asigl1' => FormLib::text("ref_asigl1", 0, $request->ref_asigl1),
			'idorigen_asigl0' => FormLib::text("idorigen_asigl0", 0, $request->idorigen_asigl0),
			'lin_asigl1' => FormLib::text("lin_asigl1", 0, $request->lin_asigl1),
			'pujrep_asigl1' => FormLib::Select('pujrep_asigl1', 0, $request->pujrep_asigl1, $pujrepsArray),
			'type_asigl1' => FormLib::Select('type_asigl1', 0, $request->type_asigl1, $typesArray),
			'descweb_hces1' => FormLib::text("descweb_hces1", 0, $request->descweb_hces1),
			'nom_cli' => FormLib::text('nom_cli', 0, $request->nom_cli),
			'licit_asigl1' => FormLib::text("licit_asigl1", 0, $request->licit_asigl1),
			'cod2_cli' => FormLib::text("cod2_cli", 0, $request->cod2_cli),
			'fec_asigl1' => FormLib::Date('fec_asigl1', 0, $request->fec_asigl1),
			'imp_asigl1' => FormLib::text('imp_asigl1', 0, $request->imp_asigl1),
			'retirado_asigl0' => FormLib::text('retirado_asigl0', 0, $request->retirado_asigl0),
			'ffin_asigl0' => FormLib::Date('ffin_asigl0', 0, $request->ffin_asigl0),
		];

		return \View::make('admin::pages.subasta.subastas._nav_pujas', compact('pujas', 'filter', 'cod_sub', 'resource_name', 'pujrepsArray', 'typesArray'))->render();
	}

	function PujasExport($cod_sub){
		return (new PujasExport($cod_sub))->download("pujas_subasta_$cod_sub" . "_" . date("Ymd") . ".xlsx");

	}

	private function pujasInstance()
	{
		if (Config::get('app.lower_bids', false) || config('app.auxiliar_bids', false)){
			$asigl1_aux = FgAsigl1_Aux::withoutGlobalScopes(['emp'])->select('emp_asigl1', 'sub_asigl1', 'ref_asigl1', 'lin_asigl1', 'licit_asigl1', 'imp_asigl1', 'fec_asigl1', 'pujrep_asigl1', 'hora_asigl1', 'type_asigl1', 'usr_update_asigl1', 'date_update_asigl1', 'type_update_asigl1', "'SI' as asigl0_aux");
			$asigl1 = FgAsigl1::withoutGlobalScopes(['emp'])->select('emp_asigl1', 'sub_asigl1', 'ref_asigl1', 'lin_asigl1', 'licit_asigl1', 'imp_asigl1', 'fec_asigl1', 'pujrep_asigl1', 'hora_asigl1', 'type_asigl1', 'usr_update_asigl1', 'date_update_asigl1', 'type_update_asigl1', "'NO' as asigl0_aux");

			$pujasSql = $asigl1->unionAll($asigl1_aux)->toSql();

			#Se añade en la query el Left Join con FXCLI para que aparezcan todos las pujas
			return DB::table(DB::raw("($pujasSql) PUJAS"))
					->where('emp_asigl1', config('app.emp'))
					->join("FGLICIT", "EMP_LICIT = EMP_ASIGL1 AND SUB_LICIT = SUB_ASIGL1 AND COD_LICIT = LICIT_ASIGL1 ")
					->leftjoin("FXCLI", "GEMP_CLI = '". \Config::get("app.gemp") ."' AND COD_CLI = CLI_LICIT")
					->join("FGASIGL0", "EMP_ASIGL0 = EMP_ASIGL1 AND SUB_ASIGL0 = SUB_ASIGL1 AND REF_ASIGL0 = REF_ASIGL1 ")
					->join('FGHCES1', 'FGHCES1.EMP_HCES1 = FGASIGL0.EMP_ASIGL0 AND FGHCES1.NUM_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND FGHCES1.LIN_HCES1 = FGASIGL0.LINHCES_ASIGL0');
		}

		#Se añade en la query el Left Join con FXCLI para que aparezcan todos las pujas
		return FgAsigl1::leftJoinCli()->joinFghces1Asigl0();
	}

	public function deleteSelection(Request $request, $cod_sub)
	{
		foreach ($request->bids as $bid) {

			if($bid['instance'] == 'FgAsigl1') {
				$this->deleteBid($cod_sub, $bid['ref'], $bid['lin']);
			}
			elseif ($bid['instance'] == 'FgAsigl1_Aux') {
				FgAsigl1_Aux::where([
					'sub_asigl1' => $cod_sub,
					'ref_asigl1' => $bid['ref'],
					'lin_asigl1' => $bid['lin'],
				])->delete();
			}
		}

		return response()->json(['success' => true]);
	}

	private function deleteBid($cod_sub, $ref, $lin)
	{
		FgAsigl1::where([
			'sub_asigl1' => $cod_sub,
			'ref_asigl1' => $ref,
			'lin_asigl1' => $lin,
		])->delete();

		$newImplic = FgAsigl1::where([
			'sub_asigl1' => $cod_sub,
			'ref_asigl1' => $ref,
		])->max('imp_asigl1');

		//Si no existe puja se elimina el campo licitado y se establece el importe a 0
		$isLicit = !$newImplic ? 'N' : 'S';
		$newImplic = $newImplic ?? 0;

		$lot = FgAsigl0::select('numhces_asigl0', 'linhces_asigl0')
			->where([
				'sub_asigl0' => $cod_sub,
				'ref_asigl0' => $ref,
			])->first();

		FgHces1::where([
			'num_hces1' => $lot->numhces_asigl0,
			'lin_hces1' => $lot->linhces_asigl0,
		])->update([
			'implic_hces1' => $newImplic,
			'lic_hces1' => $isLicit
		]);

	}

}
