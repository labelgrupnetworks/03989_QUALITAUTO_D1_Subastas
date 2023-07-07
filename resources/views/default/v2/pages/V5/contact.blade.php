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
	<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d5993.038773666047!2d2.033268!3d41.31931800000001!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x41a45e5c3be4fca8!2sLabelgrup%20Networks!5e0!3m2!1ses!2ses!4v1663759278691!5m2!1ses!2ses"
		width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</section>

<script>
	$('#button-map').click( function () {

            if($(this).hasClass('active')){
                $('.maps-house-auction').animate({left: '100%'}, 300)
                $(this)
                    .removeClass('active')
                    .find('i').addClass('fa-map-marker-alt').removeClass('fa-times')
                }else{
                    $('.maps-house-auction').animate({left: 0}, 300)
                    $(this)
                        .addClass('active')
                        .find('i').removeClass('fa-map-marker-alt').addClass('fa-times')
            }

        })
</script>

@stop
