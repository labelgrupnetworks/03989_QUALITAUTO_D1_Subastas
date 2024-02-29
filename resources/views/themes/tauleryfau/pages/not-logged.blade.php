@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')

<section class="account">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    <h3>{{$data}}</h3>
                </div>
            </div>
        </div>
    </div>
</section>


@stop













