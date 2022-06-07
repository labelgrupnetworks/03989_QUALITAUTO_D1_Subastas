@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.foot.faq') }}
@stop

@section('content')
    <?php
    $bread[] = ['name' => trans(\Config::get('app.theme') . '-app.foot.contact')];
    ?>



    <script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>
	<script>
		ga('send','event','VISITA SECCIONES','Contacto');
	</script>

    <div class="contact-banner">
        {!! BannerLib::bannersPorKey('banner-contacto', 'banner-contacto', ['dots' => false, 'arrows' => false, 'autoplay' => true]) !!}
    </div>

	<div class="contact-return-button">
		@include('includes.breadcrumb')
	</div>

    <div class="container contact-page">
		<div class="pl-3">
            {{-- <h1>{{trans(\Config::get('app.theme').'-app.foot.contact') }}</h1> --}}
            <h1>{{ trans(\Config::get('app.theme') . '-app.global.contact_2') }}</h1>
			<div><b><br></b></div>
            <p>{{ trans(\Config::get('app.theme') . '-app.global.contact_3') }}</p>

            <h2></h2>

            <div class="row">
                <div class="col-xs-12 col-md-8 contact-page-form">
                    <form name="contactForm" id="contactForm" method="post" action="javascript:sendContactCarlandia()">

                        {!! $data['formulario']['_token'] !!}
                        <div class="form-group row">
                            <div class="input-effect col-xs-12">
                                {!! $data['formulario']['nombre'] !!}
                                <label>{{ trans(\Config::get('app.theme') . '-app.global.nombre') }}</label>
                            </div>

                            <div class="input-effect col-xs-12">
                                {!! $data['formulario']['email'] !!}
                                <label>{{ trans(\Config::get('app.theme') . '-app.foot.newsletter_text_input') }}</label>
                            </div>

                            <div class="input-effect col-xs-12">
                                {!! FormLib::Text('telefono', 0) !!}
                                {{-- {!! $data['formulario']['telefono'] !!} --}}
                                <label
                                    class="not-required">{{ trans(\Config::get('app.theme') . '-app.user_panel.phone') }}</label>
                            </div>

                            <div class="input-effect col-xs-12">
                                {!! $data['formulario']['comentario'] !!}
                                <label>{{ trans(\Config::get('app.theme') . '-app.global.coment') }}</label>
                            </div>

                            <div class="col-xs-12">
                                <div class="check_term">

                                    <input type="checkbox" class="newsletter" name="condiciones" value="on"
                                        id="bool__1__condiciones" autocomplete="off">

                                    <label
                                        for="accept_new"><?= trans(\Config::get('app.theme') . '-app.emails.privacy_conditions') ?></label>

                                </div>
                            </div>

                            <div class="col-xs-12 mt-3">
                                <div class="row">
                                    <div class="g-recaptcha col-xs-6"
                                        data-sitekey="{{ \Config::get('app.codRecaptchaEmailPublico') }}"
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

                </div>

                <div class="col-xs-12 col-md-3 contact-image-wrapper">
                    <img class="img-responsive" src="/themes/{{ $theme }}/assets/img/logo_volante_trans.png"
                        alt="{{ \Config::get('app.name') }}">
                </div>
            </div>

			<div class="mt-2 pl-2 pr-2">
				<a class="button-principal" href="{{ route('allCategories') }}">{{ trans("$theme-app.home.buscar") }}</a>
			</div>

        </div>



    </div>


    <script>
        $('#button-map').click(function() {

            if ($(this).hasClass('active')) {
                $('.maps-house-auction').animate({
                    left: '100%'
                }, 300)
                $(this)
                    .removeClass('active')
                    .find('i').addClass('fa-map-marker-alt').removeClass('fa-times')
            } else {
                $('.maps-house-auction').animate({
                    left: 0
                }, 300)
                $(this)
                    .addClass('active')
                    .find('i').removeClass('fa-map-marker-alt').addClass('fa-times')
            }

        })
    </script>

@stop
