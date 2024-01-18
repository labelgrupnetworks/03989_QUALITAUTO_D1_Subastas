@if(!empty($auction) && $auction->tipo_sub != "W" && !empty($auction->descdet_sub) )
<div class="descripcion pt-1 ">
	<div class="row">
		@if($auction->tipo_sub == "O")
			<div class="col-xs-12  pb-4">
				{!! \BannerLib::bannersPorKey('SUBASTA_ONLINE_TOP', '', ['dots' => false, 'autoplay' => false, 'arrows' => false]) !!}
			</div>
		@endif
		<div class="col-xs-12  descripcion-left">
			<div class=" descripcion-cat pb-4">
				{!! $auction->descdet_sub !!}
			</div>
		</div>
	</div>
</div>

@elseif(!empty($auction) && $auction->tipo_sub == "W")

<?php

	$sessionFiles = new App\Models\V5\AucSessionsFiles();
	$files = $sessionFiles->where('"auction"', $auction->cod_sub)->get();


	$col = empty($files)? "col-lg-12" : "col-lg-8";

	$aucSessions = new  App\Models\V5\AucSessions();
	$sessions = $aucSessions->JoinLang()->where('"auction"', $auction->cod_sub)->orderby('"start"')->orderby('"reference"')->get();

?>

<div class="descripcion pt-1 ">
	<div class="row">
		<div class=" {{$col}} col-xs-12 descripcion-left">
			<div class="d-flex descripcion-cat pb-4">
				<div class="col-auto col-sm-12 auctionInfoimgAuction">

					<a href="{{ \Tools::url_auction($auction->cod_sub, $auction->des_sub,$auction->id_auc_sessions, '001' )}}">
						<img  src="{{\Tools::url_img_auction('subasta_medium',$auction->cod_sub)}}">
					</a>
					@if($auction->tipo_sub == "W")
						@foreach($sessions as $session)
							@if(empty($url_tiempo_real) && strtotime($session->end) > time())

								<center>

									<div class=" widget full-screen d-inline-flex pt-2" style="">
										<div class="bid-online"></div>
										<div class="bid-online animationPulseRed"></div>
										<a href="{{ \Tools::url_real_time_auction($session->auction,$session->name,$session->id_auc_sessions)}}" target="_blank" class=" d-flex">
											<img  src="/themes/duran/assets/img/duran_live.png" style="width: 250px;">
										</a>
									</div>
								</center>
								@break;
							@endif
						@endforeach
					@endif
				</div>


				<div class="col-md-9 col-sm-12" style="position: relative">
				@if($auction->tipo_sub == "W")
					<div class="col-xs-12">
					@foreach($sessions as $session)
						<p><strong> {{$session->name_lang??$session->name}}.  <span style="color: #760043;">  {{ date_format(date_create_from_format('Y-m-d H:i:s',$session->start),'d/m/Y H:i') }}</span> </strong></p>
						<p>{{ trans(\Config::get('app.theme').'-app.lot_list.lotes_desde',["ini" =>$session->init_lot,"fin" =>$session->end_lot]) }}</strong></p>


						<br/>
					@endforeach
					<p><strong>{{ mb_strtoupper(trans(\Config::get('app.theme').'-app.lot_list.info')) }}</strong></p>
					</div>

					<div  class=" col-xs-12  ">
						<div  class="DescDetText">
							{!! $auction->descdet_sub !!}
						</div>
					</div>


				@endif



				</div>
			</div>
		</div>


		<div class="col-lg-4 col-xs-12 descripcion-right">
			{{-- comentado para pro
			<div class="row bloque-v">

				@foreach($files as $file)


					<div class="col-xs-6 col-sm-3 col-md-6 pb-1" style="float: left;text-align: center;">
					<img class="pr-1" style="height:182px;" src="/files/{{ $file->img }}">
						<div class="botones-v w-100 d-flex">
							<a class="ver" href="{{ $file->url }}" target="_blank">{{ mb_strtoupper(trans(\Config::get('app.theme').'-app.sheet_tr.view')) }}</a>
							<a class="descargar" href="/files/{{ $file->path }}"  target="_blank">{{ mb_strtoupper(trans(\Config::get('app.theme').'-app.lot_list.download')) }}</a>
						</div>
					</div>
				@endforeach

			</div>
			--}}

			<div class="d-flex img-files-container justify-content-space-around">
				@foreach($files as $file)
				<div class="img-files-item">
					<img src="{{ $file->img }}">
					<div class="d-flex justify-content-space-between">
						{{-- @if ($auction->cod_sub <=616) --}}
							<a class="ver" href="{{ $file->url }}" target="_blank">{{ mb_strtoupper(trans(\Config::get('app.theme').'-app.sheet_tr.view')) }}</a>
							<a  class="descargar descargaPDF_JS" href="/files/{{ $file->path }}"  target="_blank"> {{ mb_strtoupper(trans(\Config::get('app.theme').'-app.lot_list.download')) }}  </a>
						{{-- @else
							<a style="width: 100%;text-align: center;" class="descargar descargaPDF_JS" href="/files/{{ $file->path }}"  target="_blank"> {{ mb_strtoupper(trans(\Config::get('app.theme').'-app.lot_list.download')) }}  </a>
						@endif --}}

					</div>
				</div>
				@endforeach
			</div>

		</div>

	</div>
</div>
@endif

<script>


function ReadMore (jObj, lineNum) { //class

var READ_MORE_LABEL = "{{ trans(\Config::get('app.theme').'-app.lot.viewMore') }}";
var HIDE_LABEL = "{{ trans(\Config::get('app.theme').'-app.lot.hideMore') }}";



var textMinHeight = ""+ (parseInt($("#" + jObj + " .DescDetText").css("line-height"),19)*lineNum) +"px";
var textMaxHeight = ""+$("#" + jObj + " .DescDetText").css("height");

console.log("textMinHeight " + textMinHeight);
console.log("textMaxHeight " + textMaxHeight);
console.log("DescDetText height " + parseInt($("#" + jObj + " .DescDetText").css("height")));


if(parseInt($("#" + jObj + " .DescDetText").css("height")) > 52  && $(document).width() < 1200){
	$("#" + jObj + " .DescDetText").css("height", ""+textMaxHeight);
	$("#" + jObj + " .DescDetText").css( "transition", "height .5s");
	$("#" + jObj + " .DescDetText").css("height", ""+textMinHeight);

	$("#" + jObj + " .DescDetText").parent().append("<span class=read-more-desc>"+READ_MORE_LABEL+"</span>");

	$("#" + jObj + " span").click ( function() {
		if ($("#" + jObj + " .DescDetText").css("height") == textMinHeight) {
		$("#" + jObj + " .DescDetText").css("height", ""+textMaxHeight);
		$("#" + jObj + " .read-more-desc").html(HIDE_LABEL);
		} else {
		$("#" + jObj + " .DescDetText").css("height", ""+textMinHeight);
		$("#" + jObj + " .read-more-desc").html(READ_MORE_LABEL);
		}
	});
}
}





	</script>
