@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@section('content')

@section('framework-css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/bootstrap/5.2.0/css/bootstrap.min.css') }}">
@endsection

@section('framework-js')
    <script src="{{ URL::asset('vendor/bootstrap/5.2.0/js/bootstrap.bundle.min.js') }}"></script>
@endsection

@section('custom-css')
    <link href="{{ Tools::urlAssetsCache('/themes/' . $theme . '/css/global.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Tools::urlAssetsCache("/themes/$theme/css/style.css") }}" rel="stylesheet" type="text/css">
    <link href="{{ Tools::urlAssetsCache('/themes/' . $theme . '/css/header.css') }}" rel="stylesheet" type="text/css">
@endsection

<main class="valuation-page pt-0">
	@php
		$bannerTitle = "<h1>" . trans("$theme-app.home.free-valuations") . "</h1>";
		$bannerSubtitle = "<h2>" . trans("$theme-app.valoracion_gratuita.banner_text") . "</h2>";
	@endphp

    {!! BannerLib::bannerWithView('tasaciones-principal', 'fluid', ['title' => "<div class='slider-title'>{$bannerTitle}{$bannerSubtitle}</div>"], ['autoplay' => true]) !!}

    <section class="valuation-form container pb-5">
        <h3 class="ff-highlight valuation-title">{!! trans("$theme-app.valoracion_gratuita.desc_assessment") !!}</h3>

        <form id="form-valoracion-adv" class="form">
            @csrf

            <div class="row g-3">
                <p class="text-danger valoracion-h4 hidden msg_valoracion">
                    {{ trans("$theme-app.valoracion_gratuita.error") }}
                </p>
                <div class="col-md-3">
                    <div class="form-floating">
                        <input class="form-control" id="name" name="name"
                            placeholder="{{ trans("$theme-app.valoracion_gratuita.name") }}" required=""
                            type="text" />
                        <label for="name">
                            {{ trans("$theme-app.valoracion_gratuita.name") }}
                        </label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <input class="form-control" id="email" name="email"
                            placeholder="{{ trans("$theme-app.valoracion_gratuita.email") }}" required=""
                            type="email" />
                        <label for="email">
                            {{ trans("$theme-app.valoracion_gratuita.email") }}
                        </label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <input class="form-control" id="telf" name="telf"
                            placeholder="{{ trans("$theme-app.valoracion_gratuita.telf") }}" required=""
                            type="tel" />
                        <label for="telf">
                            {{ trans("$theme-app.valoracion_gratuita.telf") }}
                        </label>
                    </div>
                </div>
                <div class="col-md-3">
                    {{-- <label for="categoria">
						{{ trans("$theme-app.valoracion_gratuita.category") }}
					</label> --}}
                    <select class="form-select h-100" name="categoria" id="categoria">
                        <option value="artes_decorativas" selected="">
                            {{ trans(\Config::get('app.theme') . '-app.valoracion_gratuita.artes_decorativas') }}
                        </option>
                        <option value="joyas">
                            {{ trans(\Config::get('app.theme') . '-app.valoracion_gratuita.joyas') }}</option>
                        <option value="muebles">
                            {{ trans(\Config::get('app.theme') . '-app.valoracion_gratuita.muebles') }}
                        </option>
                        <option value="pintura_antigua">
                            {{ trans(\Config::get('app.theme') . '-app.valoracion_gratuita.pintura_antigua') }}
                        </option>
                        <option value="pintura">
                            {{ trans(\Config::get('app.theme') . '-app.valoracion_gratuita.pintura') }}
                        </option>
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <textarea class="form-control" id="descripcion" name="descripcion"
                            placeholder="{{ trans("$theme-app.user_panel.description") }}" required="" rows="10" type="phone"></textarea>
                        <label for="descripcion">
                            {{ trans("$theme-app.user_panel.description") }}
                        </label>
                    </div>
                </div>
                <div class="col-md-6 position-relative">
                    <div id="dropzone" class="h-100">
                        <small class="text-danger error-dropzone"
                            style="display:none">{{ trans("$theme-app.msg_error.max_size") }}</small>
                        <div class="color-letter text-dropzone">
                            {!! trans("$theme-app.valoracion_gratuita.adj_IMG") !!}
                        </div>
                        <div class="mini-file-content d-flex align-items-center" style="position:relative"></div>
                        <input id="images" type="file" name="imagen[]" />
                    </div>
                </div>

                <div class="col-md-6" style="font-size: 18px">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="condiciones" value="on"
                            id="bool__1__condiciones" autocomplete="off">
                        <label class="form-check-label" for="bool__1__condiciones">
                            {!! trans("$theme-app.emails.privacy_conditions") !!}
                        </label>
                    </div>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <button type="submit" id="valoracion-adv"
                        class="btn btn-lb-primary btn-medium">{{ trans("$theme-app.valoracion_gratuita.send") }}</button>
                </div>

            </div>

        </form>
    </section>
</main>

<script>
    var imagesarr = [];

    function myFunction(el) {
        $(el).remove()
    }
    $(function() {

        $('.mini-upload-image').click(function() {
            alert()
        })


    });
</script>
@stop
