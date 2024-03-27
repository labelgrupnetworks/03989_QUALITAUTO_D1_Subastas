@php
    $locale = Config::get('app.locale');

    /***
	@todo
	- [] se debé cambiar la acción del botón por un formulario

	*/

@endphp

@push('scripts')
	<script src="https://www.google.com/recaptcha/api.js?render={{config('app.captcha_v3_public')}}"></script>
@endpush

<div class="container newsletter js-newletter-block">
    <div class="form-block">
        <div class="form-floating floating-center">
			<input class="form-control newsletter-input" type="email">
			<label>
				{{ trans("$theme-app.foot.newsletter_title") }}
			</label>

            <input id="lang-newsletter" type="hidden" value="{{ $locale }}">
            <input class="newsletter" name="families" type="hidden" value="1">
            {{-- <button class="btn-custom btn" id="newsletter-btn"
                type="button">{{ trans($theme . '-app.foot.newsletter_button') }}</button> --}}
        </div>

        <div class="form-check">
            <input class="form-check-input" id="condiciones" name="condiciones" type="checkbox" type="checkbox">
            <label class="form-check-label" for="condiciones">{!! trans($theme . '-app.login_register.read_conditions') !!}
                <a href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.term_condition') }}">
                    ({{ trans($theme . '-app.login_register.more_info') }})</a>
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" id="comercial" name="comercial" type="checkbox" type="checkbox">
            <label class="form-check-label"
                for="comercial">{{ trans($theme . '-app.login_register.recibir_newsletter') }}</label>
        </div>
    </div>


    <ul class="redes">
        <li>
            <a href="{{ config('app.facebook') }}" title="Facebook"><i class="fa fa-facebook"></i></a>
        </li>
        <li>
            <a href="{{ config('app.twitter') }}" title="Twitter">
                @include('components.x-icon', ['size' => '42'])
            </a>
        </li>
        <li>
            <a href="{{ config('app.instagram') }}" title="Instagram"><i class="fa fa-instagram"></i></a>
        </li>
        <li>
            <a href="<?= Config::get('app.linkedin') ?>" title="Linkedin"><i class="fa fa-linkedin"></i></a>
        </li>
    </ul>
</div>
