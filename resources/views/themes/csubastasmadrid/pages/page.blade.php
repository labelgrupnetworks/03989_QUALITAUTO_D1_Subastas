@extends('layouts.default')

@section('title')
    {{ $data['data']->name_web_page }}
@stop

@section('content')
    <main class="page-static">
        <script
            src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl={{ config('app.locale') }}"
            async defer></script>

        <div id="pagina-{{ $data['data']->id_web_page }}" class="contenido">
            <div class="container">
                {!! $data['data']->content_web_page !!}
            </div>
        </div>
    </main>
@stop
