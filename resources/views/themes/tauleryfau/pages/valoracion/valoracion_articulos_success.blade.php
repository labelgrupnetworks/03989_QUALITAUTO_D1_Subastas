@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')
<?php

$bread[] = array("name" =>$data['title']  );
?>
<section class="principal-bar no-principal">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
            <div class="princiapl-bar-wrapper">
                    <div class="principal-bar-title">
                        <h3>{{ trans($theme.'-app.valoracion_gratuita.solicitud_valoracion') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="" class="body-auctions">
	<div class="container" id="return-valoracion">
                <div class="">
                    <h1 class="titleSingle_corp text-center">{{ trans($theme.'-app.valoracion_gratuita.succes_peticion') }}</h1>
                </div>
        </div>
</div>

@stop
