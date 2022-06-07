<?php

namespace App\Http\Controllers\admin\facturacion;

use App\Http\Controllers\apilabel\PaymentController as ApilabelPaymentController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\libs\FormLib;
use App\Models\V5\FxCli;
use App\Models\V5\FxDvc0;
use stdClass;

class AdminBillController extends Controller
{

	public function __construct()
	{
		view()->share(['menu' => 'facturacion']);
	}


	public function index(Request $request)
	{

		$emp = config('app.emp');
		$url = config('app.url');
		$url_pdf = "{$url}/bills/{$emp}/";
		$paidOptions = ['S' => trans('admin-app.fields.paid'), 'N' => trans('admin-app.fields.unpaid')];

		$bills = FxDvc0::select("num_dvc0, des_dvc2t, cod_dvc0, cod2_cli, rsoc_dvc0, total_dvc0, fecha_dvc0, idtrans_pcob0, fecha_pcob0_ext")
			->selectRaw("CASE WHEN FXDVC02.FICH_DVC02 IS NULL THEN '' ELSE concat( ? ,concat(FXDVC02.FICH_DVC02,'.PDF') ) END as FICH_DVC02 ", [$url_pdf])
			->selectRaw("CASE WHEN FXCOBRO1.ANUM_COBRO1 IS NOT NULL THEN 'S' ELSE 'N' END as PAID")
			->join("fxdvc02", "emp_dvc02 = emp_dvc0 and anum_dvc0 = anum_dvc02 and  num_dvc02 = num_dvc0")
			->join("fxdvc2t", "emp_dvc2t = emp_dvc0 and anum_dvc0 = anum_dvc2t and num_dvc2t = num_dvc0")
			->join("fxcli", "gemp_cli = $emp and cod_cli = cod_dvc0")
			->leftjoin("fxcobro1", "emp_cobro1 = emp_dvc0 and afra_cobro1 = anum_dvc0  and nfra_cobro1 = num_dvc0")
			->leftjoin("fxpcob1", "emp_pcob1 = emp_dvc0 and serie_pcob1 = anum_dvc0 and numero_pcob1 = num_dvc0")
			->leftjoin("fxpcob0", "emp_pcob0 = emp_dvc0 and anum_pcob0 = anum_pcob1 and num_pcob0 = num_pcob1")
			->leftjoin("fxpcob0_ext", "emp_pcob0_ext = emp_dvc0 and anum_pcob0_ext = anum_pcob1 and num_pcob0_ext = num_pcob1")

			->when($request->num_dvc0, function ($query, $num_dvc0) {
				return $query->where('num_dvc0', $num_dvc0);
			})
			->when($request->des_dvc2t, function ($query, $des_dvc2t) {
				return $query->where('upper(des_dvc2t)', 'like', "%".strtoupper($des_dvc2t)."%");
			})
			->when($request->cod_dvc0, function ($query, $cod_dvc0) {
				return $query->where('cod_dvc0', 'like', "%{$cod_dvc0}%");
			})
			->when($request->cod2_cli, function ($query, $cod2_cli) {
				return $query->where('cod2_cli', 'like', "%{$cod2_cli}%");
			})
			->when($request->rsoc_dvc0, function ($query, $rsoc_dvc0) {
				return $query->where('upper(rsoc_dvc0)', 'like', "%".strtoupper($rsoc_dvc0)."%");
			})
			->when($request->total_dvc0, function ($query, $total_dvc0) {
				return $query->where('total_dvc0', $total_dvc0);
			})
			->when($request->paid, function ($query, $paid) {
				if($paid == 'S'){
					return $query->whereNotNull('anum_cobro1');
				}
				return $query->whereNull('anum_cobro1');
			})
			->when($request->idtrans_pcob0, function ($query, $idtrans_pcob0) {
				return $query->where('upper(idtrans_pcob0)', 'like', "%".strtoupper($idtrans_pcob0)."%");
			})
			->when($request->from_fecha_dvc0, function ($query, $fecha_dvc0) {
				return $query->where('fecha_dvc0', '>=' ,$fecha_dvc0 . ' 00:00:00');
			})
			->when($request->to_fecha_dvc0, function ($query, $fecha_dvc0) {
				return $query->where('fecha_dvc0', '<=' ,$fecha_dvc0 . ' 23:59:59');
			})

			->when($request->from_fecha_pcob0_ext, function ($query, $fecha_pcob0_ext) {
				return $query->where('fecha_pcob0_ext', '>=' ,$fecha_pcob0_ext . ' 00:00:00');
			})
			->when($request->to_fecha_pcob0_ext, function ($query, $fecha_pcob0_ext) {
				return $query->where('fecha_pcob0_ext', '<=' ,$fecha_pcob0_ext . ' 23:59:59');
			})


			->when($request->fich_dvc02, function ($query, $fich_dvc02) {
				if($fich_dvc02 == 'S'){
					return $query->whereNotNull('fich_dvc02');
				}
				return $query->whereNull('fich_dvc02');

			})
			->orderBy($request->filled('order') ? $request->order : 'num_dvc0', $request->filled('order_dir') ? $request->order_dir : 'asc')
			->paginate(30);

		$tableParams = [
			'num_dvc0' => 1, 'des_dvc2t' => 1, 'cod_dvc0' => config('external_id', 1), 'cod2_cli' => config('external_id', 0), 'rsoc_dvc0' => 1,
			'total_dvc0' => 1, 'fecha_dvc0' => 1, 'paid' => 1, 'idtrans_pcob0' => 1, 'fecha_pcob0_ext' => 1, 'fich_dvc02' => 1
		];

		$formulario = (object)[
			'num_dvc0' => FormLib::Text('num_dvc0', 0, $request->num_dvc0),
			'des_dvc2t' => FormLib::Text('des_dvc2t', 0, $request->des_dvc2t),
			'cod_dvc0' => FormLib::Text('cod_dvc0', 0, $request->cod_dvc0),
			'cod2_cli' =>  FormLib::Text('cod2_cli', 0, $request->cod2_cli),
			'rsoc_dvc0' =>  FormLib::Text('rsoc_dvc0', 0, $request->rsoc_dvc0),
			'total_dvc0' =>  FormLib::Text('total_dvc0', 0, $request->total_dvc0),
			'fecha_dvc0' => FormLib::DateTimeFromTo('fecha_dvc0', $request->from_fecha_dvc0, $request->to_fecha_dvc0),
			'paid' => FormLib::Select('paid', 0, $request->paid, $paidOptions),
			'idtrans_pcob0' =>  FormLib::Text('idtrans_pcob0', 0, $request->idtrans_pcob0),
			'fecha_pcob0_ext' => FormLib::DateTimeFromTo('fecha_pcob0_ext', $request->from_fecha_pcob0_ext, $request->to_fecha_pcob0_ext),
			'fich_dvc02' => FormLib::Select('fich_dvc02', 0, $request->fich_dvc02, ['S' => 'Si', 'N' => 'No']),
		];

		return view('admin::pages.facturacion.facturas.index', compact('bills', 'tableParams', 'formulario'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$bill = new stdClass();
		$formulario = $this->basicForm($bill);

		return view('admin::pages.facturacion.facturas.create', compact('bill', 'formulario'));
	}

	public function store(Request $request)
	{
		$file64 = '';

		$client = FxCli::select('cod2_cli')->where('cod_cli', $request->idorigincli)->first();

		if(!$client){
			return back()->withErrors(['errors' => ['Client not exist']])->withInput();
		}

		if($request->has('pdf')){
			$file = $request->file('pdf');
			$file64 = base64_encode(file_get_contents($file->getPathname()));
		}

		$newNum = FxDvc0::max('num_dvc0') + 1;

		$newBill = [
			'serial' => 'T20',
			'number' => $newNum,
			'idorigincli' => $client->cod2_cli,
			'description' => $request->description,
			'amount' => $request->amount,
			'date' => $request->date,
			'paid' => $request->paid,
		];

		if(!empty($file64)){
			$newBill['pdf64'] = $file64;
		}

		$items[] = $newBill;

		$json = (new ApilabelPaymentController())->createPayment($items);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			return back()->withErrors(['errors' => [$json]])->withInput();
		}

		return redirect(route('bills.index'))->with(['success' => array(trans('admin-app.title.created_ok'))]);

	}

	public function edit($num_dvc0)
	{

		$serach = ['serial' => 'T20', 'number' => $num_dvc0];

		$json = (new ApilabelPaymentController())->showPayment($serach);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			return back()->withErrors(['errors' => [$json]])->withInput();
		}
		if(empty($result->items)){
			return abort(404);
		}

		$bill = $result->items[0];
		$formulario = $this->basicForm($bill);

		return view('admin::pages.facturacion.facturas.edit', compact('bill', 'formulario'));
	}

