@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.foot.faq') }}
@stop

@push('scripts')
	@if(config('app.captcha_v3'))
	<script src="https://www.google.com/recaptcha/api.js?render={{config('app.captcha_v3_public')}}"></script>
	@else
	<script src="https://www.google.com/recaptcha/api.js?hl={{ config('app.locale') }}" async defer></script>
	@endif
@endpush

@section('content')

@php
$bread[] = array("name" => trans(\Config::get('app.theme').'-app.foot.contact') );
@endphp

<main>
<div class="container">
	@include('includes.breadcrumb')

	<h1>{{trans(\Config::get('app.theme').'-app.foot.contact') }}</h1>
</div>

<div class="container">

	<div class="row gy-3">
		<div class="col-md-7 contact-page-form">

			<form name="contactForm" id="contactForm" novalidate>
				@csrf
				@if(config('app.captcha_v3'))
					<input type="hidden" data-sitekey="{{ config('app.captcha_v3_public') }}" name="captcha_token" value="">
				@endif

				<div class="mb-3">
					<label for="texto__1__nombre" class="form-label">{{ trans("$theme-app.login_register.contact") }}</label>
					{!! $data['formulario']['nombre'] !!}
				</div>
				<div class="mb-3">
					<label for="email__1__email" class="form-label">{{ trans("$theme-app.foot.newsletter_text_input") }}</label>
					{!! $data['formulario']['email'] !!}
				</div>
				<div class="mb-3">
					<label for="texto__1__telefono" class="form-label">{{ trans("$theme-app.user_panel.phone") }}</label>
					{!! $data['formulario']['telefono'] !!}
				</div>
				<div class="mb-3">
					<label for="textogrande__1__comentario" class="form-label">{{ trans("$theme-app.global.coment") }}</label>
					{!! $data['formulario']['comentario'] !!}
				</div>

				<div class="form-check mb-3">
					<input class="form-check-input" type="checkbox" value="" id="bool__1__condiciones" name="condiciones" value="on" autocomplete="off" required>
					<label class="form-check-label" for="bool__1__condiciones">
					  {!! trans("$theme-app.emails.privacy_conditions") !!}
					</label>
				</div>

				@if(!config('app.captcha_v3'))
				<div class="mb-3">
					<div class="g-recaptcha" data-sitekey="{{\Config::get('app.codRecaptchaEmailPublico')}}" data-callback="onSubmit"></div>
				</div>
				@endif

				<button type="submit" class="btn btn-lb-primary">Enviar</a>

			</form>
		</div>

		<div class="col-md-5">
			{!! $data['content'] !!}
		</div>
	</div>

</div>

<section class="container-fluid p-0 py-5 map-contact">
	<iframe src="https://maps.google.com/maps?q=Coolab%20Coworking%20Space%20Calle%20Impresores%2C%2020.%20P.E.%20Prado%20del%20Espino%2028660%20-%20Boadilla%20del%20Monte%2C%20Madrid&t=m&z=17&output=embed&iwloc=near"
		width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</section>
</main>

@stop
