@php
	$pageService = new App\Services\Content\PageService();
    $locale = config('app.locale');
@endphp

<div class="register-alert">
    <div class="register-alert-close">X</div>
    <div class="register-alert-register">
        <div class="register-alert-register-title">{{ trans(\Config::get('app.theme') . '-app.emails.not_register') }}
        </div>
        <div class="register-alert-register-btn"><a
                href="{{ \Routing::translateSeo('login') }}">{{ trans(\Config::get('app.theme') . '-app.login_register.register') }}</a>
        </div>
    </div>
    <div class="register-alert-suscribe">
        <div class="register-alert-suscribe-title" style="text-transform: uppercase">
            {{ trans(\Config::get('app.theme') . '-app.foot.newsletter_title') }}</div>
        <div class="register-alert-suscribe-btn"><a
                href="#newsletter_secction">{{ trans(\Config::get('app.theme') . '-app.emails.suscribe') }}</a></div>

    </div>
</div>


<section class="carousel-gutinvest">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-9 carousel-fijo">
                <div class="owl-carousel owl-theme" id="owl-carousel" data-slider-id="1">
                    {!! BannerLib::bannerWithView('home', 'slide') !!}
                </div>
            </div>
            <div class="col-md-3  hidden-xs hidden-sm controls-carousel" style="padding-left:  0">
                <div class="controls-carousel-container">
                    {!! BannerLib::bannerWithView('home', 'slide_control') !!}
                </div>
            </div>
        </div>
    </div>
</section>

<section class="subastas-home">
    <div class="container">
        <div class="titulos-home mb-0">
            <p style="width:100%">{{ trans(\Config::get('app.theme') . '-app.subastas.next_sell') }} (<span
                    id="total_lots_title"></span>)</p>
        </div>
        <div class="search-input pstatic mb-2 mb-2">
            <form id="formsearch-responsive" role="search" action="{{ \Routing::slug('busqueda') }}">
                <div class="form-group" style="padding-right: 0;">
                    <input class="form-control input-custom" id="textSearch" name="texto" type="text"
                        placeholder="{{ trans(\Config::get('app.theme') . '-app.head.search_label') }}">
                    <button class="btn btn-custom-search" type="submit" style="right:3px;">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="subastas-home-content">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1>{{ trans(\Config::get('app.theme') . '-app.home.home_seo_title') }}</h1>
                    <h2>{{ trans(\Config::get('app.theme') . '-app.home.home_seo_subtitle') }}</h2>
                </div>

                <?php $home_page = true; ?>

                @include('includes.blocs')
            </div>
        </div>
</section>

<!-- Inicio lotes destacados -->
<div class="lotes_destacados">
    <div class="container">
        <div class="title_lotes_destacados">
            <p>{{ trans(\Config::get('app.theme') . '-app.lot_list.lotes_destacados') }}
            <p>
        </div>
        <div class="loader"></div>
        <div class="owl-theme owl-carousel" id="lotes_destacados"></div>
    </div>
</div>

<section class="contacto-home">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 contact-home-content">
                <div class='col-xs-12 col-sm-6 slogan-big no-padding'>
                    {!! $pageService->getPage('contact-home')?->content_web_page !!}
                </div>
                <div class='col-xs-12 col-sm-6 col-md-5 col-md-offset-1 img-contacto-home no-padding d-flex'>
                    <div class="hidden-xs">
						<div class="home_contact">
						{!! BannerLib::bannerWithView('home_contact', 'image') !!}
						</div>
                    </div>

                    <div class='contacto-home-title'>
                        <a href="{{ \Routing::translateSeo('pagina') . trans('web.links.contact') }}">Contacto</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<section class="sobre-nosotros-home">
    <div class="container">
        <div class="titulos-home">
            <p><?= trans(\Config::get('app.theme') . '-app.home.about_us_title') ?></p>
        </div>
    </div>
    <div class="sobre-nosotros-home-content">

        <div class="container" style="position: relative">
            <div class="sobre-nosotros-home-bg hidden-xs"></div>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-lg-6 col-md-6 serivicios-content">
                    {!! $pageService->getPage('about-home')?->content_web_page !!}
                </div>

                <div class="hidden-xs col-xs-12 col-sm-3 col-xs-offset-0 col-md-offset-1 sobre-nosotros-home-img"
                    style="position: relative">
					<div class="home_about_us">
						{!! BannerLib::bannerWithView('home_about_us', 'image') !!}
					</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="vendemos-home">
    <div class="container">
        <div class="titulos-home">
            <p><?= trans(\Config::get('app.theme') . '-app.home.sell_title') ?></p>
        </div>
    </div>
    <div class="container">
        <div class="row">

            <div class="col-xs-12 vendemos-home-content">
                <div class="col-xs-6  no-padding">
					<div class="home_sell">
						{!! BannerLib::bannerWithView('home_sell', 'image') !!}
					</div>
                </div>
                <div class="col-xs-6 vendemos-home-desc">
                    {!! $pageService->getPage('sell-home')?->content_web_page !!}
                </div>
            </div>

        </div>
    </div>
