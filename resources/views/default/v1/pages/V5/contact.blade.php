@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.foot.faq') }}
@stop

@section('content')
<?php
$bread[] = array("name" => trans(\Config::get('app.theme').'-app.foot.contact') );
?>



<script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>

<div class="container">

	<h1>{{trans(\Config::get('app.theme').'-app.foot.contact') }}</h1>

	<div class="row">
		<div class="col-xs-12 col-md-7 contact-page-form">
			<form name="contactForm" id="contactForm" method="post" action="javascript:sendContact()">

				{!! $data['formulario']['_token'] !!}
				<div class="form-group row">
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

					<div class="check_term col-xs-12">
						<div class="row">
							<div class="col-xs-2 col-md-1">
								<input type="checkbox" class="newsletter" name="condiciones" value="on"
									id="bool__1__condiciones" autocomplete="off">
							</div>
							<div class="col-xs-10 col-md-11">
								<label
									for="accept_new"><?= trans(\Config::get('app.theme') . '-app.emails.privacy_conditions') ?></label>
							</div>
						</div>
					</div>

					<div class="col-xs-12 mt-3">
						<div class="row">
							<div class="g-recaptcha col-xs-6"
								data-sitekey="{{\Config::get('app.codRecaptchaEmailPublico')}}"
								data-callback="onSubmit">
							</div>
						</div>
					</div>

					<div class="col-xs-12 mt-3">
						<div class="row">
							<div class="col-xs-6">
								{!! $data['formulario']['SUBMIT'] !!}
							</div>
						</div>
					</div>




				</div>
				<div class="clearfix"></div>



			</form>

			<br><br><br><br><br><br>

		</div>

		<div class="col-xs-12 col-md-5">

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
