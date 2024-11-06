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
			prevArrow: '<svg xmlns="http://www.w3.org/2000/svg" class="slick-prev" viewBox="0 0 512 512" fill="currentColor"><path d="M177.5 414c-8.8 3.8-19 2-26-4.6l-144-136C2.7 268.9 0 262.6 0 256s2.7-12.9 7.5-17.4l144-136c7-6.6 17.2-8.4 26-4.6s14.5 12.5 14.5 22l0 72 288 0c17.7 0 32 14.3 32 32l0 64c0 17.7-14.3 32-32 32l-288 0 0 72c0 9.6-5.7 18.2-14.5 22z"/></svg>',
			nextArrow: '<svg xmlns="http://www.w3.org/2000/svg" class="slick-next" viewBox="0 0 512 512" fill="currentColor"><path d="M334.5 414c8.8 3.8 19 2 26-4.6l144-136c4.8-4.5 7.5-10.8 7.5-17.4s-2.7-12.9-7.5-17.4l-144-136c-7-6.6-17.2-8.4-26-4.6s-14.5 12.5-14.5 22l0 72L32 192c-17.7 0-32 14.3-32 32l0 64c0 17.7 14.3 32 32 32l288 0 0 72c0 9.6 5.7 18.2 14.5 22z"/></svg>',
        });
    });
</script>
