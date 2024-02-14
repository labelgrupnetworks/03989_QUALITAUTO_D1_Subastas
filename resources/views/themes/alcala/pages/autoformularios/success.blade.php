@extends('layouts.default')

@section('title')
	{{ $data['title'] }}
@stop

@section('content')
<?php 

$bread[] = array( "name" => $data['title']  );

?>
<div class="container">

    <br><br>

    <div class="row">
        <div class="col-xs-12 col-sm-12 text-center color-letter">
            <h1 class="titlePage"> {!! trans($theme.'-app.global.form_success') !!}</h1>
        </div>
    </div>

    <br><br>
    
    <div class="row">

        <div class="col-xs-12">
            <center>{!! trans($theme.'-app.global.text_success') !!}</center>
        </div>
    </div>


    <br><br><br><br><br>

</div>   



@stop