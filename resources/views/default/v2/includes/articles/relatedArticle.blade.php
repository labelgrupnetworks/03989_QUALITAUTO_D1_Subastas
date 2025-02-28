
@php
$titulo=$relatedArticle->model_art0;

#sumarle el iva al producto
$imp =  round($relatedArticle->pvp_art0 + ($relatedArticle->pvp_art0 * $iva), 2);
$impFormat = \Tools::moneyFormat($imp, trans('web.subastas.euros'), 0);
$url=route("article", ["idArticle" => $relatedArticle->id_art0, "friendly" =>\Str::slug($titulo)])

@endphp

<a class="article article-element" href="{{$url}}">

	<div class="article-foto" style="background-image: url(/articulos/{{$relatedArticle->id_art0}}.jpg)"  ></div>

	<div class="article-content">
		<div class="artTitle"> {{$titulo}}</div>
		<div class="artPrice">  {{$impFormat}}</div>
	</div>
</a>
