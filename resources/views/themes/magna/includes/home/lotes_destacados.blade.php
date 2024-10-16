<div class="container">
    <p class="home-section_subtitle">
		{{ trans("$theme-app.lot_list.lotes_destacados_subtitle") }}
	</p>

	<h2 class="home-section_title">
        {{ trans("$theme-app.lot_list.lotes_destacados") }}
    </h2>
    <p class="home-section_desc">
		{{ trans("$theme-app.lot_list.lotes_destacados_desc") }}
	</p>


    <div class="lotes_destacados">
        <div class="loader"></div>
        <div class="carrousel-wrapper" id="lotes_destacados"></div>
    </div>

</div>

@php
    $replace = ['lang' => Tools::getLanguageComplete(Config::get('app.locale')), 'emp' => Config::get('app.emp')];
@endphp

<script>
    var replace = @json($replace);


    $(document).ready(function() {
        ajax_newcarousel("lotes_destacados", replace, null, {
            autoplay: false,
            arrows: true,
            dots: false,
            slidesToShow: 5,
            responsive: homeBannersOptions,
        });
    });
</script>
