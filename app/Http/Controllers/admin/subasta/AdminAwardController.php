<?php

namespace App\Http\Controllers\admin\subasta;

use App\Http\Controllers\Controller;
use App\Http\Controllers\apilabel\AwardController;
use App\libs\FormLib;
use App\Models\V5\FgLicit;
use App\Models\V5\FxCli;
use Illuminate\Http\Request;
use App\Exports\AwardsExport;
use App\Models\Filter;
use App\Models\V5\FgAsigl0;
use App\Providers\ToolsServiceProvider;

class AdminAwardController extends Controller
{

	function __construct()
	{
		view()->share(['menu' => 'subastas']);
	}

	function index(Request $request, $isRender = false, $idauction = null, $tipo_sub = null)
	{
		$personalizedFields = $this->getConfigFields();

		$adjudicacionesInstance = $this->getAwardsInstance($request, $idauction, $personalizedFields);
		$adjudicaciones = $adjudicacionesInstance->paginate(30);

		['adjudicacionesFormat' => $adjudicacionesFormat, 'caracteristicas' => $caracteristicas] = $this->formatData($adjudicaciones);

		$tableFilters = (object)[
			'sub_asigl0' => !empty($idauction) ? FormLib::TextReadOnly('sub_asigl0', 0, $idauction) : FormLib::text('sub_asigl0', 0, $request->sub_asigl0 ?? ''),
			'ref_asigl0' => FormLib::text("ref_asigl0", 0, $request->ref_asigl0),
			'descweb_hces1' => FormLib::text("descweb_hces1", 0, $request->descweb_hces1),
			'impsalhces_asigl0' => FormLib::text('impsalhces_asigl0', 0, $request->impsalhces_asigl0),
			'himp_csub' => FormLib::text("himp_csub", 0, $request->himp_csub),
			'base_csub' => FormLib::text('base_csub', 0, $request->base_csub),
			'fecha_csub' => [
				FormLib::Date('from_fecha_csub', 0, $request->from_fecha_csub),
				FormLib::Date('to_fecha_csub', 0, $request->to_fecha_csub),
			],
			'nom_cli' => FormLib::text("nom_cli", 0, $request->nom_cli),
			'rsoc_cli' => FormLib::text("rsoc_cli", 0, $request->rsoc_cli),
			'email_cli' => FormLib::text("email_cli", 0, $request->email_cli),
			'cod_cli' => FormLib::text("cod_cli", 0, $request->cod_cli),
			'afral_csub' => FormLib::text("afral_csub", 0, $request->afral_csub),
		];

		$tableParams = [
			'sub_asigl0' => 1,
			'ref_asigl0' => 1,
			'descweb_hces1' => 1,
			'impsalhces_asigl0' => 1,
			'himp_csub' => 1,
			'base_csub' => 1,
			'fecha_csub' => 1,
			'nom_cli' => 1,
			'rsoc_cli' => 1,
			'email_cli' => 1,
			'cod_cli' => 0,
		];

		# Este condicional lo que hace es colocar un 1 para que sea visible en caso de que esté el config activado
		if (\Config::get('app.payAwards')) {
			$tableParams['afral_csub'] = 1;
		} else {
			$tableParams['afral_csub'] = 0;
		}
		if (\Config::get('app.payDepositTpv')) {
			$tableParams['cod_deposito'] = 1;
			$tableParams['importe_deposito'] = 1;
		} else {
			$tableParams['cod_deposito'] = 0;
			$tableParams['importe_deposito'] = 0;
		}


		foreach ($personalizedFields as $field) {
			$tableParams[$field] = 1;
			$tableFilters->{$field} = FormLib::text($field, 0, $request->{$field});
		}

		if(config('app.featuresInAdmin', false) && !empty($caracteristicas)){
			$tableParams += $caracteristicas;
		}

		$data = [
			'awards' => $adjudicacionesFormat,
			'originalAwards' => $adjudicaciones,
			'tableParams' => $tableParams,
			'formulario' => $tableFilters,
			'isRender' => $isRender,
			'idauction' => $idauction,
			'caracteristicas' => array_keys($caracteristicas),
			'tipo_sub' => $tipo_sub
		];

		if($isRender){
			return \View::make('admin::pages.subasta.adjudicaciones.table', $data)->render();
		}
		return \View::make('admin::pages.subasta.adjudicaciones.index', $data);
	}

