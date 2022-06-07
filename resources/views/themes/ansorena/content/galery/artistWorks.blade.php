


 <div class="container galeryGrid">
	<div class="row galBanner">
		<div class="col-xs-12 col-md-4 " style="height: 360px;">
			<div class="galBannerText">

				<div class="col-x-12 galNameArtist">
					{{-- si hay mas de un artista no se pone nada --}}
					@php

						$name = explode(",",  $artist->name_artist);
						$artist->name_artist="";
						$val="";
						#si habia coma
						if(count($name)== 2){
							$artist->name_artist = $name[1] ." ";
						}
						$artist->name_artist .= $name[0];
					@endphp

						{{ mb_strtoupper($artist->name_artist)}}


					</div>

			</div>
		</div>

		<div class="col-xs-12 col-md-8 galeryBannerImage">

			<img src="{{"/img/autores/".$artist->id_artist.".jpg"}}">
		</div>

	</div>
</div>


<div class="container ">
	<div class="row ">


		<div class="gridGal">
			@php
				$varUrl="?artistaFondoGaleria=".$artist->id_artist;
			@endphp
			@foreach($lots as $lot)

				@include('includes.galery.lot')
			@endforeach
		</div>

		<div >




				<div id="biographyView" class="GalTitleExhibition mt-4" >{{ trans(\Config::get('app.theme').'-app.galery.biography') }}
					<span id="desplegableOFF" ><img src="/themes/ansorena/img/flechaDer.png"> </span>
					<span id="desplegableON" class=" hidden"><img src="/themes/ansorena/img/flechaAba.png"> </span>
				</div>
				<div  class="GalTextExhibition " >
					<div id="biographyArtistText" class=" hidden" >{!! $artist->biography_artist !!}</div>
				</div>

		</div>



	</div>
</div>



<script>
$("#biographyView").on("click", function(){
//	if($("#biographyArtistText").hasClass("hidden"){
		$("#biographyArtistText").toggleClass("hidden");
		$("#desplegableOFF").toggleClass("hidden");
		$("#desplegableON").toggleClass("hidden");
	//}
})
</script>
