@php
    $isRetired = $retirado || $devuelto || $fact_devuelta;

    if ($subasta_web) {
        $nameCountdown = 'countdown';
        $timeCountdown = $lote_actual->start_session;
    } elseif ($subasta_venta) {
        $nameCountdown = 'countdown';
        $timeCountdown = $lote_actual->end_session;
    } elseif ($subasta_online) {
        $nameCountdown = 'countdownficha';
        $timeCountdown = $lote_actual->close_at;
    }
@endphp


<div>
    @if ($isRetired || $sub_cerrada)
        @include('includes.ficha.pujas_ficha_cerrada')
    @elseif($subasta_venta && !$cerrado && !$end_session)
        @include('includes.ficha.pujas_ficha_V')

        {{-- si un lote cerrado no se ha vendido se podra comprar --}}
    @elseif(($subasta_web || $subasta_online) && $cerrado && empty($lote_actual->himp_csub) && $compra && !$fact_devuelta)
        @include('includes.ficha.pujas_ficha_V')
    @elseif(($subasta_online || ($subasta_web && $subasta_abierta_P)) && !$cerrado)
        @include('includes.ficha.pujas_ficha_O')
    @elseif($subasta_web && !$cerrado)
        @include('includes.ficha.pujas_ficha_W')
    @else
        @include('includes.ficha.pujas_ficha_cerrada')
    @endif
</div>

<div>
    @if (($subasta_online || ($subasta_web && $subasta_abierta_P)) && !$cerrado && !$retirado)
        @include('includes.ficha.history')
    @endif
</div>
