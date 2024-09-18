<?php

namespace App\Http\Controllers\V5;

use App\Exports\CarlandiaSalesExport;
use App\Http\Controllers\Controller;
use App\libs\FormLib;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FxCli;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CarlandiaSalesController extends Controller
{

	private $cedente;
	private $isAdmin;

	public function __construct()
	{
		$this->middleware(function ($request, $next) {

			$this->cedente = $this->checkCedenteUser();
			$this->isAdmin = session('user.admin');

			if (!$this->cedente && !$this->isAdmin) {
				abort(404);
			}

			return $next($request);
		});
	}

	public function getActiveSales(Request $request)
	{
		$ownerSelector = $this->isAdmin ? $this->getOwnerSelector($request) : null;

		$lots = $this->getQuerySales(true, $this->cedente->cod_cli, $request->search);

		return view('front::pages.panel.active_sales', ['sales' => $lots, 'selector' => $ownerSelector]);
	}

	public function getAwardSales(Request $request)
	{
		$ownerSelector = $this->isAdmin ? $this->getOwnerSelector($request) : null;

		$lots = $this->getQuerySales(false, $this->cedente->cod_cli, $request->search);

		return view('front::pages.panel.award_sales', ['sales' => $lots, 'selector' => $ownerSelector]);
	}

	public function getDownloadSales(Request $request)
	{
		if ($this->isAdmin) {
			$this->cedente = new FxCli();
			$this->cedente->cod_cli = null;
		}

		$isActive = $request->active;

		$lots = $this->getQuerySales($isActive, $this->cedente->cod_cli);

		$export = new CarlandiaSalesExport($lots, $isActive);

		$name = $isActive ? 'Ofertas_vigentes' : 'Ventas_cerradas';

		return Excel::download($export, $name . '_' . $this->cedente->cod_cli . '.xlsx');
	}

	private function formatPricesWithCommission($lots)
	{
		$comision = 1 + config('app.carlandiaCommission');

		foreach ($lots as $lot) {
			$lot->reserva = $lot->reserva / $comision;
			$lot->comprar = $lot->comprar / $comision;
			$lot->implic_hces1 = $lot->implic_hces1 / $comision;
			$lot->max_imp_asigl1 = $lot->max_imp_asigl1 / $comision;
		}

		return $lots;
	}

	private function checkCedenteUser()
	{
		$cod_cli = session('user.cod');

		if (!$cod_cli) {
			return null;
		}

		return FxCli::where(['cod_cli' => $cod_cli, 'tipo_cli' => FxCli::TIPO_CLI_VENDEDOR])->first();
	}

	private function getOwnerSelector(Request $request)
	{
		$owners = \App\Models\V5\FxCli::select('cod_cli || \' - \' || rsoc_cli as name', 'cod_cli')->where('tipo_cli', 'V')->orderBy('cod_cli')->pluck('name', 'cod_cli')->toArray();
		$firstOwner = array_key_first($owners);

		//La primera vez que se entra, como no tenemos un vendedor seleccionado buscamos info del primero.
		$this->cedente = new FxCli();
		$this->cedente->cod_cli = $request->get('prop', $firstOwner);

		return FormLib::Select('prop', 0, $request->prop ?? $firstOwner, $owners, "", "", false);
	}


	private function getQuerySales($isActive, $propertyCod, $search = null)
	{
		$idMatricula = '55';

		$lots = FgAsigl0::select('ref_asigl0', 'sub_asigl0', 'cerrado_asigl0', 'impsalhces_asigl0', 'fecalta_asigl0', 'ffin_asigl0', 'impres_asigl0', 'imptas_asigl0', 'imptash_asigl0')
			->addSelect('num_hces1', 'lin_hces1', 'pc_hces1', 'implic_hces1', 'descweb_hces1', 'webfriend_hces1', 'tipo_sub', 'cod_sub', '"id_auc_sessions"', '"name"', 'value_caracteristicas_hces1 as matricula')
			->addSelect('fxcli.rsoc_cli')

			->selectRaw('CASE WHEN tipo_sub = \'V\' THEN imptas_asigl0 ELSE impres_asigl0 END as reserva')
			->selectRaw('CASE WHEN tipo_sub = \'V\' THEN impsalhces_asigl0 ELSE imptash_asigl0 END as comprar')
			->selectRaw('(SELECT SUM(BIDS) FROM(
				SELECT COUNT(LICIT_ASIGL1) as BIDS FROM FGASIGL1 WHERE SUB_ASIGL1 = COD_SUB AND REF_ASIGL1 = REF_ASIGL0 AND EMP_ASIGL1 = EMP_ASIGL0
					UNION ALL
					SELECT COUNT(LICIT_ASIGL1) as BIDS FROM FGASIGL1_AUX WHERE SUB_ASIGL1 = COD_SUB AND REF_ASIGL1 = REF_ASIGL0 AND EMP_ASIGL1 = EMP_ASIGL0
				)) AS BIDS')

			->selectRaw('(SELECT COUNT(DISTINCT(LICIT_ASIGL1)) FROM(
					SELECT DISTINCT(LICIT_ASIGL1) FROM FGASIGL1 WHERE SUB_ASIGL1 = COD_SUB AND REF_ASIGL1 = REF_ASIGL0 AND EMP_ASIGL1 = EMP_ASIGL0
					UNION ALL
					SELECT DISTINCT(LICIT_ASIGL1) FROM FGASIGL1_AUX WHERE SUB_ASIGL1 = COD_SUB AND REF_ASIGL1 = REF_ASIGL0 AND EMP_ASIGL1 = EMP_ASIGL0
				)) as LICITS')

			->joinFghces1Asigl0()
			->joinSubastaAsigl0()
			->joinSessionAsigl0()

			->join('FXCLI', 'FXCLI.COD_CLI = FGHCES1.PROP_HCES1 AND FXCLI.GEMP_CLI = ' . config('app.gemp'))

			->leftJoin('FGCARACTERISTICAS_HCES1', "EMP_CARACTERISTICAS_HCES1 = EMP_HCES1 AND LINHCES_CARACTERISTICAS_HCES1 = LIN_HCES1 AND NUMHCES_CARACTERISTICAS_HCES1 = NUM_HCES1 AND IDCAR_CARACTERISTICAS_HCES1 = '$idMatricula'")

			//No lo han pedido, pero por si en un futuro quieren buscador
			->when($search, function ($query, $search) {
				return $query->where([
					['ref_asigl0', $search],
					['lower(descweb_hces1)', 'like', "%" . mb_strtolower($search) . "%", 'or'],
				]);
			})

			->when($propertyCod, function ($query, $cod) {
				return $query->where('prop_hces1', $cod);
			})

			//seleccionamos condiciones segun si buscamos activos o vendidos
			->when($isActive, function ($query) {
				return $query
					->selectRaw('(SELECT MAX(IMP_ASIGL1) FROM(
						SELECT MAX(IMP_ASIGL1) AS IMP_ASIGL1 FROM FGASIGL1 WHERE SUB_ASIGL1 = COD_SUB AND REF_ASIGL1 = REF_ASIGL0 AND EMP_ASIGL1 = EMP_ASIGL0
							UNION ALL
						SELECT MAX(IMP_ASIGL1) AS IMP_ASIGL1 FROM FGASIGL1_AUX WHERE SUB_ASIGL1 = COD_SUB AND REF_ASIGL1 = REF_ASIGL0 AND EMP_ASIGL1 = EMP_ASIGL0
						)) as MAX_IMP_ASIGL1')
					->where([
						['cerrado_asigl0', 'N'],
						['retirado_asigl0', 'N']
					]);
			}, function ($query) {

				return $query
					->addSelect('FGASIGL1.pujrep_asigl1', 'FGCSUB.fecha_csub')

					->joinCSubAsigl0()
					->join('FGASIGL1', 'FGASIGL1.EMP_ASIGL1 = FGCSUB.EMP_CSUB AND FGASIGL1.SUB_ASIGL1 = FGCSUB.SUB_CSUB AND FGASIGL1.REF_ASIGL1 = FGCSUB.REF_CSUB AND FGASIGL1.LICIT_ASIGL1 = FGCSUB.LICIT_CSUB AND FGASIGL1.IMP_ASIGL1 = FGCSUB.HIMP_CSUB')
					->where([
						['cerrado_asigl0', 'S'],
						['implic_hces1', '!=', '0'],
						['afral_csub', 'L00']
					]);
			})
			->orderBy('ref_asigl0')
			->get();

		return $this->formatPricesWithCommission($lots);
	}
}
