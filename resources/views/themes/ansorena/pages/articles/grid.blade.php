@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@section('framework-css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/bootstrap/5.2.0/css/bootstrap.min.css') }}">
@endsection

@section('framework-js')
    <script src="{{ URL::asset('vendor/bootstrap/5.2.0/js/bootstrap.bundle.min.js') }}"></script>
@endsection

@section('custom-css')
    <link href="{{ Tools::urlAssetsCache("/themes/$theme/css//global.css") }}" rel="stylesheet" type="text/css">
    <link href="{{ Tools::urlAssetsCache("/themes/$theme/css/style.css") }}" rel="stylesheet" type="text/css">
    <link href="{{ Tools::urlAssetsCache("/themes/$theme/css/header.css") }}" rel="stylesheet" type="text/css">
@endsection

@section('content')

    <main class="articles-grid-page">

        <h1 class="ff-highlight fs-24-40 text-uppercase">
            @if (!empty($title))
                {{ $title }}
            @else
                {{ trans("$theme-app.foot.joyeria") }}
            @endif
        </h1>

        <div id="grid" data-ort-sec="{{ $ortsec ?? '' }}" data-sec="{{ $sec ?? '' }}"
            data-familia="{{ $familia ?? '' }}">
        </div>
    </main>

	@include('includes.whatsapp_button')

    <script>
        //Página indicada en la url mediante variable get, se utiliza para la carga inicial de react
        var language = "{{ config('app.locale') }}";

        //Url inicial para que cuando se carga el react no sobreescriba la url, despues ya se usa urlarticulos
        var startUrl = location.origin + location.pathname;
        var urlArticulos = "{{ Route('articles') }}";
    </script>

    <script src="{{ Tools::urlAssetsCache("/themes/$theme/app.js") }}"></script>
    {{-- en el servidor no esta funcionando el mis, no encuentra la ruta
		<script src="{{ mix('/js/default/app.js') }}"></script>
	--}}
@stop
