<?php

namespace App\Http\Controllers\admin\subasta;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\FgVisibilidadRequest;
use App\libs\FormLib;
use App\Models\V5\FgSub;
use App\Models\V5\FgVisibilidad;
use App\ValueObjects\Filter;
use Illuminate\Http\Request;

class AdminVisibilidadController extends Controller
{

	public function __construct()
	{
		view()->share(['menu' => 'subastas']);
	}

	public function index(Request $request)
	{

		$filters = [
			new Filter('cod_visibilidad', Filter::TYPE_SAME),
			new Filter('cli_visibilidad', Filter::TYPE_LIKE),
			new Filter('sub_visibilidad', Filter::TYPE_LIKE),
			new Filter('ref_visibilidad', Filter::TYPE_LIKE),
			new Filter('inverso_visibilidad', Filter::TYPE_SAME)
		];

		$visibilitys = FgVisibilidad::with('client:cod_cli,nom_cli')
			->whenFilters($request, $filters)
			->when($request->clientName, function($query, $clientName){

				return $query->orWhereHas('client', function($query) use($clientName){
					return $query->where("upper(nom_cli)", 'like', "%". mb_strtoupper($clientName) ."%");
				});

			})
			->paginate(20);

		$tableParams = [
			'cod_visibilidad' => 1,
			'cli_visibilidad' => 1,
			'clientName' => 1,
			'sub_visibilidad' => 1,
			'ref_visibilidad' => 1,
			'inverso_visibilidad' => 1
		];

		$formulario = (object)[
			'cod_visibilidad' => FormLib::Text('cod_visibilidad', 0, $request->cod_visibilidad),
			'cli_visibilidad' => FormLib::Text('cli_visibilidad', 0, $request->cli_visibilidad),
			'clientName' => FormLib::Text('clientName', 0, $request->clientName),
			'sub_visibilidad' => FormLib::Text('sub_visibilidad', 0, $request->sub_visibilidad),
			'ref_visibilidad' => FormLib::Text('ref_visibilidad', 0, $request->ref_visibilidad),
			'inverso_visibilidad' => FormLib::Select('inverso_visibilidad', 0, $request->ref_visibilidad, ['N' => 'No', 'S' => 'Si']),
		];

		return view('admin::pages.subasta.visibilidades.index', compact('visibilitys', 'tableParams', 'formulario'));
	}

	public function create()
	{
		$visibility = new FgVisibilidad();
		$formulario = (object) $this->basicForm($visibility);

		return view('admin::pages.subasta.visibilidades.create', compact('formulario', 'visibility'));
	}

	public function store(FgVisibilidadRequest $request)
	{
		FgVisibilidad::create(
			$request->validated() + ['admin_visibilidad' => 'WEB']
		);
		return redirect(route('visibilidad.index'))->with('success', ['Deposito creado correctamente']);
	}

	public function edit($cod_visibilidad)
	{
		$visibility = FgVisibilidad::where('cod_visibilidad', $cod_visibilidad)->firstOrFail();

		$formulario = (object) $this->basicForm($visibility);

		return view('admin::pages.subasta.visibilidades.edit', compact('formulario', 'visibility'));
	}

	public function update(FgVisibilidadRequest $request, $cod_visibilidad)
	{
		$visibility = FgVisibilidad::where('cod_visibilidad', $cod_visibilidad)->firstOrFail();

		FgVisibilidad::where('cod_visibilidad', $cod_visibilidad)->update(
			$request->validated() + ['admin_visibilidad' => 'WEB']
		);

		return redirect(route('visibilidad.index'))
				->with(['success' =>array(trans('admin-app.title.updated_ok')) ]);
	}


	public function destroy($cod_visibilidad)
	{
		FgVisibilidad::where('cod_visibilidad', $cod_visibilidad)->firstOrFail();

		FgVisibilidad::where('cod_visibilidad', $cod_visibilidad)->update([
			'eliminado_visibilidad' => 'S',
			'fechaelim_visibilidad' => now(),
			'usuarioelim_visibilidad' => 'WEB',
		]);

		return redirect(route('visibilidad.index'))
				->with(['success' =>array(trans('admin-app.title.deleted_ok')) ]);
	}


	private function basicForm(FgVisibilidad $visibility)
	{
		/* $lotes = [];
		if($visibility->sub_visibilidad){
			$lotes = FgAsigl0::JoinFghces1Asigl0()->select('REF_HCES1 as id', 'nvl(TITULO_HCES1, DESCWEB_HCES1) as html')->where('sub_asigl0', $visibility->sub_visibilidad)->orderby('REF_HCES1')->get();
		} */

		return [
			'cli_visibilidad' => FormLib::Select2WithAjax('cli_visibilidad', 0, old('cli_visibilidad', $visibility->cli_visibilidad), '', route('client.list'), trans_choice('admin-app.title.client', 1)),
			'sub_visibilidad' => FormLib::Select('sub_visibilidad', 0, old('sub_visibilidad', $visibility->sub_visibilidad), FgSub::pluck('des_sub', 'cod_sub')),
			'ref_visibilidad' => FormLib::Text('ref_visibilidad', 0, old('ref_visibilidad', $visibility->ref_visibilidad)),
			//Por el momento dejo un input simple. Ya añadire un selector dinamico
			//'ref_visibilidad' => FormLib::Select('ref_visibilidad', 0, old('ref_visibilidad', $visibility->ref_visibilidad), $lotes, 'disabled'),
			'inverso_visibilidad' => FormLib::Select('inverso_visibilidad', 0, old('inverso_visibilidad', $visibility->inverso_visibilidad), ['N' => 'NO', 'S' => 'SI'], '', '', false)
		];
	}
}
