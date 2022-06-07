

	<div class = " mt-2 exhibitionGridItem">
		<div >
			<a href="{{\Tools::url_exposicion($exhibition->des_sub, $exhibition->cod_sub)}}">
				<img src="{{$imgSubastas[$exhibition->cod_sub]?? \Tools::url_img_auction('subasta_medium',$exhibition->cod_sub,'001')}}"/>
			</a>
		</div>


		<div class="exhibitionGridArtist mt-1">
			{{-- poner nombre de artista si la exposicion no se llama igual que el artista  --}}

				@if(!empty($artist) &&  (trim(mb_strtoupper($exhibition->des_sub) ) != trim(mb_strtoupper($artist->name_artist))))
					<a href="{{Route("artistaGaleria",["id_artist" => $artist->id_artist])}}">
						{{$artist->name_artist}}
					</a>
				@elseif(empty($artist))
					{{ trans(\Config::get('app.theme').'-app.galery.collective') }}
				@endif

			</div>
			<div class="exhibitionGridTitle ">
				<a href="{{\Tools::url_exposicion($exhibition->des_sub, $exhibition->cod_sub)}}">
					{{$exhibition->des_sub}}
				</a>
			</div>
		<div class="exhibitionGridDate">
			@php
				$startDate =  Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $exhibition->dfec_sub)->locale(\Tools::getLanguageComplete(\Config::get('app.locale')));
				$endDate =  Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $exhibition->hfec_sub)->locale(\Tools::getLanguageComplete(\Config::get('app.locale')));
			@endphp
			{{$startDate->day}} {{ trans(\Config::get('app.theme').'-app.galery.de') }} {{$startDate->monthName}}
			-
			{{$endDate->day}}  {{ trans(\Config::get('app.theme').'-app.galery.de') }} {{$endDate->monthName}}
			<br/>
			{{$endDate->year}}
		</div>

	</div>
</a>
