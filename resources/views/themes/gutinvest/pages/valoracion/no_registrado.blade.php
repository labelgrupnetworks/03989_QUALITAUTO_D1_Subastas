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
<div class="contenido">
	<div class="container">

            <div class="row">
                <div class="col-md-12">
                    <h1 class="titleSingle_corp">{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.solicitud_valoracion') }}</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    
                    <div class="row">
                        <div class="col-xs-12 border-content">
                            <h4 class="valoracion-h4">{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.confirmar_registro') }}</h4>
                            <p><?= trans(\Config::get('app.theme').'-app.valoracion_gratuita.text_no_loged') ?></p>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
</div>

@stop
