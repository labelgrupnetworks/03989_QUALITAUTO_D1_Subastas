<div class="mb-5">
	{!! \BannerLib::bannersPorUbicacionKeyAsClass('HOME',["HOME_LARGE" => ['dots' => true, 'autoplay' => true, 'arrows'
	=> false]]) !!}
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
				<div class="lds-ellipsis loader">
					<div></div>
					<div></div>
					<div></div>
					<div></div>
				</div>
				<div class="owl-theme owl-carousel" id="lotes_destacados"></div>
			</div>
		</div>
	</div>
</div>

@php
	$replace = array('lang' => \Tools::getLanguageComplete(Config::get('app.locale')) ,'emp' => Config::get('app.emp'));
@endphp


<script>
	var replace = @json($replace);

    $( document ).ready(function() {
        ajax_newcarousel("lotes_destacados", replace);
    });
</script>
