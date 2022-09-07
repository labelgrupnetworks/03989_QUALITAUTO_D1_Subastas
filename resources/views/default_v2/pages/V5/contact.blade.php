@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.foot.faq') }}
@stop

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>
@endpush

@section('content')

@php
$bread[] = array("name" => trans(\Config::get('app.theme').'-app.foot.contact') );
@endphp

<div class="container">

	@include('includes.breadcrumb')

	<h1>{{trans(\Config::get('app.theme').'-app.foot.contact') }}</h1>


	<div class="row">
		<div class="col-md-7 contact-page-form">

			<form name="contactForm" id="contactForm" novalidate>
				@csrf
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

				<div class="mb-3">
					<div class="g-recaptcha" data-sitekey="{{\Config::get('app.codRecaptchaEmailPublico')}}" data-callback="onSubmit"></div>
				</div>

				<button type="submit" class="btn btn-lb-primary">Enviar</a>

			</form>


		</div>

		<div class="col-md-5">
			{!! $data['content'] !!}
		</div>
	</div>

</div>

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
