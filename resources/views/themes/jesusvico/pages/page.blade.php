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
            <h1 class="text-uppercase">{{ $data['data']->name_web_page }}</h1>
        </div>

		{{-- Al crear una estática nueva hay que poner dentro de la estática el container --}}

        <div>
            <div class="contenido contenido-web static-page" id="pagina-{{ $data['data']->id_web_page }}">
                @if (request('static'))
                    @php
						$lang = \Config::get('app.locale');
                        $page = request('static');
                    @endphp
                    @include("includes.statics.$lang.$page")
                @else
                    {!! $data['data']->content_web_page !!}
                @endif
            </div>
        </div>
    </main>
@stop
