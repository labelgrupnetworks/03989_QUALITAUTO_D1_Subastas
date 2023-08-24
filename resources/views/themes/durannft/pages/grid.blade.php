@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@push('stylesheets')
    <link href="{{ Tools::urlAssetsCache('/css/default/grid.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Tools::urlAssetsCache('/themes/' . $theme . '/grid.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
    @include('content.grid')
@stop
