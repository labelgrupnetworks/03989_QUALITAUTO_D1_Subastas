



 <div class="container galeryGrid">
	<div class="row galBanner">
		<div class="col-xs-12 col-md-4 " style="height: 360px;">
			<div class="galBannerText">

				<div class="col-x-12 galNameArtist">
					{{-- si hay mas de un artista no se pone nada --}}
					@if(count($artists) == 1 )
						{{$artists[0]->name_artist}}
					@endif

					</div>
				<div class="col-x-12 galDesSub">
					{{-- Si no hay artistao el que hay no se llama igual que la exposición --}}
					@if (	count($artists) == 0 ||(  trim(mb_strtoupper($auction->des_sub) ) != trim(mb_strtoupper($artists[0]->name_artist)) ))
					 {{$auction->des_sub}}
					@endif
					</div>
				<div class="col-x-12 galFecSub">

					@php
						$startDate =  Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $auction->dfec_sub)->locale(\Tools::getLanguageComplete(\Config::get('app.locale')));
						$endDate =  Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $auction->hfec_sub)->locale(\Tools::getLanguageComplete(\Config::get('app.locale')));
					@endphp

					{{$startDate->day}} {{ trans(\Config::get('app.theme').'-app.galery.de') }} {{$startDate->monthName}}
					-
					{{$endDate->day}}  {{ trans(\Config::get('app.theme').'-app.galery.de') }} {{$endDate->monthName}}
					<br/>
			{{$endDate->year}}

				</div>
			</div>
		</div>

		<div class="col-xs-12 col-md-8 galeryBannerImage">
			<img src="{{\Tools::url_img_auction('subasta_large',$auction->cod_sub,$auction->reference)}}"/>
		</div>

	</div>
</div>

<div class="container ">
	<div class="row ">


		<div class="gridGal">
			@foreach($lots as $lot)

				@include('includes.galery.lot')
			@endforeach
		</div>

		<div >
			@if( !empty($auction->descdet_sub) )
				<div class="GalTitleExhibition mt-5" >{{ trans(\Config::get('app.theme').'-app.galery.theexhibition') }}</div>
				<div class="GalTextExhibition" >{!! nl2br($auction->descdet_sub)!!}</div>
			@endif
			{{-- si hay mas de un artista no se pone nada --}}
			@if(count($artists) == 1 )


				<div id="biographyView" class="GalTitleExhibition mt-4" >{{ trans(\Config::get('app.theme').'-app.galery.biography') }}
					<span id="desplegableOFF" ><img src="/themes/ansorena/img/flechaDer.png"> </span>
					<span id="desplegableON" class=" hidden"><img src="/themes/ansorena/img/flechaAba.png"> </span>
				</div>
				<div  class="GalTextExhibition " >
					<div id="biographyArtistText" class=" hidden" >{!! $artists[0]->biography_artist !!}</div>
				</div>
			@endif
		</div>



	</div>
</div>

@if(count($exhibitions) > 0)

@php
	$auctions = array();

	foreach($exhibitions as $exhibition){
		$auctions[] = $exhibition->cod_sub;
	}


	$fgasigl0 = new  App\Models\V5\FgAsigl0 ;
	$lots = $fgasigl0->select("cod_sub, numhces_asigl0, linhces_asigl0")->JoinSubastaAsigl0()->where("ref_asigl0",1)->wherein("cod_sub",$auctions)->get();
	$imgSubastas = [];
	foreach ($lots as $lot){
		$imgSubastas[$lot->cod_sub] = \Tools::url_img("square_medium", $lot->numhces_asigl0, $lot->linhces_asigl0, null, true);

		$numArtist = $fgasigl0->select('count(distinct(IDVALUE_CARACTERISTICAS_HCES1)) as numArtist')
							->leftjoin('FGCARACTERISTICAS_HCES1', "FGCARACTERISTICAS_HCES1.EMP_CARACTERISTICAS_HCES1 = FGASIGL0.EMP_ASIGL0 AND NUMHCES_CARACTERISTICAS_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND LINHCES_CARACTERISTICAS_HCES1 = FGASIGL0.LINHCES_ASIGL0 AND FGCARACTERISTICAS_HCES1.IDCAR_CARACTERISTICAS_HCES1 = '". \Config::get("app.ArtistCode")."'" )
							->where("COD_SUB", $lot->cod_sub)
			 				#ordenamos por orden, pero tambien tenemos en cuenta la referencia ya que por defecto el orden esta a nully rompia la ordenacion
							->ActiveLotAsigl0()->first();

		if($numArtist->numartist == 1  && count($artists) == 1){
			$artist = $artists[0];
		}

	}
@endphp

			<div class="container ">
				<div class="row ">
					<div class=" mt-3 mb-2 text-center" >
						<h3>{{ trans(\Config::get('app.theme').'-app.galery.exposicionesArtista') }}</h3>
					</div>
					<div class="gridExhibitions">
						@foreach($exhibitions as $exhibition)
							@include('includes.galery.exhibition')
						@endforeach
					</div>
				</div>
			</div>
		@endif


<script>
$("#biographyView").on("click", function(){
//	if($("#biographyArtistText").hasClass("hidden"){
		$("#biographyArtistText").toggleClass("hidden");
		$("#desplegableOFF").toggleClass("hidden");
		$("#desplegableON").toggleClass("hidden");
	//}
})
</script>
