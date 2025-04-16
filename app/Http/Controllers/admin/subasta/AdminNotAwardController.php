<?php

namespace App\Http\Controllers\admin\subasta;

use App\Exports\AwardsExport;
use App\Http\Controllers\Controller;
use App\libs\FormLib;
use App\Models\V5\FgAsigl0;
use App\Providers\ToolsServiceProvider;
use App\ValueObjects\Filter;
use Illuminate\Http\Request;

class AdminNotAwardController extends Controller
{
	function __construct()
	{
		view()->share(['menu' => 'subastas']);
	}

	function index(Request $request, $isRender = false, $idauction = null)
	{
		$personalizedFields = $this->getConfigFields();

		$lotesNotAwardInstance = $this->getNotAwardsInstance($request, $idauction, $personalizedFields);
		$lotesNotAward = $lotesNotAwardInstance->paginate(30);

		['adjudicacionesFormat' => $adjudicacionesFormat, 'caracteristicas' => $caracteristicas] = $this->formatData($lotesNotAward);

		$tableFilters = (object)[
			'sub_asigl0' => !empty($idauction) ? FormLib::TextReadOnly('sub_asigl0', 0, $idauction) : FormLib::text('sub_asigl0', 0, $request->sub_asigl0 ?? ''),
			'ref_asigl0' => FormLib::text("ref_asigl0", 0, $request->ref_asigl0),
			'descweb_hces1' => FormLib::text("descweb_hces1", 0, $request->descweb_hces1),
			'impsalhces_asigl0' => FormLib::text('impsalhces_asigl0', 0, $request->impsalhces_asigl0),
			'fini_asigl0' => FormLib::Date('fini_asigl0', 0, $request->fini_asigl0),
			'ffin_asigl0' => FormLib::Date('ffin_asigl0', 0, $request->ffin_asigl0),
		];

		$tableParams = [
			'sub_asigl0' => 1,
			'ref_asigl0' => 1,
			'descweb_hces1' => 1,
			'impsalhces_asigl0' => 1,
			'fini_asigl0' => 1,
			'ffin_asigl0' => 1,
		];

		foreach ($personalizedFields as $field) {
			$tableParams[$field] = 1;
			$tableFilters->{$field} = FormLib::text($field, 0, $request->{$field});
		}

		if(config('app.featuresInAdmin', false) && !empty($caracteristicas)){
			$tableParams += $caracteristicas;
		}

		$data = [
			'lotNotAwards' => $adjudicacionesFormat,
			'originalNotAwards' => $lotesNotAward,
			'tableParams' => $tableParams,
			'formulario' => $tableFilters,
			'isRender' => $isRender,
			'idauction' => $idauction,
			'caracteristicas' => array_keys($caracteristicas)
		];

		if($isRender){
			return \View::make('admin::pages.subasta.no_adjudicados.table', $data)->render();
		}
		return \View::make('admin::pages.subasta.no_adjudicados.index', $data);
	}

	public function export(Request $request, $id = null)
    {
		$personalizedFields = $this->getConfigFields();

		$adjudicacionesInstance = $this->getNotAwardsInstance($request, $id, $personalizedFields);
		$adjudicaciones = $adjudicacionesInstance->get();
		['adjudicacionesFormat' => $adjudicacionesFormat, ] = $this->formatData($adjudicaciones);

		/**
		 * Mejorar esta parte, por ahora:
		 * - Se obtienen las keys de adjudicaciones
		 * - Se comprueba que existan con el select que llega de la config de la tabla (¿me puedo saltar esto?)
		 * - Se vuelve a construir array de adjudicaciones pero solo con las keys del select
		 */
		$keys = array_values($adjudicacionesFormat)[0]->keys();

		$selects = $request->selects;
		$headers = [];
		$awardsToExcel = [];

		foreach ($keys as $key) {
			if(!empty($selects[$key]) && $selects[$key]){
				$headers[$key] = trans("admin-app.fields.$key");
			}
		}

		foreach ($adjudicacionesFormat as $idAward => $adjudicacion) {
			$awardsToExcel[$idAward] = [];
			foreach ($headers as $key => $value) {
				$awardsToExcel[$idAward][$key] = $adjudicacion->get($key);
			}
		}

		return (new AwardsExport($awardsToExcel, $headers))->download("adjudicaciones_subasta_" . date("Ymd") . ".xlsx");
    }

