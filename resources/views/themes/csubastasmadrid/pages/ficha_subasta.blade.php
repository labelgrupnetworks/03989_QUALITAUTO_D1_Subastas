@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@section('content')
    <main class="ficha-subasta">
        @include('content.ficha_subasta')
    </main>
@stop
