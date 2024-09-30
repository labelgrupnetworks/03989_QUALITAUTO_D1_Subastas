@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@section('content')
    <main class="ficha-subasta-page">
        @include('content.ficha_subasta')
    </main>
@stop
