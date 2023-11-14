<?php

namespace App\Http\Controllers\admin\subasta;

use App\Http\Controllers\Controller;
use App\Http\Controllers\apilabel\OrderController;
use App\libs\FormLib;
use App\Models\V5\FgLicit;
use App\Models\V5\FxCli;
use App\Models\V5\FgSub;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ExcelImport;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgOrlic;
use App\Models\V5\SubAuchouse;
use App\Providers\ToolsServiceProvider;
use Illuminate\Support\Str;
use App\Exports\OrdersExport;


class AdminOrderController extends Controller
{

	protected $isRender;

	public function __construct($isRender = false)
	{
		$this->isRender = $isRender;

		view()->share(['isRender' => $this->isRender]);
	}

	function index(Request $request, $cod_sub = null){

		$fgsub = new FgSub();
		$fglicit = new FgLicit();
		$fgorlic = new FgOrlic();
		$orders = FgOrlic::query();


		if(!empty($cod_sub)){
			$orders->where('sub_orlic', '=', $cod_sub);
		}
		else if ($request->sub_orlic) {
			$orders->where('sub_orlic', '=', $request->sub_orlic);
		}
		if ($request->ref_asigl0) {
			$orders->where('ref_asigl0', '=', $request->ref_asigl0);
		}
		if ($request->tipop_orlic) {
			$orders->where('upper(tipop_orlic)', "=", $request->tipop_orlic );
		}
		if ($request->descweb_hces1) {
			$orders->where('upper(descweb_hces1)', 'like', "%" . mb_strtoupper($request->descweb_hces1) . "%");
		}
		if ($request->nom_cli) {
			$orders->where('upper(nom_cli)', 'like', "%" . mb_strtoupper($request->nom_cli) . "%");
		}
		if ($request->fec_orlic) {
			$orders->where('fec_orlic', '>=', ToolsServiceProvider::getDateFormat($request->fec_orlic, 'Y-m-d', 'Y/m/d') . ' 00:00:00');
		}
		if ($request->himp_orlic) {

			$import = Str::replaceFirst(',', '.', $request->himp_orlic);
			$orders->where('himp_orlic', "=", $import );
		}
		if ($request->tel1_orlic) {
			$orders->where('tel1_orlic', 'like', "%". $request->tel1_orlic . "%" );
		}

		$orders = $orders->select("FGORLIC.sub_orlic", "FGASIGL0.ref_asigl0", "FGORLIC.tipop_orlic", "FGORLIC.licit_orlic", "FGHCES1.descweb_hces1", "FXCLI.nom_cli", "FXCLI.cod_cli", "FGORLIC.fec_orlic", "FGORLIC.himp_orlic", "FGORLIC.tel1_orlic")
			->JoinAsigl0()
			->JoinCli()
			->JoinFghces1()
			->orderby(request('order_orders', 'FGORLIC.fec_orlic'), request('order_orders_dir', 'desc'))
			->paginate(20);

		$filter = (object)[
			'sub_orlic' => $this->isRender ?  FormLib::TextReadOnly('sub_orlic',0, $cod_sub) : FormLib::text('sub_orlic',0, $request->sub_orlic),
			'ref_asigl0' => FormLib::text("ref_asigl0", 0, $request->ref_asigl0),
			'tipop_orlic' => FormLib::Select('tipop_orlic', 0, $request->tipop_orlic, $fgorlic->getTipoOrderType()),
			'descweb_hces1' => FormLib::text("descweb_hces1", 0, $request->descweb_hces1),
			'nom_cli' => FormLib::text('nom_cli', 0, $request->nom_cli),
			'fec_orlic' => FormLib::Date('fec_orlic', 0, $request->fec_orlic),
			'himp_orlic' => FormLib::text('himp_orlic', 0, $request->himp_orlic),
			'tel1_orlic' => FormLib::Text('tel1_orlic', 0, $request->tel1_orlic),
		];

		if($this->isRender){
			return \View::make('admin::pages.subasta.ordenes.table', compact('filter', 'orders', 'cod_sub'))->render();
		}

		return \View::make('admin::pages.subasta.ordenes.index', compact('filter', 'orders', 'cod_sub'));
	}

