@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop


@section('content')
<script src="{{ URL::asset('vendor/tiempo-real/node_modules/socket.io/node_modules/socket.io-client/socket.io.js') }}"></script>
@if(empty($data['sub_data']) || (strtoupper($data['sub_data']->tipo_sub) == 'O' || strtoupper($data['sub_data']->tipo_sub) == 'P'  || $data['sub_data']->subabierta_sub == 'P'))

<script src="{{ Tools::urlAssetsCache('/themes/'.$theme.'/custom_node_lotlist.js') }}"></script>
<script src="{{ URL::asset('js/hmac-sha256.js') }}"></script>

@endif


<script>
var auction_info = $.parseJSON('<?php  echo str_replace("\u0022","\\\\\"",json_encode($data["js_item"],JSON_HEX_QUOT)); ?>');

<?php

	if (!empty($data['sub_data'])) {
		echo "var cod_sub = '".$data['sub_data']->cod_sub."';";
	}
	elseif (!empty($data['cod_sub_aux'])) {
		echo "var cod_sub = '".$data['cod_sub_aux']->cod_sub."';";
	}
	else {
		echo "var cod_sub = '0';";
	}

	//gardamos el código de licitador para saber is esta logeado o no y mostrar mensaje de que debe logearse
	if (!empty($data['js_item']) && !empty($data['js_item']['user']) && !empty($data['js_item']['user']['cod_licit'])   )
	{
		$cod_licit ="'". $data['js_item']['user']['cod_licit']."'";
	}else{
		$cod_licit ="null";
	}
?>

var cod_licit = <?=$cod_licit?>;
routing.node_url 	 = '{{ Config::get("app.node_url") }}';
routing.comprar		 = '{{ $data["node"]["comprar"] }}';
routing.ol		 = '{{ $data["node"]["ol"] }}';

</script>


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
	<?php
	//@include('includes.breadcrumb')


	foreach($data['subastas'] as $k => $item) {

		$data['subastas'][$k]->total_pujas = 0;
		$data['subastas'][$k]->total_postores = 0;
		if (isset($item->pujas)) {
			$data['subastas'][$k]->total_pujas = sizeof($item->pujas);
			$aux_postores = array();
			foreach ($item->pujas as $key => $value) {
				$aux_postores[$value->cod_licit] = $value->cod_licit;
			}
			$data['subastas'][$k]->total_postores = sizeof($aux_postores);
		}

	}



	?>

	@include('content.subasta')
@stop

