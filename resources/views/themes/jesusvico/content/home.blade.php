<br><br>
{{-- {!! \BannerLib::bannersPorKey('new_home', 'banner_home', ['dots' => true, 'autoplay' => true, 'autoplaySpeed' => 5000, 'slidesToScroll' => 1, 'fade' => true], '001', 'afterChange', 'test') !!} --}}
{!! \BannerLib::bannersPorKey('new_home', 'banner_home') !!}

<div class="mt-2 mb-2 events-banner">
	{!! \BannerLib::bannersPorKey('events', 'doble_home', ['dots' => false, 'autoplay' => true]) !!}
</div>


{!! \BannerLib::bannersPorKey('triple_banner', 'triple', ['dots' => false]) !!}



<section class="mt-4">
	@include('includes.newsletter')
</section>

	<!-- Inicio lotes destacados -->
	<div id="lotes_destacados-content" class="lotes_destacados secundary-color-text">
		<div class="container">
			<div class="row min-height flex-display flex-wrap">
				<div class="col-xs-12 col-sm-12 col-md-12 lotes-destacados-principal-title">
					<div class="lotes-destacados-tittle color-letter">
						{{ trans(\Config::get('app.theme').'-app.lot_list.lotes_destacados') }}
					</div>
				</div>
				<div class="col-xs-12 col-xs-12 col-sm-12 text-center">
					<div class="lds-ellipsis loader"><div></div><div></div><div></div><div></div></div>
					<div class="owl-theme owl-carousel" id="lotes_destacados"></div>
				</div>
			</div>
		</div>
	</div>

		<script>
			$(document).ready(function() {
				if($('.content_art_container').height() - $('.content_art').height() > 0){
					$('.scroll-buttons').removeClass('hidden')
				}

			$('.calendar-down').click(function(){
				var scroll = $('.content_art_container').height() - $('.content_art').height()
			   if($('.content_art').scrollTop() < scroll){
				   $('.content_art').animate({scrollTop: $('.content_art').scrollTop() + (($('.content_art_container').height() / $('.contact-misc').length) / 1.5)}, 500);
			   } else{
				   $('.content_art').scrollTop(scroll)
			   }
			})


				$('.calendar-up').click(function(){
				var scroll = $('.content_art_container').height() - $('.content_art').height()
			   if($('.content_art').scrollTop() > 0){
				   $('.content_art').animate({scrollTop: $('.content_art').scrollTop() - (($('.content_art_container').height() / $('.contact-misc').length)/ 1.5)}, 500);


			   } else{
				   $('.content_art').scrollTop(0)
			   }
			})

			})
		</script>

<br><br>

@php
	$replace = array(
		'lang' => \Tools::getLanguageComplete(Config::get('app.locale')) ,'emp' => Config::get('app.emp') ,
	);
@endphp

<script>
	var replace = @json($replace);
	var key = "lotes_destacados";

	$(document).ready(function() {
		ajax_carousel(key, replace);
	 });
</script>

{!! \BannerLib::bannersPorKey('partners', 'partners', "{dots:false,autoplay: true, autoplaySpeed: 5000, slidesToScroll:1}") !!}

@php
	$page = App\Models\V5\Web_Page::where('key_web_page', 'subasta-numismatica')->where('lang_web_page', strtoupper(Config::get('app.locale')))->first();
@endphp

@if ($page)
<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 color-letter titlepage-contenidoweb">
			<h1 class="titlePage">{{ $page->name_web_page }}</h1>
		</div>
	</div>
</div>
<div id="pagina-{{ $page->id_web_page }}" class="contenido contenido-web home-static-page">
	<div class="container">
		{!! $page->content_web_page !!}
	</div>
</div>
@endif


