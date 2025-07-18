<section class="home-principal-banner">
    {!! \BannerLib::bannersPorKey('PRINCIPAL', 'banner_home banner-home-principal') !!}
</section>

{!! \BannerLib::bannersPorKey('BANNER_HOME_TRIPLE', 'banner_home') !!}
{!! \BannerLib::bannersPorKey('BANNER_HOME_TRIPLE_2', 'banner_home') !!}

<p class="text-center" style="color:#fff; cursor:context-menu;">Subastas de monedas, billetes, sellos, libros antiguos y
    coleccionismo en Barcelona. Expertos en numismática, filatelia, libros antiguos y coleccionismo. Valoraciones y
    tasaciones gratuitas.</p>

<!-- Inicio lotes destacados -->
<div class="lotes_destacados">
    <div class="container">
        <div class="title_lotes_destacados">
            Highlights
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



<div class="container">
    <div class="row news-section">
        <div class="col-xs-12 col-sm-6">
            @include('includes.newsletter')
        </div>
        <div class="col-xs-12 col-sm-6 calendar">
            <h2 style="margin-top: 0">{{ trans(\Config::get('app.theme') . '-app.home.calendar-news') }}</h2>
            <div class="content_art">
               @php
					$data = (new App\Services\Content\PageService())->getPage('subastas-programadas');
				@endphp

				{!! $data->content_web_page !!}
            </div>
        </div>
    </div>
</div>



<!-- Fin slider -->