</section>

@php
    $servicesItems = [
        [
            'es' => 'Tasación e inventariado',
            'en' => 'Valuation and inventory',
        ],
        [
            'es' => 'Campaña de marketing internacional',
            'en' => 'International marketing campaign',
        ],
        [
            'es' => 'Máxima difusión y transparencia',
            'en' => 'Maximum dissemination and transparency',
        ],
        [
            'es' => 'Máxima rentabilidad y agilidad',
            'en' => 'Maximum profitability and agility',
        ],
        [
            'es' => 'Gran experiencia y profesionalidad',
            'en' => 'Great experience and professionalism',
        ],
        [
            'es' => 'Software especializado',
            'en' => 'Specialized software',
        ],
    ];
@endphp
<section class="services-home">
    <div class="container">
        <div class="titulos-home">
            <p><?= trans(\Config::get('app.theme') . '-app.home.service_title') ?></p>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 services-content">
				@foreach ($servicesItems as $item)
					<div class="col-xs-12 col-sm-4">
						<div class="circle-services text-uppercase">
							<p>{{ $item[$locale] }}</p>
						</div>
					</div>
				@endforeach
            </div>

            <div class="col-xs-12 text-center" style="margin: 30px 0">
                <a class="btn-link-home"
                    href="<?= \Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.services') ?>"
                    title="{{ trans(\Config::get('app.theme') . '-app.foot.contact') }}">{{ trans(\Config::get('app.theme') . '-app.foot.services') }}</a>

            </div>
        </div>
    </div>
</section>

@php
$replace = [
        'lang' => Config::get('app.language_complete')[Config::get('app.locale')],
        'emp' => Config::get('app.emp'),
    ];
@endphp

<script>
    var replace = @json($replace);
    var key = "lotes_destacados";

    $(document).ready(function() {
        var totalLots = $('#total_lots_home').text()
        $('#total_lots_title').text(totalLots)

        if (localStorage.getItem("register") !== "true") {
            $('.register-alert').css('transform', 'translateY(0%)')
            localStorage.setItem("register", "true");

        }

        $('.register-alert-close').click(function() {
            $('.register-alert').css('transform', 'translateY(100%)')
        })

        $('.controls_slider').first().find('img').addClass('full-op')
        ajax_carousel(key, replace);
        var owl = $('#owl-carousel');
        owl.owlCarousel();
        // Listen to owl events:
        owl.on('changed.owl.carousel', function(event) {

            var page = event.page.index;
            var totalpage = event.page.count;
            if (totalpage > 3) {
                if (page > 2) {
                    if ($(window).width() < 1200) {
                        //var trans = (page - 4) * + 117.75
                        var trans = (page - 4) * +86.5
                    } else {
                        //var trans = (page - 4) * + 142.5
                        var trans = (page - 4) * +105.5
                    }

                    $('.controls-carousel-container').css({
                        "-webkit-transform": "translateY( " + trans + "px)"
                    });
                }
                if (page < 2) {
                    $('.controls-carousel-container').css({
                        "-webkit-transform": "translateY(0px)"
                    });
                }


            }
            /*$('.controls-carousel-container').css({"-webkit-transform":"translateY( " + trans + "px)"});*/
            $('.controls_slider img').removeClass('full-op')
            $('.controls_slider[data-index=' + page + '] img').addClass('full-op')
        })

        $('.controls_slider').click(function(e) {
            console.log($(this).attr('data-index'))
            var index = $(this).attr('data-index')
            var owl = $('#owl-carousel');
            owl.owlCarousel();
            // Listen to owl events:
            owl.trigger('to.owl.carousel', [index])
        })
    });
</script>
