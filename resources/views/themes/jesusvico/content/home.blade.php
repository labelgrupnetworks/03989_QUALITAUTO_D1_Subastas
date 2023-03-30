<main class="home">

    <section class="container home-banners">
        <div class="row gy-3">
            <div class="col-md-8">
                {!! \BannerLib::bannersPorKey('new_home', 'banner_home', ['arrows' => false, 'autoplay' => true, 'dots' => false]) !!}
            </div>
            <div class="col-md-4">
                {!! \BannerLib::bannersPorKey('blog_banner', 'blog_banner', [
                    'dots' => false,
                    'autoplay' => true,
                    'arrows' => false,
                ]) !!}
            </div>
        </div>
    </section>

    <section class="container py-3 my-md-5">
        {!! \BannerLib::bannersPorKey('triple_banner', 'triple', ['dots' => false, 'arrows' => false]) !!}
    </section>

    <!-- Inicio lotes destacados -->
    <section class="section-destacados bg-lb-color-backgorund-dark py-4 my-5">
        <div class="container">
            <h2 class="h1 mb-4">Remates destacados</h2>
            <div class="lotes_destacados">
                <div class="loader"></div>
                <div class="carrousel-wrapper" id="ventas_destacadas"></div>
            </div>
        </div>
    </section>

    @php
        $lang = Config::get('app.locale');
		$path = str_replace("\\", "/", "/themes/$theme/assets/remates.json");
    @endphp

    <script>
        const caroulseOptions = {
            autoplay: true,
			autoplaySpeed: 3000,
			dots: true,
            slidesToShow: 4,
			slidesToScroll: 1,
            arrows: false,
            responsive: [
				{breakpoint: 1200, settings: {slidesToShow: 3}},
				{breakpoint: 992, settings: {slidesToShow: 2}},
				{breakpoint: 768, settings: {slidesToShow: 1}}
            ]
        }

		const path = @json($path);

        $(document).ready(function() {
            ajaxStaticCarousel("ventas_destacadas", {path, options: caroulseOptions});
        });
    </script>

    <section class="container partners py-5 my-5">
        <h3 class="text-center mb-4">{{ trans("$theme-app.home.collaborate") }}</h3>

        <div class="row row-cols-3 row-cols-lg-6 gx-4 gy-3 align-items-center">
            <div class="col"><img class="img-fluid"
                    src="/themes/jesusvico/assets/img/colaboradores/Logo-FNMT-mono.png" alt=""></div>
            <div class="col"><img class="img-fluid"
                    src="/themes/jesusvico/assets/img/colaboradores/logo-SGS-mono.png" alt=""></div>
            <div class="col"><img class="img-fluid"
                    src="/themes/jesusvico/assets/img/colaboradores/logo-JMO-mono.png" alt=""></div>
            <div class="col"><img class="img-fluid"
                    src="/themes/jesusvico/assets/img/colaboradores/logo-UAM-V-mono.png" alt=""></div>
            <div class="col"><img class="img-fluid"
                    src="/themes/jesusvico/assets/img/colaboradores/logo-UCM-H-mono.png" alt=""></div>
            <div class="col"><img class="img-fluid"
                    src="/themes/jesusvico/assets/img/colaboradores/logo-URJC-mono.png" alt=""></div>
        </div>

    </section>

	<section class="container-fluid newsletter-banner">
		<img src="/themes/jesusvico/assets/img/1-newsletter.webp" alt="newsletter background image" loading="lazy">
		<div class="row p-md-5">
			@include('includes.newsletter')
		</div>
	</section>

    @php
        $page = App\Models\V5\Web_Page::where('key_web_page', 'subasta-numismatica')
            ->where('lang_web_page', strtoupper($lang))
            ->first();
    @endphp

    @if ($page)
        <section class="static-page home-static-page">
            <div class="container">
                <h2>{{ $page->name_web_page }}</h2>
            </div>

            <div>
                <div class="contenido contenido-web static-page" id="pagina-{{ $page->id_web_page }}">
                    {!! $page->content_web_page !!}
                </div>
            </div>
        </section>
    @endif
</main>
