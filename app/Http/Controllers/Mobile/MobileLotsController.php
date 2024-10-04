<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Mobile\Resources\LotResource;
use App\Http\Controllers\V5\LotListController;
use App\Models\Subasta;
use App\Models\V5\FgAsigl0;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class MobileLotsController extends Controller
{
	public function index(Request $request, $codsession = null)
	{
		$lang = $request->user()?->idioma_cliweb ?? 'ES';
		App::setLocale($lang);

		$fgasigl0 = FgAsigl0::query()
			->when($codsession, function ($query) use ($codsession) {
				$query->where('"id_auc_sessions"', $codsession);
			}, function ($query) {
				//establecemos un orden distinto
				$query;
			})
			->activeLotAsigl0();

		// $filters = [];
		// $fgasigl0 = (new LotListController())->setFilters($fgasigl0, $filters);

		$lots = $fgasigl0->select(['num_hces1', 'lin_hces1', 'impsalhces_asigl0', 'sub_asigl0', 'ref_asigl0', 'implic_hces1', 'retirado_asigl0', 'cerrado_asigl0', 'lic_hces1', 'tipo_sub', 'compra_asigl0', 'impres_asigl0', 'remate_asigl0', 'ffin_asigl0', 'hfin_asigl0', 'subabierta_sub', 'fac_hces1'])
			->addSelect(['descweb_hces1', 'webfriend_hces1'])
			->when($lang != 'ES', function ($query) {
				$query->joinFghces1LangAsigl0()
					->addSelect('descweb_hces1_lang', 'webfriend_hces1_lang');
			})
			->orderBy('ref_asigl0')
			//->get();
			->paginate(10);

		return LotResource::collection($lots);
	}

	public function show(Request $request, $codauction, $lotref)
	{
		$lang = $request->user()?->idioma_cliweb ?? 'ES';
		App::setLocale($lang);

		$subastaObj = new Subasta();
		$subastaObj->cod = $codauction;
		$subastaObj->lote = $lotref;
		$subastaObj->ref = $lotref;
		$subastaObj->page = 1;
		$subastaObj->itemsPerPage = 1000;

		$lot = $subastaObj->getLote(false, true, true);
		if (empty($lot) || $lot[0]->subc_sub == 'N') {
			return response()->json(['message' => 'No se ha encontrado el lote', 'errors' => ['lot' => 'No se ha encontrado el lote']], 404);
		}

		$this->createInitalLicits();

		$lot = head($lot);

		return response()->json(['data' => 'show', 'lot' => $lot]);
	}


	private function createInitalLicits()
	{
		return;
		// código wn serviceApp
		/* if (!empty($this->parameters['codcli'])) {
			# es necesario crear el dummy por si no existe
			$subastaObj->checkDummyLicitador();
			$subastaObj->cli_licit = $this->parameters['codcli'];
			#necesitamos el usuario para poder indicar el rsoc
			$user                = new User();
			$user->cod_cli       = $this->parameters['codcli'];
			$usuario = $user->getUser();
			$subastaObj->rsoc      = $usuario->rsoc_cli ?? $usuario->nom_cli;
			# Si tienen numero de ministerio asignado, creamos ministerio como licitador
			if (Config::get('app.ministeryLicit', false)) {
				$subastaObj->checkOrInstertMinisteryLicitador(Config::get('app.ministeryLicit'), 'Ministerio');
			}

			//recogemos el licitador o lo creamos si no existe
			$licit = $subastaObj->checkLicitador();
		} */
	}
}