	/**
	 * Mostrar formulario para crear uno nuevo
	 * */
	function create(Request $request, $id = null){

		if ($id!='subastas'){
			$idauction = $id;
			$data['isNew'] = true;
		}else{
			$idauction = null;
			$data['isNew'] = false;
		}

		/**Formulario de creación */
		$data['idauction'] = $id;
		$data['formularioId'] = "createAward";
		$data['formularioAction'] = "/admin/award/store";

		$data['formulario'] = array();

		if(!empty($idauction)){
			$data['formulario']['subasta'] = FormLib::TextReadOnly("idauction", 1, $idauction);
			$data['formularioCreate'] = "/admin/award/create?idauction=$idauction";
			$data['formulario']['referencia'] = FormLib::Select2WithAjax('lote', 1, '', '', route('subastas.lotes.select2', ['cod_sub' => $idauction]), trans('admin-app.placeholder.lot_creditolot' ));

		}
		else{
			$data['formulario']['subasta'] = FormLib::Select2WithAjax('subasta', 1, '', '', route('subastas.select2'), trans('admin-app.placeholder.sub_creditosub' ));
			$data['formularioCreate'] = "/admin/award/create";
			$data['formulario']['referencia'] = FormLib::Int('lote', 1, 0);
		}
		$data['formulario']['cliente'] = FormLib::Select2WithAjax('cod_cli', 1, '', '', route('client.list'), trans('admin-app.placeholder.cli_creditosub' ));
		$data['formulario']['puja'] = FormLib::Int('bid', 1, 0);
		$data['formulario']['comision'] = FormLib::Int('commission', 0, 0);
		$data['formulario']['fecha'] = FormLib::DateTime('date', 0, '');
		if (\Config::get('app.payAwards')) {
			$data['formulario']['pagado'] = FormLib::Bool('serialpay', 0, 0);
		}
		$data['SUBMIT'] = FormLib::Submit('Guardar', 'createAward');

		return \View::make('admin::pages.subasta.adjudicaciones.edit', $data);
	}

	/**
	 * Mostrar item
	 * */
	function show(){

		$idauction = request('idauction');
		if(empty($idauction)){
			return response('Error', 400);
		}

		$where = [
			'idauction' => $idauction
		];

		$idoriginlot = request('ref');
		if(!empty($idoriginlot)){
			$where['ref'] = $idoriginlot;
		}

		$licit = request('licit');
		if(!empty($licit)){
			$where['licit'] = $licit;
		}

		$awardControler = new AwardController();
		$json = $awardControler->showAward($where);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			return response($json, 400);
		}
		else if(empty($result->items)){
			return response('No existen ordenes con los parametros buscados', 400);
		}

