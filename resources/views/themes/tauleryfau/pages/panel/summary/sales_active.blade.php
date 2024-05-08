@php
    //'revaluation' =>
    /* ($statistics['auction']->sum('actual_price') /
                Tools::numberClamp($statistics['auction']->sum('starting_price'), 1)) *
            100, */
@endphp

<section class="summary-active-sales">
    <div class="sales-summary">
        <div class="sales-summary_detail">
            <span class="js-divisa sales-counter" id="actualPrice" value="{{ $summary['total_award'] }}">
                0
            </span>
            <p>{{ trans("$theme-app.user_panel.actual_price") }}</p>
        </div>
        <div class="sales-summary_detail">
            <div class="number-wrapper">
                <span class="sales-counter" id="percentage_lots_bid"
                    value="{{ ($summary['total_lots'] / max($summary['total_bids_lots'], 1)) * 100 }}">
                    0
                </span>
                <span>%</span>
            </div>
            <p>{{ trans("$theme-app.user_panel.bid") }}</p>
        </div>
        <div class="sales-summary_detail">
            <div class="number-wrapper">
                <span class="sales-counter" id="revaluation"
                    value="{{ ($summary['total_award'] / max($summary['total_impsalhces'], 1)) * 100 }}">
                    0
                </span>
                <span>%</span>
            </div>
            <p>{{ trans("$theme-app.user_panel.revaluation") }}</p>
        </div>
    </div>

    <div class="sales-auctions-block table-hover">

        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">{{ trans("$theme-app.user_panel.date") }}</th>
                    <th scope="col">{{ trans("$theme-app.user_panel.auction") }}</th>
                    <th scope="col">{{ trans("$theme-app.user_panel.lots") }}</th>
                    <th scope="col">{{ trans("$theme-app.user_panel.starting_price") }}</th>
                    <th scope="col">{{ trans("$theme-app.user_panel.actual_price") }}</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($auctions as $auction)
                    <tr>
                        <td>
							{{ date('d/m/Y', strtotime($auction['start'])) }}
						</td>
                        <td>
							<p class="max-line-2">
								{{ $auction['des_sub'] }}
							</p>
						</td>
                        <td>{{ $auction['total_lots'] }}</td>
                        <td>
							<p class="js-divisa" value="{{ $auction['total_impsalhces'] }}">
								{!! $currency->getPriceSymbol(2, $auction['total_impsalhces']) !!}
							</p>
						</td>
						<td>
							<p class="js-divisa" value="{{ $auction['total_award'] }}">
								{!! $currency->getPriceSymbol(2, $auction['total_award']) !!}
							</p>
						</td>
                        <td>
							<a href="{{ route('panel.sales', ['lang' => config('app.locale')]) . "#auction-details-{$auction['sub_asigl0']}" }}">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
				@empty
					<tr>
						<td colspan="6" class="text-center">{{ trans("$theme-app.user_panel.no_sales") }}</td>
					</tr>
                @endforelse
            </tbody>
        </table>
    </div>

</section>

<script>
   salesAnimationCounter();
</script>
