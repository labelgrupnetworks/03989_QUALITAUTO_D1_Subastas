<div class="container slider-content">
	<div class="row">
		<div class="col-xs-12 col-sm-12">

			{!! \BannerLib::bannersPorKey('top_home', 'home-top-banner', '{dots:true, arrows:true, autoplay: true,
			autoplaySpeed: 5000, slidesToScroll:1}') !!}

			<?php /* Banner Slider Antiguo
			<div class="owl-carousel owl-theme" id="owl-carousel">
				<?php
	        		$key = "slider_home_".strtoupper(Config::get('app.locale'));
            		$html="<div class='item-carousel'>
            			<a href=\"{url}\" {target}>
                			<img class=\"img-responsive\" src='{img}'>
                			<div class='slider-text'>
                    			<h1>{html}</h1>
                			</div>
            			</a>
        			</div>";
        			$content = \Tools::slider($key, $html);
    				?>
			<?= $content ?>
			</div>
		*/?>
	</div>

</div>
</div>

<!-- Inicio lotes destacados -->
<div class="lotes_destacados">
	<div class="container">
		<div class="title_lotes_destacados">
			{{ trans(\Config::get('app.theme').'-app.lot_list.lotes_destacados') }}
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-12">
				<div class="loader"></div>
				<div class="owl-theme owl-carousel" id="lotes_destacados"></div>
			</div>
		</div>

	</div>
</div>

	<script>
		<?php
        $key = "lotes_destacados";
        $replace = array(
              'lang' => Config::get('app.language_complete')[Config::get('app.locale')] ,'emp' => Config::get('app.emp') ,
                  );
    ?>
    var replace = <?= json_encode($replace) ?>;
    var key ="<?= $key ?>";
    $( document ).ready(function() {
            ajax_carousel(key,replace);
            //$('.newsletter').addClass('hidden')
            //$('.newsletter-wrapper').appendTo('.newsletter-slider')

     });
	</script>

	<section class="blog">
		<div class="container">
			<div class="row news-section">
				<div class="col-xs-12 calendar">
					<h2 class="text-center" style="
                    margin-top: 0;
                    font-weight: 100;
                    /* background: #494742; */
                    display: table;
                    text-align: center;
                    margin: 10px auto;
                    border-radius: 40px;
                    color: #ABB2B9;">
						{{ trans(\Config::get('app.theme').'-app.home.calendar-news') }}
					</h2>
					<div class="scroll-buttons hidden">
						<div class="calendar-up" role="button"><i class="fa fa-chevron-up"></i></div>
						<div class="calendar-down" role="button"><i class="fa fa-chevron-down"></i></div>

					</div>
					<div class="content_art">

						<div class="content_art_container">
							<?php
                    $slidder_obj = new \App\Models\Banners;
                    $key = "article_".strtoupper(Config::get('app.locale'));
                    $slidders = $slidder_obj->getBannerByKeyname($key,20);
                ?>
							@foreach($slidders as $article)
							<?= $article->content ?>
							<br>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

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



	<!-- Fin slider -->
