@php
    $noticias = collect($data['noticias']->all());
    $bread[] = [
        'name' => trans($theme . '-app.foot.blog'),
        'url' => route('blog.index', ['lang' => Config::get('app.locale')]),
    ];
    if (!empty($data['categ'])) {
        $bread[] = [
            'name' => $data['categ']->name_category_blog_lang,
            'url' => Routing::translateSeo("blog/{$data['categ']->url_category_blog_lang}"),
        ];
    }
@endphp

<main class="blog-page">

    {!! BannerLib::bannerWithView('blog-page', 'hero', [
        'title' => trans("$theme-app.foot.blog"),
        'breadcrumb' => view('includes.breadcrumb', ['bread' => $bread])->render(),
    ]) !!}

    <div class="container">
        <div class="row">
            <div class="col-8">
                @foreach ($noticias as $noticia)
                    @php
                        $content = strip_tags($noticia->content);
                        $content = Str::of($content)->words(50, '...');
                    @endphp
                    <article class="card card-blog mt-3">
                        <img class="card-img-top" src="{{ $noticia->img_web_blog }}"
                            alt="Imagen para artículo {{ $noticia->titulo_web_blog_lang }}">

                        <div class="card-body">
                            <p class="card-subtitle opacity-50">
                                {{ $noticia->cita_web_blog_lang }}
                            </p>
                            <p class="card-title font-clamp">
                                {{ $noticia->titulo_web_blog_lang }}
                            </p>

                            <p class="opacity-75">
                                {{ $content }}
                            </p>

                            <p class="mt-3">
                                <a class="text-decoration-underline" href="{{ $noticia->url }}">{{ trans("web.blog.learn_more") }} ...</a>
                            </p>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Categorias y posts? --}}
            <div class="col-3 ms-auto">
                <div class="blog-categories">
                    <h3>{{ trans("web.blog.categorias") }}</h3>

                    <div class="list-group list-group-flush opacity-75">
                        @foreach ($data['categories'] as $category)
                            <a href="{{ Routing::translateSeo("blog/$category->url_category_blog_lang") }}"
                                @class([
                                    'list-group-item list-group-item-action d-flex justify-content-between align-items-start',
                                    'active' =>
                                        !empty($data['categ']) &&
                                        $data['categ']->id_category_blog == $category->id_category_blog,
                                ])>
                                <span class="me-auto">
                                    {{ $category->name_category_blog_lang }}
                                </span>
                                <span>{{ $category->count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3 pagination-wrapper">
            {{ $data['noticias']->links('front::includes.grid.paginator_pers') }}
        </div>
    </div>


</main>
