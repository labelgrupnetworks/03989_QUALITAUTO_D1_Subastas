<?php

namespace App\Http\Controllers\admin\facturacion;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\admin\ProviderRequest;
use App\libs\FormLib;
use App\Models\V5\FxPro;
use App\Models\V5\FsParams;
use App\Models\V5\FsPaises;
use App\Models\V5\FgSg;

class AdminProviderController extends Controller
{

	public function __construct()
	{
		view()->share(['menu' => 'facturacion']);
	}

	public function index(Request $request)
	{
		$providers = FxPro::select('cod_pro', 'nom_pro', 'pro_pro', 'tel1_pro', 'email_pro', 'baja_temp_pro')
			->when($request->cod_pro, function ($query, $cod_pro) {
				return $query->where('cod_pro', 'like', "%{$cod_pro}%");
			})
			->when($request->nom_pro, function ($query, $nom_pro) {
				return $query->where('upper(nom_pro)', 'like', "%".mb_strtoupper($nom_pro)."%");
			})
			->when($request->pro_pro, function ($query, $pro_pro) {
				return $query->where('upper(pro_pro)', 'like', "%".mb_strtoupper($pro_pro)."%");
			})
			->when($request->tel1_pro, function ($query, $tel1_pro) {
				return $query->where('tel1_pro', 'like', "%{$tel1_pro}%");
			})
			->when($request->email_pro, function ($query, $email_pro) {
				return $query->where('upper(email_pro)', 'like', "%".mb_strtoupper($email_pro)."%");
			})
			->when($request->baja_temp_pro, function ($query, $baja_temp_pro) {
				return $query->where('baja_temp_pro', $baja_temp_pro);
			})
			->orderBy($request->filled('order') ? $request->order : 'cod_pro', $request->filled('order_dir') ? $request->order_dir : 'asc')
			->paginate(30);

		$tableParams = ['cod_pro' => 1, 'nom_pro' => 1, 'pro_pro' => 1, 'tel1_pro' => 1, 'email_pro' => 1, 'baja_temp_pro' => 1];

		$formulario = (object)[
			'cod_pro' => FormLib::Text('cod_pro', 0, $request->cod_pro),
			'nom_pro' => FormLib::Text('nom_pro', 0, $request->nom_pro),
			'pro_pro' => FormLib::Text('pro_pro', 0, $request->pro_pro),
			'tel1_pro' => FormLib::Text('tel1_pro', 0, $request->tel1_pro),
			'email_pro' => FormLib::Text('email_pro', 0, $request->email_pro),
			'baja_temp_pro' => FormLib::Select('baja_temp_pro', 0, $request->baja_temp_pro, ['S' => 'Si', 'N' => 'No']),
		];

		return view('admin::pages.facturacion.proveedores.index', compact('providers', 'tableParams', 'formulario'));
	}

	public function create()
	{
		$provider = new FxPro();

		$formulario = $this->basicForm($provider);

		return view('admin::pages.facturacion.proveedores.create', compact('provider', 'formulario'));
	}

	public function store(ProviderRequest $request)
	{

		$sizeProID = FsParams::select('TPRO_PARAMS')->where([['emp_params','=',\Config::get('app.emp')], ['cla_params','=', '1']])->first();

		$maxCodPro = DB::select(
			'select BuscarMinCodProLibre(:gemp,:numsize) as exportacion from dual',
			array(
				'gemp' => \Config::get('app.gemp'),
				'numsize' => $sizeProID->tpro_params
			)
		);

		$actualCodpro = $maxCodPro[0]->exportacion;

		$provider = $request->validated();
		$provider += [
			'cod_pro' => $actualCodpro,
		];

		try {
			DB::beginTransaction();

			FxPro::create($provider);

			DB::commit();

		} catch (\Throwable $th) {
			DB::rollBack();

			return back()->withErrors(['errors' => [$th->getMessage()]])->withInput();
		}

		return redirect(route('providers.index'))->with(['success' => array(trans('admin-app.title.created_ok'))]);
	}

	public function edit($cod_pro)
	{
		$provider = FxPro::where('cod_pro', $cod_pro)->first();

		if (!$provider) {
			abort(404);
		}

		$formulario = $this->basicForm($provider);

		return view('admin::pages.facturacion.proveedores.edit', compact('provider', 'formulario'));
	}

