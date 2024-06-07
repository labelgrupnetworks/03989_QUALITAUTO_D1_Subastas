@extends('layouts.panel')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@php
	$statistics = [];
	$statistics['auction'] = $auctions->keyBy('sub_asigl0');
	$statistics['total'] = $summary;
@endphp

@section('content')

    <script>
        var currency = @JSON($divisas);
        var divisa = @JSON($divisa);
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
                <a class="btn btn-lb btn-lb-primary btn-large" href="{{ route('panel.sales.active', ['lang' => config('app.locale')]) }}">
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
                        value="{{ $summary['total_award'] }}">
                        0
                    </span>
                    <p>{{ trans("$theme-app.user_panel.actual_price") }}</p>
                </div>
                <div class="sales-summary_detail">
                    <div class="number-wrapper">
                        <span class="sales-counter" id="percentage_lots_bid"
                            value="{{ $summary['percentage_lots_with_bid'] }}">
                            0
                        </span>
                        <span>%</span>
                    </div>
                    <p>{{ trans("$theme-app.user_panel.bid") }}</p>
                </div>
                <div class="sales-summary_detail">
                    <div class="number-wrapper">
                        <span class="sales-counter" id="revaluation" value="{{ $summary['revaluation'] }}">
                            0
                        </span>
                        <span>%</span>
                    </div>
                    <p>{{ trans("$theme-app.user_panel.revaluation") }}</p>
                </div>
                <div class="sales-summary_detail sales-summary_detail_lots">
                    <span class="sales-counter" id="consigned_lots" value="{{ $summary['total_lots'] }}">
                        0
                    </span>
                    <p>{{ trans("$theme-app.user_panel.consigned_lots") }}</p>
                </div>
                <div class="sales-summary_detail sales-summary_detail_lots">
                    <span class="sales-counter" id="bid_lots" value="{{ $summary['total_bids_lots'] }}">0</span>
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

				@forelse ($auctions as $auction)
					@include('pages.panel.sales.auction_active', [
						'auctions' => $auction,
					])
				@empty
				<div class="sales-auction-wrapper empty-auction">
					<div class="sales-auction">
						<p>{{ trans("$theme-app.user_panel.no_sales") }}</p>
					</div>
				</div>
				@endforelse

			</div>
        </div>

		<section class="tab-content" id="auction-details">

			@foreach ($auctions as $auction)
				@include('pages.panel.sales.auction_details', [
					'id' => $auction['sub_asigl0'],
					'title' => $auction['des_sub'],
					'lots' => $lots[$auction['sub_asigl0']],
					'invoice' => false
				])
			@endforeach

		</section>

    </section>
@stop
