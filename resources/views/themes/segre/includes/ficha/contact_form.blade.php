@php
    $name = $data['usuario']->nom_cliweb ?? '';
    $phone = $data['usuario']->tel1_cli ?? '';
    $email = $data['usuario']->email_cliweb ?? '';
@endphp

<h2 class="ficha-contact-title h5 mt-5 mb-5">{{ trans("$theme-app.galery.request_information") }}</h2>

<form id="infoLotForm" name="infoLotForm" method="post" action="javascript:sendInfoLot()">
    <input name="captcha_token" data-sitekey="{{ config('app.captcha_v3_public') }}" type="hidden" value="">
    <input name="auction" type="hidden" value="{{ $lote_actual->cod_sub }} - {{ $lote_actual->des_sub }}">
    <input name="lot" type="hidden" value="{{ $lote_actual->ref_asigl0 }}">
    <input name="lot_name" type="hidden" value="{{ $lote_actual->descweb_hces1 }}">

    <div class="mb-3 pb-3">
        <label class="form-label" for="texto__1__nombre">{{ trans("$theme-app.login_register.contact") }} *</label>
        <input class="form-control form-control-p-lg" id="texto__1__nombre" name="nombre"
            type="text" value="{{ $name }}" onblur="comprueba_campo(this)" autocomplete="off">

    </div>

    <div class="mb-3 pb-3">
        <label class="form-label" for="email__1__email">{{ trans("$theme-app.foot.newsletter_text_input") }} *</label>
        <input class="form-control form-control-p-lg" id="email__1__email" name="email"
            type="text" value="{{ $email }}" onblur="comprueba_campo(this)" autocomplete="off">

    </div>

    <div class="mb-3 pb-3">
        <label class="form-label" for="texto__1__telefono">{{ trans("$theme-app.user_panel.phone") }} *</label>
        <input class="form-control form-control-p-lg" id="texto__1__telefono" name="telefono"
            type="text" value="{{ $phone }}" onblur="comprueba_campo(this)" autocomplete="off">

    </div>

    <div class="mb-3 pb-3">
        <label class="form-label" for="textogrande__1__comentario">{{ trans("$theme-app.global.coment") }} *</label>
        <textarea class="form-control form-control-p-lg" id="textogrande__1__comentario" name="comentario" rows="10">  </textarea>

    </div>

    <div class="mb-3 pb-3">
        <p>{{ trans("web.login_register.all_fields_are_required") }}</p>

        <p class="captcha-terms">
            {!! trans("$theme-app.global.captcha-terms") !!}
        </p>
    </div>

    <div class="mb-3">
        <button class="btn btn-lb-primary" type="button"
            onclick="submit_form(document.getElementById('infoLotForm'),0)">
            {{ trans("web.global.enviar") }}
        </button>
    </div>
</form>
