<?php

namespace App\Http\Controllers\admin\subasta;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\admin\UpdateDepositoPut;
use App\Models\V5\FgDeposito;
use App\libs\FormLib;
use App\Models\V5\FgSub;
use App\Models\V5\FxCli;
use App\Providers\ToolsServiceProvider;

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
		if ($request->sub_deposito) {
			$fgDepositos->where('upper(sub_deposito)', 'like', "%" . mb_strtoupper($request->sub_deposito) . "%");
		}
		if ($request->ref_deposito) {
			$fgDepositos->where('upper(ref_deposito)', 'like', "%" . mb_strtoupper($request->ref_deposito) . "%");
		}
		if ($request->estado_deposito) {
			$fgDepositos->where('estado_deposito', '=', $request->estado_deposito);
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

		$fgDepositos = $fgDepositos->select('cod_deposito', 'sub_deposito', 'ref_deposito', 'estado_deposito', 'importe_deposito', 'fecha_deposito', 'cli_deposito')
			->orderBy("fecha_deposito", "desc")
			->paginate(40);

		$fgDeposito = new FgDeposito();

		$formulario = (object)[
			'sub_deposito' => FormLib::Text('sub_deposito', 0, $request->sub_deposito, '', 'Subasta'),
			'ref_deposito' => FormLib::Text('ref_deposito', 0, $request->ref_deposito, '', 'Referencia'),
			'estado_deposito' => FormLib::Select('estado_deposito', 0, $request->estado_deposito, $fgDeposito->getEstados(), '', ''),
			'importe_deposito' => FormLib::Text('importe_deposito', 0, $request->importe_deposito, '', 'Importe'),
			'fecha_deposito' => FormLib::Date('fecha_deposito', 0, $request->fecha_deposito),
			'cli_deposito' => FormLib::Text('cli_deposito', 0, $request->cli_deposito, '', 'Cliente')
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

		$formulario = (object) $this->formFgDeposito($fgDeposito);

		return view('admin::pages.subasta.depositos.edit', compact('formulario', 'fgDeposito'));

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateDepositoPut $request, $cod_deposito)
	{
		$fgDeposito = FgDeposito::where('cod_deposito', $cod_deposito)->firstOrFail();

		FgDeposito::where('cod_deposito', $cod_deposito)->update($request->validated());

		return redirect(route('deposito.index'))
				->with(['success' =>array(trans('admin-app.title.updated_ok')) ]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}


	private function formFgDeposito(FgDeposito $fgDeposito)
	{
		//subastas
		$fgSubs = FgSub::select('cod_sub', 'des_sub')->pluck('des_sub', 'cod_sub');

		//referencia
		#Buscar min y max y limitar en ref

		//clientes
		$fxCli = FxCli::select('cod_cli', 'nom_cli')->pluck('nom_cli', 'cod_cli');

		return [
			'sub_deposito' => FormLib::Select2WithArray('sub_deposito', 0, $fgDeposito->sub_deposito, $fgSubs),
			'ref_deposito' => FormLib::Text('ref_deposito', 0, $fgDeposito->ref_deposito, '', ''),
			'estado_deposito' => FormLib::Select('estado_deposito', 1, $fgDeposito->estado_deposito, $fgDeposito->getEstados()),
			'importe_deposito' => FormLib::Text('importe_deposito', 1, $fgDeposito->importe_deposito, '', ''),
			'cli_deposito' => FormLib::Select2WithArray('cli_deposito', 1, $fgDeposito->cli_deposito, $fxCli)
		];
	}
}
