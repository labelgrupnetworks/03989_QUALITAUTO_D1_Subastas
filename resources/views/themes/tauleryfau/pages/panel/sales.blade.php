@extends('layouts.panel')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@php
    use App\Models\SubastaTiempoReal;
	use App\libs\Currency;
    $currency = new Currency();
    $divisa = Session::get('user.currency', 'EUR');
    $divisas = $currency->setDivisa($divisa)->getAllCurrencies();

    $activeAuctions = $subastas->filter(function ($lotes) {
        return true; //ahora mismo para mostrar todas y tener datos de prueba

        $firtsLot = $lotes->first();
        $sessionReference = $firtsLot->reference;

        //esta comprobación se hace en varios sitios, valorar encapsular en una clase
        $subasta = new SubastaTiempoReal();
        $subasta->cod = $firtsLot->cod_sub;
        $subasta->session_reference = $sessionReference;
        $status = $subasta->getStatus();

        return $firtsLot->subc_sub == 'S' && $status != 'ended';
    });

    //devemos mostrar las subastas activas y que el tiempo real no haya finalizado.
    //una vez finalizadas las pasamos a la vista de subastas finalizadas
    $statistics = [];
    $statistics['auction'] = collect([]);

    foreach ($activeAuctions as $cod_sub => $lotes) {
        $statistics['auction']->put($cod_sub, [
            'actual_price' => $lotes->sum(function ($lote) {
                return $lote->implic_hces1 ?? $lote->impsalhces_asigl0;
            }),
            'consigned_lots' => $lotes->count(),
            'count_lots_with_bids' => $lotes
                ->filter(function ($lote) {
                    return $lote->bids > 0;
                })
                ->count(),
            'estimate_price' => $lotes->sum('imptas_asigl0'),
            'starting_price' => $lotes->sum('impsalhces_asigl0'),
        ]);
    }

    $statistics['total'] = [
        'actual_price' => $statistics['auction']->sum('actual_price'),
        'bid_lots' => $statistics['auction']->sum('count_lots_with_bids'),
        'consigned_lots' => $statistics['auction']->sum('consigned_lots'),
        'percentage_lots_bid' =>
            ($statistics['auction']->sum('count_lots_with_bids') /
                Tools::numberClamp($statistics['auction']->sum('consigned_lots'), 1)) *
            100,
        'revaluation' =>
            ($statistics['auction']->sum('actual_price') /
                Tools::numberClamp($statistics['auction']->sum('starting_price'), 1)) *
            100,
        'start_price' => $statistics['auction']->sum('starting_price'),
    ];
@endphp

