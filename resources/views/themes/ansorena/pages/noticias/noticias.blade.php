@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@section('assets_components')
    <link href="{{ Tools::urlAssetsCache('/css/default/noticias.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css"
        href="{{ Tools::urlAssetsCache('/themes/' . env('APP_THEME') . '/css/noticias.css') }}">
@endsection

@section('content')
    @php
        $emp = Config::get('app.emp');
    @endphp

    @if (in_array($emp, ['001', '002']) && empty($data['categ']))
        @include('pages.noticias.portada')
    @else
        @include('pages.noticias.categoria')


    @endif
@stop
