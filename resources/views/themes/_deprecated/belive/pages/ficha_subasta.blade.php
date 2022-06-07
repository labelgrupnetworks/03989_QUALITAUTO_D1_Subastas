@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content') 
    <?php
   
        $bread = array();        
        $bread[] = array("url" =>$data["url_bread"], "name" =>$data["name_bread"] );
        $bread[] = array( "name" =>$data['auction']->des_sub );
    ?>
    <div class="container">
        <h3 class="titleSingle">{{ $data["auction"]->des_sub}}</h1>
        @include('includes.breadcrumb')
    </div>

    
    @include('content.ficha_subasta')
@stop