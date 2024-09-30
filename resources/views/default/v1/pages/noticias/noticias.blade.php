@extends('layouts.default')

@section('title')
{{ trans($theme.'-app.head.title_app') }}
@stop

@section('assets_components')
<link href="{{ Tools::urlAssetsCache('/css/default/noticias.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="{{ Tools::urlAssetsCache("/themes/$theme/css/noticias.css") }}" >
@endsection

@section('content')
<style>
	@import url('https://fonts.googleapis.com/css?family=Noto+Serif+KR:400,500,700');
</style>

<!-- titlte & breadcrumb -->
<section>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 text-center color-letter titlepage-contenidoweb">

				<?php
			$bread = array();
			$bread[] = array("name" => trans($theme.'-app.blog.name'), 'url' => "/" . \Routing::slugSeo('blog'));
			if(!empty ($data['categ'])){
				$categoria = $data['categ']->title_category_blog_lang;
				$bread[] = array("name" => $categoria );
			}
		?>

				@if(empty ($data['categ']))
				<h1 class="titlePage"><?= trans($theme.'-app.blog.principal_title') ?>
				</h1>
				@else
				<h1 class="titlePage">{{ $data['categ']->title_category_blog_lang }}</h1>
				@endif

				@include('includes.breadcrumb')

			</div>
		</div>
	</div>
</section>

<!-- Posts -->
<section class="post_content">
	<div class="container">
		@if(count($data['noticias']) != 0)


		@foreach ($data['noticias'] as $key => $noticias)
		@php
		$url = \Routing::slug('blog').'/'.$data['categorys'][$noticias->primary_category_web_blog]->url_category_blog_lang.'/'.$noticias->url_web_blog_lang;
		@endphp

		<div class="row entrada-post">

			<div class="col-sm-12 col-xs-12 primer-post ">
				<div class="col-xs-12  hidden-md hidden-lg">
					<img alt="{{$noticias->titulo_web_blog_lang}}" class="img-responsive img-blog"
						src="{{$noticias->img_web_blog}}">
				</div>
				<div class="col-xs-12 col-md-7">
					<div class="post-conent-principal">
					<div class="title-post-principal">
						<p>{{$noticias->titulo_web_blog_lang}}</p>
					</div>
					<div class="date-post-principal">
						<?php
							$fecha = strftime('%d %b %Y',strtotime($noticias->publication_date_web_blog));

							if(\App::getLocale() != 'en'){
								$array_fecha = explode(" ",$fecha);
								$array_fecha[1] = \Tools::get_month_lang($array_fecha[1],trans($theme."-app.global.month_large"));
								$fecha = $array_fecha[0].' '.$array_fecha[1].' '.$array_fecha[2];
							}
							?>
						<p>{{ $fecha }}</p>
					</div>
					<div class="resumen resumen-principal">
						@php
						$noticias->texto_web_blog_lang = str_replace("a:visited", ".post_body a:visited",
						$noticias->texto_web_blog_lang);
						$noticias->texto_web_blog_lang = str_replace("a:link", ".post_body a:link",
						$noticias->texto_web_blog_lang);
						$noticias->texto_web_blog_lang = str_replace("<style>
							", "<style>
							/*", $noticias->texto_web_blog_lang);
						$noticias->texto_web_blog_lang = str_replace("
						</style>", "*/</style>", $noticias->texto_web_blog_lang);
						@endphp
						{!! $noticias->texto_web_blog_lang !!}

					</div>
					<div class="button-post">
						<a href="{{ $url }}"><?= trans($theme.'-app.blog.more') ?></a>
					</div>
				</div>
				</div>
				<div class="col-xs-12 col-md-5 hidden-xs hidden-sm">
					<img alt="{{$noticias->titulo_web_blog_lang}}" class="img-responsive img-blog"
						src="{{$noticias->img_web_blog}}">
				</div>
			</div>

		</div>

		@endforeach

		@endif

	</div>
</section>

<div class="container">
	<div class="row">
		<div class="col-xs-12 text-center pagination_blog">

			@if(count($data['noticias']) != 0)
			<?php echo $data['noticias']->links(); ?>
			@endif
		</div>
	</div>
</div>

<section>
	<div id='seo_content' class='container content'>
		<div class='row'>
			<div class="col-sm-12">
				@if(empty ($data['categ']))
				<?php
                    $key = "info_h2_blog_".strtoupper(Config::get('app.locale'));
                    $html="{html}";
                    $content = \Tools::slider($key, $html);
                ?>
				<?= $content ?>
				@else
				<?=  $data['categ']->metacont_category_blog_lang ?>
				@endif
			</div>
		</div>
	</div>
</section>

<script>
	/*   funcion para categorias con scroll horizontal
(function ($) {
    var scrollCategories = {
        init: function () {
            this.cache();
            this.bindEvents();
        },
        cache: function () {
            this.btnR = $('.scroll-controller.right')
            this.btnL = $('.scroll-controller.left')
            this.scroll = 0;
            this.container = $('.blog_categories_list')
            this.scrollTotal =  $('.blog_categories_list')[0].scrollWidth - 250;
        },
        move: function (e) {

           if($(e.currentTarget).hasClass('left')){
               if(this.scroll > 0){
                    this.scroll = this.scroll - (this.scrollTotal /10);
                    if(this.scroll < 0 ){
                        this.scroll = 0;
                    }else{
                        //$(e.currentTarget).find('i').hide()
                    }
               }

           }else{
                if(this.scroll < this.scrollTotal){

                    this.scroll = this.scroll + (this.scrollTotal /10);
                    if(this.scroll > this.scrollTotal ){
                        this.scroll = this.scrollTotal;
                    }
               }
           }
           this.moveScroll()


        },
        moveScroll: function () {
                console.log(this.scroll)
                this.container.animate({
                    scrollLeft: this.scroll
                },200)
            },

        activeBtn: function(){


        },
        disabledBtn: function(){


        },
        bindEvents: function () {
            //this.btnL.hide()
            this.btnR.on('click', this.move.bind(this));
            this.btnL.on('click', this.move.bind(this));
        }
    };

    scrollCategories.init();

})($);

*/
    $(document).ready(function(){
        $('.resumen').each(function (){
            var str = $(this).text();
            var res = str.replace("[*CITA*]","");
            var str = $(this).text(str);
        });

    });

</script>




@stop
