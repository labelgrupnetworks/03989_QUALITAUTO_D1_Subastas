@extends('layouts.default')

@section('title')
	{{ $artist->name_artist }}
@stop

@section('content')
<?php
$bread[] = array("name" =>trans(\Config::get('app.theme').'-app.artist.artists'), "url" => route("artists")  );
$bread[] = array("name" => $artist->name_artist  );
?>


@php



@endphp

<div class="container">
	<div class="breadcrumb-total row">
		<div class="col-xs-12 col-sm-12 text-center color-letter">
			@include('includes.breadcrumb')

				<div class="col-xs-12 header_artist" >
					<div class="col-xs-12 col-md-6">


						@if (file_exists("img/autores/".$artist->id_artist.".jpg"))
							<img src="/img/autores/{{$artist->id_artist}}.jpg" >
						@endif
					</div>
					<div class="col-xs-12 col-md-6">
						<h1 class="name_artist">{{ $artist->name_artist }} </h1>
						<p class="info_artist"> {{ $artist->info_artist }} </p>
						<div class="bio_artist" id="biographyArtist">
							<div class="biographyArtistText"> {!! $artist->biography_artist !!}</div>
						</div>
						<div>
							<a style="float:right" href="/es/valoracion-articulos?tipo=valoracion">
								<button type="button" class="button-como-vender">{{trans(\Config::get('app.theme').'-app.valoracion_gratuita.vender_duran_consulta')}}</button>
							</a>
						</div>

					</div>
				</div>
				<div class="col-xs-12 articles_artist mt-4 mb-4 text-left" >


						<div >
							<ul class="nav nav-tabs">
								@if(count($activeLots)>0)
									<li class="active">
										<a data-toggle="tab" href="#activeLots" aria-expanded="false">{{trans(\Config::get('app.theme').'-app.artist.actualAuctions')}}</a>
									</li>
								@endif
								@if(count($historyLots)>0)
									<li class="@if(count($activeLots)==0)  active @endif">
										<a data-toggle="tab" href="#historyLots" aria-expanded="true">{{trans(\Config::get('app.theme').'-app.artist.passAuctions')}}</a>
									</li>
								@endif
								@if(count($articles)>0)
									<li class="@if(count($activeLots)==0 && count($historyLots)==0)   active @endif">
										<a data-toggle="tab" href="#articles" aria-expanded="true">{{trans(\Config::get('app.theme').'-app.artist.relatedArticles')}}</a>
									</li>
								@endif
							</ul>
							<div class="tab-content">

									@if(count($activeLots)>0)
										<div id="activeLots" class="tab-pane fade in active">
											<div class="row">
												@foreach($activeLots as $lot)
													@include("includes/artists/lot")
												@endforeach
											</div>
										</div>
									@endif

									@if(count($historyLots)>0)
										<div id="historyLots" class="tab-pane fade @if(count($activeLots)==0)  in active @endif" >
											<div class="row">
												@foreach($historyLots as $lot)
													@include("includes/artists/lot")
												@endforeach
											</div>
										</div>
									@endif

									@if(count($articles)>0)
										<div id="articles" class="tab-pane fade @if(count($activeLots)==0 && count($historyLots)==0)  in active @endif" >
											<ul>
											@foreach($articles as $article)
												<li> <a href="{{$article->url_artist_article}}"  target="_blank"> {{$article->title_artist_article}} </a></li>
											@endforeach
											</ul>
										</div>
									@endif

							</div>


						</div>



				</div>


			</div>

		</div>
	</div>
</div>


<script>



$("input[name=order]").on("click", function(){
	artistForm_JS.submit();
})

function ReadMore (_jObj, lineNum) { //class

var READ_MORE_LABEL = "{{ trans(\Config::get('app.theme').'-app.lot.viewMore') }}";
var HIDE_LABEL = "{{ trans(\Config::get('app.theme').'-app.lot.hideMore') }}";

var jObj = _jObj;

var textMinHeight = ""+ (parseInt(jObj.children(".biographyArtistText").css("line-height"),19)*lineNum) +"px";
var textMaxHeight = ""+jObj.children(".biographyArtistText").css("height");
if(parseInt(jObj.children(".biographyArtistText").css("height")) > 52 && $(document).width() > 768){
	jObj.children(".biographyArtistText").css("height", ""+textMaxHeight);
	jObj.children(".biographyArtistText").css( "transition", "height .5s");
	jObj.children(".biographyArtistText").css("height", ""+textMinHeight);

	jObj.append("<span class=read-more-artist>"+READ_MORE_LABEL+"</span>");
/*
	jObj.children(".read-more-artist").css({
		"color": "#283747",
		"font-weight": "bold",
		"cursor": "pointer",
		"margin":"0",
		"height": "auto"
	});
*/
	jObj.children("span").click ( function() {
		if (jObj.children(".biographyArtistText").css("height") == textMinHeight) {
		jObj.children(".biographyArtistText").css("height", ""+textMaxHeight);
		jObj.children(".read-more-artist").html(HIDE_LABEL);
		} else {
		jObj.children(".biographyArtistText").css("height", ""+textMinHeight);
		jObj.children(".read-more-artist").html(READ_MORE_LABEL);
		}
	});
}
}

ReadMore($("#biographyArtist"),10);

</script>

@stop

