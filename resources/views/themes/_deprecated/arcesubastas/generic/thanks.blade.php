@extends('layouts.default')

@section('title')

@stop

@section('content')
        <div class="container">
            <div class="content text-center">
                <div class="title"><h1>{{ trans(\Config::get('app.theme').'-app.emails.thanks') }} </h1><br /> </div>
                <a href="/" class="btn-valoracion">{{ trans(\Config::get('app.theme').'-app.emails.back') }}</a>
            </div>
        </div>
@stop
