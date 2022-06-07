@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')


 <?php
        $sub_data = $data["subasta"];
        $url_subasta=\Routing::translateSeo('info-subasta').$sub_data->cod_sub."-".str_slug($sub_data->name);
        $bread = array();
        $bread[] = array("url" =>$url_subasta, "name" =>$sub_data->name  );
        $bread[] = array( "name" =>trans(\Config::get('app.theme').'-app.lot_list.indice_auction'));
   
 
    /*
    $sub_data = $data['sub_data'];
    $url_subasta=\Routing::translateSeo('info-subasta').$sub_data->cod_sub."-".str_slug($sub_data->des_sub);
    $url_lotes=\Routing::translateSeo('subasta').$data['cod_sub']."-".str_slug($data['sub_data']->des_sub)."-".$data['id_auc_sessions'];  
    $bread = array();
    $bread[] = array("url" =>$url_lotes, "name" =>$sub_data->des_sub  );
    $bread[] = array( "name" =>"Lotes" );
    */
    ?>

     
    @include('content.indice_subasta')
    
@stop

