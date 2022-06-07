@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')
<div class="container" style="margin-top: 3rem; margin-bottom: 200px;">
    <div class="row">
        <div class="col-xs-12 col-sm-12 text-center">
            <br><br><br><br>
            <h3 class="titlePage">Vehículo no disponible</h3>
            <br><br>
			Este vehículo ya no está disponible
            <br><br><br><br><br><br>
        </div>
        <br>
        <br>


    </div>
</div>

@stop
