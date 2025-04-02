<?php

namespace App\Http\Controllers\admin\subasta;

use App\Exports\DepositsExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\admin\UpdateDepositoPut;
use App\Models\V5\FgDeposito;
use App\libs\FormLib;
use App\Models\V5\FgRepresentados;
use App\Models\V5\FgSub;
use App\Models\V5\FxCli;
use App\Providers\ToolsServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class AdminDepositoController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$fgDepositos = FgDeposito::query();
		$fgDepositos = self::filtersDepositos($fgDepositos, $request);
		$fgDepositos = $fgDepositos->select('cod_deposito', 'rsoc_cli', 'nom_cli', 'sub_deposito', 'ref_deposito', 'estado_deposito', 'importe_deposito', 'fecha_deposito', 'cli_deposito')
			->when(Config::get('app.withRepresented', false), function ($query) {
				$query->with('represented');
			})
			->joinCli()
			->orderBy("fecha_deposito", "desc");


		if ($request->input('to_export')) {
			return (new DepositsExport($fgDepositos))->download("depositos_" . date("Ymd") . ".xlsx");
		}

		$fgDepositos = $fgDepositos->paginate(40);

		$fgDeposito = new FgDeposito();

		$formulario = (object)[
			'sub_deposito' => FormLib::Text('sub_deposito', 0, $request->sub_deposito, '', 'Subasta'),
			'ref_deposito' => FormLib::Text('ref_deposito', 0, $request->ref_deposito, '', 'Referencia'),
			'rsoc_cli' => FormLib::Text('rsoc_cli', 0, $request->rsoc_cli, '', 'Razón social'),
			'nom_cli' => FormLib::Text('nom_cli', 0, $request->nom_cli, '', 'Nombre'),
			'estado_deposito' => FormLib::Select('estado_deposito', 0, $request->estado_deposito, $fgDeposito->getEstados(), '', ''),
			'importe_deposito' => FormLib::Text('importe_deposito', 0, $request->importe_deposito, '', 'Importe'),
			'fecha_deposito' => FormLib::Date('fecha_deposito', 0, $request->fecha_deposito),
			'cli_deposito' => FormLib::Text('cli_deposito', 0, $request->cli_deposito, '', 'Cliente'),
		];

		return view('admin::pages.subasta.depositos.index', compact('fgDepositos', 'formulario'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$fgDeposito = new FgDeposito();
		$fgDeposito->sub_deposito = old('sub_deposito', '');
		$fgDeposito->ref_deposito = old('ref_deposito', '');
		$fgDeposito->estado_deposito = old('estado_deposito', '');
		$fgDeposito->importe_deposito = old('importe_deposito', '');
		$fgDeposito->cli_deposito = old('cli_deposito', '');

		$formulario = (object) $this->formFgDeposito($fgDeposito);

		return view('admin::pages.subasta.depositos.create', compact('formulario', 'fgDeposito'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(UpdateDepositoPut $request)
	{
		FgDeposito::create($request->all());
		return redirect(route('deposito.index'))->with('success', ['Deposito creado correctamente']);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $cod_deposito
	 * @return \Illuminate\Http\Response
	 */
	public function edit($cod_deposito)
	{
		$fgDeposito = FgDeposito::where('cod_deposito', $cod_deposito)->firstOrFail();

		$fgDeposito->sub_deposito = old('sub_deposito', $fgDeposito->sub_deposito);
		$fgDeposito->ref_deposito = old('ref_deposito', $fgDeposito->ref_deposito);
		$fgDeposito->estado_deposito = old('estado_deposito', $fgDeposito->estado_deposito);
		$fgDeposito->importe_deposito = old('importe_deposito', $fgDeposito->importe_deposito);
		$fgDeposito->cli_deposito = old('cli_deposito', $fgDeposito->cli_deposito);
		$fgDeposito->representado_deposito = old('representado_deposito', $fgDeposito->representado_deposito);

		$formulario = (object) $this->formFgDeposito($fgDeposito);

		return view('admin::pages.subasta.depositos.edit', compact('formulario', 'fgDeposito'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  FgDeposito  $deposito
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateDepositoPut $request, FgDeposito $deposito)
	{
		$deposito->update($request->validated());

		return redirect(route('deposito.index'))
			->with(['success' => array(trans('admin-app.title.updated_ok'))]);
	}

	public function updateSelections(Request $request)
	{
		$ids = $request->input('ids', []);
		$new_estado_deposito = $request->input('estado_deposito_edit', '');

		FgDeposito::whereIn('cod_deposito', $ids)
			->update(['estado_deposito' => $new_estado_deposito]);


		return response()->json(['success' => true, 'message' => trans("admin-app.success.update_mass_deposits")], 200);
	}

	public function updateWithFilters(Request $request)
	{
		$new_estado_deposito = $request->input('estado_deposito_edit', '');

		$fgDepositos = FgDeposito::query();
		$fgDepositos = self::filtersDepositos($fgDepositos, $request);
		$ids = $fgDepositos->joinCli()->pluck('cod_deposito');

		FgDeposito::whereIn('cod_deposito', $ids)
			->update(['estado_deposito' => $new_estado_deposito]);

		return response()->json(['success' => true, 'message' => trans("admin-app.success.update_mass_deposits")], 200);
	}

	private function formFgDeposito(FgDeposito $fgDeposito)
	{
		//subastas
		$fgSubs = FgSub::select('cod_sub', 'des_sub')->pluck('des_sub', 'cod_sub');

		//referencia
		#Buscar min y max y limitar en ref

		//clientes
		$fxCli = FxCli::select('cod_cli', 'nom_cli')->pluck('nom_cli', 'cod_cli');

		$formulario = [
			'sub_deposito' => FormLib::Select2WithArray('sub_deposito', 0, $fgDeposito->sub_deposito, $fgSubs),
			'ref_deposito' => FormLib::Text('ref_deposito', 0, $fgDeposito->ref_deposito, '', ''),
			'estado_deposito' => FormLib::Select('estado_deposito', 1, $fgDeposito->estado_deposito, $fgDeposito->getEstados()),
			'importe_deposito' => FormLib::Text('importe_deposito', 1, $fgDeposito->importe_deposito, '', ''),
			'cli_deposito' => FormLib::Select2WithArray('cli_deposito', 1, $fgDeposito->cli_deposito, $fxCli)
		];

		if (Config::get('app.withRepresented', false)) {
			$formulario['representado_deposito'] = FormLib::Select2WithArray('representado_deposito', 0, $fgDeposito->representado_deposito, FgRepresentados::getRepresentedToSelect($fgDeposito->cli_deposito));
		}

		return $formulario;
	}

	private function filtersDepositos(Builder $fgDepositos, Request $request)
	{
		$defalutState = Config('app.admin_default_deposit_state', null);

		if ($request->sub_deposito) {
			$fgDepositos->where('upper(sub_deposito)', 'like', "%" . mb_strtoupper($request->sub_deposito) . "%");
		}
		if ($request->ref_deposito) {
			$fgDepositos->where('upper(ref_deposito)', 'like', "%" . mb_strtoupper($request->ref_deposito) . "%");
		}
		if ($request->rsoc_cli) {
			$fgDepositos->where('upper(rsoc_cli)', 'like', "%" . mb_strtoupper($request->rsoc_cli) . "%");
		}
		if ($request->nom_cli) {
			$fgDepositos->where('upper(nom_cli)', 'like', "%" . mb_strtoupper($request->nom_cli) . "%");
		}
		if ($request->estado_deposito) {
			$fgDepositos->where('estado_deposito', '=', $request->estado_deposito);
		} else if (is_null($request->estado_deposito) && $defalutState) {
			$fgDepositos->where('estado_deposito', '=', $defalutState);
		}
		if ($request->importe_deposito) {
			$fgDepositos->where('importe_deposito', '=', $request->importe_deposito);
		}
		if ($request->fecha_deposito) {
			$fgDepositos->where('fecha_deposito', '>=', ToolsServiceProvider::getDateFormat($request->fecha_deposito, 'Y-m-d', 'Y/m/d'));
		}
		if ($request->cli_deposito) {
			$fgDepositos->where('cli_deposito', 'like', "%" . mb_strtoupper($request->cli_deposito) . "%");
		}

		return $fgDepositos;
	}
}
