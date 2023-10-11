@extends('layouts.default')

@section('title')
    {{ trans("$theme-app.head.title_app") }}
@stop

@section('content')
    @if (Config::get('app.linkTiempoRealHome', false))
        @include('includes.tiempo_real_btn')
    @endif

    @include('content.home')
@stop
