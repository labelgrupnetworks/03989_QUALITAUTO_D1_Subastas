@php
	$years=[];
	$auctions=[];
	foreach($exhibitions as $exhibition){
		$startDate =  Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $exhibition->dfec_sub)->locale(\Tools::getLanguageComplete(\Config::get('app.locale')));
		#Se organiza por temporadas y no por años, las temporadas empiezan el septiembre y acaban en agosto
		#si el mes es menor que septiembre pertenece a la temporada anterior, por lo que restaremos un año
		$year = $startDate->year;
		if($startDate->month <9){
			$year--;
		}
		if(empty($years[$year])){
			$years[$year] = [];
		}
		$years[$year][]=$exhibition;

		#código de subasta para buscar el lote
		$auctions[]=$exhibition->cod_sub;

	}

	#cojer todos los primeros lotes para poder sacar las iamgenes.
	$fgasigl0 = new  App\Models\V5\FgAsigl0 ;
	$lots = $fgasigl0->select("cod_sub, numhces_asigl0, linhces_asigl0")->JoinSubastaAsigl0()->where("ref_asigl0",1)->wherein("cod_sub",$auctions)->get();
	$imgSubastas = [];
	foreach ($lots as $lot){
		$imgSubastas[$lot->cod_sub] = \Tools::url_img("square_medium", $lot->numhces_asigl0, $lot->linhces_asigl0, null, true);
	}

	#cojer los artistas
	$fgasigl0 = new  App\Models\V5\FgAsigl0 ;
	$artists =	$fgasigl0->JoinSubastaAsigl0()
				->select("COD_SUB, NAME_ARTIST, ID_ARTIST")
				->join('FGCARACTERISTICAS_HCES1', 'FGCARACTERISTICAS_HCES1.EMP_CARACTERISTICAS_HCES1 = FGASIGL0.EMP_ASIGL0 AND NUMHCES_CARACTERISTICAS_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND LINHCES_CARACTERISTICAS_HCES1 = FGASIGL0.LINHCES_ASIGL0')
				->join('WEB_ARTIST', 'WEB_ARTIST.EMP_ARTIST = FGCARACTERISTICAS_HCES1.EMP_CARACTERISTICAS_HCES1 AND WEB_ARTIST.ID_ARTIST =  FGCARACTERISTICAS_HCES1.IDVALUE_CARACTERISTICAS_HCES1')
				->wherein("cod_sub",$auctions)
				->groupby("COD_SUB, ID_ARTIST, NAME_ARTIST")
				->get();
	$artistSub = [];
	foreach ($artists as $artist){
		if(empty($artistSub[$artist->cod_sub]) ){
			$artistSub[$artist->cod_sub] = [];
		}

		$name = explode(",",  $artist->name_artist);
		$artist->name_artist="";
			$val="";
			#si habia coma
			if(count($name)== 2){
				$artist->name_artist = $name[1] ." ";
			}
			$artist->name_artist .= $name[0];

		$artistSub[$artist->cod_sub][] =   $artist;
	}


@endphp



<div class="container ">
	<div class="row ">

			@foreach($years as  $year => $exhibitions)
				<div class="yearExhibition" >  <span>{{ trans(\Config::get('app.theme').'-app.galery.season') }}    {{$year}} - {{$year+1}}</span>  </div>
				<div class="gridExhibitions">
					@foreach($exhibitions as $exhibition)
						@php
							$artist=null;
							#si hay artistas y solo hay uno, si no hay o si hay mas de uno pondremos colectiva
							if( !empty($artistSub[$exhibition->cod_sub]) &&  count($artistSub[$exhibition->cod_sub]) == 1){
								$artist = $artistSub[$exhibition->cod_sub][0];
							}

						@endphp



						@include('includes.galery.exhibition')
					@endforeach
				</div>
			@endforeach

	</div>
</div>
