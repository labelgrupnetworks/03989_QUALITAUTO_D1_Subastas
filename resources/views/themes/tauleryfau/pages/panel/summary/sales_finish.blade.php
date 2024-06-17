@php
    $auctionNumber = fn($text, $codSub) => preg_match('/\b\d+\b/', $text, $matches) ? $matches[0] : $codSub;
@endphp

<section class="summary-active-sales">
    <div class="sales-summary">
        <div class="sales-summary_detail">
            <span class="js-divisa sales-counter" id="actualPrice" data-format="0,0"
                value="{{ $summary['total_liquidation'] }}">
                0
            </span>
            <p>{{ trans("$theme-app.user_panel.amount_sold") }}</p>
        </div>
        <div class="sales-summary_detail">
            <div class="number-wrapper">
                <span class="sales-counter" id="percentage_lots_bid"
                    value="{{ ($summary['total_awarded_lots'] / max($summary['total_lots'], 1)) * 100 }}">
                    0
                </span>
                <span>%</span>
            </div>
            <p>{{ trans("$theme-app.user_panel.awarded") }}</p>
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
        <div class="sales-summary_detail sales-summary_detail_lots">
            <span class="sales-counter" id="consignedLots" value="{{ $summary['total_lots'] }}">
                0
            </span>
            <p>{{ trans("$theme-app.user_panel.consigned_lots") }}</p>
        </div>
    </div>

    <div class="sales-auctions-block">

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">{{ trans("$theme-app.user_panel.date") }}</th>
                    <th class="text-center text-md-start" scope="col">{{ trans("$theme-app.user_panel.auction") }}
                    </th>
                    <th class="hidden-xs" scope="col">{{ trans("$theme-app.user_panel.no_invoice") }}</th>
                    <th scope="col">{{ trans("$theme-app.user_panel.total") }}</th>
                    <th scope="col">{{ trans("$theme-app.user_panel.status") }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($auctionsWithoutInvoice as $auction)
                    @php
                        $auctionData = $auction->first();
                        $totalSettlement = $auction->sum('imp_liquidacion');
                        $state = ['class' => 'alert', 'text' => 'Provisional'];
						$link = route('panel.sales.finish', ['lang' => config('app.locale')]) . "#auction-details-{$auctionData->sub_asigl0}";
                    @endphp
                    <tr>
                        <td>
                            <a
                                href="{{ $link }}">
                                {{ date('d/m/Y', strtotime($auctionData->end)) }}
                            </a>
                        </td>
                        <td>
                            <a
								href="{{ $link }}">
                                <p class="max-line-2 text-center text-md-start">
                                    <span class="visible-md visible-lg">{{ $auctionData->des_sub }}</span>
                                    <span class="hidden-md hidden-lg">
                                        {{ $auctionNumber($auctionData->des_sub, $auctionData->sub_asigl0) }}
                                    </span>
                                </p>
                            </a>
                        </td>
                        <td class="hidden-xs">
                            <a href="{{ $link }}">
                                -
                            </a>
                        </td>
                        <td>
                            <a href="{{ $link }}">
                                <p class="js-divisa fw-bold" value="{{ $totalSettlement }}">
                                    {!! $currency->getPriceSymbol(2, $totalSettlement) !!}
                                </p>
                            </a>
                        </td>
                        <td>
                            <a href="{{ $link }}">
                                <span class="badge badge-{{ $state['class'] }}">{{ $state['text'] }}</span>
                            </a>
                        </td>
						<td class="hidden-xs">
							<a href="{{ $link }}">
								<img src="/themes/{{ $theme }}/assets/icons/eye-regular.svg" alt="go to" style="display: block"
									width="20.25">
							</a>
						</td>
                    </tr>
                @endforeach

                @foreach ($ownerInvoices as $invoiceId => $invoices)
                    @php
                        $auctionData = $invoices->first();
                        $totalSettlement = $invoices->sum('implic_hces1') - $auctionData->total_dvc0;
                        $totalPending = $invoices->sum('imp_pending');
						$link = route('panel.sales.finish', ['lang' => config('app.locale')]) . "#auction-details-{$invoiceId}";
                        $state = match (true) {
                            $totalPending != 0 => ['class' => 'warning', 'text' => 'En curso'],
                            default => ['class' => 'success', 'text' => 'Pagado'],
                        };
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ $link }}">
                                {{ date('d/m/Y', strtotime($auctionData->fecha_dvc0)) }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ $link }}">
                                <p class="max-line-2 text-center text-md-start">
                                    <span class="visible-md visible-lg">{{ $auctionData->des_sub }}</span>
                                    <span class="hidden-md hidden-lg">
                                        {{ $auctionNumber($auctionData->des_sub, $auctionData->sub_asigl0) }}
                                    </span>
                                </p>
                            </a>
                        </td>
                        <td class="hidden-xs">
                            <a href="{{ $link }}">
                                {{ str_replace('-', '/', $invoiceId) }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ $link }}">
                                <span class="js-divisa fw-bold" value="{{ $totalSettlement }}">
                                    {!! $currency->getPriceSymbol(2, $totalSettlement) !!}
                                </span>
                            </a>
                        </td>
                        <td>
                            <a href="{{ $link }}">
                                <span class="badge badge-{{ $state['class'] }}">{{ $state['text'] }}</span>
                            </a>
                        </td>
						<td class="hidden-xs">
							<a href="{{ $link }}">
								<img src="/themes/{{ $theme }}/assets/icons/eye-regular.svg" alt="go to" style="display: block"
									width="20.25">
							</a>
						</td>
                    </tr>
                @endforeach

                @if ($auctionsWithoutInvoice->isEmpty() && $ownerInvoices->isEmpty())
                    <tr>
                        <td class="text-center" colspan="6">{{ trans("$theme-app.user_panel.no_sales") }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

</section>

<script>
    salesAnimationCounter();
</script>
