<section class="summary-active-sales">

	<div class="sales-summary">
		<div class="sales-summary_detail">
			<span class="js-divisa sales-counter" id="impsalPrice" value="{{ $summary->sum_impsalhces }}">
				0
			</span>
			<p>Precio de salida</p>
		</div>
		<div class="sales-summary_detail">
			<div class="number-wrapper">
				<span class="js-divisa sales-counter" id="imptasPrice" value="{{ $summary->sum_imptashces }}">
					0
				</span>
			</div>
			<p>Precio de estimación</p>
		</div>
		<div class="sales-summary_detail">
			<div class="number-wrapper">
				<span class="sales-counter" id="countLots" value="{{ $summary->count_lots }}">
					0
				</span>
			</div>
			<p>Lotes pendientes</p>
		</div>
	</div>

    <div class="sales-auctions-block table-hover">

        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Cesión</th>
                    <th scope="col">Línea</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Precio Salida</th>
                    <th scope="col">Estimado</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
				@forelse ($lots as $lot)
                    <tr>
                        <td>
							{{ $lot->num_hces1 }}
                        </td>
                        <td>
							{{ $lot->lin_hces1 }}
                        </td>
                        <td>
                            <p class="max-line-2">
                                {!! $lot->descweb_hces1 !!}
                            </p>
                        </td>
                        <td>
							<p class="js-divisa" value="{{ $lot->impsal_hces1 }}">
								{!! $currency->getPriceSymbol(2, $lot->impsal_hces1) !!}
							</p>
                        </td>
                        <td>
							<p class="js-divisa" value="{{ $lot->imptas_hces1 }}">
								{!! $currency->getPriceSymbol(2, $lot->imptas_hces1) !!}
							</p>
                        </td>
                        <td>
                            <a
                                href="{{ route('panel.sales.pending-assign', ['lang' => config('app.locale')]) }}">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
					<tr>
						<td colspan="6" class="text-center">
							No hay lotes pendientes
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
