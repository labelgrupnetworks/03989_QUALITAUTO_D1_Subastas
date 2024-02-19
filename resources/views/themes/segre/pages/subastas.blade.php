@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')

    <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 text-center color-letter">
                        <h1 class="titlePage"> {{ $data['name'] }}</h1>
                </div>
            </div>
        </div>

    @include('content.subastas')
@stop
