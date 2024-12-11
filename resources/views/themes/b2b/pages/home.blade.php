@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@php
    header('Location: ' . URL::to(Routing::translateSeo('subastas-online')), true, 302);
    exit();
@endphp
