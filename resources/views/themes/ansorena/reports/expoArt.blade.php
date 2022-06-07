<style>
.header{
	width:100%;
}
.title{
	width:50%;
	text-align: center;
}
.imgAuction{
	width:50%;
	text-align: left;
}
.imgAuction img {
	max-height:200px;
}

.precio{
	text-align: right;
}

.sinIva{
	text-align: right;
}

</style>

<table class="header">
	<tr>
		<td class="title">
			{{-- si hay mas de un artista no se pone nada --}}
			@if(count($artists) == 1 )
			<H1>	{{$artists[0]->name_artist}}</H1>
			@endif

			{{-- Si no hay artistao el que hay no se llama igual que la exposición --}}
			@if (	count($artists) == 0 ||(  trim(mb_strtoupper($auction->des_sub) ) != trim(mb_strtoupper($artists[0]->name_artist)) ))
					<h2>	{{$auction->des_sub}}</h2>
			@endif


			@php
			$startDate =  Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $auction->dfec_sub)->locale(\Tools::getLanguageComplete(\Config::get('app.locale')));
			$endDate =  Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $auction->hfec_sub)->locale(\Tools::getLanguageComplete(\Config::get('app.locale')));
				@endphp

				{{$startDate->day}} {{ trans(\Config::get('app.theme').'-app.galery.de') }} {{$startDate->monthName}}
				-
				{{$endDate->day}}  {{ trans(\Config::get('app.theme').'-app.galery.de') }} {{$endDate->monthName}}
				<br/>
			{{$endDate->year}}
		</td>
		<td class="imgAuction">
			@php
				$arrContextOptions=array(
					"ssl"=>array(
						"verify_peer"=>false,
						"verify_peer_name"=>false,
					),
				);
				$image = \Tools::url_img_auction('subasta_large',$auction->cod_sub,$auction->reference);
				$imageData = base64_encode(file_get_contents($image, false, stream_context_create($arrContextOptions)));
				$src = 'data:image/jpg;base64,'.$imageData;
			@endphp
			<img src="{{$src}}" />
			{{--
			<img src="{{Config::get('app.url')}}/img/thumbs/780/AUCTION_{{\Config::get("app.emp")}}_{{$auction->cod_sub}}.jpg"/>
			--}}
		</td>
	</tr>
</table>

<br/>

	<table class="table-bordered" style="width: 100%">

		<tbody>
			@foreach($lots as $lot)
				<tr>
					<td>{{$lot->ref_asigl0}} </td>
					<td>{{$lot->descweb_hces1}} </td>

					<td>

						@if(!empty($caracteristicas[$lot->num_hces1."_".$lot->lin_hces1][2]))
							{{$caracteristicas[$lot->num_hces1."_".$lot->lin_hces1][2]}}
						@endif
					</td>
					<td>
						@if(!empty($caracteristicas[$lot->num_hces1."_".$lot->lin_hces1][3]))
							{{$caracteristicas[$lot->num_hces1."_".$lot->lin_hces1][3]}}
						@endif
					</td>
					<td class="precio">{{ \Tools::moneyFormat($lot->impsalhces_asigl0)}}€ </td>
				</tr>
			@endforeach

		</tbody>
	</table>

	<table class="table-bordered" style="width: 100%">
		<tbody>
				<tr>
					<td class="sinIva">Sin Iva </td>

				</tr>
		</tbody>
	</table>
