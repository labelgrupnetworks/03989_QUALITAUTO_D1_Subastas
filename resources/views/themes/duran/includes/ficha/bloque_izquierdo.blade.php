
<div class="bloque-izquierda-1 flex-wrap w-100 justify-content-space-between">



	<div class="volver-listado">
		@php
		if (strpos(url()->previous(), $lote_actual->url_subasta) !== false){
			$url_subasta = url()->previous();
		}else{
			$url_subasta = $lote_actual->url_subasta;
			if($lote_actual->tipo_sub=="O" ){
				$url_subasta .="?order=orden_asc";
			}elseif($lote_actual->tipo_sub=="V" ){
				$url_subasta .="?order=orden_desc";
			}

		}



		@endphp
		<a href="{{ $url_subasta }}"><i class="fas fa-arrow-left"></i>   {{ trans(\Config::get('app.theme').'-app.lot.volver_listado') }}</a>
	</div>
	<div>

	<?php

		#es posible que no haya texto, en ese caso, la funcion trans muestra lo mismo que la clave, hay que hacer que no muestre nada si no tiene traduccion
		$key_trans = \Config::get('app.theme').'-app.lot.texto_destacado_lote_'.$data['subasta_info']->lote_actual->tipo_sub;

		$trans = trans($key_trans);
		if ($trans != $key_trans){
			echo $trans;
		}
	?>

	</div>
	<div class="mt-1">
		<?= $lote_actual->descdet_hces1 ?>
	</div>
	<div class="visible-xs visible-sm" style="position: relative;">
		@if(Session::has('user') &&  !$retirado)
			<div class=" favoritos_mobile">
				<a  class="  <?= $lote_actual->favorito? 'hidden':'' ?>" id="add_fav" href="javascript:action_fav_modal('add')">
					<i class="fa fa-heart" aria-hidden="true" ></i>
				</a>
				<a class="  <?= $lote_actual->favorito? '':'hidden' ?>" id="del_fav" href="javascript:action_fav_modal('remove')">
					<i class="fa fa-heart" aria-hidden="true" ></i>
				</a>
			</div>
		@endif
	</div>

	<div class="owl-theme owl-carousel visible-xs visible-sm" id="owl-carousel-responsive">

		@foreach($lote_actual->imagenes as $key => $imagen)
			   <div class="item_content_img_single" style="position: relative; height: 290px; overflow: hidden;">
					<img loading="lazy" style="max-width: 100%; max-height: 100%;top: 50%; transform: translateY(-50%); position: relative; width: auto !important;    display: inherit !important;    margin: 0 auto !important;" class="img-responsive" src="{{Tools::url_img('lote_medium_large',$lote_actual->num_hces1,$lote_actual->lin_hces1, $key)}}" alt="{{$lote_actual->titulo_hces1}}">
			   </div>

		 @endforeach
		 @foreach($lote_actual->videos as $video)
			<div class="item_content_img_single video-item">
				<video width="100%" controls>
					<source src="{{$video}}" type="video/mp4">
				</video>
			</div>
		@endforeach
	</div>
	<div class="row visible-xs visible-sm mt-2 mb-2">
		@include('includes.ficha.share')
	</div>


	<div class="referencia-ficha">
	<span>
		@if($subasta_web )
			{{ trans(\Config::get('app.theme').'-app.lot.lot-name') }}:
		@else
			{{ trans(\Config::get('app.theme').'-app.lot.referencia') }}
		@endif
		@php
			#si hay separador decimal ponemos los bises
			$refLot  = $lote_actual->ref_asigl0;
			#si hay separador decimal ponemos los bises
			if(strpos($refLot ,'.')){
				if($lote_actual->tipo_sub=="W"){
					$refLot = str_replace(array(".1",".2",".3",".4",".5"), array(" A"," B", " C", " D", " E"),  $refLot );
				}else{
					#solo en las subastas presenciales pueden verse los bises, en el resto hay que quitarlo
					$refLot = str_replace(array(".1",".2",".3",".4",".5",".6",".7",".8",".9"), array("", "", "", "", "", "", "", "", "", ""),  $lote_actual->ref_asigl0);
					$refLot = substr($refLot ,-\config::get("app.substrRef"))+0;
				}

			}elseif( \config::get("app.substrRef")){
				#si no cogemos solo los últimos 7 numeros, ya que se usaran hasta 9, los dos primeros para diferenciar un lote cuando se ha vuelto a subir a subasta
				# le sumamos 0 para convertirlo en numero y así eliminamos los 0 a la izquierda

				$refLot = substr($refLot ,-\config::get("app.substrRef"))+0;
			}

		@endphp
		{{ $refLot }}</span>
	</div>
	<div class="ficha-info-title w-100">
		<div class="titleficha w-100 secondary-color-text">
			{!!$titulo!!}
		</div>
	</div>
	<div class="single-lot-desc-content col-xs-12 no-padding desc-lot-profile-content  pb-2 pt-2">
		<p>{!! $lote_actual->desc_hces1 !!}</p>
	</div>
	<a href="javascript:seeMoreInfo();"><span class="seemore hidden-md hidden-lg">{{ trans(\Config::get('app.theme').'-app.lot.see_more') }}</span></a>
	<a href="javascript:seeLessInfo();"><span class="seeless" style="display: none">{{ trans(\Config::get('app.theme').'-app.lot.see_less') }}</span></a>


