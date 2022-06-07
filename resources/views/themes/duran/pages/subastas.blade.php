@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
    <?php
    $bread[] = array("name" => $data['name'] );
    if(empty($data['type']) && !empty($data['sub_data']) ){
        $sub_data = $data['sub_data'];
        $url_subasta=\Routing::translateSeo('info-subasta').$sub_data->cod_sub."-".str_slug($sub_data->des_sub);

        $url_indice=\Routing::translateSeo('indice-subasta').$sub_data->cod_sub.'-'.str_slug($sub_data->des_sub.'-'.$sub_data->id_auc_sessions);
        $indice = trans(\Config::get('app.theme').'-app.lot_list.indice_auction');
        $name= trans(\Config::get('app.theme').'-app.subastas.auctions');
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
            $indice = trans(\Config::get('app.theme').'-app.lot_list.indice_venta_directa');
            $name = trans(\Config::get('app.theme').'-app.foot.direct_sale');
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

<div class="breadcrumb-total row">
    <div class="col-xs-12 col-sm-12 color-letter">
		@include('includes.breadcrumb')
		<div class="container ">
			@php
				$auction = null;
				if (request("actual")){
					$fgsub = new App\Models\V5\FgSub();
					$auction = $fgsub->joinLangSub()->joinSessionSub()
						->where('SUBC_SUB','S')->where('TIPO_SUB','W')->first();
				}

			@endphp
			@if(!empty($auction) )
					<h2> {{ $auction->des_sub}}</h2>
					@include('includes.grid.info_auction')
					<h3> {{ $data['name'] }}</h3>
			@else
				<h2> {{ $data['name'] }}</h2>
			@endif

        </div>
    </div>
</div>

    @include('content.subastas')
@stop
