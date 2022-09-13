@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')

<link href="{{ Tools::urlAssetsCache('/css/default/articles.css') }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/css/articles.css') }}" rel="stylesheet" type="text/css">
<div class="container">
	<div class="row">
		<div id="grid">

		</div>

	</div>
</div>
{{-- cargamos las variables que vienen por url --}}
<script>
const queryParams = new URLSearchParams(window.location.search)


	var ortSec = "{{ $ortsec?? ''}}";
	var sec = "{{ $sec?? ''}}";
	var familia = "{{ $familia?? ''}}";
	var tallaColor = queryParams.get('tallaColor') || "";
	var order = queryParams.get('order') || "id_art0";
	var orderDir = queryParams.get('order_dir') || "desc";
	{{-- Página indicada en la url mediante variable get, se utiliza para la carga inicial de react --}}
	var startPage = (queryParams.get('page')) || "1";
	const language = "{{config('app.locale')}}";
	{{-- Url inicial para que cuando se carga el react no sobreescriba la url, despues ya se usa urlarticulos --}}
	var startUrl = location.origin + location.pathname;
	var urlArticulos = "{{ Route("articles") }}";

</script>
<script src="{{ Tools::urlAssetsCache("/js/default/app.js") }}"></script>
{{-- en el servidor no esta funcionando el mis, no encuentra la ruta
<script src="{{ mix('/js/default/app.js') }}"></script>

--}}
@stop
