@extends('layouts.default')

@section('title')
    {{ $data['data']->name_web_page }}
@stop

@section('content')
    @php
        $bread[] = ['name' => $data['data']->name_web_page];
    @endphp

    <div class="static-main-page">
        <div class="container">
            @include('includes.breadcrumb')
            <h1>{{ $data['data']->name_web_page }}</h1>
        </div>

        <div class="container-fluid">
            <div class="contenido contenido-web static-page" id="pagina-{{ $data['data']->id_web_page }}">
                {!! $data['data']->content_web_page !!}
            </div>
        </div>
    </div>
@stop