@section('content')

    <script>
        var currency = @JSON($divisas);
        var divisa = @JSON($divisa);
        var replaceZeroDecimals = true;
        const statistics = @JSON($statistics);
    </script>

    <section class="sales-page">
        <div class="sticky-section">
            <div class="panel-title">
                <h1>{{ trans("$theme-app.user_panel.my_assignments") }}</h1>

                <select id="actual_currency">
                    @foreach ($divisas as $divisaOption)
                        <option value='{{ $divisaOption->cod_div }}' @selected($divisaOption->cod_div == $divisa)>
                            {{ $divisaOption->cod_div }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="sales-menu">
                <a class="btn btn-lb btn-lb-outline btn-large" href="{{ route('panel.sales.pending-assign', ['lang' => config('app.locale')]) }}">
                    <span class="visible-md visible-lg">{{ trans("$theme-app.user_panel.pending_auction") }}</span>
                    <span class="hidden-md hidden-lg">{{ trans("$theme-app.user_panel.pendings") }}</span>
                </a>
                <a class="btn btn-lb btn-lb-primary btn-large" href="{{ route('panel.sales', ['lang' => config('app.locale')]) }}">
                    <span class="visible-md visible-lg">{{ trans("$theme-app.user_panel.active_auctions") }}</span>
                    <span class="hidden-md hidden-lg">{{ trans("$theme-app.user_panel.active") }}</span>
                </a>
                <a class="btn btn-lb btn-lb-outline btn-large" href="{{ route('panel.sales.finish', ['lang' => config('app.locale')]) }}">
                    <span class="visible-md visible-lg">{{ trans("$theme-app.user_panel.auctions_completed") }}</span>
                    <span class="hidden-md hidden-lg">{{ trans("$theme-app.user_panel.finished") }}</span>
                </a>
            </div>

            <div class="sales-summary">
                <div class="sales-summary_detail">
                    <span class="js-divisa sales-counter" id="actualPrice"
                        value="{{ $statistics['total']['actual_price'] }}">
                        0
                    </span>
                    <p>{{ trans("$theme-app.user_panel.actual_price") }}</p>
                </div>
                <div class="sales-summary_detail">
                    <div class="number-wrapper">
                        <span class="sales-counter" id="percentage_lots_bid"
                            value="{{ $statistics['total']['percentage_lots_bid'] }}">
                            0
                        </span>
                        <span>%</span>
                    </div>
                    <p>{{ trans("$theme-app.user_panel.bid") }}</p>
                </div>
                <div class="sales-summary_detail">
                    <div class="number-wrapper">
                        <span class="sales-counter" id="revaluation" value="{{ $statistics['total']['revaluation'] }}">
                            0
                        </span>
                        <span>%</span>
                    </div>
                    <p>{{ trans("$theme-app.user_panel.revaluation") }}</p>
                </div>
                <div class="sales-summary_detail sales-summary_detail_lots">
                    <span class="sales-counter" id="consigned_lots" value="{{ $statistics['total']['consigned_lots'] }}">
                        0
                    </span>
                    <p>{{ trans("$theme-app.user_panel.consigned_lots") }}</p>
                </div>
                <div class="sales-summary_detail sales-summary_detail_lots">
                    <span class="sales-counter" id="bid_lots" value="{{ $statistics['total']['bid_lots'] }}">0</span>
                    <p>{{ trans("$theme-app.user_panel.bid_lots") }}</p>
                </div>
            </div>
        </div>

        <div class="sales-auctions-block">

			<div class="sales-auctions sales-active-auctions">

				<div class="sales-header-wrapper">
					<div class="table-grid_header sales-auctions_header">
						<p>{{ trans("$theme-app.user_panel.date") }}</p>
						<p>{{ trans("$theme-app.user_panel.auction") }}</p>
						<p>
							<span class="visible-md visible-lg">{{ trans("$theme-app.user_panel.no") }} {{ trans("$theme-app.user_panel.lots") }}</span>
							<span class="hidden-md hidden-lg">{{ trans("$theme-app.user_panel.lots") }}</span>
						</p>
						<p class="visible-md visible-lg">Total {{ trans("$theme-app.user_panel.starting_price") }}</p>
						<p class="visible-md visible-lg">Total {{ trans("$theme-app.user_panel.estimated") }}</p>
						<p class="sales-auctions_actual-price">
							<span class="visible-md visible-lg">Total {{ trans("$theme-app.user_panel.actual_price") }}</span>
							<span class="hidden-md hidden-lg">Total {{ trans("$theme-app.user_panel.actual_price_min") }}</span>
						</p>
					</div>
				</div>

				@foreach ($subastas as $cod_sub => $auctions)
					@include('pages.panel.sales.auction_active', [
						'auctions' => $auctions,
						'auctionStatistics' => $statistics['auction'][$cod_sub],
					])
				@endforeach

			</div>
        </div>

		<section class="tab-content" id="auction-details">

			@foreach ($subastas as $cod_sub => $lots)
				@include('pages.panel.sales.auction_details', [
					'id' => $cod_sub,
					'title' => $lots->first()->des_sub,
					'lotes' => $lots,
				])
			@endforeach

		</section>

    </section>

	<script>
		$(document).ready(function () {
			if (window.location.hash) {
				const hash = window.location.hash;
				$(`[href="${hash}"]`).trigger('click');
			}
		});
	</script>
@stop
