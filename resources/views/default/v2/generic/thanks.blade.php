@extends('layouts.default')

@section('title')

@stop

@section('content')
        <div class="container">
            <div class="content text-center">
                <div class="title"><h1>{{ trans('web.emails.thanks') }} </h1><br /> </div>
                <a href="/" class="btn-valoracion btn-color">{{ trans('web.emails.back') }}</a>
            </div>
        </div>
@stop
