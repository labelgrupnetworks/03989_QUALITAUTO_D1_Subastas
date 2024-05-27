@section('framework-css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/bootstrap/5.2.0/css/bootstrap.min.css') }}">
@endsection

@section('framework-js')
    <script src="{{ URL::asset('vendor/bootstrap/5.2.0/js/bootstrap.bundle.min.js') }}"></script>
@endsection

@section('custom-css')
    <link href="{{ Tools::urlAssetsCache('/themes/' . $theme . '/css/global.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Tools::urlAssetsCache("/themes/$theme/css/style.css") }}" rel="stylesheet" type="text/css">
    <link href="{{ Tools::urlAssetsCache('/themes/' . $theme . '/css/header.css') }}" rel="stylesheet" type="text/css">
@endsection

@php
    $envioroment = Config::get('app.env');

    $domains = [
        'local' => 'http://subastas.test',
        'develop' => 'https://auctions-ansorena.labelgrup.com',
        'production' => 'https://www.ansorena.com',
    ];

    $galleryDomains = [
        'local' => 'http://www.gallery.test',
        'develop' => 'https://www.preprodgaleria.enpreproduccion.come',
        'production' => 'https://galeria.ansorena.com',
    ];
    $domain = $domains[$envioroment];
    $galleryDomain = $galleryDomains[$envioroment];
    $noticias = collect($data['noticias']->all());
    $noticiasFirstBlock = $noticias->splice(0, 3);
    $noticiasSecondBlock = $noticias->splice(0, 3);
    $noticiasThirdBlock = $noticias->splice(0, 1);
    $noticiasLastBlock = $noticias;
@endphp

