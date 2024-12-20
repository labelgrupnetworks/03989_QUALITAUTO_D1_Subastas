
@php
 $options = [
	"TOP_HOME" => ['dots' => true, 'autoplay' => true, 'arrows' => false],
	"VENDIDOS_HOME" => ['dots' => false],
	"matteport" => ['title' => trans("$theme-app.home.virtual_tour")],
	"issuu" => ['title' => trans("$theme-app.home.catalogs")]
 ]
@endphp

{!! \BannerLib::bannersPorUbicacionKeyAsClass('HOME', $options) !!}

<!-- Inicio lotes destacados -->
<div id="lotes_destacados-content" class="lotes_destacados secundary-color-text" >
	<div class="container">
		<div class="row flex-display flex-wrap">
			<div class="col-xs-12 col-sm-12 col-md-12 lotes-destacados-principal-title">
				<div class="lotes-destacados-tittle color-letter">
					{{ trans(\Config::get('app.theme').'-app.lot_list.lotes_destacados') }}
				</div>
			</div>
			<div class="col-xs-12 col-sm-10 col-md-12 text-center lotes-destacados-principal-lot">
				<div class="lds-ellipsis loader">
					<div></div>
					<div></div>
					<div></div>
					<div></div>
				</div>
				<div class="owl-theme owl-carousel" id="lotes_destacados"></div>
				<div class="owl-theme owl-carousel owl-loaded owl-drag m-0 pl-10" id="navs-arrows">
					<div class="owl-nav">
						<div class="owl-prev"><i class="fas fa-chevron-left"></i></div>
						<div class="owl-next"><i class="fas fa-chevron-right"></i></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="pt-3 pb-3">
	{!! BannerLib::bannersPorKey('CENTRADO_HOME', '', ['dots' => false]) !!}
</div>

@php
	$replace = array(
        'lang' => \Tools::getLanguageComplete(Config::get('app.locale')) ,'emp' => Config::get('app.emp') ,
    );
@endphp

<script>
    var replace = @json($replace);
    $( document ).ready(function() {
        ajax_newcarousel("lotes_destacados",replace);
     });
</script>
