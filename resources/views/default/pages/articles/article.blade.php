@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')
<script src="{{ URL::asset('js/openseadragon.min.js') }}"></script>
<script src="{{ Tools::urlAssetsCache('js/default/articles.js') }}"></script>

<link href="{{ Tools::urlAssetsCache('/css/default/articles.css') }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/css/articles.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
<script>
var logged = {{ Session::has('user')? "true" : "false"  }} ;
var lang = '{{ \Config::get("app.locale")}}';
</script>
<?php

        $bread = array();
	if( !empty(\Config::get("app.uniqueArtCategory"))){
		#hace falta tener en links la traducción montada con el nombre de la categoria y _category
		$bread[] = array("url" => route('articles-category',["category" => trans(\Config::get('app.theme').'-app.links.'.\Config::get("app.uniqueArtCategory").'_category')]), "name" =>trans(\Config::get("app.theme")."-app.articles.articles") );
	}else{
        $bread[] = array("url" => Route("articles"), "name" =>trans(\Config::get("app.theme")."-app.articles.articles") );
	}
        $bread[] = array( "name" => $article->model_art0 );

?>


<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 color-letter">
			@include('includes.breadcrumb_before_after')
		</div>
	</div>
</div>
@include('content.articles.article')
@stop
