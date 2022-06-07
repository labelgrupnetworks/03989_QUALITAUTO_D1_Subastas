@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@section('content')

<div class="register-landing-banner">
	{!! \BannerLib::bannersPorKey('landing-login-image', 'register-landing-banner-img', ['arrows' => false, 'dots' => false, 'autoplay' => false]) !!}
</div>

    <div class="container container-register-landig">

        <div class="row">

            <div class="col-xs-12">
                <h1>{{ trans("$theme-app.login_register.modality_register") }}</h1>
                <p>{!! trans("$theme-app.login_register.info_modality_register") !!}</p>
            </div>

		</div>

		<div class="wrapper d-flex justify-content-space-evently flex-wrap my-1">
			<div class="">
				<a href="{{ \Routing::slug('register') }}" class="btn btn-default btn-client">
					<div>
						<img src="/themes/{{ $theme }}/assets/img/pre_registro/icono_pre_registro.png" alt="">
						<p class="login-landing-button-text">{{ trans("$theme-app.login_register.buyer_registration") }}</p>
					</div>
				</a>
			</div>
			<div class="">
				<a href="{{ \Routing::slug('register') . '?transferor' }}" class="btn btn-default btn-vendor">
					<div>
						<img src="/themes/{{ $theme }}/assets/img/pre_registro/icono_pre_registro.png" alt="">
						<p class="login-landing-button-text">{{ trans("$theme-app.login_register.seller_registration") }}</p>
					</div>
				</a>
			</div>

		</div>

    </div>

@stop
