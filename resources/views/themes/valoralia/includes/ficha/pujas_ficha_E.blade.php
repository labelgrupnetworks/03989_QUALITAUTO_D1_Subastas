@php
    $usuario = $data['usuario'] ?? null;
@endphp

<div class="info-ficha-buy-info-price d-flex">
    <div class="pre">
        @if ($lote_actual->ocultarps_asigl0 != 'S')
            <p class="pre-title">{{ trans(\Config::get('app.theme') . '-app.lot.lot-price') }}</p>
            <p class="pre-price">{{ $lote_actual->formatted_impsalhces_asigl0 }}
                {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}
                @if (\Config::get('app.exchange'))
                    | <span id="startPriceExchange_JS" class="exchange"> </span>
                @endif
            </p>
        @endif
    </div>
</div>

@if (!Session::has('user'))
    <button type="button" class="button-principal offer-btn" onclick="userLogin()">
        {{ trans("$theme-app.lot.submit_offer_concursal") }}
    </button>
@else
    <form name="infoLotForm" id="infoLotForm">
        <input type="hidden" name="auction" value="{{ $lote_actual->cod_sub }} - {{ $lote_actual->des_sub }}">
        <input type="hidden" name="lot"
            value="{{ $lote_actual->ref_asigl0 }} - {{ $lote_actual->descweb_hces1 }} ">
        @csrf
        <div class="form-group">
            <label>{{ trans("$theme-app.lot.bidder_name_concursal") }}</label>
            <input type="text" class="form-control" name="nombre" id="texto__1__nombre"
                value="{{ $usuario->nom_cliweb }}" onblur="comprueba_campo(this)" autocomplete="off" disabled>
        </div>

        <div class="form-group">
            <label>{{ trans(\Config::get('app.theme') . '-app.foot.newsletter_text_input') }}</label>
            <input type="text" class="form-control" name="email" id="email__1__email"
                value="{{ $usuario->email_cliweb }}" onblur="comprueba_campo(this)"autocomplete="off" disabled>
        </div>

        <div class="form-group">
            <label>{{ trans(\Config::get('app.theme') . '-app.user_panel.phone') }}</label>
            <input type="tel" class="form-control" name="telefono" id="texto__0__telefono"
                value="{{ $usuario->tel1_cli }}" onblur="comprueba_campo(this)" autocomplete="off">
        </div>

        <div class="form-group">
            <label>{{ trans(\Config::get('app.theme') . '-app.global.coment') }}</label>
            <textarea class="form-control" name="comentario" id="textogrande__0__comentario" rows="5"></textarea>
        </div>

        <div class="form-group">
            <label>{{ trans("$theme-app.lot.price_offer") }}</label>
            <input type="number" class="form-control" name="user_price" id="number__1__price"
                onblur="comprueba_campo(this)" autocomplete="off">
        </div>

        <div class="checkbox">
            <label>
                <input type="checkbox" name="condiciones" value="on" id="bool__1__condiciones"
                    autocomplete="off"> <span>{!! trans("$theme-app.emails.privacy_conditions") !!}</span>
            </label>
        </div>
        <button type="submit" class="button-principal offer-btn"> {{ trans("$theme-app.lot.submit_offer_concursal") }}</button>
    </form>
@endif
