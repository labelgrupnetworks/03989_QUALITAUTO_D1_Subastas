@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')
<link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/autocomplete/jquery.auto-complete.css') }}" />

<script src="{{ URL::asset('vendor/tiempo-real/node_modules/socket.io/node_modules/socket.io-client/socket.io.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/autocomplete/jquery.auto-complete.min.js') }}"></script>
@if(strtoupper($data['subasta_info']->lote_actual->tipo_sub) == 'O' || strtoupper($data['subasta_info']->lote_actual->tipo_sub) == 'P' || $data['subasta_info']->lote_actual->subabierta_sub == 'P')

<script src="{{ Tools::urlAssetsCache('/vendor/tiempo-real/tr_main.js') }}"></script>
<script src="{{ URL::asset('js/hmac-sha256.js') }}"></script>

@endif
<script src="{{ URL::asset('js/openseadragon.min.js') }}"></script>

<script src="{{ URL::asset('vendor/jquery-print/jQuery.print.js') }}"></script>

@if(\Config::get("app.exchange"))
	<script src="{{ URL::asset('js/default/divisas.js') }}"></script>
@endif

<script>
var auction_info = $.parseJSON('<?php  echo str_replace("\u0022","\\\\\"",json_encode($data["js_item"],JSON_HEX_QUOT)); ?>');

@if(\Config::get("app.exchange"))
	var currency = $.parseJSON('<?php  echo str_replace("\u0022","\\\\\"",json_encode($data["divisas"],JSON_HEX_QUOT)); ?>');
@endif

var cod_sub = '{{$data['subasta_info']->lote_actual->cod_sub}}';
var ref = '{{$data['subasta_info']->lote_actual->ref_asigl0}}';
var imp = '{{$data['subasta_info']->lote_actual->impsalhces_asigl0}}';
<?php
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
routing.favorites	 = '{{ Config::get('app.url')."/api-ajax/favorites" }}';

$(document).ready(function() {
	$('.add_bid').removeClass('loading');
});

</script>
<?php
    $lote_actual = $data['subasta_info']->lote_actual;
	$artistaFondoGaleria = request("artistaFondoGaleria");
	$caracteristicas = App\Models\V5\FgCaracteristicas_Hces1::getByLot($lote_actual->num_hces1, $lote_actual->lin_hces1);

	$autor = $caracteristicas[1]->value_caracteristicas_hces1 ?? '';
	$bread = array();

	if($artistaFondoGaleria && !empty($autor)){

		$nameAutor = explode(",",  $autor);
			$autor="";

			if(count($nameAutor)== 2){
				$autor = $nameAutor[1] ." ";
			}
			$autor.= $nameAutor[0];
			//ponemos bien el nombre del autor para que se vea bien en la ficha del lote
			$caracteristicas[1]->value_caracteristicas_hces1 = $autor;

		$name = trans(\Config::get('app.theme').'-app.galery.volverArtistaFondoGaleria').' '. $autor;
		$bread[] = array("url" => route("artistaFondoGaleria", ["id_artist" =>$artistaFondoGaleria ]), "name" => $name  );
	}elseif(!empty($lote_actual)){

		if($lote_actual->tipo_sub =="E"){
			$url = route("exposicion", ["texto" => \Str::slug($lote_actual->des_sub), "cod" => $lote_actual->cod_sub, "reference" => $lote_actual->reference]);
			$bread[] = array("url" => $url, "name" =>$lote_actual->des_sub );
		}else{
			$bread[] = array("url" => $lote_actual->url_subasta, "name" =>$lote_actual->title_url_subasta  );
			if(!empty($data['seo']->meta_title)){
            $bread[] = array( "name" => $data['seo']->meta_title );
        }else{
            $bread[] = array( "name" => $lote_actual->descweb_hces1 );
        }
		}



    }



?>



	<div class="container">
				<div class="row">
					<div class="col-xs-12 col-sm-12 color-letter">
						{{-- Si venimos de la página del artista de fondo de galeria, debemos volver a esa página, si no mostramso el breadcrumb --}}
						@if( request("artistaFondoGaleria")  )
							@include('includes.breadcrumb')
						@else
							@include('includes.breadcrumb_before_after')
						@endif
					</div>
				</div>
	</div>

    @include('content.ficha')
@stop