	/**
	 * Mostrar formulario para crear uno nuevo
	 * */
	function create(Request $request){

		/**Tipos de orden */
		$tiposOrden = [
			'W' => "Web",
			'T' => "Teléfono",
			'S' => "Sala",
			'I' => "Internacional"
		];

		$data['formulario'] = array();

		if(!empty($request->idAuction)){
			$data['formulario']['subasta'] = FormLib::TextReadOnly("idauction", 1, $request->idAuction);
		}
		else{
			$data['formulario']['subasta'] = FormLib::Select2WithAjax('idauction', 1, '', '', '/admin/subasta/select2list', trans('admin-app.placeholder.sub_creditosub' ));
		}

		$data['formulario']['referencia lote'] = FormLib::Select2WithAjax('ref', 1, '', '', route('subastas.lotes.select2', ['cod_sub' => $request->idAuction ?? '']), trans('admin-app.placeholder.lot_creditolot'));
		$data['formulario']['cliente'] = FormLib::Select2WithAjax('cod_cli', 1, '', '', route('client.list'), trans('admin-app.placeholder.cli_creditosub' ));
		$data['formulario']['orden'] = FormLib::Int('order', 1, 0);
		$data['formulario']['fecha'] = FormLib::DateTime('date', 0, '');
		$data['formulario']['tipo de orden'] = FormLib::Select('type', 0, '', $tiposOrden);
		$data['formulario']['telefono1'] = FormLib::Text('phone1', 0, '');
		$data['formulario']['telefono2'] = FormLib::Text('phone2', 0, '');
		$data['formulario']['telefono3'] = FormLib::Text('phone3', 0, '');
		$data['SUBMIT'] = FormLib::Submit('Guardar', 'createOrders');

		return view('admin::pages.subasta.ordenes.create', $data);
	}

	/**
	 * Formulario con item
	 * */
	function edit(Request $request, $cod_sub)
	{
		$where = [
			'idauction' => $cod_sub,
			'ref' => request('ref'),
			'licit' => request('licit')
		];
		$lotControler = new OrderController();
		$json = $lotControler->showOrder($where);
		$order = json_decode($json);
		$order = $order->items[0];

		/**Tipos de orden */
		$tiposOrden = [
			'W' => "Web",
			'T' => "Teléfono",
			'S' => "Sala",
			'I' => "Internacional"
		];

		/**Formulario de creación */

		$data = $where;

		$data['formulario'] = array();
		$data['formulario']['subasta'] = FormLib::TextReadOnly("idauction", 1, $order->idauction);
		$data['formulario']['referencia lote'] = FormLib::TextReadOnly("ref", 1, $order->ref);
		//$data['formulario']['cliente'] = FormLib::Text("cod_cli", 1, '');
		$data['formulario']['licitador'] = FormLib::TextReadOnly("licit", 1, $order->licit);
		$data['formulario']['orden'] = FormLib::Int('order', 1, $order->order);
		$data['formulario']['fecha'] = FormLib::DateTime('date', 0, $order->date);
		$data['formulario']['tipo de orden'] = FormLib::Select('type', 0, $order->type, $tiposOrden);
		$data['formulario']['telefono1'] = FormLib::Text('phone1', 0, $order->phone1);
		$data['formulario']['telefono2'] = FormLib::Text('phone2', 0, $order->phone2);
		$data['formulario']['telefono3'] = FormLib::Text('phone3', 0, $order->phone3);

		return view('admin::pages.subasta.ordenes.edit', $data);
	}


