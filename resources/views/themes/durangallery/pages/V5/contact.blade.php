@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.foot.faq') }}
@stop

@section('content')
    <?php
    $bread[] = ['name' => trans($theme . '-app.foot.contact')];
    ?>

	<div class="container">
		<div class="row">
			<div class="col-xs-12 contact-data text-center">
				<h1 class="">{{ trans($theme . '-app.foot.contact') }}</h1>
			</div>
		</div>
	</div>

	<div class="contact-banner-wrapper mb-5">
        {!! \BannerLib::bannersPorKey('contact-text-image', 'contact-banner', ['arrows' => false, 'dots' => false]) !!}
    </div>

    <div class="container{{-- -fluid --}} contact-page">

        <div class="row">
            <div class="col-xs-12 col-md-8 contact-page-form contact-form">

                <h1 class="title-30">{{ trans("$theme-app.global.contact_us") }}</h1>
                <h2 class="title-40 bold mt-2">{{ trans("$theme-app.global.help_you") }}</h2>


                <form name="contactForm" id="contactForm" method="post" action="javascript:sendContact()">
                    {!! $data['formulario']['_token'] !!}
					<input type="hidden" data-sitekey="{{ config('app.captcha_v3_public') }}" name="captcha_token" value="">


                    <div class="form-group row">
                        <div class="input-effect col-xs-12 col-md-9">
                            {!! $data['formulario']['nombre'] !!}
                            <label>{{ trans($theme . '-app.login_register.contact') }}</label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="input-effect col-xs-12 col-md-9">
                            {!! $data['formulario']['email'] !!}
                            <label>{{ trans($theme . '-app.foot.newsletter_text_input') }}</label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="input-effect col-xs-12 col-md-9">
                            {!! $data['formulario']['telefono'] !!}
                            <label>{{ trans($theme . '-app.user_panel.phone') }}</label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="input-effect col-xs-12">
                            {!! $data['formulario']['comentario'] !!}
                            <label>{{ trans($theme . '-app.global.coment') }}</label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-xs-1">
                            <input type="checkbox" class="newsletter" name="condiciones" value="on"
                                id="bool__1__condiciones" autocomplete="off">

                        </div>
                        <div class="col-xs-11">
                            <label for="accept_new">{!! trans("$theme-app.emails.privacy_conditions") !!}</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
							<span class="captcha-terms">
								{!! trans("$theme-app.global.captcha-terms") !!}
							</span>
						</div>
                    </div>

                    <div class="form-group row">
                        <div class="input-effect col-xs-12">
                            {!! $data['formulario']['SUBMIT'] !!}
                        </div>
                    </div>

                </form>

            </div>

            <div class="col-xs-12 col-md-4">
                <div class="contact-data">

                    <h1 class="title-80">{{ trans($theme . '-app.foot.contact') }}</h1>
                    <h2 class="title-30">{{ trans("$theme-app.login_register.direccion") }}</h2>
                    <p>{!! trans("$theme-app.global.gallery_address") !!}</p>

                    <h2 class="title-30">{{ trans("$theme-app.login_register.email") }}</h2>
                    <p>{!! trans("$theme-app.emails.durangallery_mail") !!}</p>

                    <h2 class="title-30">{{ trans("$theme-app.login_register.phone") }}</h2>
                    <p>{!! trans("$theme-app.global.gallery_phone_numbers") !!}</p>
                </div>
            </div>
        </div>
    </div>

@stop
