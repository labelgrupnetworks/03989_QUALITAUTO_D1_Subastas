@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('assets_components')
<link href="{{ Tools::urlAssetsCache('/css/default/noticias.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="{{ Tools::urlAssetsCache("/themes/" . env('APP_THEME') . "/css/noticias.css") }}" >;
@endsection

@section('content')
@include('includes.modals')

<style>
	<?="@import url('https://fonts.googleapis.com/css?family=Playfair+Display:700'); "?>
</style>

@php
if(\Config::get("app.emp") == '001' || \Config::get("app.emp") == '002'){
	#mostraremos el menu de condecoraciones solo si pertenece a condecoraciones
		if($data['news']->id_category_blog ==2 ){
			$key = "MENUCONDECORACIONES";
		}else{
			$key = "MENUANSORENA";
		}
		#si aun no ha encontrado un menu que sustituir
		$pagina = new App\Models\Page();

		$menuEstaticoHtml  = $pagina->getPagina(\Config::get("app.locale"),$key);
		if(!empty($menuEstaticoHtml)){
			echo $menuEstaticoHtml->content_web_page;
		}

}
@endphp
<section>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 text-center color-letter titlepage-contenidoweb">
				<h1 class="titlePage">{{ $data['news']->titulo_web_blog_lang }}</h1>

			</div>
		</div>
	</div>
</section>





<section class="blog_title_content">
	<div class="container">
		<div class="row">

			<div class="col-xs-12 text-center">
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
			<div class="col-xs-12">

				<div class="col-md-12 col-xs-12">

					@php
						// Ruta del directorio donde están los archivos
							$path  = 'img/blog/'.$data['news']->id_web_blog;

							$files = array();
							// Arreglo con todos los nombres de los archivos
							if(is_dir($path)){
								$files = array_diff(scandir($path), array('.', '..'));
							}


					@endphp
					@if(count($files)>0)
						<div class='row rowBanner'>
							<div class="column_banner columnSliderBlog col-xs-6  col-xs-offset-3">
								<div id="sliderBlog" class="sliderBlog">
									@foreach($files as $file)
										<div class="item ">
											<img class="imgPopUpCall_JS cursor" src="/img/blog/{{ $data['news']->id_web_blog }}/{{$file}}" >
										</div>
									@endforeach
								</div>
							</div>
						</div>
					{{-- si no hay slider pondremso la foto--}}
					@else
						<div class="post_body_image">

								@if(!empty($data['news']->video_web_blog_lang) &&	str_is('*youtu*',$data['news']->video_web_blog_lang) == true)
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

					@endif
				</div>
			</div>

		</div>
	</div>
</section>

<section class="article-body mt-2">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
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
</section>



<section class="entradas-realacionadas">
	<div class="container">
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
							<a href="{{ $url }}">
							<img class="img-responsive" src="{{ $rel_link->img_web_blog }}">
							</a>
						</div>
						<div class="button-post">
							<a href="{{ $url }}"
								role="button"><?= trans(\Config::get('app.theme').'-app.blog.more') ?></a>
						</div>
					</div>



					@endforeach
				</div>
			</div>
		</div>
	</div>
</section>

<script>
	<?php
            $sub_categories_web = str_replace(",","','",$data['news']->lot_sub_categories_web_blog);

            $key = "relacionados_noticia";
            $replace = array(
                  'sec'=>$sub_categories_web,
                  'lang' => Config::get('app.language_complete')[Config::get('app.locale')] ,
                'emp' => Config::get('app.emp') ,
                      );
        ?>
        var replace = <?= json_encode($replace) ?>;
        var key ="<?= $key ?>";
    $( document ).ready(function() {

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


	$('#sliderBlog').slick();


</script>
@stop
