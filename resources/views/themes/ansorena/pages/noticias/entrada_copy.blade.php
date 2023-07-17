@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

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

@section('content')

    @php
        /**
         * @todo
         * [] - La foto principal puede ser un slider o video?
         * [] - Se puede añadir un slider en cualquier parte del texto?
         **/

        $post = $data['news'];
        $category = $data['categorys'][$post->primary_category_web_blog];

        // Ruta del directorio donde están los archivos
        $path = "img/blog/$post->id_web_blog";
        $files = [];
        // Arreglo con todos los nombres de los archivos
        if (is_dir($path)) {
            $files = array_diff(scandir($path), ['.', '..']);
        }

        $withSlider = count($files) > 0;
        $withVideo = !$withSlider && str_is('*youtu*', $post->video_web_blog_lang) == true;

        $content = $post->texto_web_blog_lang;
        $content = str_replace('a:visited', '.post_body a:visited', $content);
        $content = str_replace('a:link', '.post_body a:link', $content);
        $content = str_replace('<style>', '<style>/*', $content);
        $content = str_replace('</style>', '*/</style>', $content);

    @endphp

    <main class="post-blog-page">
        <section class="container entrada-container">
            @if ($withSlider)
                <div class='row rowBanner'>
                    <div class="column_banner columnSliderBlog col-xs-6 col-xs-offset-3">
                        <div id="sliderBlog" class="sliderBlog">
                            @foreach ($files as $file)
                                <div class="item ">
                                    <img class="imgPopUpCall_JS cursor"
                                        src="/img/blog/{{ $data['news']->id_web_blog }}/{{ $file }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @elseif($withVideo)
                @php
                    $urlVideo = $post->video_web_blog_lang;
                    $urlVideo = str_replace('https://youtu.be/', 'https://www.youtube.com/embed/', $cod_video);
                @endphp
                <iframe class="video_post" style="width: 100%;min-height: 462px;" width="560" height="315"
                    src="{{ $urlVideo }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
            @else
                <img class="post-image" src="{{ $post->img_web_blog }}" alt="">
            @endif
        </section>

        <section>
            <div class="container text-center">
                <div class="blog-categoires">
                    <ul class="list-inline d-flex justify-content-center gap-4 ff-highlight">
                        <li class="list-inline-item">
                            <a href="{{ Routing::translateSeo('blog') }}">
                                STORIES
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="{{ Routing::translateSeo("blog/{$category->url_category_blog_lang}") }}">{{ $category->name_category_blog_lang }}
                            </a>
                        </li>
                    </ul>
                </div>

                <h1 class="ff-highlight text-uppercase">{{ $post->titulo_web_blog_lang }}</h1>

                <h2 class="ff-highlight">{!! $post->cita_web_blog_lang !!}</h2>

                <div class="redes"></div>
            </div>
        </section>

        <section class="post-content container" style="max-width: 75ch;">
            {!! $content !!}
        </section>

        <section class="post-relacionados container">
            <p class="fs-24 ff-highlight mb-3 mb-lg-5">{{ trans("$theme-app.blog.post_related") }}</p>

            <div class="row row-cols-1 row-cols-lg-3">
                @foreach ($data['relationship_new'] as $noticia)
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

		@include('includes.newsletter')

    </main>

    @php
        $sub_categories_web = str_replace(',', "','", $data['news']->lot_sub_categories_web_blog);
        $replace = [
            'sec' => $sub_categories_web,
            'lang' => Config::get('app.language_complete')[Config::get('app.locale')],
            'emp' => Config::get('app.emp'),
        ];
    @endphp

    <script>
        const replace = @json($replace);
        const key = 'relacionados_noticia';

        $(document).ready(function() {

            ajax_carousel(key, replace);
            if ($('.post_recents_list ul li').length < 4) {
                $('.post_recents_button').hide();
            }

            $('.post_recents_button').on('click', function() {
                $('.post_recents_list ul li').toggleClass('active');

                if ($(this).attr('data-open') === 'open') {
                    $(this).text('Ver más')
                    $(this).attr('data-open', 'close')

                } else {
                    $(this).text('Ver menos')
                    $(this).attr('data-open', 'open')
                }
            });

        });

        $('#sliderBlog').slick();
    </script>
@stop
