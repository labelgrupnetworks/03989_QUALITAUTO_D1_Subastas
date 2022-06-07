@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.foot.faq') }}
@stop

@section('content')
<?php
$bread[] = array("name" => trans(\Config::get('app.theme') . '-app.foot.contact'));
?>


<link href="/themes/papaya/css/page/contact.css" rel="stylesheet" type="text/css" />
<script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>
<section class="all-aution-title title-content pb-1">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 h1-titl text-center">
				<h1 class="page-title mb-3">{{trans(\Config::get('app.theme').'-app.foot.contact') }}</h1>
			</div>
		</div>
	</div>
</section>

<div class="container mt-3">

	@if(!empty($data['content']))
	{!! $data['content'] !!}
	@endif



	<div class="row">
		<div class="col-xs-12 col-md-7 contact-page-form pl-0">
			<form name="contactForm" id="contactForm" method="post" action="javascript:sendContact()">

				{!! $data['formulario']['_token'] !!}
				<div class="form-group">
					<div class="input-effect col-xs-12">
						{!! $data['formulario']['nombre'] !!}
						<label>{{trans(\Config::get('app.theme').'-app.login_register.contact') }}</label>
					</div>

					<div class="input-effect col-xs-12">
						{!! $data['formulario']['email'] !!}
						<label>{{trans(\Config::get('app.theme').'-app.foot.newsletter_text_input') }}</label>
					</div>

					<div class="input-effect col-xs-12">
						{!! $data['formulario']['telefono'] !!}
						<label>{{trans(\Config::get('app.theme').'-app.user_panel.phone') }}</label>
					</div>

					<div class="input-effect col-xs-12">
						{!! $data['formulario']['comentario'] !!}
						<label>{{trans(\Config::get('app.theme').'-app.global.coment') }}</label>
					</div>
					<div class="col-xs-12">
						<label for="accept_new" class="accept_new">
							<input name="accept_news" required type="checkbox" class="form-control" id="accept_new" />
							<strong><span
									style="color:black;font-size: 12px;margin-top: 6px;">{{ trans(\Config::get('app.theme').'-app.emails.accept_news') }}</span></strong>
						</label>
					</div>
					<div class="col-xs-12">
						<label for="condiciones" class="condicines">
							<input name="condiciones" required type="checkbox" class="form-control"
								id="accept_new_condiciones" />
							<strong><span class="link-condiciones"
									style="color:black;font-size: 12px;margin-top: 6px;"><?= trans(\Config::get('app.theme') . '-app.emails.privacy_conditions') ?></span></strong>
						</label>
					</div>

					<br>

					<div class="g-recaptcha col-xs-12" data-sitekey="{{\Config::get('app.codRecaptchaEmailPublico')}}"
						data-callback="onSubmit">
					</div>
					<div class="col-xs-12 contact-submit">
						<br>
						{!! $data['formulario']['SUBMIT'] !!}
					</div>

				</div>
				<div class="clearfix"></div>



			</form>

			<br><br>

		</div>

		<div class="col-xs-12 col-md-5 mb-3">

			<?php /*  MAPA */ ?>
			<div class="map_contact">
				<iframe
					src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3035.535483366877!2d-3.6947489491948953!3d40.46341706062172!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd42291aadf95d6b%3A0x6a15b6c27dee09e6!2sCalle%20del%20Poeta%20Joan%20Maragall%2C%2051%2C%2028020%20Madrid!5e0!3m2!1ses!2ses!4v1574095868481!5m2!1ses!2ses"
					frameborder="0" allowfullscreen=""></iframe>

			</div>


		</div>
	</div>

</div>

<script>
	$('#button-map').click(function () {

    if ($(this).hasClass('active')) {
        $('.maps-house-auction').animate({left: '100%'}, 300)
        $(this)
                .removeClass('active')
                .find('i').addClass('fa-map-marker-alt').removeClass('fa-times')
    } else {
        $('.maps-house-auction').animate({left: 0}, 300)
        $(this)
                .addClass('active')
                .find('i').removeClass('fa-map-marker-alt').addClass('fa-times')
    }

})
</script>

@stop
