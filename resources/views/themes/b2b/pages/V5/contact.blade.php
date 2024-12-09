@extends('layouts.default')

@section('title')
{{ trans($theme.'-app.foot.faq') }}
@stop

@section('content')

@php
$bread[] = array("name" => trans($theme.'-app.foot.contact') );
@endphp

<main>
<div class="container">
	@include('includes.breadcrumb')

	<h1>{{trans($theme.'-app.foot.contact') }}</h1>
</div>

<div class="container">

	<div class="row gy-3">
		<div class="col-md-7 contact-page-form">

			<form name="contactForm" id="contactForm" novalidate>
				@csrf

				<input type="hidden" data-sitekey="{{ config('app.captcha_v3_public') }}" name="captcha_token" value="">

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

				<p class="captcha-terms">
					{!! trans("$theme-app.global.captcha-terms") !!}
				</p>

				<button type="submit" class="btn btn-lb-primary">Enviar</a>

			</form>
		</div>

		<div class="col-md-5">
			{!! $data['content'] !!}
		</div>
	</div>

</div>

</main>

@stop