<main class="stories-page pb-0">
    <div class="container">
        <h1 class="ff-highlight">STORIES</h1>

        <div class="blog-categoires">
            <ul class="list-inline d-flex justify-content-center gap-4 ff-highlight">
                <li class="list-inline-item">
                    <a
                        href="{{ Routing::translateSeo('blog/joyeria', null, $domain) }}">{{ trans("$theme-app.foot.joyeria") }}</a>
                </li>
                <li class="list-inline-item">
                    <a
                        href="{{ Routing::translateSeo('blog/comunicacion', null, $domain) }}">{{ trans("$theme-app.subastas.auctions") }}</a>
                </li>
                <li class="list-inline-item">
                    <a
                        href="{{ Routing::translateSeo('blog/noticias', null, $galleryDomain) }}">{{ trans("$theme-app.galery.galery") }}</a>
                </li>
            </ul>
        </div>

        <section class="row">
            <div class="col-md-6">

                @php
                    $noticia = $noticiasFirstBlock->shift();
                @endphp
                @if ($noticia)
                    <article class="card card-blog card-blog-xl h-100">
                        <a
                            href="{{ Routing::translateSeo("blog/{$data['categorys'][$noticia->primary_category_web_blog]->url_category_blog_lang}/{$noticia->url_web_blog_lang}") }}">
                            <img class="card-img-top" src="{{ $noticia->img_web_blog }}"
                                alt="Imagen para artículo {{ $noticia->titulo_web_blog_lang }}">
                        </a>
                        <div class="card-body">
                            <p class="card-title font-clamp">
                                <a
                                    href="{{ Routing::translateSeo("blog/{$data['categorys'][$noticia->primary_category_web_blog]->url_category_blog_lang}/{$noticia->url_web_blog_lang}") }}">
                                    {{ $noticia->titulo_web_blog_lang }}
                                </a>
                            </p>
                        </div>
                        <div class="card-footer">
                            <p class="card-subtitle">
                                <a
                                    href="{{ Routing::translateSeo("blog/{$data['categorys'][$noticia->primary_category_web_blog]->url_category_blog_lang}") }}">
                                    {{ $data['categorys'][$noticia->primary_category_web_blog]->name_category_blog_lang }}
                                </a>
                            </p>
                        </div>
                    </article>
                @endif

            </div>
            <div class="col-md-6">
                <div class="row row-cols-1 row-cols-md-2 lb-sticky-header">
                    @foreach ($noticiasFirstBlock as $noticia)
                        <div class="col">
                            <article class="card card-blog h-100">
                                <a
                                    href="{{ Routing::translateSeo("blog/{$data['categorys'][$noticia->primary_category_web_blog]->url_category_blog_lang}/{$noticia->url_web_blog_lang}") }}">
                                    <img class="card-img-top" src="{{ $noticia->img_web_blog }}"
                                        alt="Imagen para artículo {{ $noticia->titulo_web_blog_lang }}">
                                </a>
                                <div class="card-body">
                                    <p class="card-title font-clamp">
                                        <a
                                            href="{{ Routing::translateSeo("blog/{$data['categorys'][$noticia->primary_category_web_blog]->url_category_blog_lang}/{$noticia->url_web_blog_lang}") }}">
                                            {{ $noticia->titulo_web_blog_lang }}
                                        </a>
                                    </p>
                                </div>
                                <div class="card-footer">
                                    <p class="card-subtitle">
                                        <a
                                            href="{{ Routing::translateSeo("blog/{$data['categorys'][$noticia->primary_category_web_blog]->url_category_blog_lang}") }}">
                                            {{ $data['categorys'][$noticia->primary_category_web_blog]->name_category_blog_lang }}
                                        </a>
                                    </p>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        @if ($noticiasSecondBlock->count() > 0)
            <section class="row row-cols-1 row-cols-md-3">
                @foreach ($noticiasSecondBlock as $noticia)
                    <div class="col">
                        <article class="card card-blog h-100">
                            <a
                                href="{{ Routing::translateSeo("blog/{$data['categorys'][$noticia->primary_category_web_blog]->url_category_blog_lang}/{$noticia->url_web_blog_lang}") }}">
                                <img class="card-img-top" src="{{ $noticia->img_web_blog }}"
                                    alt="Imagen para artículo {{ $noticia->titulo_web_blog_lang }}">
                            </a>
                            <div class="card-body">
                                <p class="card-title font-clamp">
                                    <a
                                        href="{{ Routing::translateSeo("blog/{$data['categorys'][$noticia->primary_category_web_blog]->url_category_blog_lang}/{$noticia->url_web_blog_lang}") }}">
                                        {{ $noticia->titulo_web_blog_lang }}
                                    </a>
                                </p>
                            </div>
                            <div class="card-footer">
                                <p class="card-subtitle">
                                    <a
                                        href="{{ Routing::translateSeo("blog/{$data['categorys'][$noticia->primary_category_web_blog]->url_category_blog_lang}") }}">
                                        {{ $data['categorys'][$noticia->primary_category_web_blog]->name_category_blog_lang }}
                                    </a>
                                </p>
                            </div>
                        </article>
                    </div>
                @endforeach
            </section>
        @endif
    </div>

    @if ($noticiasThirdBlock->count() > 0)
        @php
            $noticia = $noticiasThirdBlock->first();
            //dd($noticia);
        @endphp
        <section>
            <article class="card card-blog card-blog_overlay">
                <img src="{{ $noticia->img_web_blog }}" class="card-img" alt="">
                <div class="card-img-overlay">
                    <p class="card-title font-clamp">{{ $noticia->titulo_web_blog_lang }}</p>
                    <p class="card-subtitle">
                        {{ $data['categorys'][$noticia->primary_category_web_blog]->name_category_blog_lang }}
                    </p>
                </div>
                <a class="stretched-link"
                    href="{{ Routing::translateSeo("blog/{$data['categorys'][$noticia->primary_category_web_blog]->url_category_blog_lang}/{$noticia->url_web_blog_lang}") }}"></a>
            </article>
        </section>
    @endif

    @if ($noticiasLastBlock->count() > 0)
        <section class="container">
            <div class="row row-cols-1 row-cols-md-4">
                @foreach ($noticiasLastBlock as $noticia)
                    <div class="col">
                        <article class="card card-blog h-100">
                            <a
                                href="{{ Routing::translateSeo("blog/{$data['categorys'][$noticia->primary_category_web_blog]->url_category_blog_lang}/{$noticia->url_web_blog_lang}") }}">
                                <img class="card-img-top" src="{{ $noticia->img_web_blog }}"
                                    alt="Imagen para artículo {{ $noticia->titulo_web_blog_lang }}">
                            </a>
                            <div class="card-body">
                                <p class="card-title font-clamp">
                                    <a
                                        href="{{ Routing::translateSeo("blog/{$data['categorys'][$noticia->primary_category_web_blog]->url_category_blog_lang}/{$noticia->url_web_blog_lang}") }}">
                                        {{ $noticia->titulo_web_blog_lang }}
                                    </a>
                                </p>
                            </div>
                            <div class="card-footer">
                                <p class="card-subtitle">
                                    <a
                                        href="{{ Routing::translateSeo("blog/{$data['categorys'][$noticia->primary_category_web_blog]->url_category_blog_lang}") }}">
                                        {{ $data['categorys'][$noticia->primary_category_web_blog]->name_category_blog_lang }}
                                    </a>
                                </p>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <section class="text-center">
        {{ $data['noticias']->links('front::includes.grid.paginator_pers') }}
    </section>

    @include('includes.newsletter')

</main>
