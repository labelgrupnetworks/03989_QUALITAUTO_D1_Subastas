@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@section('content')

    <main class="press-page">

        <section class="container">
            <h1>{{ trans("$theme-app.foot.press") }}</h1>

            @foreach ($data['noticias']->chunk(2) as $noticias)
                <div @class([
                    'row row-cols-1 row-cols-lg-2 g-5 press-row',
                    'with-border border-secondary border-opacity-75' => !$loop->last,
                ])>

                    @foreach ($noticias as $noticia)
                        @php
                            $image = $noticia->img_web_blog;
                            $url = $noticia->url;
                            $authorPage = $noticia->author_web_blog;
                            $date = strftime('%d %b %Y', strtotime($noticia->publication_date_web_blog));
                            $title = $noticia->titulo_web_blog_lang;
                            $text = $noticia->texto_web_blog_lang;
                        @endphp

                        <article class="col">
                            <div class="card press-card h-100 border-0">
                                <img class="card-img-top" src="{{ $image }}" alt="{{ $title }}">

                                <div class="card-body p-0 pt-3">
                                    <h4 class="card-author">{{ $authorPage }}</h4>
                                    <h5 class="card-year text-lb-gray opacity-75">{{ $date }}</h5>
                                    <h3 class="card-title">{{ $title }}</h3>
                                    <h4 class="card-description fw-lighter text-lb-gray max-line-3">{{ $text }}</h4>
                                </div>


                                <div class="card-footer bg-transparent border-0 p-0">
                                    <a class="btn btn-lb-primary" href="{{ $url }}" target="_blank">
                                        {{ trans("$theme-app.blog.more") }}
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endforeach

        </section>

    </main>
@stop
