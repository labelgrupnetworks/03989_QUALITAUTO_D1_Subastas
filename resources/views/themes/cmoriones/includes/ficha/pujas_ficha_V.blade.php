@push('scripts')
	@if(config('app.captcha_v3'))
	<script src="https://www.google.com/recaptcha/api.js?render={{config('app.captcha_v3_public')}}"></script>
	@else
	<script src="https://www.google.com/recaptcha/api.js?hl={{ config('app.locale') }}" async defer></script>
	@endif
@endpush

@php
    $name = $data['usuario']->nom_cliweb ?? '';
    $phone = $data['usuario']->tel1_cli ?? '';
    $email = $data['usuario']->email_cliweb ?? '';
@endphp

<div class="ficha-pujas ficha-venta">
    <form id="infoLotForm" name="infoLotForm" method="post" onsubmit="sendInfoLot(event)">
        @csrf

		@if(config('app.captcha_v3'))
			<input type="hidden" data-sitekey="{{ config('app.captcha_v3_public') }}" name="captcha_token" value="">
		@endif

        <input name="auction" type="hidden" value="{{ $lote_actual->cod_sub }} - {{ $lote_actual->des_sub }}">
        <input name="lot_name" type="hidden" value="{{ $lote_actual->ref_asigl0 }} - {{ $lote_actual->descweb_hces1 }} ">

        <div class="row g-3">
            <div class="col-12">
                <label for="nombre">
                    {{ trans("$theme-app.login_register.contact") }}
                </label>
                <input class="form-control" id="texto__1__nombre" name="nombre" type="text"
                    value="{{ $name }}" placeholder="{{ trans("$theme-app.login_register.contact") }}" required
                    onblur="comprueba_campo(this)" autocomplete="off" />
            </div>

            <div class="col-12">
                <label for="email">
                    {{ trans("$theme-app.foot.newsletter_text_input") }}
                </label>
                <input class="form-control" id="email__1__email" name="email" type="email"
                    value="{{ $email }}"
                    placeholder="{{ trans("$theme-app.login_register.foot.newsletter_text_input") }}" required
                    onblur="comprueba_campo(this)" autocomplete="off" />

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

            <div class="text-center text-lg-end">
                <button class="btn btn-lb-primary btn-medium" type="submit">
                    {{ trans("$theme-app.valoracion_gratuita.send") }}
                </button>
            </div>

        </div>
    </form>
</div>
