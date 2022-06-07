@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content') 
    <?php
    
        if($data['auction']->subc_sub == 'H'){
            $url = \Routing::translateSeo('subastas-historicas');
        }elseif($data['auction']->tipo_sub == 'W'){
            $url = \Routing::translateSeo('presenciales');
        }elseif($data['auction']->tipo_sub == 'O'){
            $url = \Routing::translateSeo('subastas-online');
        }elseif($data['auction']->tipo_sub == 'V'){
            $url = \Routing::translateSeo('venta-directa');
        }
        
    
        $bread = array();
        
        $bread[] = array("url" =>$url, "name" =>"".trans(\Config::get('app.theme').'-app.subastas.auctions').""  );
        $bread[] = array( "name" =>$data['auction']->des_sub );
    ?>
    <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 text-center color-letter">
                        <h1 class="titlePage"> {{ trans(\Config::get('app.theme').'-app.subastas.auctions') }}</h1>

                    @include('includes.breadcrumb')
                </div>
            </div>
        </div>

    
    @include('content.ficha_subasta')
@stop