	private function getNotAwardsInstance(Request $request, $idauction = null, $personalizedFields = [])
	{
		$filters = [
			new Filter('sub_asigl0', Filter::TYPE_SAME),
			new Filter('ref_asigl0', Filter::TYPE_SAME),
			new Filter('descweb_hces1', Filter::TYPE_LIKE),
			new Filter('impsalhces_asigl0', Filter::TYPE_SAME),
		];

		foreach ($personalizedFields as $field) {
			$filters[] = new Filter($field, Filter::TYPE_SAME);
		}

		$lotesNoAdjudicados = FgAsigl0::select('sub_asigl0', 'ref_asigl0', 'descweb_hces1', 'impsalhces_asigl0', 'fini_asigl0', 'ffin_asigl0')
			->addSelect($personalizedFields)
			->joinFghces1Asigl0()
			->where([
				['cerrado_asigl0', 'S'],
				['implic_hces1', '=', '0']
			])
			->when(config('app.featuresInAdmin', false), function($query, $features){
				return $query->addSelect('name_caracteristicas', 'value_caracteristicas_hces1', 'value_caracteristicas_value')
					->leftJoinCaracteristicasAsigl0()
					->whereIn('id_caracteristicas', explode(',', $features));
			})
			->when($idauction, function($query, $cod_sub){
				return $query->where('sub_asigl0', $cod_sub);
			})
			->when($request->fini_asigl0, function($query, $fecha){
				return $query->where('fini_asigl0', '>=', ToolsServiceProvider::getDateFormat($fecha, 'Y-m-d', 'Y/m/d') . ' 00:00:00');
			})
			->when($request->ffin_asigl0, function($query, $fecha){
				return $query->where('ffin_asigl0', '<=', ToolsServiceProvider::getDateFormat($fecha, 'Y-m-d', 'Y/m/d') . ' 23:59:59');
			})
			->whenFilters($request, $filters)
			->orderBy(request('order_not_awards', 'sub_asigl0'), request('order_not_awards_dir', 'desc'));

			return $lotesNoAdjudicados;
	}

	private function formatData($adjudicaciones)
	{
		$adjudicacionesFormat = [];
		$caracteristicas = [];

		foreach ($adjudicaciones as $adjudicacion) {

			$identificador = "$adjudicacion->sub_asigl0-$adjudicacion->ref_asigl0";

			$exist = !empty($adjudicacionesFormat[$identificador]);

			if(!$exist){
				$adjudicacionTemp = collect($adjudicacion->toArray());
				$adjudicacionesFormat[$identificador] = $adjudicacionTemp;
				$adjudicacionesFormat[$identificador]['fini_asigl0'] = ToolsServiceProvider::getDateFormat($adjudicacionesFormat[$identificador]['fini_asigl0'], 'Y-m-d H:i:s', 'd/m/Y');
				$adjudicacionesFormat[$identificador]['ffin_asigl0'] = ToolsServiceProvider::getDateFormat($adjudicacionesFormat[$identificador]['ffin_asigl0'], 'Y-m-d H:i:s', 'd/m/Y');
				$adjudicacionesFormat[$identificador][$adjudicacion->name_caracteristicas] = $adjudicacion->value_caracteristicas_hces1 ?? $adjudicacion->value_caracteristicas_value;
			}
			else{
				$adjudicacionesFormat[$identificador][$adjudicacion->name_caracteristicas] = $adjudicacion->value_caracteristicas_hces1 ?? $adjudicacion->value_caracteristicas_value;
			}

			$caracteristicas[$adjudicacion->name_caracteristicas] = 1;
		}

		return compact('adjudicacionesFormat', 'caracteristicas');
	}

	/**
	 * Añade campos establecidos en config
	 */
	private function getConfigFields()
	{
		$personalizedFieldsConfig = config('app.admin_notawards_params', null);

		if(!$personalizedFieldsConfig){
			return [];
		}

		return array_map(function($field){
			return trim($field);
		}, explode(',', $personalizedFieldsConfig));
	}

}
