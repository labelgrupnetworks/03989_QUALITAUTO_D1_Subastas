@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<?php 

$bread[] = array("name" =>$data['title']  );
?>
<section class="bread-new">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h1 class="titlePage"> {{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.solicitud_valoracion') }}</h1>
            </div>
        </div>
    </div>
        @include('includes.breadcrumb')
</section>
<div id="">
	<div class="container" id="return-valoracion">
                <div class="col-xs-12">                
                <h1 class="title_single_adj">{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.succes_peticion') }}</h1>
            </div>           
        </div>
</div>

@stop