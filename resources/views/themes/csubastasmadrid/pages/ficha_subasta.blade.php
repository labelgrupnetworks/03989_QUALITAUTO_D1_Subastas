@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content') 
    <?php
     $name= trans(\Config::get('app.theme').'-app.subastas.auctions');
        if($data['auction']->subc_sub == 'H'){
            $url = \Routing::translateSeo('subastas-historicas');
        }elseif($data['auction']->tipo_sub == 'W'){

            if(strtotime($data['auction']->end) <= time()){
               $url = \Routing::translateSeo('todas-subastas').'?finished=true'; 
            }else{
               $url = \Routing::translateSeo('todas-subastas').'?finished=false';
            }
           
        }elseif($data['auction']->tipo_sub == 'O'){
            $url = \Routing::translateSeo('subastas-online');
        }elseif($data['auction']->tipo_sub == 'V'){
            $url = \Routing::translateSeo('venta-directa');
             $name = trans(\Config::get('app.theme').'-app.foot.direct_sale');
        }
        
    
        $bread = array();
        
        $bread[] = array("url" =>$url, "name" =>$name );
        $bread[] = array( "name" =>$data['auction']->des_sub );
    ?>
    @include('includes.breadcrumb_before_after')
    @include('content.ficha_subasta')
@stop