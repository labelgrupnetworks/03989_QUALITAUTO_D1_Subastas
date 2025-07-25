@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@section('assets_components')
    {{-- <link type="text/css" href="{{ Tools::urlAssetsCache('/css/default/noticias.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ Tools::urlAssetsCache("/themes/$theme/css/noticias.css") }}" rel="stylesheet">; --}}
@endsection

@section('content')
    @include('pages.noticias.portada')
@stop
