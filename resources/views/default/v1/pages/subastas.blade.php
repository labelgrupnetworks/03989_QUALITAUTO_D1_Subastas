@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')
    <?php
    $bread[] = array("name" => $data['name'] );
    if(empty($data['type']) && !empty($data['sub_data']) ){
        $sub_data = $data['sub_data'];
        $url_subasta=\Routing::translateSeo('info-subasta').$sub_data->cod_sub."-".str_slug($sub_data->des_sub);

        $url_indice=\Routing::translateSeo('indice-subasta').$sub_data->cod_sub.'-'.str_slug($sub_data->des_sub.'-'.$sub_data->id_auc_sessions);
        $indice = trans($theme.'-app.lot_list.indice_auction');
        $name= trans($theme.'-app.subastas.auctions');
        if($data['sub_data']->subc_sub == 'H'){
             $url = \Routing::translateSeo('subastas-historicas');
        }elseif($data['sub_data']->tipo_sub == 'W'){

            if(strtotime($data['sub_data']->end) <= time()){
               $url = \Routing::translateSeo('todas-subastas').'?finished=true';
            }else{
               $url = \Routing::translateSeo('todas-subastas').'?finished=false';
            }

        }elseif($data['sub_data']->tipo_sub == 'O'){
            $url = \Routing::translateSeo('subastas-online');
        }elseif($data['sub_data']->tipo_sub == 'V'){
            $url = \Routing::translateSeo('venta-directa');
            $indice = trans($theme.'-app.lot_list.indice_venta_directa');
            $name = trans($theme.'-app.foot.direct_sale');
        }
        $bread[] = array("url" =>$url, "name" =>$name  );
        $bread[] = array("url" =>$url_subasta, "name" =>$sub_data->des_sub  );
        $bread[] = array("url" =>$url_indice, "name" =>$indice  );
        $bread[] = array( "name" =>"Lotes" );
    }elseif(!empty($data['seo']->webname)){
        if(!empty($data['seo']->subcategory)){
            $bread[] = array("url" =>$data['seo']->url, "name" =>$data['seo']->webname  );
            $bread[] = array( "name" =>$data['seo']->subcategory  );
        }else{
            $bread[] = array( "name" =>$data['seo']->webname  );
        }
    }

    ?>
    <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 text-center color-letter">
                        <h1 class="titlePage"> {{ $data['name'] }}</h1>

                    @include('includes.breadcrumb')
                </div>
            </div>
        </div>

    @include('content.subastas')
@stop
