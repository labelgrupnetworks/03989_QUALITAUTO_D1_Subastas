@extends('layouts.panel')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@php
if(!Session::has('user')) {
	header('Location: /');
	exit;
}
@endphp

@section('content')

    <div class="static-page" id="pagina-{{ $data['data']->id_web_page }}">
			{!! $data['data']->content_web_page !!}
    </div>

@stop
