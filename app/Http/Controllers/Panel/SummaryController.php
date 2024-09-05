<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use App\Models\V5\FxDvc0;
use App\Models\V5\FxCliWeb;
use App\Models\V5\FgHces1;
use App\Models\V5\FgAsigl0;
use App\Models\User;
use App\Models\Subasta;
use App\libs\Currency;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\Controller;
use App\Models\V5\FgCsub;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
	public function summary()
	{
		if(!Session::has('user')){
            $url =  Config::get('app.url'). parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH).'?view_login=true';
            $data['data'] = trans_choice(Config::get('app.theme').'-app.user_panel.not-logged', 1, ['url'=>$url]);
			$seo = new \Stdclass();
			$seo->noindex_follow = true;
			if(config('app.seo_notlogged_page', 0)){
				$seo->meta_title = trans(Config::get('app.theme').'-app.metas.title_no_logged');
				$seo->meta_description = trans(Config::get('app.theme').'-app.metas.description__no_logged');
			}

			$data['seo'] = $seo;
            return view('front::pages.not-logged', $data);
        }

		$cod_cli = Session::get('user.cod');
		$currency = new Currency();
		$divisa = Session::get('user.currency', 'EUR');
		$divisas = $currency->setDivisa($divisa)->getAllCurrencies();

		$invoicesYearsAvailables = FxDvc0::getInvoicesYearsAvailables($cod_cli, null);
		$profomaYearsAvailables = FgCsub::getYearsToAllAwardsAvailables($cod_cli);

		$yearsAvailables = $invoicesYearsAvailables->merge($profomaYearsAvailables)->unique()->sortDesc();
		$yearsSelected = [date('Y'), date('Y') - 1];

		$data = [
			'divisas' => $divisas,
			'divisa' => $divisa,
			'yearsAvailables' => $yearsAvailables,
			'yearsSelected' => $yearsSelected
		];

		return view('front::pages.panel.summary', $data);
	}

	public function summaryActiveSales()
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
		];

		$currency = new Currency();
		$divisa = Session::get('user.currency', 'EUR');
		$currency->setDivisa($divisa);

		$data = [
			'auctions' => $auctions,
			'summary' => $summary,
			'currency' => $currency
		];

		return view('front::pages.panel.summary.sales_active', $data);
	}

	public function summaryFinishSales(Request $request)
	{
		$cod_cli = Session::get('user.cod');
		$maxLines = 3;
		$yearSelected = $request->input('years', [date('Y'), date('Y') - 1]);

		$paymentController = new PaymentsController();
		$iva = $paymentController->getIva(Config::get('app.emp'), date("Y-m-d"));
		$tipo_iva = $paymentController->user_has_Iva(Config::get('app.gemp'), $cod_cli);

		//Lotes sin factura
		$allLotsWithoutInvoice = FgAsigl0::getLotsAwardedWithoutInvoiceByOwnerQuery($cod_cli)
			->whereYearsDates('auc."end"', $yearSelected)
			->orderBy('auc."end"', 'desc')
			->get()
			->each(function ($item) use ($paymentController, $iva, $tipo_iva) {
				$item->imp_award = ($item->implic_hces1 * $item->ratio_hcesmt) / 100;
				$item->imp_comision = ($item->imp_award * $item->comphces_asigl0) / 100;
				$item->imp_tax = $paymentController->calculate_iva($tipo_iva->tipo, $iva, $item->imp_comision);
				$item->imp_liquidacion = $item->imp_award - $item->imp_comision - $item->imp_tax;
			});

		$auctionsWithoutInvoice = $allLotsWithoutInvoice->groupBy('sub_asigl0');
		$activeAuctions = $auctionsWithoutInvoice->keys()->all();

		$auctionsResults = FgAsigl0::getAuctionsResultsByOwnerQuery($activeAuctions, $cod_cli)
			->get()
			->each(function ($item) use ($allLotsWithoutInvoice) {
				$item->total_liquidation = $allLotsWithoutInvoice->where('sub_asigl0', $item->sub_asigl0)->sum('imp_liquidacion');
			});

		$auctionsWithoutInvoice = $auctionsWithoutInvoice->take($maxLines);

		//Facturas
		$owerInvoicesLots = FxDvc0::getInvoicesByOwnerQuery($cod_cli)
			->whereYearsDates($yearSelected)
			->orderBy('fecha_dvc0', 'desc')
			->get();

		$invoiceAuctions = $owerInvoicesLots->pluck('sub_asigl0')->unique()->all();
		$ownerInvoices = $owerInvoicesLots->groupBy(fn ($item) => "$item->anum_dvc0-$item->num_dvc0");
		$invoiceResults = FgAsigl0::getAuctionsResultsByOwnerQuery($invoiceAuctions, $cod_cli)
			->get()
			->each(function ($item) use ($owerInvoicesLots) {
				$totalDvc0 = $owerInvoicesLots->where('sub_asigl0', $item->sub_asigl0)->value('total_dvc0');
				$item->total_liquidation = $item->total_award - $totalDvc0;
			});

		$auctionsResults = $auctionsResults->concat($invoiceResults);

		$summary = [
			'total_lots' => $auctionsResults->sum('total_lots'),
			'total_awarded_lots' => $auctionsResults->sum('total_awarded_lots'),
			'total_award' => $auctionsResults->sum('total_award'),
			'total_impsalhces' => $auctionsResults->sum('total_impsalhces'),
			'total_liquidation' => $auctionsResults->sum('total_liquidation'),
		];

		$currency = new Currency();
		$divisa = Session::get('user.currency', 'EUR');
		$currency->setDivisa($divisa);

		$data = [
			'auctionsWithoutInvoice' => $auctionsWithoutInvoice,
			'ownerInvoices' => $ownerInvoices->take($maxLines - $auctionsWithoutInvoice->count()),
			'summary' => $summary,
			'currency' => $currency
		];
		return view('front::pages.panel.summary.sales_finish', $data);
	}

	public function summaryPendingToBeAssigned()
	{
		$cod_cli = Session::get('user.cod');
		$maxLines = 3;

		$lotsQuery = FgHces1::query()
			->whereOwner($cod_cli, false)
			->notInAuction()
			->isVisibleWeb();


		$summary = $lotsQuery->clone()
			->select('count(*) as count_lots', 'nvl(sum(impsal_hces1), 0) as sum_impsalhces', 'nvl(sum(imptas_hces1), 0) as sum_imptashces')
			->get();

		$lots = $lotsQuery->clone()
			->select('num_hces1', 'lin_hces1', 'impsal_hces1', 'imptas_hces1')
			->addSelectTranslationsAttributes()
			->orderBy('num_hces1')
			->orderBy('lin_hces1')
			->limit($maxLines)
			->get();

		$currency = new Currency();
		$divisa = Session::get('user.currency', 'EUR');
		$currency->setDivisa($divisa);

		$data = [
			'lots' => $lots,
			'currency' => $currency,
			'summary' => $summary->first()
		];

		return view('front::pages.panel.summary.sales_pending', $data);
	}

	public function favoritesCarrousel()
	{
		$sub = new Subasta();
		$sub->licit = Session::get('user.cod');
		$sub->page  = 'all';

		$lots = $sub->getAllBidsAndOrders(true, true);
		$currency = new Currency();
		$divisa = Session::get('user.currency', 'EUR');
		$currency->setDivisa($divisa);

		$data = [
			'lots' => $lots,
			'currency' => $currency
		];

		return view('front::pages.panel.summary.favorites', $data);
	}
}