	/**
	 * Guardar con item
	 * */
	public function store(Request $request){

		$idAuction = request('idauction');
		$cod_cli = request('cod_cli');

		$fgsub = FgSub::where("COD_SUB", $idAuction)
						->first();

		if($fgsub->tipo_sub == 'V' || ($fgsub->tipo_sub == 'W' && $fgsub->subabierta_sub == 'P')){
			return redirect()->back()
			->with(['errors' => [0 => 'Este tipo de subasta no permite ordenes']]);
		}


		$licitTemp = FgLicit::select('cod_licit', 'rsoc_cli', 'cli_licit')
							->joinCli()
							->where("SUB_LICIT", $idAuction)
							->where('CLI_LICIT', $cod_cli)
							->first();

		if(!$licitTemp){
			$cod_licit = FgLicit::select("max(cod_licit) max_cod_licit")->joinCli()->where("sub_licit",$idAuction )->first()->max_cod_licit +1;
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
			'ref' => request('ref'),
			'idoriginclient' => 0,
			'licit' => $licitTemp->cod_licit,
			'order' => request('order'),
			'date' => date("Y-m-d h:i:s", strtotime(request('date'))),
			'type' => request('type', 'W'),
			'phone1' => request('phone1', ''),
			'phone2' => request('phone2', ''),
			'phone3' => request('phone3', ''),
		];

		$items[] = $item;

		$orderController = new OrderController();
		$json = $orderController->createOrder($items);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			return redirect()->back()
			->with(['errors' => [0 => $json]]);
		}

