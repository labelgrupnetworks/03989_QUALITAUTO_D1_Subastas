@php
    $empresa = (new \App\Models\Enterprise())->getEmpre();
	$formulario = App\Http\Controllers\V5\ContactController::formContact();
@endphp
<div class="row gy-4">
    <div class="col-md-4">

		{{ $topAddress }}

        <div class="contact-company">
            <x-icon.logo />

            <div class="footer-address">
                <p>{{ $empresa->dir_emp ?? '' }}</p>
                <p>{{ $empresa->cp_emp ?? '' }} {{ $empresa->pob_emp ?? '' }},
                    {{ $empresa->pais_emp ?? '' }}
                </p>
                <p><a href="tel:{{ $empresa->tel1_emp ?? '' }}">{{ $empresa->tel1_emp ?? '' }}</a></p>
                <p><a href="mailto:{{ $empresa->email_emp ?? '' }}">{{ $empresa->email_emp ?? '' }}</a></p>
            </div>

            <div class="contact-links">
                <a href="{{ config('app.facebook') }}" title="facebook" target="_blank">
                    <x-icon.fontawesome type="brands" icon="facebook-f" />
                </a>

                <a href="{{ config('app.instagram') }}" title="instagram" target="_blank">
                    <x-icon.fontawesome type="brands" icon="square-instagram" />
                </a>

                <a href="{{ config('app.twitter') }}" title="twitter" target="_blank">
                    <x-icon.fontawesome type="brands" icon="square-x-twitter" />
                </a>
            </div>
        </div>

    </div>
    <div class="col-md-7 ms-auto contact-page-form">

		@if(!empty($topForm))
			{{ $topForm }}
		@endif

        <form id="contactForm" name="contactForm" method="post" action="javascript:sendContact()">

            {!!$formulario['_token'] !!}
            <input name="captcha_token" data-sitekey="{{ config('app.captcha_v3_public') }}" type="hidden"
                value="">

            <div class="form-floating mb-3">
                {!!$formulario['nombre'] !!}
                <label for="floatingInput">
                    {{ trans("$theme-app.login_register.contact") }}
                </label>
            </div>
            <div class="form-floating mb-3">
                {!!$formulario['email'] !!}
                <label for="floatingInput">
                    {{ trans("$theme-app.foot.newsletter_text_input") }}
                </label>
            </div>
            <div class="form-floating mb-3">
                {!!$formulario['telefono'] !!}
                <label for="floatingInput">
                    {{ trans("$theme-app.user_panel.phone") }}
                </label>
            </div>
            <div class="form-floating mb-3">
                {!!$formulario['comentario'] !!}
                <label for="floatingInput">
                    {{ trans("$theme-app.global.coment") }}
                </label>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" id="bool__1__condiciones" name="condiciones" type="checkbox"
                    value="on" autocomplete="off">
                <label class="form-check-label" for="bool__1__condiciones">
                    {!! trans("$theme-app.emails.privacy_conditions") !!}
                </label>
            </div>

            <div class="mb-3 px-1">
                <p class="captcha-terms">
                    {!! trans("$theme-app.global.captcha-terms") !!}
                </p>
            </div>

            <div>
                {!!$formulario['SUBMIT'] !!}
            </div>
        </form>

    </div>
</div>
