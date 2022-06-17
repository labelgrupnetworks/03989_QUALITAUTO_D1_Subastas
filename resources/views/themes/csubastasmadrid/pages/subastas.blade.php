@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')
    <?php

    if(request('finished') == "false"){
        $bread[] = array("name" => $data['name'] . '&nbsp&nbsp&nbsp<i class="fa fa-angle-right"></i>   ' .trans(\Config::get('app.theme').'-app.subastas.current_auction') );
    }
    else if(request('finished') == "true"){
        $bread[] = array("name" => $data['name'] . '&nbsp&nbsp&nbsp<i class="fa fa-angle-right"></i>   ' .trans(\Config::get('app.theme').'-app.subastas.price_made') );
    }
    else{
        $bread[] = array("name" => $data['name'] );
    }

    ?>
    @include('includes.breadcrumb')

    @include('content.subastas')
@stop
