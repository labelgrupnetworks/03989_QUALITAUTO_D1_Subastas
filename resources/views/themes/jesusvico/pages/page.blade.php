@extends('layouts.default')

@section('title')
    {{ $data['data']->name_web_page }}
@stop

@section('content')
    @php
        $bread[] = ['name' => $data['data']->name_web_page];
    @endphp

    <main class="static-page">
        <div class="container">
            {{-- @include('includes.breadcrumb') --}}
            <h1>{{ $data['data']->name_web_page }}</h1>
        </div>

        <div class="container-fluid">
            <div class="contenido contenido-web static-page" id="pagina-{{ $data['data']->id_web_page }}">
                @if (request('static'))
                    @php
                        $page = request('static');
                    @endphp
                    @include("includes.statics.$page")
                @else
                    {!! $data['data']->content_web_page !!}
                @endif
            </div>
        </div>
    </main>
@stop
