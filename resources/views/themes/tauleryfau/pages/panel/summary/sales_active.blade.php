@php
    $auctionNumber = fn($text, $codSub) => preg_match('/\b\d+\b/', $text, $matches) ? $matches[0] : $codSub;
@endphp

<section class="summary-active-sales">
    <div class="sales-summary">
        <div class="sales-summary_detail">
            <span class="js-divisa sales-counter" id="actualPrice" data-format="0,0" value="{{ $summary['total_award'] }}">
                0
            </span>
            <p>{{ trans("$theme-app.user_panel.actual_price") }}</p>
        </div>
        <div class="sales-summary_detail">
            <div class="number-wrapper">
                <span class="sales-counter" id="percentage_lots_bid"
                    value="{{ ($summary['total_bids_lots'] / max($summary['total_lots'], 1)) * 100 }}">
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

    <div class="sales-auctions-block">

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">{{ trans("$theme-app.user_panel.date") }}</th>
                    <th class="text-center text-md-start" scope="col">{{ trans("$theme-app.user_panel.auction") }}
                    </th>
                    <th scope="col">{{ trans("$theme-app.user_panel.lots") }}</th>
                    <th class="visible-md visible-lg" scope="col">
                        {{ trans("$theme-app.user_panel.starting_price") }}
                    </th>
                    <th scope="col">{{ trans("$theme-app.user_panel.actual_price") }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($auctions as $auction)
                    <tr>
                        <td>
                            <a
                                href="{{ route('panel.sales.active', ['lang' => config('app.locale')]) . "#auction-details-{$auction['sub_asigl0']}" }}">
                                {{ date('d/m/Y', strtotime($auction['start'])) }}
                            </a>
                        </td>
                        <td>
                            <a
                                href="{{ route('panel.sales.active', ['lang' => config('app.locale')]) . "#auction-details-{$auction['sub_asigl0']}" }}">
                                <p class="max-line-2 text-center text-md-start">
                                    <span class="visible-md visible-lg">{{ $auction['des_sub'] }}</span>
                                    <span class="hidden-md hidden-lg">
                                        {{ $auctionNumber($auction['des_sub'], $auction['sub_asigl0']) }}
                                    </span>
                                </p>
                            </a>
                        </td>
                        <td>
                            <a
                                href="{{ route('panel.sales.active', ['lang' => config('app.locale')]) . "#auction-details-{$auction['sub_asigl0']}" }}">
                                {{ $auction['total_lots'] }}
                            </a>
                        </td>
                        <td class="visible-md visible-lg">
                            <a
                                href="{{ route('panel.sales.active', ['lang' => config('app.locale')]) . "#auction-details-{$auction['sub_asigl0']}" }}">
                                <p class="js-divisa" value="{{ $auction['total_impsalhces'] }}">
                                    {!! $currency->getPriceSymbol(2, $auction['total_impsalhces']) !!}
                                </p>
                            </a>
                        </td>
                        <td>
                            <a
                                href="{{ route('panel.sales.active', ['lang' => config('app.locale')]) . "#auction-details-{$auction['sub_asigl0']}" }}">
                                <p class="js-divisa fw-bold" value="{{ $auction['total_award'] }}">
                                    {!! $currency->getPriceSymbol(2, $auction['total_award']) !!}
                                </p>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="6">{{ trans("$theme-app.user_panel.no_sales") }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</section>

<script>
    salesAnimationCounter();
</script>
