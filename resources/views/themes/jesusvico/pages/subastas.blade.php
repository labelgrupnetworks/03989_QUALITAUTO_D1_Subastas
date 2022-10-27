@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@section('content')
    <main class="subastas">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1 class="titlePage"> {{ $data['name'] }}</h1>
                </div>
            </div>
        </div>

        @include('content.subastas')
    </main>
@stop