<div class="info-lot-extra">
	<div class="w-100 ficha-tipo-v">



		@php
			$caracteristicas = App\Models\V5\FgCaracteristicas_Hces1::getByLot($lote_actual->num_hces1, $lote_actual->lin_hces1);
			$idCaracteristicaAutor =289;
		@endphp
		@if(!empty($caracteristicas))
			<div class="col-xs-12 no-padding">
				<div class="caracteristicas-ficha">
					@foreach($caracteristicas as $caracteristica)
					{{-- si la caracteristica es Autor --}}
					@php
						$url ="";

						if ($caracteristica->id_caracteristicas == $idCaracteristicaAutor){
							$artist = App\Models\V5\Web_Artist::where("ID_ARTIST",$caracteristica->idvalue_caracteristicas_hces1 )->first();
							if($artist){
								$url = route("artist",["name" => \Str::slug($artist->name_artist), "idArtist" => $artist->id_artist] );
							}
						}

					@endphp
					<div class="row">
						<div class="col-xs-4 pb-2">

							{{$caracteristica->name_caracteristicas}}

						</div>
						<div class="col-xs-8 pb-2">
							@if(!empty($url))
								<a href="{{$url}}" target="_blank">
							@endif
							<strong>{{$caracteristica->value_caracteristicas_hces1}}</strong>
							@if(!empty($url))
								</a>
							@endif
						</div>
					</div>
					@endforeach
				</div>
			</div>
		@endif
		@if($lote_actual->permisoexp_hces1=='S')
			<div class="col-xs-12 no-padding desc-lot-profile-content pt-1">
				<p>
					{!! trans(\Config::get('app.theme').'-app.lot.permiso_exportacion') !!}
				</p>
			</div>
		@endif
	</div>

	<?php
	$FgOrtsec1 = new \App\Models\V5\FgOrtsec1();
	$linOrtsec1 = $FgOrtsec1->select("LIN_ORTSEC1,NVL(DES_ORTSEC0_LANG, DES_ORTSEC0) DES_ORTSEC0 ")->JoinFgOrtsec0()->where("SEC_ORTSEC1",$lote_actual->sec_hces1 )->first();


	?>

		@if(!empty($linOrtsec1))
			<div class="col-xs-12 no-padding fincha-info-cats">
				<div class="cat">{{ trans(\Config::get('app.theme').'-app.lot.categories') }}</div>
					<span class="badge"><img class="img-responsive" src="{{ asset('themes/duran/assets/img/categorias/cat-'.$linOrtsec1->lin_ortsec1.'.png')}}">{{$linOrtsec1->des_ortsec0}}</span>
			</div>
		@endif


		@if(!empty($data['subasta_info']->almacen))
			<div class="horarios">
			<span><strong>{{ trans(\Config::get('app.theme').'-app.lot.puede_visitar') }}</strong></span><br>
			<span>{{$data['subasta_info']->almacen->dir_alm}} {{$data['subasta_info']->almacen->cp_alm}}  {{$data['subasta_info']->almacen->pob_alm}} </span><br>
			<a href="{{$data['subasta_info']->almacen->maps_alm}}" target="blank">{{ trans(\Config::get('app.theme').'-app.subastas.how_to_get') }}</a><br><br>
			{!! trans(\Config::get('app.theme').'-app.lot.horario_almacen_'.$data['subasta_info']->almacen->cod_alm) !!}
			</div>
		@endif
		<div class="cms-product">
		@php /* cambio el enlace	{{  Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.valorar_producto')  }} */ @endphp
		<a href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.valorar_producto')  }}">{{ trans(\Config::get('app.theme').'-app.lot.quiere_valorar_bienes') }} </a>
		<a href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.how_to_sell')  }}">{{ trans(\Config::get('app.theme').'-app.foot.how_to_sell') }}</a>
		<a href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.how_to_buy')  }}">{{ trans(\Config::get('app.theme').'-app.foot.how_to_buy') }}</a>
		@if ($subasta_web)
			<a href="{{ Routing::translateSeo('calendar')  }}">{{ trans(\Config::get('app.theme').'-app.foot.calendar') }}</a>
		@endif

		</div>
</div>
		<div class="row hidden-xs hidden-sm">
			@include('includes.ficha.share')
		</div>

</div>
