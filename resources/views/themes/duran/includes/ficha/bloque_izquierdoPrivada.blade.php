
<div class="bloque-izquierda-1 flex-wrap w-100 justify-content-space-between">



	<div class="volver-listado">
		@php
			if (strpos(url()->previous(), $lote_actual->url_subasta) !== false){
				$url_subasta = url()->previous();
			}else{
				$url_subasta = $lote_actual->url_subasta."?order=orden_desc";
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


	<div class="owl-theme owl-carousel visible-xs visible-sm" id="owl-carousel-responsive">

		@foreach($lote_actual->imagenes as $key => $imagen)
			   <div class="item_content_img_single" style="position: relative; height: 290px; overflow: hidden;">
					<img style="max-width: 100%; max-height: 100%;top: 50%; transform: translateY(-50%); position: relative; width: auto !important;    display: inherit !important;    margin: 0 auto !important;" class="img-responsive" src="{{Tools::url_img('lote_medium_large',$lote_actual->num_hces1,$lote_actual->lin_hces1, $key)}}" alt="{{$lote_actual->titulo_hces1}}">
			   </div>
		 @endforeach
	</div>
	<div class="row visible-xs visible-sm mt-2 mb-2">
		@include('includes.ficha.share')
	</div>

	<div class="single-lot-desc-content col-xs-12 no-padding desc-lot-profile-content  pb-4 pt-2">
		<p>{!! $lote_actual->desc_hces1 !!}</p>
	</div>




		@php
			$idCaracteristicaAutor =289;
			$caracteristicas = App\Models\V5\FgCaracteristicas_Hces1::getByLot($lote_actual->num_hces1, $lote_actual->lin_hces1);

			if(!empty($caracteristicas[$idCaracteristicaAutor])){
				$artist = App\Models\V5\Web_Artist::where("ID_ARTIST",$caracteristicas[$idCaracteristicaAutor]->idvalue_caracteristicas_hces1 )->first();
				$articles =  App\Models\V5\Web_Artist_Article::where("IDARTIST_ARTIST_ARTICLE",$caracteristicas[$idCaracteristicaAutor]->idvalue_caracteristicas_hces1 )->get();
			}

		@endphp
		@if(!empty($artist))
			<div class="col-xs-12 no-padding">
				<div class="artist_venta_privada">

					<div class="row">
						<a href="{{ route("artist",["name" => \Str::slug($artist->name_artist), "idArtist" => $artist->id_artist] )}}" target="_blank">
							<div class="col-xs-12 pb-2 name_artist">
								<strong>{{$artist->name_artist}}</strong>
							</div>
							<div class="col-xs-12    ">
								<div class="col-xs-12 col-sm-3   ">
									@if (file_exists("img/autores/".$artist->id_artist.".jpg"))
										<img src="/img/autores/{{$artist->id_artist}}.jpg" >
									@endif
								</div>
								<div class="col-xs-12 col-sm-9  max-line-3 biography_artist">
									{{$artist->biography_artist}}
								</div>
								<div class="col-xs-12 col-sm-9   ver_mas_artist">
									Ver más
								</div>
							</div>
						</a>

						@if(!empty($articles))
							<div class="col-xs-12 pb-2 pl-4">
								<ul >
									@foreach($articles as $article)
										<li> <a href="{{$article->url_artist_article}}" target="_blank"> {{$article->title_artist_article}} </a>

										</li>
									@endforeach
								</ul>
							</div >


						@endif
					</div>

				</div>
			</div>
		@endif
		<?php

		//comprobamos si hay ficheros
			$ruta="files/". Config::get("app.emp")."/".$lote_actual->num_hces1."/".$lote_actual->lin_hces1."/files";
			$files=array();
			if(file_exists($ruta)){
				$dir=scandir($ruta);
				$files=array_diff($dir, array('..', '.'));

			}
		?>
		@if(!empty($files))
		<div role="tabpanel" class="tab-pane ficheros_adj " id="ficheros">
		{{-- 	<p> {{ trans(\Config::get('app.theme').'-app.lot.documents') }}</p>--}}
			@foreach($files as $file)
			<img src="/img/icons/pdf.png" style="float: left;" width="20px">
			 <a role="button" href="/{{$ruta."/".$file}}" target="_blank" style="display: block; font-weight: 600">{{str_replace (".pdf","",$file)}}</a>
			@endforeach
		</div>
		@endif


</div>
