{!! \BannerLib::bannersPorKey('home-top-banner', 'home-top', [
    'dots' => false,
    'autoplay' => true,
    'autoplaySpeed' => 5000,
    'slidesToScroll' => 1
]) !!}

<p class="text-center" style="color:#fff; cursor:context-menu;">Subastas de monedas, billetes, sellos, libros antiguos
    y coleccionismo en Barcelona. Expertos en numismática, filatelia, libros antiguos y coleccionismo. Valoraciones y
    tasaciones gratuitas.</p>

<!-- Inicio lotes destacados -->
<div class="lotes_destacados">
    <div class="container">
        <div class="title_lotes_destacados">
            {{ trans(\Config::get('app.theme') . '-app.lot_list.lotes_destacados') }}
        </div>
        <div class="loader"></div>
        <div class="owl-theme owl-carousel" id="lotes_destacados"></div>
    </div>
</div>

@php
    $replace = [
        'lang' => Config::get('app.language_complete')[Config::get('app.locale')],
        'emp' => Config::get('app.emp'),
		'agrsub' => Config::get('app.agrsub'),
    ];
@endphp

<script>
    var replace = @json($replace);
    $(document).ready(function() {
        ajax_carousel("lotes_destacados", replace);
    });
</script>

{!! \BannerLib::bannersPorKey('home-bottom-banner', 'home-bottom') !!}
<!-- Fin slider -->
