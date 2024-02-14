{{-- Lotes --}}
@php
$url_friendly = str_slug($lot->titulo_hces1);
$url_friendly = \Routing::translateSeo('lote') . $lot->cod_sub . '-' . str_slug($lot->name) . '-' . $lot->id_auc_sessions . '/' . $lot->ref_asigl0 . '-' . $lot->num_hces1 . '-' . $url_friendly;
$precio_remapte = $lot->himp_csub;
@endphp

@if ($loop->first)
    {{-- Cabeceras --}}
    <div class="custom-head-wrapper flex">
        <div class="table-data-check flex hidden">

        </div>
        <div class="img-data-customs flex "></div>
        <div class="lot-data-custon">
            <p>{{ trans($theme . '-app.user_panel.lot') }}</p>
        </div>
        <div class="name-data-custom">
            <p style="font-weight: 900">{{ trans($theme . '-app.lot.description') }}</p>
        </div>
        <div class="remat-data-custom">
            <p>{{ trans($theme . '-app.user_panel.price') }}</p>
        </div>
    </div>
@endif


<div class="custom-wrapper valign">

    <div class="img-dat img-data-customs flex valign" role="button" id="" style="grid-area: image;">
        <img loading="lazy" class="img-responsive" src="/img/load/lote_medium/{{ $lot->imagen }}">
    </div>

    <div class="account-lot-wrapper font-data-custom" style="grid-area: lot;">
        <p><span class="visible-expand">{{ trans($theme . '-app.lot.lot-name') }}</span>
            {{ $lot->ref_asigl1 }}</p>
    </div>

    <div class="description" style="grid-area: description;">
        {!! $lot->desc_hces1 !!}
    </div>

    <div class="d-flex align-items-center justify-content-space-between buttons-price-wrapper"
        style="grid-area: price;">
        <div>
            <p class="visible-expand" style="font-weight: 900">
                {{ trans($theme . '-app.user_panel.price') }}</p>
            <p class="font-data-custom">
                {{ \Tools::moneyFormat($precio_remapte, false, 2) }}
                {{ trans($theme . '-app.lot.eur') }}
                &nbsp;|&nbsp;<span value="{{ $precio_remapte }}" class="js-divisa"></span>
            </p>
        </div>

        @if (!$isPayed)
            <a
                class="btn btn-color btn-puja-panel btn-disabled d-flex align-items-center justify-content-center">{{ trans($theme . '-app.user_panel.high_quality_photography') }}</a>
            <a
                class="btn btn-color btn-puja-panel btn-disabled d-flex align-items-center justify-content-center">{{ trans($theme . '-app.user_panel.certificate') }}</a>
        @else
            <a href="/img/load/real/{{ $lot->imagen }}" download="{{ $lot->ref_asigl0 }}_{{ $lot->name }}"
                alt="{{ $lot->titulo_hces1 }}"
                class="btn btn-color btn-puja-panel btn-gold d-flex align-items-center justify-content-center">{{ trans($theme . '-app.user_panel.high_quality_photography') }}</a>
            <a data-codsub="{{ $lot->cod_sub }}" data-ref="{{ $lot->ref_asigl0 }}"
                class="btn btn-color btn-puja-panel btn-blue d-flex align-items-center justify-content-center js-btn-certificate">{{ trans($theme . '-app.user_panel.certificate') }}</a>
        @endif
    </div>

    <div class="slick-arrow" style="grid-area: arrow;">
        <p>←</p>
    </div>

</div>


@if ($loop->last)
    {{-- Facturas PDF --}}
    <div class="text-right factura-buttons">
        @if (!$isPayed)

            @if (!empty($lot->prefactura))
                <a href="/prefactura/{{ $codSub }}" download
                    class="btn btn-color factura-button mb-1">{{ trans($theme . '-app.user_panel.proforma_invoice') }}</a>
            @endif

            @if ($auction->compraweb_sub == 'S')
                <input id="shipping_express" type="radio" name="shipping" value="express" checked="checked"
                    style="display: none">
                <span id="total_pagar_{{ $codSub }}" class='hidden precio_final_{{ $codSub }}'></span>
                <a class="btn btn-color btn-gold mb-1" data-toggle="modal" data-target="#largeModal"
                    data-codsub="{{ $codSub }}"
                    data-concept="{{ explode('-', $name)[0] }}-{{ \Session::get('user.cod') }}">{{ trans("$theme-app.user_panel.bank_transfer") }}</a>

                <a class="btn btn-color btn-blue mb-1"
                    href="{{ route('panel.allotment.sub', ['cod_sub' => $codSub, 'lang' => Config::get('app.locale')]) }}"
                    cod_sub="{{ $codSub }}">{{ trans($theme . '-app.user_panel.pay_now') }}</a>
            @endif
        @else
            <a class="btn btn-color btn-blue mb-1 js-btn-shipment" data-afral_csub="{{ $lot->afral_csub }}"
                data-nfral_csub="{{ $lot->nfral_csub }}" cod_sub="{{ $codSub }}" data-toggle="modal"
                data-target="#modal_shipment">
                {{ trans($theme . '-app.user_panel.shipment_tracking') }}
            </a>

            @if (!empty($lot->factura))
                <a href="/factura/{{ $lot->afral_csub . '-' . $lot->nfral_csub }}" download
                    class="btn btn-color factura-button mb-1">{{ trans($theme . '-app.user_panel.invoice_pdf') }}</a>
            @endif

        @endif
    </div>
@endif
