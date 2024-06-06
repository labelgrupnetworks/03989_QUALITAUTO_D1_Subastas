<section class="summary-active-sales">

    <div class="sales-summary">
        <div class="sales-summary_detail">
            <span class="js-divisa sales-counter" id="impsalPrice" value="{{ $summary->sum_impsalhces }}">
                0
            </span>
            <p>{{ trans("$theme-app.user_panel.starting_price") }}</p>
        </div>
        <div class="sales-summary_detail">
            <div class="number-wrapper">
                <span class="js-divisa sales-counter" id="imptasPrice" value="{{ $summary->sum_imptashces }}">
                    0
                </span>
            </div>
            <p>{{ trans("$theme-app.user_panel.estimate_price") }}</p>
        </div>
        <div class="sales-summary_detail">
            <div class="number-wrapper">
                <span class="sales-counter" id="countLots" value="{{ $summary->count_lots }}">
                    0
                </span>
            </div>
            <p>{{ trans("$theme-app.user_panel.pending_lots") }}</p>
        </div>
    </div>

    <div class="sales-auctions-block">

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">{{ trans("$theme-app.user_panel.assignment") }}</th>
                    <th class="text-center text-md-start" scope="col">{{ trans("$theme-app.user_panel.line") }}</th>
                    <th style="width: 45%" scope="col">{{ trans("$theme-app.user_panel.description") }}</th>
                    <th class="hidden-xs" scope="col">{{ trans("$theme-app.user_panel.starting_price") }}</th>
                    <th scope="col">{{ trans("$theme-app.user_panel.estimated") }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($lots as $lot)
                    <tr>
                        <td>
                            <a href="{{ route('panel.sales.pending-assign', ['lang' => config('app.locale')]) }}">
                                {{ $lot->num_hces1 }}
                            </a>
                        </td>
                        <td class="text-center text-md-start">
                            <a href="{{ route('panel.sales.pending-assign', ['lang' => config('app.locale')]) }}">
                                {{ $lot->lin_hces1 }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('panel.sales.pending-assign', ['lang' => config('app.locale')]) }}">
                                <div class="max-line-2">
                                    {!! $lot->descweb_hces1 !!}
                                </div>
                            </a>
                        </td>
                        <td class="hidden-xs">
                            <a href="{{ route('panel.sales.pending-assign', ['lang' => config('app.locale')]) }}">
                                <p class="js-divisa" value="{{ $lot->impsal_hces1 }}">
                                    {!! $currency->getPriceSymbol(2, $lot->impsal_hces1) !!}
                                </p>
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('panel.sales.pending-assign', ['lang' => config('app.locale')]) }}">
                                <p class="js-divisa fw-bold" value="{{ $lot->imptas_hces1 }}">
                                    {!! $currency->getPriceSymbol(2, $lot->imptas_hces1) !!}
                                </p>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="6">
                            {{ trans("$theme-app.user_panel.no_pending_lots") }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</section>

<script>
    salesAnimationCounter();
</script>
