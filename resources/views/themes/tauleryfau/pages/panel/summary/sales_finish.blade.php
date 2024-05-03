<section class="summary-active-sales">
    <div class="sales-summary">
        <div class="sales-summary_detail">
            <span class="js-divisa sales-counter" id="actualPrice" value="{{ $summary['total_liquidation'] }}">
                0
            </span>
            <p>{{ trans("$theme-app.user_panel.total_liquidation") }}</p>
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

    <div class="sales-auctions-block table-hover">

        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">{{ trans("$theme-app.user_panel.date") }}</th>
                    <th scope="col">{{ trans("$theme-app.user_panel.auction") }}</th>
                    <th scope="col">{{ trans("$theme-app.user_panel.no_invoice") }}</th>
                    <th scope="col">{{ trans("$theme-app.user_panel.total_liquidation") }}</th>
                    <th scope="col">{{ trans("$theme-app.user_panel.status") }}</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($auctionsWithoutInvoice as $auction)
                    @php
                        $auctionData = $auction->first();
                        $totalSettlement = $auction->sum('imp_liquidacion');
                        $state = ['class' => 'alert', 'text' => 'Provisional'];
                    @endphp
                    <tr>
                        <td>
                            {{ date('d/m/Y', strtotime($auctionData->end)) }}
                        </td>
                        <td>
                            <p class="max-line-2">
                                {{ $auctionData->des_sub }}
                            </p>
                        </td>
                        <td>-</td>
                        <td>
							<p class="js-divisa" value="{{ $totalSettlement }}">
								{!! $currency->getPriceSymbol(2, $totalSettlement) !!}
							</p>
                        </td>
                        <td>
                            <span class="badge badge-{{ $state['class'] }}">{{ $state['text'] }}</span>
                        </td>
                        <td>
                            <a
                                href="{{ route('panel.sales.finish', ['lang' => config('app.locale')]) . "#auction-details-{$auctionData->sub_asigl0}" }}">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach

                @foreach ($ownerInvoices as $invoiceId => $invoices)
                    @php
                        $auctionData = $invoices->first();
                        $totalSettlement = $invoices->sum('implic_hces1') - $auctionData->total_dvc0;
                        $totalPending = $invoices->sum('imp_pending');
                        $state = match (true) {
                            $totalPending != 0 => ['class' => 'warning', 'text' => 'En curso'],
                            default => ['class' => 'success', 'text' => 'Pagado'],
                        };
                    @endphp
					<tr>
                        <td>
                            {{ date('d/m/Y', strtotime($auctionData->fecha_dvc0)) }}
                        </td>
                        <td>
                            <p class="max-line-2">
                                {{ $auctionData->des_sub }}
                            </p>
                        </td>
                        <td>
							{{ str_replace('-', '/', $invoiceId) }}
						</td>
                        <td class="js-divisa" value="{{ $totalSettlement }}">
                            {!! $currency->getPriceSymbol(2, $totalSettlement) !!}
                        </td>
                        <td>
                            <span class="badge badge-{{ $state['class'] }}">{{ $state['text'] }}</span>
                        </td>
                        <td>
                            <a
                                href="{{ route('panel.sales.finish', ['lang' => config('app.locale')]) . "#auction-details-{$invoiceId}" }}">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach

				@if($auctionsWithoutInvoice->isEmpty() && $ownerInvoices->isEmpty())
					<tr>
						<td colspan="6" class="text-center">{{ trans("$theme-app.user_panel.no_sales") }}</td>
					</tr>
				@endif
            </tbody>
        </table>
    </div>

</section>

<script>
    salesAnimationCounter();
</script>
