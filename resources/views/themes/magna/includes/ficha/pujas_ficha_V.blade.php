@php
    $importe = \Tools::moneyFormat($lote_actual->actual_bid);
    $importeExchange = $lote_actual->actual_bid;
    if (!empty($lote_actual->impres_asigl0) && $lote_actual->impres_asigl0 > $lote_actual->impsalhces_asigl0) {
        $importe = \Tools::moneyFormat($lote_actual->impres_asigl0);
        $importeExchange = $lote_actual->impres_asigl0;
    }

    $name = $data['usuario']->nom_cliweb ?? '';
    $phone = $data['usuario']->tel1_cli ?? '';
    $email = $data['usuario']->email_cliweb ?? '';
@endphp

<div class="ficha-pujas ficha-venta">

    {{-- Precio venta --}}
    <h4 class="price sold-price mb-4">
        <span>{{ trans("$theme-app.subastas.price_sale") }}</span>
        <span>
            {{ $importe }} {{ trans("$theme-app.subastas.euros") }}
            @if (\Config::get('app.exchange'))
                | <span class="exchange" id="directSaleExchange_JS"> </span>
                <input id="startPriceDirectSale" type="hidden" value="{{ $importeExchange }}">
            @endif
        </span>
    </h4>

    {{-- Packengers --}}
    @if (config('app.urlToPackengers'))
        @php
            $lotFotURL = $lote_actual->cod_sub . '-' . $lote_actual->ref_asigl0;
            $urlCompletePackengers = \Config::get('app.urlToPackengers') . $lotFotURL;
        @endphp

        <div>
            <a class="d-block btn btn-outline-lb-secondary" href="{{ $urlCompletePackengers }}" target="_blank">
                <svg class="bi" width="16" height="16" fill="currentColor">
                    <use xlink:href="/bootstrap-icons.svg#truck"></use>
                </svg>
                {{ trans("$theme-app.lot.packengers_ficha") }}
            </a>
        </div>
    @endif

    <div class="my-3">
        <form id="infoLotForm" name="infoLotForm" method="post" onsubmit="sendInfoLot(event)">
            @csrf

            <input name="captcha_token" data-sitekey="{{ config('app.captcha_v3_public') }}" type="hidden"
                value="">

            <input name="auction" type="hidden" value="{{ $lote_actual->cod_sub }} - {{ $lote_actual->des_sub }}">
            <input name="lot_name" type="hidden"
                value="{{ $lote_actual->ref_asigl0 }} - {{ $lote_actual->descweb_hces1 }} ">

            <div class="row g-3">
                <div class="col-12">
                    <label for="nombre">
                        {{ trans("$theme-app.login_register.contact") }}
                    </label>
                    <input class="form-control" id="texto__1__nombre" name="nombre" type="text"
                        value="{{ $name }}" placeholder="{{ trans("$theme-app.login_register.contact") }}"
                        required onblur="comprueba_campo(this)" autocomplete="off" />
                </div>

                <div class="col-12">
                    <label for="email">
                        {{ trans("$theme-app.foot.newsletter_text_input") }}
                    </label>
                    <input class="form-control" id="email__1__email" name="email" type="email"
                        value="{{ $email }}" placeholder="{{ trans("$theme-app.foot.newsletter_text_input") }}"
                        required onblur="comprueba_campo(this)" autocomplete="off" />

                </div>

                <div class="col-12">
                    <label for="telefono">
                        {{ trans("$theme-app.user_panel.phone") }}
                    </label>
                    <input class="form-control" id="texto__1__telefono" name="telefono" type="tel"
                        value="{{ $phone }}" placeholder="{{ trans("$theme-app.user_panel.phone") }}" required
                        onblur="comprueba_campo(this)" autocomplete="off" />
                </div>

                <div class="col-12">
                    <label for="comentario">
                        {{ trans("$theme-app.global.coment") }}
                    </label>
                    <textarea class="form-control" id="textogrande__0__comentario" name="comentario" rows="10"></textarea>
                </div>

                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" id="bool__1__condiciones" name="condiciones" type="checkbox"
                            value="on" autocomplete="off">
                        <label class="form-check-label" for="bool__1__condiciones">
                            {!! trans("$theme-app.emails.privacy_conditions") !!}
                        </label>
                    </div>
                </div>

                <p class="captcha-terms">
                    {!! trans("$theme-app.global.captcha-terms") !!}
                </p>

                <button class="btn btn-lb-primary btn-medium w-100" type="submit">
                    {{ trans("$theme-app.valoracion_gratuita.send") }}
                </button>

            </div>
        </form>
    </div>

</div>
