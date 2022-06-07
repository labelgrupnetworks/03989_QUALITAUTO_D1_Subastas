@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')
 <?php
    if(empty($data['type']) && !empty($data['sub_data']) ){
        $sub_data = $data['sub_data'];
        $url_subasta=\Routing::translateSeo('info-subasta').$sub_data->cod_sub."-".str_slug($sub_data->des_sub);
        $bread = array();
        $bread[] = array("url" =>$url_subasta, "name" =>$sub_data->des_sub  );
        $bread[] = array( "name" =>"Lotes" );
    }elseif(!empty($data['seo']->webname)){

        $bread = array();
        if(!empty($data['seo']->subcategory)){
            $bread[] = array("url" =>$data['seo']->url, "name" =>$data['seo']->webname  );            
            $bread[] = array( "name" =>$data['seo']->subcategory  );
        }else{
            $bread[] = array( "name" =>$data['seo']->webname  );  
        }
    }
 
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
     
    @include('content.subasta')
@stop

