<div class="banner-home-row">
	{!! \BannerLib::bannersPorKey('home', 'home-banner','{dots:false, arrows:false, autoplay: true, autoplaySpeed: 4000, slidesToScroll:1}') !!}
</div>

{{-- <div style=" text-align: center;color: #FFF;  margin: 15px;" >
	<p style="background-color: #5C0120; padding: 4px;	">Del <strong>d&iacute;a 12 hasta el 27 de agosto (incluidos los dos)</strong> Subarna permanecer&aacute; <strong>cerrado por per&iacute;odo vacacional.</strong> &iexcl;Te animamos a que realices tus gestiones con nosotros antes o despu&eacute;s de estas fechas!</p>
	<p style="background-color: #5C0120; padding: 4px;	margin-top: 15px;"><strong>From August 12th to August 27th (including both)</strong> Subarna will be closed<strong> for holidays</strong>. Before or after these dates, we invite you to arrange your appointments with us.</p>
</div> --}}

{!! \BannerLib::bannerParallax('principal') !!}

<section class="section section-light">
	<div class="banner-home-row2 container-fluid" style="background-color: white">

		<div class="row">
			<div class="col-xs-12 separator ">
				 <p>{!! trans("$theme-app.home.info") !!}</p>
			</div>
		</div>

		{!! \BannerLib::bannersPorKey('home_2', 'home-banner2','{dots:false, arrows:false, autoplay: true, autoplaySpeed: 4000, slidesToScroll:1}') !!}
		<div class="row">
			<div class="col-xs-12 separator"></div>
		</div>
	</div>
</section>

{!! \BannerLib::bannerParallax('home_3', '', '500px') !!}

<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12 separator"></div>
	</div>
	<div class="row mb-5">
		<div class="col-xs-12">
			<div class="title-home text-center">
				<h3>{{ trans($theme.'-app.home.contact_our_experts') }}</h3>
			</div>
		</div>
	</div>
</div>

<div class="banner-home-row-departamentos">
	{!! \BannerLib::bannersPorKey('home_departamentos', 'home-banner-departamentos','{dots:false, arrows:false,
	autoplay: true,
	autoplaySpeed: 4000, slidesToScroll:1}') !!}
</div>

<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12 separator"></div>
	</div>
</div>

<?php
	$noticiasController = new App\Http\Controllers\NoticiasController();
	$blogs = $noticiasController->smallIndex(\Config::get('app.locale'));
	$noticias = $blogs->data['noticias'];
?>
<div class="blog_home">
	<div class="container-fluid carrousel-centerMode">
		<div class="carrousel-wrapper" id="blog-home">
			@foreach ($noticias as $noticia)
			<div class="item_home">
				<div class="border_item_img">
					<div class="item_img d-flex justify-content-center">
						<img src="{{$noticia->img_web_blog}}" alt="{{$noticia->titulo_web_blog_lang}}">
					</div>
				</div>
				<div class="title_item">
					<h4>{{$noticia->titulo_web_blog_lang}}</h4>
				</div>
				<div class="desc_lot">
					{{-- <p>{!!Str::limit(strip_tags($noticia->texto_web_blog_lang), 300)!!}</p> --}}
					<p>{!! substr(strip_tags($noticia->texto_web_blog_lang), 0, 200)!!}</p>
				</div>
				<div class="blog_enlace">
					<a
						href="{{\Routing::slugSeo('blog').'/'.$noticia->url_category_blog_lang.'/'.$noticia->url_web_blog_lang}}">{{ trans($theme.'-app.home.learn_more') }}</a>
				</div>
			</div>
			@endforeach
		</div>
	</div>
</div>

<!-- Fin slider -->
<!-- Inicio lotes destacados -->
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12 separator"></div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<div class="title-home text-center">
				<h3>{{ trans($theme.'-app.lot_list.lotes_destacados') }}</h3>
			</div>
		</div>
	</div>
</div>



<div class="lotes_destacados">
	<div class="container-fluid carrousel-centerMode">
		<div class="loader"></div>
		<div class="carrousel-wrapper" id="lotes_destacados"></div>
	</div>
</div>

@section('seo_block')
<div class="seo-container">
	<div class="container">
		<p>{!! trans($theme.'-app.home.seo') !!}</p>
	</div>
</div>
@endsection


{{-- script para botón de mensaje --}}
@if((new App\Models\Cookies)->isAnalysisAllowed())
<script type="text/javascript">
	(function(d, src, c) { var t=d.scripts[d.scripts.length - 1],s=d.createElement('script');s.id='la_x2s6df8d';s.async=true;s.src=src;s.onload=s.onreadystatechange=function(){var rs=this.readyState;if(rs&&(rs!='complete')&&(rs!='loaded')){return;}c(this);};t.parentElement.insertBefore(s,t.nextSibling);})(document,
	'https://subarna.ladesk.com/scripts/track.js',
	function(e){ LiveAgent.createButton('kfxo3hnj', e); });
</script>
@endif

@php
	$replace = array('lang' => Config::get('app.language_complete')[Config::get('app.locale')] ,'emp' => Config::get('app.emp'));
@endphp

<script>
	var replace = @json($replace);

	$(document).ready(function(){
		ajax_carousel("lotes_destacados",replace, true);
	});
</script>
