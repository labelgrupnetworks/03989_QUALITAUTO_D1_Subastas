<?php

	$blog = DB::table("WEB_BLOG")
	->where("LANG_WEB_BLOG_LANG",strtoupper(Config::get('app.locale')))
	->where("EMP_WEB_BLOG",Config::get('app.emp'))
	//->where("PUBLICATION_DATE_WEB_BLOG",">=",date("Y-m-d G:i:s"))
	->join("WEB_BLOG_LANG","WEB_BLOG.ID_WEB_BLOG","=","WEB_BLOG_LANG.IDBLOG_WEB_BLOG_LANG")
	->limit(2)->get();

	foreach($blog as $item) {

		$item->snippet = Tools::acortar(strip_tags($item->texto_web_blog_lang),250);
		$a = Tools::getFragment('<img ','>',$item->texto_web_blog_lang);
		$b = str_replace('src="','',Tools::getFragment('src="','" ',$a));
		$item->img = $b;
	}

?>

<div class="home-slider">
	<div class="container">
		<div class="row flex-display row-custom">
			@if(!Session::has('user'))
			<div class="col-xs-3 home-slider-control hidden-xs hidden-sm hidden-md">
				<div class="banner-register">
					<div class="banner-register-title">{{ trans(\Config::get('app.theme').'-app.home.not_account') }}</div>
					<div class="banner-register-sub-title hidden">{{ trans(\Config::get('app.theme').'-app.login_register.crear_cuenta') }}</div>
					<div class="banner-register-btn text-center">
							<a class="button-principal" title="{{ trans(\Config::get('app.theme').'-app.login_register.register') }}" href="{{ \Routing::slug('register') }}">{{ trans(\Config::get('app.theme').'-app.login_register.register') }}</a>
					</div>
					<div class="banner-register-hr">
						<hr>
					</div>
					<div class="banner-register-title">{{ trans(\Config::get('app.theme').'-app.home.account') }}</div>
					 <div class="banner-register-btn text-center">
							<a class="secondary-button user-account btn_login" href="javascript:;">{{ trans(\Config::get('app.theme').'-app.login_register.generic_name') }}</a>
					</div>
				</div>
			</div>
			@endif
			<div class="slider-new-banner col-xs-12 p-0 @if(!Session::has('user'))col-md-9 col-lg-9  @else col-xs-12 @endif">
                                {!! \BannerLib::bannersPorKey('new_home', 'home-top-banner') !!}
                        </div>
                </div>
        </div>
</div>
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


<?php /*  */ ?>

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

<!---

<div class="video-explain video-buy" style="display: none">
	<div class="close-video" role="button">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 33.697 33.544">
			<defs>
			  <style>
				.close-svg {
				  fill: #fff;
				}
			  </style>
			</defs>
			<g id="cancel" transform="translate(0 -0.435)">
			  <path id="Path_27" data-name="Path 27" class="close-svg" d="M18.993,17.284,33.238,3.039a1.481,1.481,0,0,0,0-2.144,1.481,1.481,0,0,0-2.144,0L16.849,15.139,2.6.894a1.481,1.481,0,0,0-2.144,0,1.481,1.481,0,0,0,0,2.144L14.7,17.284.459,31.528a1.481,1.481,0,0,0,0,2.144,1.842,1.842,0,0,0,1.225.306c.306,0,.919,0,.919-.306L16.848,19.428,31.093,33.673a1.842,1.842,0,0,0,1.225.306c.306,0,.919,0,.919-.306a1.481,1.481,0,0,0,0-2.144Z" transform="translate(0 0)"/>
			</g>
		  </svg>
	</div>


</div>
-->
{!! \BannerLib::bannersPorKey('como_comprar', 'home-how_to_buy') !!}

<div class="banner_calendario">
    {!! \BannerLib::bannersPorKey('home_calendar', 'home_calendar') !!}
</div>
@php

/*
 <section class="blog">
	<div class="container">

		<h2>{{ trans(\Config::get('app.theme').'-app.home.blog') }}</h2>

		<div class="row">
			@foreach($blog as $item)
				<div class="col-xs-12 col-md-6" onclick="">
					<div class="row">
						<div class="col-xs-12 col-md-5">
							<br>
							<img src="{{ $item->img }}" width="100%">
						</div>
						<div class="col-xs-12 col-md-7">
							<h4>{{ $item->titulo_web_blog_lang }}</h4>
							<br>
							{!! $item->snippet !!}
						</div>
					</div>
				</div>
			@endforeach
		</div>
	</div>
</section>
*/
@endphp

<br><br><br>

<script>
	<?php
		$key = "lotes_destacados";
		//$keyExtra = "mas_altas";
		$replace = array(
			  'lang' => \Tools::getLanguageComplete(Config::get('app.locale')) ,'emp' => Config::get('app.emp') ,
				  );
	?>
	var replace = <?= json_encode($replace) ?>;
	var key ="<?= $key ?>";
   var keyExtra ="<?= 0//$keyExtra ?>";
	$( document ).ready(function() {
			ajax_carousel(key,replace);
		   // ajax_carousel(keyExtra,replace);

	 });

	 $('.close-video').click(function() {
		 $('.video-explain').fadeOut()
	 })

	//  $('.item-play').click(function(){
	//     $('.video-explain.video-'+ $(this).attr('id')).fadeIn()
	//  })
</script>






