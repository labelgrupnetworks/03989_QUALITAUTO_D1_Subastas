<?php

namespace App\Http\Controllers\admin\b2b;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\FgVisibilidadRequest;
use App\libs\FormLib;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgSub;
use App\Models\V5\FgVisibilidad;
use App\Models\V5\FgSubInvites;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class AdminB2BVisibilitiesController extends Controller
{
	public function index(Request $request)
	{
		$userCod = Session::get('user.cod');

		$auctionsToOwner = FgSub::query()
			->where('agrsub_sub', $userCod)
			->pluck('cod_sub');

		$auctionVisibility = FgVisibilidad::query()
			->whereIn('sub_visibilidad', $auctionsToOwner)
			->first();

		$visibilities = FgVisibilidad::query()
			->with('client.invitation:owner_codcli_subinvites,invited_codcli_subinvites,invited_nom_subinvites')
			->whereHas('client.invitation')
			->whereIn('sub_visibilidad', $auctionsToOwner)
			->where('eliminado_visibilidad', 'N')
			->when($request->cli_visibilidad, function ($query, $cli_visibilidad) {
				return $query->where('cli_visibilidad', $cli_visibilidad);
			})
			->when($request->ref_visibilidad, function ($query, $ref_visibilidad) {
				return $query->where('ref_visibilidad', $ref_visibilidad);
			})
			->paginate(40);

		$tableParams = [
			'sub_visibilidad' => 1,
			'cli_visibilidad' => 1,
			'clientName' => 1,
			'email_cli' => 1,
			'ref_visibilidad' => 1
		];

		$numberOfColumns = count(array_filter($tableParams));

		$formulario = (object)[
			'cli_visibilidad' => FormLib::Text('cli_visibilidad', 0, $request->cli_visibilidad),
			'ref_visibilidad' => FormLib::Text('ref_visibilidad', 0, $request->ref_visibilidad),
		];

		return view('admin::pages.b2b.visibilities.index', [
			'visibilities' => $visibilities,
			'auctionVisibility' => $auctionVisibility,
			'formulario' => $formulario,
			'tableParams' => $tableParams,
			'numberOfColumns' => $numberOfColumns
		]);
	}

	public function create()
	{
		$visibility = new FgVisibilidad();
		$formulario = (object) $this->basicForm($visibility);

		return view('admin::pages.b2b.visibilities.create', [
			'formulario' => $formulario,
			'visibility' => $visibility
		]);
	}

	public function store(FgVisibilidadRequest $request)
	{
		FgVisibilidad::create(array_merge($request->validated(), [
			'inverso_visibilidad' => 'N',
			'admin_visibilidad' => 'WEB'
		]));

		return redirect(route('admin.b2b.visibility'))->with('success', ['Visibilidad creada correctamente']);
	}

	public function edit($cod_visibilidad)
	{
		$visibility = FgVisibilidad::query()
			->where('cod_visibilidad', $cod_visibilidad)
			->firstOrFail();

		$formulario = (object) $this->basicForm($visibility);

		return view('admin::pages.b2b.visibilities.edit', [
			'formulario' => $formulario,
			'visibility' => $visibility
		]);
	}

	public function update(FgVisibilidadRequest $request, $cod_visibilidad)
	{
		$visibility = FgVisibilidad::query()
			->where('cod_visibilidad', $cod_visibilidad)
			->firstOrFail();

		FgVisibilidad::where('cod_visibilidad', $cod_visibilidad)
			->update(array_merge($request->validated(), [
				'inverso_visibilidad' => 'N',
				'admin_visibilidad' => 'WEB'
			]));

		return redirect(route('admin.b2b.visibility'))
			->with(['success' => array(trans('admin-app.title.updated_ok'))]);
	}

	public function destroy($cod_visibilidad)
	{
		FgVisibilidad::where('cod_visibilidad', $cod_visibilidad)->firstOrFail();

		FgVisibilidad::where('cod_visibilidad', $cod_visibilidad)->update([
			'eliminado_visibilidad' => 'S',
			'fechaelim_visibilidad' => now(),
			'usuarioelim_visibilidad' => 'WEB',
		]);

		return redirect(route('admin.b2b.visibility'))
			->with(['success' => array(trans('admin-app.title.deleted_ok'))]);
	}

	public function showOrHideEveryone(Request $request)
	{
		$userCod = Session::get('user.cod');
		$action = $request->action;
		$state = $action === 'show' ? 'N' : 'S';

		$auctionsToOwner = FgSub::query()
			->where('agrsub_sub', $userCod)
			->pluck('cod_sub');

		if ($action === 'show') {

			$visibilities = $auctionsToOwner->map(function ($auction) {
				return [
					'emp_visibilidad' => Config::get('app.emp'),
					'cli_visibilidad' => null,
					'sub_visibilidad' => $auction,
					'ref_visibilidad' => null,
					'inverso_visibilidad' => 'N',
					'admin_visibilidad' => 'WEB',
					'eliminado_visibilidad' => 'N',
				];
			});

			FgVisibilidad::insert($visibilities->toArray());
		} else {
			FgVisibilidad::query()
				->whereIn('sub_visibilidad', $auctionsToOwner)
				->whereNull('cli_visibilidad')
				->whereNull('ref_visibilidad')
				->delete();
		}

		return response()->json(['success' => true]);
	}

	private function basicForm(FgVisibilidad $visibility)
	{
		$clients = FgSubInvites::query()
			->where('owner_codcli_subinvites', Session::get('user.cod'))
			->pluck('invited_nom_subinvites', 'invited_codcli_subinvites');

		$acutions = FgSub::query()
			->where('agrsub_sub', Session::get('user.cod'))
			->pluck('des_sub', 'cod_sub');

		// Mientas mantengamos una subasta por empresa vendedora
		// nos sirve.
		// Es posible que sea necesario añadir filtro de no cerrados.
		$lots = FgAsigl0::JoinFghces1Asigl0()
			->select('ref_hces1', 'nvl(TITULO_HCES1, DESCWEB_HCES1) as description')
			->whereIn('sub_asigl0', $acutions->keys())
			->orderby('ref_hces1')
			->pluck('description', 'ref_hces1');

		return [
			'cli_visibilidad' => FormLib::Select2WithArray('cli_visibilidad', 0, old('cli_visibilidad', $visibility->cli_visibilidad), $clients, true),
			'sub_visibilidad' => FormLib::Select2WithArray('sub_visibilidad', 0, old('sub_visibilidad', $visibility->sub_visibilidad), $acutions, false),
			'ref_visibilidad' => FormLib::Select2WithArray('ref_visibilidad', 0, old('ref_visibilidad', $visibility->ref_visibilidad), $lots, true),
		];
	}
}
