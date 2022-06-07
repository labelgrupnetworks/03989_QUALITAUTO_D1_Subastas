<?php

namespace App\Http\Controllers\admin\bi;

use App\Http\Controllers\Controller;
use App\libs\FormLib;
use App\Models\Filter;
use App\Models\V5\FxCli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminBiCedentesController extends Controller
{

	function __construct()
	{
		view()->share(['menu' => 'bi']);
	}

	public function index(Request $request)
	{

		/* DB::listen(function ($query) {
			dump($query->sql);
			dump($query->bindings);
		}); */

		$filters = [
			new Filter('cod_cli', Filter::TYPE_LIKE),
			new Filter('rsoc_cli', Filter::TYPE_LIKE),
			new Filter('pais_cli', Filter::TYPE_LIKE)
		];

		$cedentes = FxCli::select('cod_cli', 'rsoc_cli', 'pais_cli')

			->whereHas('hojasCesion')
			->with(['hojasCesion' => function($query){
				$query->select('sub_hces1, num_hces1, lin_hces1, prop_hces1, ref_asigl0')
					->leftJoin('FGASIGL0', 'FGHCES1.EMP_HCES1 = FGASIGL0.EMP_ASIGL0 AND FGHCES1.NUM_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND FGHCES1.LIN_HCES1 = FGASIGL0.LINHCES_ASIGL0');
			}])
			->withCount('hojasCesion as lotes_count')
			//los withcount añaden mucha carga en la busqueda, mirar de optimizar con
			//collection
			/* ->withCount([
				'hojasCesion as lotes_count',
				'hojasCesion as lotes_en_subasta_count' => function($query) {
					$query->join('FGASIGL0', 'FGHCES1.EMP_HCES1 = FGASIGL0.EMP_ASIGL0 AND FGHCES1.NUM_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND FGHCES1.LIN_HCES1 = FGASIGL0.LINHCES_ASIGL0');
				}
			]) */
			->whenFilters($request, $filters)
			->whereIn('tipo_cli', [FxCli::TIPO_CLI_CEDENTE, FxCli::TIPO_CLI_AMBOS])
			->orderBy(request('order', 'lotes_count'), request('order_dir', 'desc'))
			->paginate(20);


		$tableParams = [
			'cod_cli' => 1,
			'rsoc_cli' => 1,
			'pais_cli' => 1,
			'hces1_count' => 1,
			'lotes_count' => 1,
			'lotes_withoutaucion_count' => 1
		];

		$formulario = (object)[
			'cod_cli' => FormLib::Text('cod_cli', 0, $request->cod_cli),
			'rsoc_cli' => FormLib::Text('rsoc_cli', 0, $request->rsoc_cli),
			'pais_cli' => FormLib::Text('pais_cli', 0, $request->pais_cli),
		];

		return view('admin::pages.bi.cedentes.index', compact('cedentes', 'tableParams', 'formulario'));
	}


	public function show(Request $request, $cod_cli)
	{

		$cedente = FxCli::select('cod_cli', 'rsoc_cli')
			->where('cod_cli', $cod_cli)
			->first();

			if(!$cedente){
				return abort(404);
			}
		/* $cedente = FxCli::select('cod_cli', 'rsoc_cli')
			->whereHas('hojasCesionCabecera.hojaCesionLineas')
			->with(['hojasCesionCabecera:prop_hces0, num_hces0', 'hojasCesionCabecera.hojaCesionLineas' => function($query){
				$query->select('sub_hces1, num_hces1, lin_hces1, prop_hces1, ref_asigl0, implic_hces1, cerrado_asigl0')
					->leftJoin('FGASIGL0', 'FGHCES1.EMP_HCES1 = FGASIGL0.EMP_ASIGL0 AND FGHCES1.NUM_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND FGHCES1.LIN_HCES1 = FGASIGL0.LINHCES_ASIGL0');
			}])
			->where('cod_cli', $cod_cli)
			->first();

		if(!$cedente){
			return abort(404);
		} */

		/* $cedente->num_hojas_cesion = $cedente->hojasCesion->unique('num_hces1')->count();
		$cedente->num_lots_withouAuction = $cedente->hojasCesion->where('ref_asigl0', null)->count();
		$cedente->num_lots_auction = $cedente->lotes_count - $cedente->num_lots_withouAuction;

		$cedente->lotes_adjudicados = $cedente->hojasCesion->filter(function ($hces, $key) {
			return $hces->isAwarded;
		}); */

		//dd($cedente);

		return view('admin::pages.bi.cedentes.show', compact('cod_cli'));
	}

	public function getShow(Request $request, $cod_cli)
	{

		$cedente = FxCli::select('cod_cli', 'rsoc_cli')
			->whereHas('hojasCesionCabecera.hojaCesionLineas')
			->with(['hojasCesionCabecera:prop_hces0, num_hces0', 'hojasCesionCabecera.hojaCesionLineas' => function($query){
				$query->select('sub_hces1, num_hces1, lin_hces1, prop_hces1, ref_asigl0, implic_hces1, impsalhces_asigl0, cerrado_asigl0')
					->leftJoin('FGASIGL0', 'FGHCES1.EMP_HCES1 = FGASIGL0.EMP_ASIGL0 AND FGHCES1.NUM_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND FGHCES1.LIN_HCES1 = FGASIGL0.LINHCES_ASIGL0');
			}])
			->where('cod_cli', $cod_cli)
			->first();

		if(!$cedente){
			return abort(404);
		}

		/* $cedente->num_hojas_cesion = $cedente->hojasCesion->unique('num_hces1')->count();
		$cedente->num_lots_withouAuction = $cedente->hojasCesion->where('ref_asigl0', null)->count();
		$cedente->num_lots_auction = $cedente->lotes_count - $cedente->num_lots_withouAuction;

		$cedente->lotes_adjudicados = $cedente->hojasCesion->filter(function ($hces, $key) {
			return $hces->isAwarded;
		}); */

		//dd($cedente);

		return response($cedente);
	}
}
