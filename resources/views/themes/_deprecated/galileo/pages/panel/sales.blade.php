@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<div class="color-letter">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
            <h1 class="titlePage">{{ trans(\Config::get('app.theme').'-app.user_panel.mi_cuenta') }}</h1>
            <small>{{  !empty($name[1]) ? $name[1] : ''}} {{ !empty($name[0]) ? $name[0] : '' }}</small>
            </div>
        </div>
    </div>
</div>
<div class="container panel">
    <div class="row">
	<div class="col-xs-12 col-sm-12">
            @foreach($data as $lot)
            
            @endforeach
        </div>
    </div>
</div>

@stop
