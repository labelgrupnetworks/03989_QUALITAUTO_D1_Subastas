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
	var ortSec = "{{ $ortsec?? ''}}";
	var sec = "{{ $sec?? ''}}";
	var familia = "{{ $familia?? ''}}";
	const language = "{{config('app.locale')}}";

</script>
<script src="/js/default/app.js"></script>
{{-- en el servidor no esta funcionando el mis, no encuentra la ruta
<script src="{{ mix('/js/default/app.js') }}"></script>

--}}
@stop
