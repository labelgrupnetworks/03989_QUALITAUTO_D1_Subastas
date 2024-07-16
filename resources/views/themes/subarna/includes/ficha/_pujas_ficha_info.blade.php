@php
    $isNotFinish = $cerrado_N && !empty($lote_actual->close_at) && strtotime($lote_actual->close_at) > getdate()[0];

    $closeDate = $lote_actual->start_session;
    if ($isNotFinish && $subasta_online) {
        $closeDate = $lote_actual->close_at;
    }

    $closeDateFormat = Tools::getParseDateFormat($closeDate, 'd/m/Y');
    $nowDate = getdate()[0];
    $difference = strtotime($closeDate) - $nowDate;

	$currencySimbol = trans("$theme-app.subastas.euros");
@endphp


<div class="ficha-info-block">
    <div class="ficha-dates_close-date">
        <span>Fecha de cierre</span>
        <span>{{ $closeDateFormat }}</span>
    </div>
    <div class="ficha-dates_countdown">
        @if ($isNotFinish && $subasta_online)
            <span class="timer" data-countdownficha="{{ $difference }}"
                data-format="{{ Tools::down_timer($closeDate) }}">
            </span>
        @else
            <span class="timer" data-countdown="{{ $difference }}" data-format="{{ Tools::down_timer($closeDate) }}"
                data-closed="{{ $lote_actual->cerrado_asigl0 }}">
            </span>
        @endif
    </div>
</div>

<div class="ficha-info-block">
    <div class="ficha-info_init-price">
        @if (config('app.estimacion'))
            <span>{{ trans("$theme-app.subastas.estimate") }}</span>
            <span>
				{{ "$lote_actual->formatted_imptas_asigl0 - $lote_actual->formatted_imptash_asigl0 $currencySimbol" }}
            </span>
		@elseif(config('app.impsalhces_asigl0'))
		<p>
			<span>{{ trans("$theme-app.lot.lot-price") }}</span>
			<span>
				{{ "$lote_actual->formatted_impsalhces_asigl0{$currencySimbol}" }}
			</span>
		</p>
		<p>
			<span>{{ trans("$theme-app.subastas.estimate") }}</span>
            <span>
				{{ "$lote_actual->formatted_imptash_asigl0{$currencySimbol}" }}
            </span>
		</p>
        @endif
    </div>

    <div class="ficha-info_actual-bid">
		<p id="text_actual_max_bid" @class(['hidden' => count($lote_actual->pujas) == 0])>
			<span>{{ trans("$theme-app.lot.puja_actual") }}</span>
            <span id="actual_max_bid">
				{{ "$lote_actual->formatted_actual_bid{$currencySimbol}" }}
            </span>
		</p>
		<p id="text_actual_no_bid" @class(['hidden' => count($lote_actual->pujas) > 0])>
			<span>{{ trans("$theme-app.lot_list.no_bids") }}</span>
		</p>
	</div>
</div>
