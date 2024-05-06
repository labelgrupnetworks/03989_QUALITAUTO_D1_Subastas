@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@section('content')
    <main>
        <div class="container" id="return-valoracion">
            <div class="">
                <h1 class="titlePage">
                    {{ trans(\Config::get('app.theme') . '-app.valoracion_gratuita.succes_peticion') }}</h1>
            </div>
        </div>
    </main>

    <script>
        ga('send', 'event', 'tasacion', 'confirmada');
    </script>
@stop
