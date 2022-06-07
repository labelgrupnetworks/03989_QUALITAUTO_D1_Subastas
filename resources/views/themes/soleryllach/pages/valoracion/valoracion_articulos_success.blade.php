@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<?php 

$bread[] = array("name" =>$data['title']  );
?>
    @include('includes.breadcrumb')
<div id="">
	<div class="container" id="return-valoracion">
                <div class="">
                    <h1 class="titleSingle_corp">{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.succes_peticion') }}</h1>
                </div>               
        </div>
</div>
    
<script>
    ga('send', 'event', 'tasacion', 'confirmada'); 
</script>
@stop