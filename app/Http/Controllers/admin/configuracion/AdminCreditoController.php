<?php

namespace App\Http\Controllers\admin\configuracion;

use App\Exports\CreditoExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\admin\StoreCreditoPost;
use App\libs\FormLib;
use App\Models\V5\FgCreditoSub;
use App\Models\V5\FxCli;
use App\Providers\ToolsServiceProvider;
use Illuminate\Support\Carbon;

class AdminCreditoController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$fgCreditoSubs = FgCreditoSub::query();
		if ($request->cli_creditosub) {
			$fgCreditoSubs->where([
				['upper(cli_creditosub)', 'like',  "%" . mb_strtoupper($request->cli_creditosub) . "%", 'or'],
				['upper(rsoc_cli)', 'like',  "%" . mb_strtoupper($request->cli_creditosub) . "%", 'or'],
			]);
		}
		if ($request->sub_creditosub) {
			$fgCreditoSubs->where('sub_creditosub', '=', $request->sub_creditosub);
		}
		if ($request->actual_creditosub) {
			$fgCreditoSubs->where('actual_creditosub', '=', $request->actual_creditosub);
		}
		if ($request->nuevo_creditosub) {
			$fgCreditoSubs->where('nuevo_creditosub', '=', $request->nuevo_creditosub);
		}
		if ($request->fecha_creditosub) {
			$day = ToolsServiceProvider::getDateFormat($request->fecha_creditosub, 'Y-m-d', 'Y/m/d');
			$fgCreditoSubs->where('fecha_creditosub', '>=', $day);
			$fgCreditoSubs->where('fecha_creditosub', '<', Carbon::createFromFormat('Y/m/d', $day)->addDay()->format('Y/m/d'));
		}

		$fgCreditoSubs = $fgCreditoSubs
			->select('FGCREDITOSUB.*','FXCLI.RSOC_CLI', 'FXCLI.RIES_CLI', 'FXCLI.RIESMAX_CLI')
			->join('FXCLI', 'FXCLI.COD_CLI = FGCREDITOSUB.CLI_CREDITOSUB')
			->join('FXCLIWEB', 'FXCLIWEB.GEMP_CLIWEB = FXCLI.GEMP_CLI AND FXCLIWEB.EMP_CLIWEB = FGCREDITOSUB.EMP_CREDITOSUB AND FXCLI.COD_CLI = FXCLIWEB.COD_CLIWEB')
			->orderBy("fecha_creditosub", "desc")
			->paginate(40);

		$formulario = (object)[
			'cli_creditosub' => FormLib::Text('cli_creditosub', 0, $request->cli_creditosub),
			'sub_creditosub' => FormLib::Text('sub_creditosub', 0, $request->sub_creditosub),
			'actual_creditosub' => FormLib::Text('actual_creditosub', 0, $request->actual_creditosub),
			'nuevo_creditosub' => FormLib::Text('nuevo_creditosub', 0, $request->nuevo_creditosub),
			'fecha_creditosub' => FormLib::Date('fecha_creditosub', 0, $request->fecha_creditosub)
		];

		return view('admin::pages.configuracion.credito.index', compact('fgCreditoSubs', 'formulario'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$fgCreditoSub = new FgCreditoSub();
		//$fgCreditoSub->cli_creditosub = old('cli_creditosub', '');
		//$fgCreditoSub->sub_creditosub = old('sub_creditosub', '');
		//$fgCreditoSub->actual_creditosub = old('actual_creditosub', '');
		//$fgCreditoSub->nuevo_creditosub = old('nuevo_creditosub', '');

		$formulario = (object) $this->formFgCreditoSub($fgCreditoSub);

		return view('admin::pages.configuracion.credito.create', compact('formulario', 'fgCreditoSub'));
	}



	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(StoreCreditoPost $request)
	{
		FgCreditoSub::create($request->all());
		return redirect(route('credito.index'))->with('success', ['Crédito creado correctamente']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $cod_cli)
	{

		FxCli::where('cod_cli', $cod_cli)
			->update(['riesmax_cli' => $request->get('riesmax_cli', 0)]);

		return redirect()->back()->with(['success' =>array(trans('admin-app.title.updated_ok'))]);

	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id_creditosub)
	{
		FgCreditoSub::where('id_creditosub', $id_creditosub)->delete();
		return redirect()->back()->with(['success' =>array(trans('admin-app.title.deleted_ok'))]);
	}

	public function export(Request $request){
		return (new CreditoExport($request->cli_creditosub, $request->sub_creditosub, $request->fecha_creditosub))->download(trans("admin-app.title.credits") . "_" . date("Ymd") . ".xlsx");
	}

	public function getCreditData(Request $request){

		$cod_cli = $request->get('cod_cli');
		$cod_sub = $request->get('cod_sub');

		//credito actual en la subasta
		$currentCredit = FgCreditoSub::getCurrentCredit($cod_cli, $cod_sub);

		//credito minimo y maximo del usuario
		$user_ries = FxCli::select('riesmax_cli', 'ries_cli')->where('COD_CLI', $cod_cli)->first();

		//Si no tiene credito en subasta, su credito minimo lo establece ries_cli
		$currentCredit = $currentCredit ?? $user_ries->ries_cli ?? 0;

		return response()->json(['actual_creditosub' => $currentCredit, 'riesmax_cli' => $user_ries->riesmax_cli]);

	}


	private function formFgCreditoSub(FgCreditoSub $fgCreditoSub)
	{
		return [
			'cli_creditosub' => FormLib::Select2WithAjax('cli_creditosub', 1, $fgCreditoSub->cli_creditosub, $fgCreditoSub->cli_creditosub, route('client.list'), trans('admin-app.placeholder.cli_creditosub')),
			'sub_creditosub' => FormLib::Select2WithAjax('sub_creditosub', 1, $fgCreditoSub->sub_creditosub, $fgCreditoSub->sub_creditosub, '/admin/subasta/select2list', trans('admin-app.placeholder.sub_creditosub')),
			'actual_creditosub_create' => FormLib::Text('actual_creditosub', 1, $fgCreditoSub->actual_creditosub, 'readonly = "true"'),
			'nuevo_creditosub_create' => FormLib::Int('nuevo_creditosub', 1, $fgCreditoSub->nuevo_creditosub, 'readonly = "true"'),
		];
	}
}
