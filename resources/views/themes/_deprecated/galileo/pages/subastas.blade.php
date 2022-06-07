@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
    <?php 
   
    if(!empty($_GET['finished']) && $_GET['finished'] == 'true'){
        $name =  trans(\Config::get('app.theme').'-app.foot.auctions-finished') ;
    }else{
        $name =  $data['name'] ;
    }
   $bread[] =  array("name" => $name );
    
    
    ?>
    <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 text-center color-letter">
                        <h1 class="titlePage"> <?= $name ?></h1>

                    @include('includes.breadcrumb')
                </div>
            </div>
        </div>
    
    @include('content.subastas')
@stop