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
			<div class="col-xs-12 @if(!Session::has('user'))col-lg-9 home-slider-control @else col-xs-12 @endif">
				<div class="owl-carousel owl-theme" id="owl-carousel" style="display:none">

					<?php
					   $key = "slider_home_".strtoupper(Config::get('app.locale'));   
					   $html = "<div><div class='home-slider-content'>
						<a class='color-letter' style='    display: block;
							height: 100%;
							width: 100%;
							text-align: center;' href='{url}'> {html}</a>
						   </div><img class='img-responsive' src='{img}' /></div> ";
					   $content = \Tools::slider($key, $html);
				   ?>
					<?= $content ?>
   
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



<div class="how-to-buy color-letter">
	<?php
	$key = "how_to_buy_".strtoupper(Config::get('app.locale'));   
	$html = "{html}";
	$content = \Tools::slider($key, $html);
?>
 <?= $content ?>
</div>
<div class="how-to-buy color-letter">
	<?php
	$key = "calendario_home_".strtoupper(Config::get('app.locale'));   
	$html = "{html}";
	$content = \Tools::slider($key, $html);
?>
 <?= $content ?>
</div>
-->


<div class="how-to-buy color-letter">
	 <div class="container">
		<div class="row flex-display flex-wrap">
			<div class="col-xs-12 col-sm-5">
				<div class="img-buy-content">
						<div class="text-rotate" style="position: relative; width: 45px; margin-right: 10px">
								<span class="special-title-home">{{ trans(\Config::get('app.theme').'-app.home.how_to_buy') }}</span>
							
						</div>
						<div class="img-buy-img">
							<img class="img-responsive" src="/themes/demo/assets/img/how-to-buy.png">
							<div id="buy" role="button" class="item-play hide">
									<a title="Cómo comprar" href="/es/pagina/como-comprar">
								<img src="/themes/demo/assets/img/play.png">
									</a>
							</div>
						</div>

				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-sm-offset-1 align-self-center">
				<div class="how-to-buy-desc">
						<div class="how-to-buy-title">
								{{ trans(\Config::get('app.theme').'-app.home.how_to_buy') }}
							</div>
							<p class="how-to-buy-description">
								{{ trans(\Config::get('app.theme').'-app.home.how_to_buy_text') }}
								Comprar/pujar en galileo subastas, es bastante sencillo, gracias a su nuevo sistema de pujas y compras en línea.  Con tan solo unos pocos clics usted se podrá beneficiar de algún lote en caso de que sea ganador, todo esto con un proceso de pago seguro eficiente y rápido. Si quiere información sobre las condiciones de compra haga  <a title="Cómo comprar" href="/es/pagina/como-comprar" class="color-letter"><strong>click aquí.</strong></a>
							</p>  
							<br>
							<ul class="ul-format list-features-how flex-display">
								<li>
										<img width="30px" src="/themes/demo/assets/img/free-delivery.png">
										<span class="item-how-desc">{{ trans(\Config::get('app.theme').'-app.home.servicio_de_transporte') }}</span>
								</li>    
								<li class="flex-display align-items-center">
										<img width="35px" src="/themes/demo/assets/img/worldwide.png">
										<span class="align-self-center item-how-desc" style="margin-bottom: 0">{{ trans(\Config::get('app.theme').'-app.home.atencion_global') }}</span>
								</li>    
								<li>
										<img width="35px" src="/themes/demo/assets/img/visa.png">
										<span class="item-how-desc">{{ trans(\Config::get('app.theme').'-app.home.pago_online') }}</span>
								</li>    
								<li>
										<img width="25px" src="/themes/demo/assets/img/security-on.png">
										<span class="item-how-desc">{{ trans(\Config::get('app.theme').'-app.home.pago_seguro') }}</span>
								</li>    
			  
							</ul>  
				</div>
			</div>
		</div>
	</div>
	

</div>

	 <div class="container">
		<div class="row">
			<div class="col-xs-7">
				<h3>{{ trans(\Config::get('app.theme').'-app.home.calendario') }}</h3>
						
				<p>
					{{ trans(\Config::get('app.theme').'-app.home.calendario_text') }}
					No se pierda ninguna subasta, adelántese al día de la subasta realice su orden y no pierda su lote preferido.
				</p>  
				<br>
				<i class="fas fa-2x fa-calendar-alt"></i>
				<span class="item-how-desc"><a class="color-letter" href="/es/calendar/">{{ trans(\Config::get('app.theme').'-app.home.ir_al_calendario') }}</a></span>
				
			</div>
			<div class="col-xs-12 col-md-5">
				<img class="img-responsive" src="/themes/demo/assets/img/calendar-img.png">
			</div>
				</div>
			</div>
		</div>
	</div>
	

</div>

		
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
							{{ $item->snippet }}
						</div>
					</div>
				</div>
			@endforeach
		</div>
	</div>  
</section>  
		

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






