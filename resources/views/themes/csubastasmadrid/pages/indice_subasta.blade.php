@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')


 <?php
        $sub_data = $data["subasta"];
        $url_subasta=\Routing::translateSeo('info-subasta').$sub_data->cod_sub."-".str_slug($sub_data->name);
        $bread = array();
        $name= trans(\Config::get('app.theme').'-app.subastas.auctions');
        $indice = trans(\Config::get('app.theme').'-app.lot_list.indice_auction');
        if($data['subasta']->subc_sub == 'H'){
             $url = \Routing::translateSeo('subastas-historicas');
        }elseif($data['subasta']->tipo_sub == 'W'){

            if(strtotime($data['subasta']->end) <= time()){
               $url = \Routing::translateSeo('todas-subastas').'?finished=true'; 
            }else{
               $url = \Routing::translateSeo('todas-subastas').'?finished=false';
            }
           
        }elseif($data['subasta']->tipo_sub == 'O'){
            $url = \Routing::translateSeo('subastas-online');
        }elseif($data['subasta']->tipo_sub == 'V'){
            $url = \Routing::translateSeo('venta-directa');
             $name = trans(\Config::get('app.theme').'-app.foot.direct_sale');
             $indice = trans(\Config::get('app.theme').'-app.lot_list.indice_venta_directa');
        }
        $bread[] = array("url" =>$url, "name" =>$name  );
        $bread[] = array("url" =>$url_subasta, "name" =>$sub_data->name  );
        $bread[] = array( "name" =>$indice);
   
 
    /*
    $sub_data = $data['sub_data'];
    $url_subasta=\Routing::translateSeo('info-subasta').$sub_data->cod_sub."-".str_slug($sub_data->des_sub);
    $url_lotes=\Routing::translateSeo('subasta').$data['cod_sub']."-".str_slug($data['sub_data']->des_sub)."-".$data['id_auc_sessions'];  
    $bread = array();
    $bread[] = array("url" =>$url_lotes, "name" =>$sub_data->des_sub  );
    $bread[] = array( "name" =>"Lotes" );
    */
    ?>
    @include('includes.breadcrumb')
     
    @include('content.indice_subasta')
    
@stop

