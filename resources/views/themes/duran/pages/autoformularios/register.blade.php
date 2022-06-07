@extends('layouts.default')

@section('title')
	{{ $data['title'] }}
@stop

@section('content')
<?php 

$bread[] = array( "name" => $data['title']  );

?>

<div class="breadcrumb-total row">
    <div class="col-xs-12 col-sm-12 text-center color-letter">
        @include('includes.breadcrumb')
        <div class="container">
            <h1 class="titlePage"> {{ $data['title'] }} </h1>
        </div>
    </div>
</div>

<div class="container">

    <br><br>
    
    <div class="row">

        <div class="col-xs-12">
            {!! trans(\Config::get('app.theme').'-app.valoracion_gratuita.text_no_loged') !!}
        </div>
    </div>


    <br><br><br><br><br>

</div>   



@stop