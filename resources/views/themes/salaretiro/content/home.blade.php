<div style="margin-top: 20px;">
	{!! \BannerLib::bannersPorKey('banner_home', 'home-top-banner', '{dots:true, arrows:false, autoplay: true, autoplaySpeed: 5000, slidesToScroll:1}') !!}
</div>
<!-- Fin slider -->
<!-- Inicio lotes destacados -->
<div class="lotes_destacados">
	<div class="container">
		<div class="title_lotes_destacados">
			 {{ trans(\Config::get('app.theme').'-app.lot_list.lotes_destacados') }}
		</div>
                <div class="loader"></div>
		<div class="owl-theme owl-carousel" id="lotes_destacados"></div>
	</div>
</div>


<div class="container" style="margin-top: 20px;">
	{!! \BannerLib::bannersPorKey('banner_home_logos', 'banner_home_logos', '{dots:false}') !!}
</div>

@php
$replace = array('lang' => Config::get('app.language_complete')[Config::get('app.locale')] ,'emp' => Config::get('app.emp'));
@endphp


<script>
$( document ).ready(() => ajax_carousel('lotes_destacados', @json($replace)));
</script>