		return response($result->items, 200);

	}

	/**
	 * Formulario con item
	 * */
	function edit(){

		$where = [
			'idauction' => request('idauction'),
			'ref' => request('ref'),
			'licit' => request('licit')
		];

		$data['isNew'] = false;

		$awardControler = new AwardController();
		$json = $awardControler->showAward($where);
		$award = json_decode($json);
		$award = $award->items[0];

		/**Formulario de creación */

		$data['formularioId'] = "editAward";
		$data['formularioAction'] = "/admin/award/update";

		$data['formulario'] = array();
		$data['formulario']['subasta'] = FormLib::TextReadOnly("idauction", 1, $award->idauction);
		$data['formulario']['referencia'] = FormLib::TextReadOnly("ref", 1, $award->ref);
		$data['formulario']['licitador'] = FormLib::TextReadOnly("licit", 1, $award->licit);
		$data['formulario']['puja'] = FormLib::Int('bid', 1, $award->bid);
		$data['formulario']['comision'] = FormLib::Int('commission', 1, $award->bid);
		$data['formulario']['fecha'] = FormLib::DateTime('date', 0, $award->date);
		if (\Config::get('app.payAwards')) {
			$data['formulario']['pagado'] = FormLib::Bool('serialpay', 0, $award->serialpay);
		}
		$data['SUBMIT'] = FormLib::Submit('Guardar', 'editAward');

		return \View::make('admin::pages.subasta.adjudicaciones.edit', $data);
	}

	/**
	 * Guardar con item
	 * */
	function store(){

		if (!empty(request('idauction'))){
			$idAuction = request('idauction');
		}else{
			$idAuction = request('subasta');
		}
		$cod_cli = request('cod_cli');

		$licitTemp = FgLicit::select('cod_licit', 'rsoc_cli', 'cli_licit')
							->joinCli()
							->where("SUB_LICIT", $idAuction)
							->where('CLI_LICIT', $cod_cli)
							->first();

		if(!$licitTemp){
			$cod_licit = FgLicit::newCodLicit($idAuction);
			$rsoc_licit = FxCli::SelectBasicCli()->where('COD_CLI', $cod_cli)->first()->rsoc_cli;

			$licit = array("sub_licit" => $idAuction,
					"cli_licit" => $cod_cli,
					"cod_licit" => $cod_licit,
					"rsoc_licit" => $rsoc_licit);

			$licitTemp = FgLicit::create($licit);
		}

		$item = [
			'idauction' => $idAuction,
			'idoriginlot' => 0,
			'ref' => request('lote'),
			'idoriginclient' => 0,
			'clifac' => $cod_cli,
			'licit' => $licitTemp->cod_licit,
			'bid' => request('bid'),
			'commission' => request('commission'),
			'date' => date("Y-m-d h:i:s", strtotime(request('date'))),
		];

		# Escanea el config y si el 'serialpay' tiene un valor diferente de nulo, pone en el campo serialpay 'L00'
		if (\Config::get('app.payAwards')) {
			$item['serialpay'] = request('serialpay') != null ? 'L00' : null;
		}

		$items[] = $item;

		$awardController = new AwardController();
		$json = $awardController->createAward($items);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			return redirect()->back()
			->with(['errors' => [0 => $json]]);
		}

		return redirect()->back()
			->with(['success' => [0 => 'Adjudicación creada correctamente'] ]);
	}

	/**
	 * Actualizar item
	 * */
	function update(){

		$item = [
			'idauction' => request('idauction'),
			'idoriginlot' => 0,
			'ref' => request('ref'),
			'idoriginclient' => 0,
			'licit' => request('licit'),
			'bid' => request('bid'),
			'commission' => request('commission'),
			'date' => date("Y-m-d h:i:s", strtotime(request('date'))),
		];

		# Escanea el config y si el 'serialpay' tiene un valor diferente de nulo, pone en el campo serialpay 'L00'
		if (\Config::get('app.payAwards')) {
			$item['serialpay'] = request('serialpay') != null ? 'L00' : null;
		}

		$items[] = $item;

		$awardController = new AwardController();
		$json = $awardController->createAward($items);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			return redirect()->back()
			->with(['errors' => [0 => $json]]);
		}

		return redirect()->back()
			->with(['success' => [0 => 'Adjudicación actualizada correctamente'] ]);

	}


	function destroy(){

		$where = [
			'idauction' => request('idauction'),
			'ref' => request('ref'),
			'licit' => request('licit')
		];

		$awardController = new AwardController();
		$json = $awardController->eraseAward($where);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			return response($json, 400);
		}

		FgAsigl0::query()
			->where([
				'sub_asigl0' => request('idauction'),
				'ref_asigl0' => request('ref')
			])
			->update([
				'desadju_asigl0' => 'S'
			]);

		return response(json_encode($result), 200);

	}

	public function export(Request $request, $id = null)
    {
		$personalizedFields = $this->getConfigFields();

		$adjudicacionesInstance = $this->getAwardsInstance($request, $id, $personalizedFields);
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

		if(config('app.surface_euro', false)){
			$headers['surface_euro'] = trans("admin-app.fields.surface_euro");
			$headers['pieces_euro'] = trans("admin-app.fields.pieces_euro");
		}

		foreach ($adjudicacionesFormat as $idAward => $adjudicacion) {
			$awardsToExcel[$idAward] = [];
			foreach ($headers as $key => $value) {
				$awardsToExcel[$idAward][$key] = $adjudicacion->get($key);
			}

			/* pongo un espacio para que el excel interprete el numero como texto, ya que es muy largo y en excel no se ve bien   */
			if(config('app.payDepositTpv', false) && !empty($awardsToExcel[$idAward]['cod_deposito'] )){
				$awardsToExcel[$idAward]['cod_deposito'] = " ". $awardsToExcel[$idAward]['cod_deposito'];
			}

			if(config('app.surface_euro', false)){
				if(!$adjudicacion->get('ancho_hces1')){
					$awardsToExcel[$idAward]['surface_euro'] = 0;
				}
				else {
					$awardsToExcel[$idAward]['surface_euro'] = ToolsServiceProvider::moneyFormat($adjudicacion->get('himp_csub') / $adjudicacion->get('ancho_hces1'), false, 2);
				}

				//@todo: precio x num objetos. Solo para stn. extraer a otro config o buscar otra manera de mostrar estos datos.
				if(!$adjudicacion->get('nobj_hces1')){
					$awardsToExcel[$idAward]['pieces_euro'] = 0;
				}
				else {
					$awardsToExcel[$idAward]['pieces_euro'] = ToolsServiceProvider::moneyFormat($adjudicacion->get('himp_csub') / $adjudicacion->get('nobj_hces1'), false, 2);
				}
			}

		}

		return (new AwardsExport($awardsToExcel, $headers))->download("adjudicaciones_subasta_" . date("Ymd") . ".xlsx");
    }

	/**
	 * Añade campos establecidos en config
	 */
	private function getConfigFields()
	{
		$personalizedFieldsConfig = config('app.admin_awards_params', null);

		if(!$personalizedFieldsConfig){
			return [];
		}

		return array_map(function($field){
			return trim($field);
		}, explode(',', $personalizedFieldsConfig));
	}

	private function getAwardsInstance(Request $request, $idauction = null, $personalizedFields = [])
	{
		$filters = [
			new Filter('sub_asigl0', Filter::TYPE_SAME),
			new Filter('ref_asigl0', Filter::TYPE_SAME),
			new Filter('descweb_hces1', Filter::TYPE_LIKE),
			new Filter('impsalhces_asigl0', Filter::TYPE_SAME),
			new Filter('himp_csub', Filter::TYPE_SAME),
			new Filter('base_csub', Filter::TYPE_SAME),
			new Filter('nom_cli', Filter::TYPE_LIKE),
			new Filter('rsoc_cli', Filter::TYPE_LIKE),
			new Filter('email_cli', Filter::TYPE_LIKE),
			new Filter('cod_cli', Filter::TYPE_SAME),
		];

		#Si el el config está activado añade el filtro de afral_csub
		if (\Config::get('app.payAwards')) {
			$filters[] = new Filter('afral_csub', Filter::TYPE_LIKE);
		}

		foreach ($personalizedFields as $field) {
			$filters[] = new Filter($field, Filter::TYPE_SAME);
		}

		# Si está activado el config lo que hace es añadir el campo 'afral_csub' al select de la query
		$adjudicaciones = FgAsigl0::select('sub_asigl0', 'ref_asigl0', 'descweb_hces1', 'himp_csub', 'base_csub', 'impsalhces_asigl0', 'fecha_csub', 'licit_csub')
		->addSelect('nom_cli', 'rsoc_cli', 'email_cli', 'cod_cli')
		->addSelect($personalizedFields)
			->joinFghces1Asigl0()
			->joinCSubAsigl0()
			->leftJoinCliWithCsub()
			->leftjoin('FXCLID', "FXCLID.GEMP_CLID = FXCLI.GEMP_CLI AND FXCLID.CLI_CLID = FXCLI.COD_CLI AND CODD_CLID = 'W1'")
			->whereNotNull('clifac_csub')

			->when(\Config::get('app.payDepositTpv'), function($query){
				return $query->addSelect('cod_deposito, importe_deposito')->
					#COGEMOS SOLO LAS QUE ESTAN MARCADAS COMO CONFIRMACION PAGADA, ENVIADO_DEPOSITO = 'P'
					leftjoin("FGDEPOSITO","EMP_DEPOSITO = EMP_ASIGL0 AND  SUB_DEPOSITO = SUB_ASIGL0 AND REF_DEPOSITO=REF_ASIGL0 AND CLI_DEPOSITO = COD_CLI AND ESTADO_DEPOSITO = 'V'  AND ENVIADO_DEPOSITO = 'P' ");
			})
			->when(\Config::get('app.payAwards'), function($query){
				return $query->addSelect('afral_csub');
			})

			->when(config('app.featuresInAdmin', false), function($query, $features){
				return $query->addSelect('name_caracteristicas', 'value_caracteristicas_hces1', 'value_caracteristicas_value')
					->leftJoinCaracteristicasAsigl0()
					->whereIn('id_caracteristicas', explode(',', $features));
			})
			->when($idauction, function($query, $cod_sub){
				return $query->where('sub_asigl0', $cod_sub);
			})
			->when($request->from_fecha_csub, function($query, $fecha){
				return $query->where('fecha_csub', '>=', ToolsServiceProvider::getDateFormat($fecha, 'Y-m-d', 'Y/m/d') . ' 00:00:00');
			})
			->when($request->to_fecha_csub, function($query, $fecha){
				return $query->where('fecha_csub', '<=', ToolsServiceProvider::getDateFormat($fecha, 'Y-m-d', 'Y/m/d') . ' 23:59:59');
			})
			->whenFilters($request, $filters)->orderBy(request('order_awards', 'sub_asigl0'), request('order_awards_dir', 'desc'));

			return $adjudicaciones;
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
				$adjudicacionesFormat[$identificador]['fecha_csub'] = ToolsServiceProvider::getDateFormat($adjudicacionesFormat[$identificador]['fecha_csub'], 'Y-m-d H:i:s', 'd/m/Y');
				$adjudicacionesFormat[$identificador][$adjudicacion->name_caracteristicas] = $adjudicacion->value_caracteristicas_hces1 ?? $adjudicacion->value_caracteristicas_value;
			}
			else{
				$adjudicacionesFormat[$identificador][$adjudicacion->name_caracteristicas] = $adjudicacion->value_caracteristicas_hces1 ?? $adjudicacion->value_caracteristicas_value;
			}

			$caracteristicas[$adjudicacion->name_caracteristicas] = 1;
		}

		return compact('adjudicacionesFormat', 'caracteristicas');
	}

}
