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
        <div class="container">
            <h1 class="titlePage"> {!! trans(\Config::get('app.theme').'-app.global.form_success') !!}</h1>
        </div>
    </div>
</div>

<div class="container">

    <br><br>
    
    <div class="row">

        <div class="col-xs-12">
            <center>{!! trans(\Config::get('app.theme').'-app.global.text_success') !!}</center>
        </div>
    </div>


    <br><br><br><br><br>

</div>   



@stop