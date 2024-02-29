@extends('layouts.default')

@section('title')
	{{ $data['title'] }}
@stop

@section('content')
<?php

$bread[] = array( "name" => $data['title']  );

?>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 text-center color-letter">
            <h1 class="titlePage"> {{ $data['title'] }} </h1>
            @include('includes.breadcrumb')
        </div>
    </div>

    <br><br>

    <div class="row">

        <div class="col-xs-12">
            {!! trans($theme.'-app.valoracion_gratuita.text_no_loged') !!}
        </div>
    </div>


    <br><br><br><br><br>

</div>



@stop
