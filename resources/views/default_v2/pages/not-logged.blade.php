@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

<section class="account  min-height">
    <div class="container not-logged-container">
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2">
                <div class="text-center">
                    <div class="not-logged-text color-letter">{{$data}}</div>
                </div>
            </div>
        </div>
    </div>
</section>


@stop













