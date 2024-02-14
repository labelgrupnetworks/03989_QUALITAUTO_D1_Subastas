@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')
<?php
$lang = App::getLocale();

?>

<section class="principal-bar no-principal services-page">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
            <div class="princiapl-bar-wrapper">
                    <div class="principal-bar-title">
                        <h3>{{ trans($theme.'-app.services.title') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>

<section class="services-page">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                    <div class="content-services-list-container pt-4">
                <div class="content-services-list mb-4">
                    <a href="{{ \Routing::translateSeo('valoracion-articulos') }}" class="d-block position-relative">
                        <div class="color-white text-services position-absolute d-flex align-items-center "><?= trans($theme.'-app.services.tasation') ?></div>
                    <img class="img-responsive" src="/themes/{{$theme}}/img/servicios/manos.png" />
                    </a>
                </div>
                <div class="content-services-list mb-4">
                        <a href="{{ \Routing::translateSeo('servicios/fotografias') }}" class="d-block position-relative">
                            <div class="text-right color-white text-services position-absolute d-flex align-items-center justify-content-end"><?= trans($theme.'-app.services.photo_and_collect') ?></div>
                            <img class="img-responsive" src="/themes/{{$theme}}/img/servicios/moneda.png"/>
                        </a>
                    </div>
                    <div class="content-services-list mb-4">
                            <a href="{{ \Routing::translateSeo('servicios/encapsulacion') }}" class="d-block position-relative">
                                <div class="color-tauler text-services position-absolute d-flex align-items-center"><?= trans($theme.'-app.services.encap_service') ?></div>

                            <img class="img-responsive" src="/themes/{{$theme}}/img/servicios/certs.png"/>
                            </a>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</section>

@stop