	public function update(ProviderRequest $request, $cod_pro)
	{
		$provider = FxPro::where('cod_pro', $cod_pro)->first();

		if (!$provider) {
			return back()->withErrors(['errors' => ['Provider not exist']])->withInput();
		}

		try {
			DB::beginTransaction();

			FxPro::where('cod_pro', $cod_pro)->update($request->validated());

			DB::commit();

		} catch (\Throwable $th) {
			DB::rollBack();

			return back()->withErrors(['errors' => [$th->getMessage()]])->withInput();
		}

		return redirect(route('providers.index'))->with(['success' => array(trans('admin-app.title.created_ok'))]);
	}

	public function destroy($cod_pro)
	{
		$provider = FxPro::where('cod_pro', $cod_pro)->first();

		if (!$provider) {
			return back()->withErrors(['errors' => ['Provider not exist']])->withInput();
		}

		try {
			DB::beginTransaction();

			FxPro::where('cod_pro', $cod_pro)->delete();

			DB::commit();

		} catch (\Throwable $th) {
			DB::rollBack();

			return back()->withErrors(['errors' => [$th->getMessage()]])->withInput();
		}

		return redirect(route('providers.index'))->with(['success' => array(trans('admin-app.title.deleted_ok'))]);

	}

	public function basicForm($provider)
	{

		$FgSg = FgSg::select('COD_SG','DES_SG')->get();
		$FsPaises = FsPaises::select('COD_PAISES','DES_PAISES')->orderBy('DES_PAISES')->get();

		$streetType = [];
		$streetType = $FgSg->mapWithKeys(function ($roadTypes) {
			return [$roadTypes['cod_sg'] => $roadTypes['des_sg']];
		});

		$countryCode = [];
		$countryCode = $FsPaises->mapWithKeys(function ($country) {
			return [$country['cod_paises'] => $country['des_paises']];
		});

		return [
			'nom_pro' => FormLib::Text('nom_pro', 0, old('nom_pro', $provider->nom_pro ?? '')),
			'contacto_pro' => FormLib::Text('contacto_pro', 0, old('contacto_pro', $provider->contacto_pro ?? '')),
			'cif_pro' => FormLib::Text('cif_pro', 0, old('cif_pro', $provider->cif_pro ?? '')),
			'tel1_pro' => FormLib::Text('tel1_pro', 0, old('tel1_pro', $provider->tel1_pro ?? '')),
			'email_pro' => FormLib::Text('email_pro', 0, old('email_pro', $provider->email_pro ?? '')),
			'margen_pro' => FormLib::Int('margen_pro',0, old('margen_pro',$provider->margen_pro ?? '0')),
			'pais_pro' => FormLib::Select('pais_pro', 0, old('pais_pro', $provider->pais_pro ?? 'ES'), $countryCode, "", "", false),
			'pro_pro' => FormLib::Text('pro_pro', 0, old('pro_pro', $provider->pro_pro ?? '')),
			'pob_pro' => FormLib::Text('pob_pro', 0, old('pob_pro', $provider->pob_pro ?? '')),
			'cp_pro' => FormLib::Int('cp_pro', 0, old('cp_pro',$provider->cp_pro ?? '')),
			'sg_pro' => FormLib::Select('sg_pro', 0, old('sg_pro', $provider->sg_pro ?? ''), $streetType, "", "", true),
			'dir_pro' => FormLib::Text('dir_pro', 0, old('dir_pro', $provider->dir_pro ?? '')),
			'baja_temp_pro' => FormLib::Select('baja_temp_pro', 0, old('baja_temp_pro', $provider->baja_temp_pro ?? 'N'), ['N' => 'No', 'S' => 'Si'], '', '', false),
		];
	}


	function getSelectProviders(){

		$query =  mb_strtoupper(request('q'));

		if(!empty($query)){

			$where = [
				['upper(nom_pro)', 'LIKE', "%$query%", 'or'],
				['upper(COD_PRO)', 'LIKE', "%$query%", 'or']
			];

			$providers = FxPro::select('nom_pro as html', 'cod_pro as id')->where($where)->get();

			return response()->json($providers);
		}

		return response();

	}

}
