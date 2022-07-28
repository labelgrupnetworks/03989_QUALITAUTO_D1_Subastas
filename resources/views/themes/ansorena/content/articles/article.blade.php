@php
	$titulo=$article->model_art0;
	$img = $article->img; # "https://ansorenacompromiso.com/sites/default/files/styles/sortija_ficha/public/images/productos/ar_1762-2.png";
	$description = $article->des_art0;
	#sumarle el iva al producto


@endphp
<div class="container fichaArticulo">
	<div class="row">
		<div class="col-xs-12 col-sm-7 pb-3">
			<div id="img_main" class="img_single">
				<a title="{{$titulo}}" >
				<img class="img-responsive" src="{{ $img}}">
				</a>
			</div>

			<div  class="sliderthumbArticulo ">
				@if(count($article->images) > 1)
					@foreach($article->images as $image)
					<div  class="sliderThumbsArticulos"  >
						<img src="{{$image}}" data-img="{{$image}}" >
					</div>
					@endforeach
				@endif
			</div>

		</div>

		<div class="col-xs-12 col-sm-5">
			<form id="articleForm">
				<input type="hidden" name="idArt0" value="{{$article->id_art0}}">
				<div class="col-xs-12 titleArticlePage"> {{$titulo}}</div>
				<div class="col-xs-12 descriptionArticlePage"> {{$description}}</div>

					@if( $article->imp> 0)
					<div class="col-xs-12 priceArticlePage">
						<span id="art-original_JS" class="art-price_JS"  >{{ \Tools::moneyFormat($article->imp,'' , 0)}} </span>
						@foreach($precioArticulos as $keyArticulo =>$precioArticulo)
							<span id="art-{{$keyArticulo}}_JS" class="art-price_JS hide" >{{ \Tools::moneyFormat($precioArticulo,'' , 0)}} </span>
						@endforeach
						{{trans(\Config::get('app.theme').'-app.subastas.euros')}}
					</div>
					@else
					<div class="col-xs-12 titleArticlePage mt-1">
						{!! trans(\Config::get('app.theme').'-app.articles.consultarPrecio') !!}
					</div>
					@endif


				@php
					#solo recargaremos el segundo select de variantes con la eleccion del primero y ñle pondremso la clase PrincipalTallaColor_JS
					$numVariante=0;
				@endphp
				@foreach($variantes as $keyVariante => $variante)
				<div class=" col-xs-12 @if($numVariante!=0)  hidden  @endif mt-1">
					<label> {{$variante}} </label>
					<br>

						<select name="tallaColor[{{$keyVariante}}]"  class="tallaColor_JS  @if($numVariante==0) PrincipalTallaColor_JS   @endif ">
						{{--se rellena por json --}}
						</select>

					@if ($variante == "TALLAS")
					{!! trans(\Config::get('app.theme').'-app.articles.consultar_tallas') !!}</a>
					@endif
				</div>
				@php $numVariante++; @endphp
				@endforeach

				<div class=" buyButtonArticlePage ">
					{{-- código de token --}}
					@csrf

					{{-- 07-07-22 Mónica ha pedido que los articulos de la seccion joyas únicas muestre siempre el cotactenos, lo saco del circuito de js que mlo muestra is hay stock  --}}
				@if($article->sec_art0 == 'JX' || $article->imp== 0)
					<div class="col-xs-12 mt-1 descriptionArticlePage">
						<br/>{!! trans(\Config::get('app.theme').'-app.articles.contactenos') !!}<br/><br/>
					</div>
				@else
					<div class="siStock_JS col-xs-12 mt-1">
						<button style="width: 100%;" class="button-principal addCartButton addArticleCard_JS" type="button" >{{ trans(\Config::get('app.theme').'-app.articles.addCart') }}</button>
					</div>
					<div class="col-xs-12 mt-1  noStock_JS hidden">
						{{--
							Ansorena Han pedido que no aparezca el no disponible
							<button style="width: 100%;"  disabled="disabled" type="button" >{{ trans(\Config::get('app.theme').'-app.articles.outStock') }}</button> --}}
						<br/><br/>{{ trans(\Config::get('app.theme').'-app.articles.contactenos') }}<br/><br/>

					</div>
				@endif
					<div class=" col-xs-6 mt-1">
						<a  href="{{ trans(\Config::get('app.theme').'-app.articles.hrefMoreInfo',["joya" =>$titulo]) }}">
							<button style="width: 100%;" class="button-secondary " type="button" >{{ trans(\Config::get('app.theme').'-app.articles.moreInfo') }}</button>
						</a>
					</div>

					<div class=" col-xs-6 mt-1">
						<a  href="{{ trans(\Config::get('app.theme').'-app.articles.hrefCreatujoya',["joya" =>$titulo] ) }}">
							<button style="width: 100%;" class="button-secondary " type="button" >{{ trans(\Config::get('app.theme').'-app.articles.creaTujoya') }}</button>
						</a>
					</div>


						</div>
				<div  class="col-xs-12 mt-1 ">
					@include('includes.ficha.share')
				</div>
			</form>

		</div>
	</div>

	<div class="col-xs-12 relatedArticles">
		<h2 class="text-center titleReatedArticles">  {{ trans(\Config::get('app.theme').'-app.articles.more') }}  </h2>
		@php
			$relatedArticles = App\Models\articles\FgArt0::select("ID_ART0, MODEL_ART0,   DES_ART0, PVP_ART0, SEC_ART0")->where("ID_ART0","!=", $article->id_art0)->where("SEC_ART0","=", $article->sec_art0)->Activo()->orderby(" DBMS_RANDOM.VALUE")->take(3)->get();
		@endphp
		<div class="Grid articles-container">
			@foreach($relatedArticles as $relatedArticle)
				@include("includes/articles/relatedArticle")
			@endforeach
		</div>
	</div>
</div>

<script>

getTallasColoresFicha();
/*
// no tiene que aparecer marcada ningun rsultado por defecto ya que no apareceran el resto de selects
$("select ").each(function(){
				$(this).val("");
		})
*/
		$(".sliderThumbsArticulos img").on("click", function(){
			loadSeaDragon($(this).data("img"));
		});

function loadSeaDragon(img){

	var element = document.getElementById("img_main");
	console.log()
	while (element.firstChild) {
		element.removeChild(element.firstChild);
	}
	OpenSeadragon({
		id:"img_main",
		prefixUrl: "/img/opendragon/",

		showReferenceStrip:  true,


		tileSources: [{
				type: 'image',
				url:img
			/*	url:  '/img/load/real/'+img*/
			}],
		showNavigator:false,
	});
}
loadSeaDragon('{{$img}}');


$(".sliderthumbArticulo").slick({
	slidesToShow: 6,
 	slidesToScroll: 6,
//	prevArrow: $('.prev'),
//	nextArrow: $('.next'),
	nextArrow: '<i class="icon fas fa-arrow-alt-circle-right nextArrowBtn"></i>',
    prevArrow: '<i class="icon fas fa-arrow-alt-circle-left prevArrowBtn"></i>',
	focusOnSelect: true,

	responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 4,
					slidesToScroll: 4,

				}
			},
			{
				breakpoint: 600,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2,


				}
			}

		]
});
</script>