	public function update(Request $request, $num_dvc0)
	{
		$file64 = '';
		$client = FxCli::select('cod2_cli')->where('cod_cli', $request->idorigincli)->first();

		if(!$client){
			return back()->withErrors(['errors' => ['Client not exist']])->withInput();
		}

		if($request->has('pdf')){
			$file = $request->file('pdf');
			$file64 = base64_encode(file_get_contents($file->getPathname()));
		}

		$newBill = [
			'serial' => 'T20',
			'number' => $num_dvc0,
			'idorigincli' => $client->cod2_cli,
			'description' => $request->description,
			'amount' => $request->amount,
			'date' => $request->date,
			'paid' => $request->paid,
		];

		if(!empty($file64)){
			$newBill['pdf64'] = $file64;
		}

		$items[] = $newBill;

		$json = (new ApilabelPaymentController())->updatePayment($items);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			return back()->withErrors(['errors' => [$json]])->withInput();
		}

		return redirect(route('bills.index'))->with(['success' => array(trans('admin-app.title.updated_ok'))]);
	}

	public function destroy($num_dvc0)
	{
		$bill = [
			'serial' => 'T20',
			'number' => $num_dvc0
		];

		$json = (new ApilabelPaymentController())->erasePayment($bill);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			return back()->withErrors(['errors' => [$json]])->withInput();
		}

		return redirect(route('bills.index'))->with(['success' => array(trans('admin-app.title.deleted_ok'))]);

	}

	public function basicForm($bill)
	{
		$paidOptions = ['S' => trans('admin-app.fields.paid'), 'N' => trans('admin-app.fields.unpaid')];
		$client = null;
		if (!empty($bill->idorigincli)) {
			$client = FxCli::select('RSOC_CLI, COD_CLI')->where('COD2_CLI', $bill->idorigincli)->first();
		}

		return [
			'idorigincli' => FormLib::Select2WithAjax('idorigincli', 1, old('idorigincli', (!empty($client)) ? $client->cod_cli : ''), (!empty($client)) ? $client->rsoc_cli : '', route('client.list'), trans('admin-app.placeholder.cli_creditosub')),
			'description' => FormLib::Text('description', 0, old('description', $bill->description ?? '')),
			'amount' => FormLib::Int('amount', 1, old('amount', $bill->amount ?? 0)),
			'date' => FormLib::DateTimePicker("date", 1, old('date', $bill->date ?? ''), '', true),
			'paid' => FormLib::Select('paid', 1, old('paid', $bill->paid ?? 'N'), $paidOptions, '', '', false),
			'pdf' => FormLib::File('pdf', 0, 'multiple="false" accept=".pdf"'),
		];
	}

}