		return redirect($request->back)
			->with(['success' => [0 => 'Orden creada correctamente'] ]);
	}

	/**
	 * Actualizar item
	 * */
	function update(Request $request, $cod_sub){

		$item = [
			'idauction' => $cod_sub,
			'idoriginlot' => 0,
			'ref' => request('ref'),
			'idoriginclient' => 0,
			'licit' => request('licit'),
			'order' => request('order'),
			'date' => date("Y-m-d h:i:s", strtotime(request('date'))),
			'type' => request('type', 'W'),
			'phone1' => request('phone1', ''),
			'phone2' => request('phone2', ''),
			'phone3' => request('phone3', ''),
		];

		$items[] = $item;

		$orderController = new OrderController();
		$json = $orderController->createOrder($items);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			return redirect()->back()
			->with(['errors' => [0 => $json]]);
		}


		return redirect($request->back)
			->with(['success' => [0 => 'Orden actualizada correctamente'] ]);

	}

	function destroy(Request $request)
	{

		$where = [
			'idauction' => request('idauction'),
			'ref' => request('ref'),
			'licit' => request('licit')
		];

		$orderController = new OrderController();
		$json = $orderController->eraseOrder($where);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			return response($json, 400);
		}

		return response(json_encode($result), 200);
	}

	public function deleteSelection(Request $request, $cod_sub)
	{
		$orderController = new OrderController();
		$results = [];
		foreach ($request->orders as $order) {

			$order = [
				'idauction' => $cod_sub,
				'ref' => '300',//$order['ref'],
				'licit' => $order['licit']
			];

			$json = $orderController->eraseOrder($order);
			$results[] = array_merge(json_decode($json, true), $order);
		}

		return response()->json(['success' => 'Ordenes eliminadas correctamente', 'results' => $results]);
	}

	function excel($idAuction)
	{
		return \View::make('admin::pages.subasta.ordenes.importar', compact('idAuction'));
	}

	/**
	 * Cargar archvivo excel de ordenes
	 */
	public function import($idAuction){

		//obtenemos datos
		$file = Input::file('csv');

		if(empty($file)){
			return redirect()->back()
			->with([ 'errors' => array(trans('admin-app.error.no_file')) ]);
		}

		//cargamos las filas del excel
		$rows = Excel::toArray(new ExcelImport, $file)[0];
		$cabeceras = $this->orderTitlesExcel($rows[0]);

		//obtenemos a todos los licitadores y codigos de cliente
		$licitadores = FgLicit::select('cod_licit')->where('sub_licit', $idAuction)->get()->pluck('cod_licit');

		$fxCli = FxCli::select('cod_cli', 'cod2_cli', 'rsoc_cli')->get()
		->mapWithKeys(function ($cli) {
			return [$cli['cod2_cli'] => $cli];
		});

		$orders = array();
		$newLicits = array();

		//recorremos excel
		for ($i = 1; $i < count($rows); $i++) {

			if(empty(trim($rows[$i][0]))){
				continue;
			}

			$order = $this->createApiObject($rows[$i], $cabeceras);
			$order['idauction'] = $idAuction;
			$orders[] = $order;

			//Si no existe cod_cli con el idoriginclient retornamos error
			if(empty($fxCli[$order['idoriginclient']])){
				return redirect()->back()
					->with(['errors' => array(trans('admin-app.error.no_id_cli', ['cod2_cli' => $order['idoriginclient']])) ]);
			}

			$existLicit = in_array($order['licit'], $licitadores->all());
			if(!$existLicit){

				$licit = [
					'emp_licit' => Config::get('app.emp'),
					'sub_licit' => $idAuction,
					'cod_licit' => $order['licit'],
					'cli_licit' => $fxCli[$order['idoriginclient']]->cod_cli,
					'rsoc_licit' => $fxCli[$order['idoriginclient']]->rsoc_cli,
				];

				$newLicits[] = $licit;
			}
		}

		//si hay nuevos licitadores, los creamos.
		if(!empty($newLicits)){
			FgLicit::insert($newLicits);
		}

		$orderControler = new OrderController();

		if (!empty($orders)) {

			$json = $orderControler->createOrder($orders);
			$result = json_decode($json);

			if ($result->status == 'ERROR') {
				return redirect()->back()
				->with(['errors' => [0 => $json]]);
			}
		}

		return redirect()->back()
			->with(['success' => array(trans('admin-app.success.import')) ]);

	}


	function orderTitlesExcel($cabecera)
	{
		$array[] = array();
		foreach ($cabecera as $column => $value) {
			$array[$column] = $value;
		}
		return $array;
	}

	function createApiObject($rows, $propiedades)
	{
		$object = array();
		foreach ($rows as $key => $value) {
			$object[$propiedades[$key]] = $value;
		}

		return $object;
	}

	public function export($id)
    {
		return (new OrdersExport($id))->download("ordenes_subasta_$id" . "_" . date("Ymd") . ".xlsx");
    }

	#usamos esta función para poder llamar al web service desde el admin
	function send_ws(Request $request){
		#por seguridad solo podrá ejecutar este código el usuari ode subastas
		if ( Config::get('app.WebServiceClient') && (strtoupper(session('user.usrw')) == 'SUBASTAS@LABELGRUP.COM') ){
			$theme  = Config::get('app.theme');
			$rutaOrderController = "App\Http\Controllers\\externalws\\$theme\OrderController";

			$orderController = new $rutaOrderController();

			$orderController->createOrder($request->codcli, $request->sub, $request->ref, $request->imp);
		}
	}


	#subalia llamara a una url que ejecutara esta funcion para poder llamar al web service
	function subalia_send_ws(Request $request){

		$hashSubalia = $request->hash;
		#recuperamos el código de la casa desubastas dentro de subalia
		$cliAuchouse = Config::get('app.subalia_cli');
		#si es una casa de subastas con webservice y existe el código en subalia
        if ( Config::get('app.WebServiceClient') && !empty($cliAuchouse) ) {

            $key = SubAuchouse::select('COD_AUCHOUSE', 'HASH')
                    ->where('CLI_AUCHOUSE', '=', $cliAuchouse)
                    ->where('EMP_ORIGIN_AUCHOUSE', '=', Config::get('app.emp'))
                    ->first();

			$licit =$request->licit;
			$sub = $request->sub;
			$ref = $request->ref;
			$imp = $request->imp;

			#debe coincidir el hash de la llamada con el que generemos aquí para dar por buena la llamada
			if($hashSubalia  ==	hash_hmac("sha256", "$licit $sub $ref $imp",  $key->hash)){
					$theme  = Config::get('app.theme');
					$rutaOrderController = "App\Http\Controllers\\externalws\\$theme\OrderController";

					$orderController = new $rutaOrderController();

					$licitador = fgLicit::where("cod_licit", $licit)->where("sub_licit", $sub)->first();

					if(!empty($licitador)){
						\Log::info("orden de subalia por curl licit=$licit sub=$sub ref=$ref imp=$imp codCli=".$licitador->cli_licit);
						$orderController->createOrder($licitador->cli_licit, $sub, $ref, $imp);

					}else{
						\Log::info("Error orden de subalia por curl, no existe el licitador ".$licit. " para la subasta $sub");
					}

			}else{
				\Log::info("Error orden de subalia por curl, el hash no coincider licit= $licit  sub= $sub  ref= $ref  imp=$imp  hash=$hashSubalia // hashcalculado=".hash_hmac("sha256", "$licit $sub $ref $imp",  $key->hash));
			}
		}
	}



}
