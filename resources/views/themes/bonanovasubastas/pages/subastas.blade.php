@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')
    <?php 
    $bread[] = array("name" => $data['name'] );
    ?>
    @include('includes.breadcrumb')
    
    @include('content.subastas')
@stop