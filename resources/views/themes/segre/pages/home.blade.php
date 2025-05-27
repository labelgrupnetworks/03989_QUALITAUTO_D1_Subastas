@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@section('content')
    @if (Session::has('user') && Session::get('user.admin'))
        @include('includes.tiempo_real_btn')
    @endif

    @include('content.home')
@stop
