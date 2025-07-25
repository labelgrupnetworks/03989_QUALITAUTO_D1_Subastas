@php
    $esSubastaCerradaNoHistorica = $sub_cerrada && !$sub_historica;
    $esLoteComprable =
        ($subasta_web || $subasta_online || $subasta_inversa) &&
        $cerrado &&
        empty($lote_actual->himp_csub) &&
        $compra &&
        !$fact_devuelta;
@endphp

@if ($esSubastaCerradaNoHistorica || $esLoteComprable)
    <section class="ficha-contact-form">
		@include('includes.ficha.contact_form')
    </section>
@endif
