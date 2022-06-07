@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('assets_components')
<link href="{{ Tools::urlAssetsCache('/css/default/noticias.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="{{ Tools::urlAssetsCache("/themes/" . env('APP_THEME') . "/css/noticias.css") }}" >;
@endsection

@section('content')

<!-- titlte & breadcrumb -->
<section>
	<div class="container custom-container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 color-letter titlepage-contenidoweb">

				<div class="contenido contenido-web">
					<?php
					$bread[] = array("name" => trans(\Config::get('app.theme').'-app.blog.name'), 'url'=> "/" . \Routing::slugSeo('blog') );
					$titulo_post = $data['news']->titulo_web_blog_lang;
					$bread[] = array("name" => $titulo_post) ;
					?>

					@include('includes.breadcrumb')
					<h1 class="titlePage">{{ $data['news']->titulo_web_blog_lang }}</h1>

				</div>

			</div>
		</div>
	</div>
</section>

<style>
	<?="@import url('https://fonts.googleapis.com/css?family=Playfair+Display:700'); "?>
</style>


<section class="blog_title_content">
	<div class="container custom-container">
		<div class="row">

			<div class="col-xs-12 text-center">

				<div class="contenido contenido-web">
					<div class="date-post-principal article-data text-center">
						<?php

							$fecha = strftime('%d %b %Y',strtotime($data['news']->publication_date_web_blog));

							if(\App::getLocale() != 'en'){
								$array_fecha = explode(" ",$fecha);
								$array_fecha[1] = \Tools::get_month_lang($array_fecha[1],trans(\Config::get('app.theme')."-app.global.month_large"));
								$fecha = $array_fecha[0].' '.$array_fecha[1].' '.$array_fecha[2];
							}

							?>
						<p>{{ $fecha }}</p>
					</div>
				</div>
			</div>

			<div class="col-xs-12">

				<div class="contenido contenido-web">
					<div class="col-md-12 col-xs-12">
						<div class="post_body_image">

							@if(!empty($data['news']->video_web_blog_lang) &&
							str_is('*youtu*',$data['news']->video_web_blog_lang) == true)
							<?php
									$cod_video = $data['news']->video_web_blog_lang;
									$cod_video = str_replace('https://youtu.be/', 'https://www.youtube.com/embed/', $cod_video);
								?>
							<iframe class="video_post" style="width: 100%;min-height: 462px;" width="560" height="315"
								src="{{ $cod_video }}" frameborder="0" allow="autoplay; encrypted-media"
								allowfullscreen></iframe>
							@else
							<img class="img-responsive center-block img-web-blog" src="{{ $data['news']->img_web_blog }}">

							@endif

						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</section>

<section>
	<div class="container custom-container">
		<div class="row">
			<div class="col-xs-12">
				<div class="contenido contenido-web">
					<?php
						$data['news']->texto_web_blog_lang = str_replace("a:visited", ".post_body a:visited", $data['news']->texto_web_blog_lang);
						$data['news']->texto_web_blog_lang = str_replace("a:link", ".post_body a:link", $data['news']->texto_web_blog_lang);
						$data['news']->texto_web_blog_lang = str_replace("<style>", "<style>/*", $data['news']->texto_web_blog_lang);
						$data['news']->texto_web_blog_lang = str_replace("</style>", "*/</style>", $data['news']->texto_web_blog_lang);
					?>
					<div class="cuerpo-del-articulos">
						<?= $data['news']->texto_web_blog_lang ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>



<section>
	<div class="container custom-container">
		<div class="row">
			<div class="col-xs-12">
				<div class="article-categoria-titulo">
					{{ trans(\Config::get('app.theme').'-app.blog.post_related') }}:
				</div>
				<div class="entradas-relacionadas-lista">
					@foreach($data['relationship_new'] as $rel_link)
					<?php
                                    $url = \Routing::slug('blog').'/'.$data['categorys'][$rel_link->primary_category_web_blog]->url_category_blog_lang.'/'.$rel_link->url_web_blog_lang
                                ?>
					<div class="col-md-4 entrada-relacionada-item col-xs-6 d-flex flex-direction-column justify-content-space-between">
						<div class="entrada-relacionada-title">
							{{ $rel_link->titulo_web_blog_lang }}
						</div>
						<div class="img-related-post">
							<img class="img-responsive" src="{{ $rel_link->img_web_blog }}">
						</div>
						<div>
							<a class="btn button-principal" href="{{ $url }}"
								role="button"><?= trans(\Config::get('app.theme').'-app.blog.more') ?></a>
						</div>
					</div>



					@endforeach
				</div>
			</div>
		</div>
	</div>
</section>

@php
	$sub_categories_web = str_replace(",","','",$data['news']->lot_sub_categories_web_blog);
	$replace = array(
				'sec'=> $sub_categories_web,
				'lang' => Config::get('app.language_complete')[Config::get('app.locale')] ,
				'emp' => Config::get('app.emp') ,
			);
@endphp

<script>
	var replace = @json($replace);
	var key = "relacionados_noticia";

	$(document).ready(function() {

		ajax_carousel(key,replace);

		if($('.post_recents_list ul li').length < 4){
			$('.post_recents_button').hide();
		}

		$('.post_recents_button').on('click', function(){
			$('.post_recents_list ul li').toggleClass('active');

			if($(this).attr('data-open') === 'open'){
				$(this).text('Ver más')
				$(this).attr('data-open', 'close')

			}else{
				$(this).text('Ver menos')
				$(this).attr('data-open', 'open')
			}
		});

	});
</script>
@stop
