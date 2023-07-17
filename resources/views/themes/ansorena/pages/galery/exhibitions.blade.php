@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.galery.exhibitions') }}
@stop

@section('framework-css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/bootstrap/5.2.0/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('vendor/swiper/swiper-bundle.min.css') }}" />
@endsection

@section('framework-js')
    <script src="{{ URL::asset('vendor/bootstrap/5.2.0/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ URL::asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
@endsection

@section('custom-css')
    <link href="{{ Tools::urlAssetsCache('/themes/' . $theme . '/css/global.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Tools::urlAssetsCache("/themes/$theme/css/style.css") }}" rel="stylesheet" type="text/css">
    <link href="{{ Tools::urlAssetsCache('/themes/' . $theme . '/css/header.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')
    @include('includes.galery.subnav')

    <main class="exhibitions-page">
        <div class="container-fluid">
            <h1 class="page-title">
                {{ request('online') === 'S' ? trans("$theme-app.galery.online_exhibitions") : trans("$theme-app.galery.exhibitions") }}
            </h1>

            <h2 class="page-subtitle">
                {{ request('online') === 'S' ? trans("$theme-app.galery.texto_online_exhibitions") : trans("$theme-app.galery.texto_exhibitions") }}
            </h2>

            @include('content.galery.exhibitions');
        </div>

    </main>
@stop
