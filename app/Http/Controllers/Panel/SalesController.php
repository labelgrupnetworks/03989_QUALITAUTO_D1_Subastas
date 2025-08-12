<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentsController;
use App\libs\Currency;
use App\Models\User;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgAsigl1;
use App\Models\V5\FgHces1;
use App\Models\V5\FxCliWeb;
use App\Models\V5\FxDvc0;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class SalesController extends Controller
{
	# Adjudicaciones del usuario en sesion
	public function getSales(Request $request)
	{
		$user = new User();
		$cod_cli = Session::get('user.cod');

		if (config('app.permission_to_view_seller_panel', false)) {
			$cliweb = FxCliWeb::where('cod_cliweb', $cod_cli)->whereNotNull('permission_id_cliweb')->first();
			abort_if(!$cliweb, 404);
		}

		$user->cod_cli = $cod_cli;
		$lotes = $user->getSales($request->all());

		$condAuctions = $lotes->pluck('cod_sub')->unique()->values();
		$pujas = FgAsigl1::select('ref_asigl1, lin_asigl1, licit_asigl1, imp_asigl1, fec_asigl1', 'sub_asigl1')
			->joinFghces1Asigl0()
			->where('prop_hces1', $cod_cli)
			->whereIn('sub_asigl1', $condAuctions)
			->orderBy('imp_asigl1', 'desc')
			->orderBy('fec_asigl1', 'desc')->get();

		foreach ($lotes as $lot) {

			//$pujas = $subasta->getPujas(null,$lot->sub_asigl0);
			$lot->pujas = $pujas->where('ref_asigl1', $lot->ref_asigl0)->where('sub_asigl1', $lot->cod_sub);
			$lot->puja_max = 0;
			if ($lot->pujas->count() > 0) {
				$lot->puja_max = $lot->pujas->first();
			}
		}

		$collection = collect($lotes);

		$subastas = $collection->mapToGroups(function ($item) {
			return [$item->cod_sub => $item];
		});

		$data = array('subastas' => $subastas);

		return view('front::pages.panel.sales', $data);
	}

	public function getSalesToActiveAuctions(Request $request)
	{
		$user = new User();
		$cod_cli = Session::get('user.cod');

		if (config('app.permission_to_view_seller_panel', false)) {
			$cliweb = FxCliWeb::where('cod_cliweb', $cod_cli)->whereNotNull('permission_id_cliweb')->first();
			abort_if(!$cliweb, 404);
		}

		$auctions = $user->setCodCli($cod_cli)
			->getSalesToNotFinishAuctions();

		$summary = [
			'total_lots' => $auctions->sum('total_lots'),
			'total_award' => $auctions->sum('total_award'),
			'total_impsalhces' => $auctions->sum('total_impsalhces'),
			'total_bids_lots' => $auctions->sum('total_bids_lots'),
			'total_imptas' => $auctions->sum('total_imptas'),
		];

		$summary['percentage_lots_with_bid'] = ($summary['total_bids_lots'] / max($summary['total_lots'], 1)) * 100;
		$summary['revaluation'] = ($summary['total_award'] / max($summary['total_impsalhces'], 1)) * 100;

		$lots = FgAsigl0::getActiveLotsSalesByOwnerQuery($auctions->pluck('sub_asigl0'), $cod_cli, true)
			->orderby("sub_asigl0, ref_asigl0")
			->get()
			->groupBy('sub_asigl0');

		$currency = new Currency();
		$divisa = Session::get('user.currency', 'EUR');
		$divisas = $currency->setDivisa($divisa)->getAllCurrencies();

		$data = [
			'auctions' => $auctions,
			'lots' => $lots,
			'summary' => $summary,
			'currency' => $currency,
			'divisa' => $divisa,
			'divisas' => $divisas,
		];

		return view('front::pages.panel.sales', $data);
	}
	public function invoiceSalesOfFinishAuctions(Request $request)
	{
		$cod_cli = Session::get('user.cod');
		$yearSelected = $request->input('years', [date('Y'), date('Y') - 1]);

		$paymentController = new PaymentsController();
		$iva = $paymentController->getIva(Config::get('app.emp'), date("Y-m-d"));
		$tipo_iva = $paymentController->user_has_Iva(Config::get('app.gemp'), $cod_cli);

		//Lotes sin factura
		$lotsWithoutInvoice = FgAsigl0::getLotsAwardedWithoutInvoiceByOwnerQuery($cod_cli)
			->addSelectLotDesctiptionsAttributes()
			->orderBy('auc."end"', 'desc')
			->whereYearsDates('auc."end"', $yearSelected)
			->get()
			->each(function ($item) use ($paymentController, $iva, $tipo_iva) {
				$item->imp_award = ($item->implic_hces1 * $item->ratio_hcesmt) / 100;
				$item->imp_comision = ($item->imp_award * $item->comphces_asigl0) / 100;
				$item->imp_tax = $paymentController->calculate_iva($tipo_iva->tipo, $iva, $item->imp_comision);
				$item->imp_liquidacion = $item->imp_award - $item->imp_comision - $item->imp_tax;
			});

		$auctionsWithoutInvoice = $lotsWithoutInvoice->groupBy('sub_asigl0');
		$activeAuctions = $auctionsWithoutInvoice->keys()->all();

		$auctionsResults = FgAsigl0::getAuctionsResultsByOwnerQuery($activeAuctions, $cod_cli)
			->get()
			->each(function ($item) use ($lotsWithoutInvoice) {
				$item->total_liquidation = $lotsWithoutInvoice->where('sub_asigl0', $item->sub_asigl0)->sum('imp_liquidacion');
			});

		//Facturas
		$owerInvoicesLots = FxDvc0::getInvoicesByOwnerQuery($cod_cli)
			->addSelectLotDesctiptionsAttributes()
			->whereYearsDates($yearSelected)
			->orderBy('fecha_dvc0', 'desc')
			->get();

		$invoiceAuctions = $owerInvoicesLots->pluck('sub_asigl0')->unique()->all();
		$ownerInvoices = $owerInvoicesLots->groupBy(fn($item) => "$item->anum_dvc0-$item->num_dvc0");
		$invoiceResults = FgAsigl0::getAuctionsResultsByOwnerQuery($invoiceAuctions, $cod_cli)
			->get()
			->each(function ($item) use ($owerInvoicesLots) {
				$invoiceLots = $owerInvoicesLots->where('sub_asigl0', $item->sub_asigl0);

				//TODO: Esta operación se puede realizar fuera del bucle,
				//y realizar el where sub sobre este resultado.
				$distinctInvoices = $invoiceLots->unique(fn($item) => $item['anum_dvc0'] . $item['num_dvc0']);

				$totalDvc0 = $distinctInvoices->sum('total_dvc0');
				$item->total_liquidation = $item->total_award - $totalDvc0;
			});

		$invoicesYearsAvailables = FxDvc0::getInvoicesYearsAvailables($cod_cli, FxDvc0::TIPO_PROPIETARIO);

		$data = [
			'auctionsWithoutInvoice' => $auctionsWithoutInvoice,
			'ownerInvoices' => $ownerInvoices,
			'auctionsResults' => $auctionsResults->concat($invoiceResults),
			'invoicesYearsAvailables' => $invoicesYearsAvailables,
			'yearSelected' => $yearSelected
		];

		return view('front::pages.panel.sales_finish', $data);
	}

	public function getLotsSalesPendingToBeAssign(Request $request)
	{
		$cod_cli = Session::get('user.cod');
		$sheetsSelected = $request->input('sheets', []);

		$lotsQuery = FgHces1::query()
			->whereOwner($cod_cli, false)
			->notInAuction()
			->isVisibleWeb()
			->isNotReturnedOrWithdrawn();

		$numsHces = $lotsQuery->clone()
			->select('num_hces1')
			->distinct()
			->pluck('num_hces1');

		$lots = $lotsQuery->clone()
			->select('num_hces1', 'lin_hces1', 'impsal_hces1', 'imptas_hces1')
			->addSelectTranslationsAttributes()
			->when($sheetsSelected, fn($query, $sheetsSelected) => $query->whereIn('num_hces1', $sheetsSelected))
			->orderBy('num_hces1')
			->orderBy('lin_hces1')
			->get();

		$data = [
			'lots' => $lots,
			'numsHces' => $numsHces,
			'sheetsSelected' => $sheetsSelected
		];

		return view('front::pages.panel.sales_pending', $data);
	}
}
