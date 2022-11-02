<div class="ficha-info pb-3">
    <div class="d-flex justify-content-between flex-wrap">

        <p>{{ trans("$theme-app.lot.auction_date") }}</p>
        <p>{{ Tools::getDateFormat($lote_actual->start_session, 'Y-m-d H:i:s', 'd-m-Y H:i') }}
            {{ trans("$theme-app.lot_list.time_zone") }}</p>
    </div>
    @if ($cerrado)
        <div class="px-3 py-2 float-end bg-lb-color-backgorund-light d-flex alig-items-center gap-3">
			<img class="mb-1" src="/themes/{{$theme}}/assets/icons/hammer.svg" alt="hammer">
            <p class="ficha-info-clock">{{ trans(\Config::get('app.theme') . '-app.subastas.finalized') }}</p>
        </div>
    @endif
</div>
