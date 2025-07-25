@php
    use App\Models\V5\Web_Blog;
    $noticias = Web_Blog::getNoticiesQuery(false)->limit(2)->get();
@endphp

<div class="container">
    <h2 class="section-home-blog-title">{{ trans("web.blog.blog_news") }}</h2>

    <div class="home-blog-articles row row-cols-lg-2 gx-5">
        @foreach ($noticias as $noticia)
            <a class="stretched-link" href="{{ $noticia->url }}">
                <article class="card card-blog mt-3">
                    <div class="card-header">
                        <p class="card-title">
                            {{ $noticia->localeLang->titulo_web_blog_lang }}
                        </p>
                    </div>

                    <div class="card-body">
                        <img class="card-img" src="{{ $noticia->img_web_blog }}"
                            alt="Imagen para artículo {{ $noticia->localeLang->titulo_web_blog_lang }}">
                    </div>

                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <span class="card-subtitle opacity-50">
                            {{ $noticia->localeLang->cita_web_blog_lang }}
                        </span>
                    </div>
                </article>
            </a>
        @endforeach
    </div>

    <div class="section-blog-action">
        <a class="btn btn-outline-lb-primary px-md-5"
            href="{{ route('blog.index', ['lang' => Config::get('app.locale')]) }}">
            {{ trans("web.global.see_more") }}
        </a>
    </div>
</div>
