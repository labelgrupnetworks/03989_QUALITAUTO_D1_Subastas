@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

<!-- titlte & breadcrumb -->
@php
    use App\Models\V5\Web_Content_Page;
    use Carbon\Carbon;

    $post = $data['news'];
    $isNewer = $post->publication_date_web_blog > Config::get('app.most_distant_blog_date');
    $category = $data['categorys'][$post->primary_category_web_blog];
    $locale = Config('app.locale') == 'es' ? 'es_ES' : 'en_GB';
    $fecha = Carbon::parse($post->publication_date_web_blog)->locale($locale)->isoFormat('D MMMM YYYY');
    $url_translated = Tools::getBlogURLTranslated(\Config::get('app.locale'), $post->id_web_blog);

@endphp

@section('content')

    @if (count($url_translated) > 0)
        <script>
            let url = "{{ $url_translated['url'] }}";
            let to_lang = "{{ $url_translated['to_lang'] }}";
            $("select#locale-select option[data-lang='" + to_lang + "']").val(url);
        </script>
    @endif

    <main class="post-blog-page p-0">
        <section class="post-blog-front">


            @if ($isNewer && Tools::fileNameIsImage($post->img_web_blog))
                <img src="{{ $post->img_web_blog }}" alt="">
            @elseif($isNewer)
                <video src="{{ $post->img_web_blog }}" autoplay muted loop controls></video>
            @endif

            <div class="container text-center blog-container">
                <div class="blog-categoires py-3">
                    <ul class="list-inline d-flex justify-content-center gap-4">

                        <li class="list-inline-item">
                            <a href="{{ Routing::translateSeo("blog/{$category->url_category_blog_lang}") }}">
                                {{ $category->name_category_blog_lang }}
                            </a>
                        </li>

                        <li class="list-inline-item opacity-50">
                            {{ $fecha }}
                        </li>
                    </ul>
                </div>

                <h1 class="post-title">{{ $post->titulo_web_blog_lang }}</h1>
                <h2 class="post-cita">{!! $post->cita_web_blog_lang !!}</h2>
            </div>
        </section>

        <section class="content-blocks">

            @if (!$isNewer)
                <div class="container container_type_old_image text-center">
                    <img class="img-fluid" src="{{ $post->img_web_blog }}" alt="{{ $post->titulo_web_blog_lang }}">
                </div>
            @endif

            @foreach ($data['contents'] as $content)
                <div
                    class="container container_type_{{ mb_strtolower($content->type_content_page) }} content_{{ $content->id_content_page }}">
                    @if (in_array($content->type_content_page, [
                            Web_Content_Page::TYPE_CONTENT_PAGE_HTML,
                            Web_Content_Page::TYPE_CONTENT_PAGE_TEXT,
                        ]))
                        {!! $content->content !!}
                    @elseif ($content->type_content_page === Web_Content_Page::TYPE_CONTENT_PAGE_BANNER)
                        {!! BannerLib::bannerPorId($content->type_id_content_page) !!}
                    @elseif ($content->type_content_page === Web_Content_Page::TYPE_CONTENT_PAGE_IMAGE)
                        <img class="img-responsive center-block" src="{{ $content->content }}">
                    @elseif($content->type_content_page === Web_Content_Page::TYPE_CONTENT_PAGE_VIDEO)
                        <div class="ratio ratio-16x9">
                            <video src="{{ $content->content }}" autoplay muted controls></video>
                        </div>
                    @elseif($content->type_content_page === Web_Content_Page::TYPE_CONTENT_PAGE_IFRAME)
                        <div class="ratio ratio-16x9">
                            <iframe src="{{ $content->content }}" frameborder="0" allowfullscreen></iframe>
                        </div>
                    @endif
                </div>
            @endforeach
        </section>

        @if (count($data['relationship_new']) > 0)
            <section class="post-relacionados container">
                <h2 class="mb-3 post-relacionados-title">{{ trans("$theme-app.blog.post_related") }}</h2>

                <div class="post-relacionados-grid row row-cols-1 row-cols-lg-3">
                    @foreach ($data['relationship_new'] as $noticia)
                        <div class="col">
                            <article class="card card-blog h-100">
                                <a
                                    href="{{ Routing::translateSeo("blog/{$data['categorys'][$noticia->primary_category_web_blog]->url_category_blog_lang}/{$noticia->url_web_blog_lang}") }}">
                                    @if (Tools::fileNameIsImage($noticia->img_web_blog))
                                        <img class="card-img-top" src="{{ $noticia->img_web_blog }}"
                                            alt="Imagen para artículo {{ $noticia->titulo_web_blog_lang }}" loading="lazy">
                                    @else
                                        <video class="card-img-top" src="{{ $noticia->img_web_blog }}" controls autoplay
                                            loop playsinline muted></video>
                                    @endif
                                </a>
                                <div class="card-body">
                                    <p class="card-title">
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

    </main>
@stop
