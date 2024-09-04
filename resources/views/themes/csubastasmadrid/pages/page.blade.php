@extends('layouts.default')

@section('title')
    {{ $data['data']->name_web_page }}
@stop

@section('content')
    <main class="page-static">


        <div id="pagina-{{ $data['data']->id_web_page }}" class="contenido">
            <div class="container">
                {!! $data['data']->content_web_page !!}
            </div>
        </div>
    </main>
@stop
