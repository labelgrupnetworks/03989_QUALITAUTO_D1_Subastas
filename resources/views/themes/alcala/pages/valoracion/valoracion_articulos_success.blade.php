@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')
<?php

$bread[] = array("name" =>$data['title']  );
?>


    <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 text-center color-letter">
                        <h1 class="titlePage"> {{ trans($theme.'-app.subastas.auctions') }}</h1>

                    @include('includes.breadcrumb')
                </div>
            </div>
        </div>
<div id="">
	<div class="container" id="return-valoracion">
                <div class="">
                    <h1 class="titleSingle_corp color-letter">{{ trans($theme.'-app.valoracion_gratuita.succes_peticion') }}</h1>
                </div>
        </div>
</div>
<br><br><br><br><br><br><br><br><br><br>

<script>
    ga('send', 'event', 'tasacion', 'confirmada');
</script>
@stop
