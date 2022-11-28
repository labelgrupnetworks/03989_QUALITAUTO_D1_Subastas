<main class="home">

    <section class="container">
        <div class="row gy-3">
            <div class="col-md-8">
                {!! \BannerLib::bannersPorKey('new_home', 'banner_home', ['arrows' => false]) !!}
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

    <section class="container-fluid newsletter-banner">
        <div class="row p-md-5">
            @include('includes.newsletter')
        </div>
    </section>

    <!-- Inicio lotes destacados -->
    <div class="lotes_destacados secundary-color-text" id="lotes_destacados-content">
        <div class="container">
            <div class="row min-height flex-display flex-wrap">
                <div class="col-12 lotes-destacados-principal-title">
                    <div class="lotes-destacados-tittle color-letter">
                        {{ trans(\Config::get('app.theme') . '-app.lot_list.lotes_destacados') }}
                    </div>
                </div>
                <div class="col-12 text-center">
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
        $lang = Config::get('app.locale');
        $replace = [
            'lang' => \Tools::getLanguageComplete($lang),
            'emp' => Config::get('app.emp'),
        ];
    @endphp

    <script>
        var replace = @json($replace);
        var key = "lotes_destacados";

        $(document).ready(function() {
            ajax_carousel(key, replace);
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

    @php
        $page = App\Models\V5\Web_Page::where('key_web_page', 'subasta-numismatica')
            ->where('lang_web_page', strtoupper($lang))
            ->first();
    @endphp

    @if ($page)
        <section class="static-page-banner">
            <img src="/themes/jesusvico/assets/img/static_home.webp" alt="{{ $page->name_web_page }}" loading="lazy">
        </section>

        <section class="static-page home-static-page">
            <div class="container">
                <h2>{{ $page->name_web_page }}</h2>
            </div>

            <div>
                <div class="contenido contenido-web static-page" id="pagina-{{ $page->id_web_page }}">
                    {{-- {!! $page->content_web_page !!} --}}
                    @include("includes.statics.$lang.subasta_numismatica")
                </div>
            </div>
        </section>
    @endif
</main>
