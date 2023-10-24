<?php

namespace App\Http\Controllers\admin\subasta;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\V5\FgSub;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgOrlic;
use App\Models\V5\FgAsigl1;
use App\Models\V5\FxPro;
use App\Models\V5\FxCli;
use App\libs\FormLib;
use App\Http\Controllers\CustomControllers;

class AdminStockController extends Controller
{
	protected $resource_name;
	protected $parent_name;
	protected $emp;

	public function __construct()
	{

		$this->emp = Config::get('app.emp');
		$this->resource_name = 'stock';
		$this->parent_name = 'subastas';
		view()->share(['menu' => 'subastas']);
	}

	public function index(Request $request)
    {
/*
		$cod_sub="FONDOGAL";
		$tipo_sub = FgSub::where('stock_hces1','>', 0)->where('controlstock_hces1', 'S')->first()->tipo_sub;
*/
		$lotes = FgAsigl0::query();
		if ($request->obsdet_hces1) {
			$lotes->where('upper(obsdet_hces1)', 'like', "%" . mb_strtoupper($request->obsdet_hces1) . "%");
		}
		if ($request->des_alm) {
			$lotes->where('upper(des_alm)', 'like', "%" . mb_strtoupper($request->des_alm) . "%");
		}
		if ($request->fecalta_asigl0) {
			$lotes->where('fecalta_asigl0', '=', $request->fecalta_asigl0);
		}

		if ($request->stock_hces1) {
			$lotes->where('stock_hces1', '=', $request->stock_hces1);
		}
		if ($request->sub_asigl0) {
			$lotes->where('upper(sub_asigl0)', '=', mb_strtoupper($request->sub_asigl0));
		}
		if ($request->ref_asigl0) {
			$lotes->where('ref_asigl0', '=', $request->ref_asigl0);
		}
		if ($request->idorigen_asigl0) {
			$lotes->where('upper(idorigen_asigl0)', 'like', "%" . mb_strtoupper($request->idorigen_asigl0) . "%");
		}
		if ($request->cerrado_asigl0) {
			$lotes->where('cerrado_asigl0', '=', $request->cerrado_asigl0);
		}
		if ($request->impsalhces_asigl0) {
			$lotes->where('impsalhces_asigl0', '=', $request->impsalhces_asigl0);
		}
		if ($request->destacado_asigl0) {
			$lotes->where('destacado_asigl0', '=', $request->destacado_asigl0);
		}
		if ($request->retirado_asigl0) {
			$lotes->where('retirado_asigl0', '=', $request->retirado_asigl0);
		}
		if ($request->oculto_asigl0) {
			$lotes->where('oculto_asigl0', '=', $request->oculto_asigl0);
		}
		if ($request->impres_asigl0) {
			$lotes->where('impres_asigl0', '=', $request->impres_asigl0);
		}
		if ($request->impres_asigl0) {
			$lotes->where('impres_asigl0', '=', $request->impres_asigl0);
		}
		if ($request->imptas_asigl0) {
			$lotes->where('imptas_asigl0', '=', $request->imptas_asigl0);
		}
		if ($request->imptash_asigl0) {
			$lotes->where('imptash_asigl0', '=', $request->imptash_asigl0);
		}
		if ($request->comlhces_asigl0) {
			$lotes->where('comlhces_asigl0', '=', $request->comlhces_asigl0);
		}
		if ($request->comphces_asigl0) {
			$lotes->where('comphces_asigl0', '=', $request->comphces_asigl0);
		}
		if ($request->prop_hces1) {
			$lotes->where('prop_hces1', '=', $request->prop_hces1);
		}
		if ($request->descweb_hces1) {
			$lotes->where('upper(descweb_hces1)', 'like', "%" . mb_strtoupper($request->descweb_hces1) . "%");
		}
		if ($request->fini_asigl0) {
			$lotes->where('fini_asigl0', '>=' ,$request->fini_asigl0);
		}
		if ($request->ffin_asigl0) {
			$lotes->where('ffin_asigl0', '<=',$request->ffin_asigl0);
		}
		if ($request->compra_asigl0) {
			$lotes->where('compra_asigl0', '<=',$request->compra_asigl0);
		}

		$select = ['SUB_ASIGL0', 'REF_ASIGL0', 'IDORIGEN_ASIGL0', 'CERRADO_ASIGL0', 'IMPSALHCES_ASIGL0', 'impres_asigl0', 'imptas_asigl0', 'imptash_asigl0', 'comlhces_asigl0', 'comphces_asigl0', 'DESTACADO_ASIGL0', 'RETIRADO_ASIGL0', 'OCULTO_ASIGL0', 'NUMHCES_ASIGL0', 'LINHCES_ASIGL0', 'PROP_HCES1', 'DESCWEB_HCES1', 'fini_asigl0', 'ffin_asigl0','STOCK_HCES1','OBSDET_HCES1','FECALTA_ASIGL0', 'DES_ALM', 'compra_asigl0'];

		$tableParams = [
			'sub_asigl0' => 1, 'ref_asigl0' => Config::get('external_id', 1),'stock_hces1' => 1,'compra_asigl0' => 1,'des_alm' => 1,'obsdet_hces1' => 1,'fecalta_asigl0' => 1,'idorigen_asigl0' => Config::get('external_id', 0), 'prop_hces1' => 1,
			'descweb_hces1' => 0, 'artist_name' => 1,'technique' => 1,'measurement' => 1, 'impsalhces_asigl0' => 1,  'impres_asigl0' => 0, 'imptas_asigl0' => 0, 'imptash_asigl0' => 0,
			'comlhces_asigl0' => 0, 'comphces_asigl0' => 0, 'cerrado_asigl0' => 0, 'destacado_asigl0' => 0, 'retirado_asigl0' => 0, 'oculto_asigl0' => 0,
			'fini_asigl0' => 0, 'ffin_asigl0' => 0
		];

		$lotes = $lotes->select($select)
			->joinFghces1Asigl0()
			->LeftJoinAlm()
			->where('stock_hces1','>', 0); /* ->where('controlstock_hces1', 'S');*/

		if(config('app.ArtistCode', false)){
			$lotes = $lotes->withArtist()
			->when($request->artist_name, function($query, $artist) {
				//return $query->havingRaw("upper(LISTAGG(FGCARACTERISTICAS_VALUE.VALUE_CARACTERISTICAS_VALUE, ', ')) LIKE upper('%$artists%')");
				return $query->where('upper(FGCARACTERISTICAS_VALUE.VALUE_CARACTERISTICAS_VALUE)', 'like', "%" . mb_strtoupper($artist) . "%");
			});
		}
		if(config('app.techniqueCodeInStock', false)){
			$lotes = $lotes->leftJoin("FGCARACTERISTICAS technique" , "technique.EMP_CARACTERISTICAS = EMP_ASIGL0 and technique.ID_CARACTERISTICAS = ".config('app.techniqueCodeInStock', 0))
			->leftJoin("FGCARACTERISTICAS_HCES1 technique_hces1" , "technique_hces1.EMP_CARACTERISTICAS_HCES1 = EMP_ASIGL0 AND technique_hces1.IDCAR_CARACTERISTICAS_HCES1 = technique.ID_CARACTERISTICAS AND technique_hces1.NUMHCES_CARACTERISTICAS_HCES1 = NUMHCES_ASIGL0 AND technique_hces1.LINHCES_CARACTERISTICAS_HCES1 = LINHCES_ASIGL0")
			->addSelect('technique_hces1.VALUE_CARACTERISTICAS_HCES1 as technique');
			#busqueda
			$lotes = $lotes->when($request->technique, function($query, $technique) {
				//return $query->havingRaw("upper(LISTAGG(FGCARACTERISTICAS_VALUE.VALUE_CARACTERISTICAS_VALUE, ', ')) LIKE upper('%$artists%')");
				return $query->where('upper(technique_hces1.VALUE_CARACTERISTICAS_HCES1)', 'like', "%" . mb_strtoupper($technique) . "%");
			});
		}



		if(config('app.measurementCodeInStock', false)){
			$lotes = $lotes->leftJoin("FGCARACTERISTICAS measurement" , "measurement.EMP_CARACTERISTICAS = EMP_ASIGL0 and measurement.ID_CARACTERISTICAS = ".config('app.measurementCodeInStock', 0))
			->leftJoin("FGCARACTERISTICAS_HCES1 measurement_hces1" , "measurement_hces1.EMP_CARACTERISTICAS_HCES1 = EMP_ASIGL0 AND measurement_hces1.IDCAR_CARACTERISTICAS_HCES1 = measurement.ID_CARACTERISTICAS AND measurement_hces1.NUMHCES_CARACTERISTICAS_HCES1 = NUMHCES_ASIGL0 AND measurement_hces1.LINHCES_CARACTERISTICAS_HCES1 = LINHCES_ASIGL0")
			->addSelect('measurement_hces1.VALUE_CARACTERISTICAS_HCES1 as measurement');
			#busqueda
			$lotes = $lotes->when($request->measurement, function($query, $measurement) {
				//return $query->havingRaw("upper(LISTAGG(FGCARACTERISTICAS_VALUE.VALUE_CARACTERISTICAS_VALUE, ', ')) LIKE upper('%$artists%')");
				return $query->where('upper(measurement_hces1.VALUE_CARACTERISTICAS_HCES1)', 'like', "%" . mb_strtoupper($measurement) . "%");
			});
		}



		$lotes = $lotes->orderBy($request->filled('order') ? $request->order : 'ref_asigl0', $request->filled('order_dir') ? $request->order_dir : 'asc')
			->paginate(30, '*', 'lotesPage');


		$fgAsigl0 = new FgAsigl0();
		$lotesRef = $lotes->pluck('ref_asigl0');
/*
		$ordenes = FgOrlic::where('sub_orlic', $cod_sub)->whereIn('ref_orlic', $lotesRef)->get();
		$pujas = FgAsigl1::where('sub_asigl1', $cod_sub)->whereIn('ref_asigl1', $lotesRef)->get();
*/
		$propietarios = null;
		if(config('app.useProviders', 0)){
			$propietarios = FxPro::select('cod_pro', 'nom_pro')->pluck('nom_pro', 'cod_pro');
		}
		else{
			$propietarios = FxCli::select('cod_cli', 'rsoc_cli')->pluck('rsoc_cli', 'cod_cli');
		}

		$formulario = (object)[
			'stock_hces1' => FormLib::Text('stock_hces1', 0, $request->stock_hces1, '', ''),
			'ref_asigl0' => FormLib::Text('ref_asigl0', 0, $request->ref_asigl0, '', ''),
			'idorigen_asigl0' => FormLib::Text('idorigen_asigl0', 0, $request->idorigen_asigl0, '', ''),
			'sub_asigl0' => FormLib::Text('sub_asigl0', 0, $request->sub_asigl0, '', ''),
			'prop_hces1' => FormLib::Select2WithAjax('prop_hces1', 0, $request->prop_hces1, '', config('app.useProviders', 0) ? route('provider.list') : route('client.list'), trans('admin-app.placeholder.owner')),
			'descweb_hces1' => FormLib::Text('descweb_hces1', 0, $request->descweb_hces1, '', ''),
			'artist_name' => FormLib::Text('artist_name', 0, $request->artist_name),
			'measurement' => FormLib::Text('measurement', 0, $request->measurement),
			'technique' => FormLib::Text('technique', 0, $request->technique),
			'impsalhces_asigl0' => FormLib::Text('impsalhces_asigl0', 0, $request->impsalhces_asigl0, '', ''),
			'impres_asigl0' => FormLib::Text('impres_asigl0', 0, $request->impres_asigl0, '', ''),
			'imptas_asigl0' => FormLib::Text('imptas_asigl0', 0, $request->imptas_asigl0, '', ''),
			'imptash_asigl0' => FormLib::Text('imptash_asigl0', 0, $request->imptash_asigl0, '', ''),
			'comlhces_asigl0' => FormLib::Text('comlhces_asigl0', 0, $request->comlhces_asigl0, '', ''),
			'comphces_asigl0' => FormLib::Text('comphces_asigl0', 0, $request->comphces_asigl0, '', ''),
			'cerrado_asigl0' => FormLib::Select('cerrado_asigl0', 0, $request->cerrado_asigl0, ['S' => 'Si', 'N' => 'No']),
			'destacado_asigl0' => FormLib::Select('destacado_asigl0', 0, $request->destacado_asigl0, ['S' => 'Si', 'N' => 'No']),
			'retirado_asigl0' => FormLib::Select('retirado_asigl0', 0, $request->retirado_asigl0, ['S' => 'Si', 'N' => 'No']),
			'oculto_asigl0' => FormLib::Select('oculto_asigl0', 0, $request->oculto_asigl0, ['S' => 'Si', 'N' => 'No']),
			'fini_asigl0' => FormLib::Date('fini_asigl0', 0, $request->fini_asigl0),
			'ffin_asigl0' => FormLib::Date('ffin_asigl0', 0, $request->ffin_asigl0),
			'stock_hces1' => FormLib::Text('stock_hces1', 0, $request->stock_hces1, '', ''),
			'des_alm' => FormLib::Text('des_alm', 0, $request->des_alm, '', ''),
			'obsdet_hces1' => FormLib::Text('obsdet_hces1', 0, $request->obsdet_hces1, '', ''),
			'fecalta_asigl0' => FormLib::Date('fecalta_asigl0', 0, $request->fecalta_asigl0, '', ''),
			'compra_asigl0' => FormLib::Text('compra_asigl0', 0, $request->compra_asigl0, '', ''),
		];

		//retorna la vista completa o solamente la tabla

		$resource_name = $this->resource_name;
		$parent_name = $this->parent_name;
		$render = false;
		$data = compact('lotes', 'formulario',  'tableParams', 'propietarios', 'resource_name', 'parent_name', 'render');



		return view('admin::pages.subasta.stock.index', $data);

	}

	public function excel(){
		#Hay que hacer que imprima los lotes que tienen stock

		$excelController = new CustomControllers();
		return $excelController->excelExhibition(null,null, true);

	}

}
