<div class="ficha-info pb-3">
    <div class="d-flex justify-content-between flex-wrap">

        <p>{{ trans("$theme-app.lot.auction_date") }}</p>
        <p>{{ Tools::getDateFormat($lote_actual->start_session, 'Y-m-d H:i:s', 'd-m-Y H:i') }}
            {{ trans("$theme-app.lot_list.time_zone") }}</p>
    </div>

    @if (!$cerrado)
        <p class="text-end" class="timer fw-normal"
            data-countdown="{{ strtotime($lote_actual->start_session) - getdate()[0] }}"
            data-format="{{ Tools::down_timer($lote_actual->start_session) }}" data-closed="0"></p>
    @else
        <div class="px-3 py-2 float-end bg-lb-color-backgorund-light d-flex alig-items-center gap-3"
            style="margin-right: -1rem">
            <img class="mb-1" src="/themes/{{ $theme }}/assets/icons/hammer.svg" alt="hammer">
            <p class="ficha-info-clock">{{ trans(\Config::get('app.theme') . '-app.subastas.finalized') }}</p>
        </div>
    @endif
</div>
