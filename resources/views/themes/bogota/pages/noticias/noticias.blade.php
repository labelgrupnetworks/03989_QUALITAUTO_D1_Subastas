@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@section('assets_components')
    <link href="{{ Tools::urlAssetsCache('/css/default/noticias.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css"
        href="{{ Tools::urlAssetsCache('/themes/' . env('APP_THEME') . '/css/noticias.css') }}">
@endsection

@php
$noticias = $data['noticias']->all();
@endphp

@section('content')
    <!-- titlte & breadcrumb -->
    <section>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 text-center color-letter titlepage-contenidoweb">

                    <?php
                    $bread = [];
                    $bread[] = ['name' => trans(\Config::get('app.theme') . '-app.blog.name'), 'url' => '/' . \Routing::slugSeo('blog')];
                    if (!empty($data['categ'])) {
                        $categoria = $data['categ']->title_category_blog_lang;
                        $bread[] = ['name' => $categoria];
                    }
                    ?>

                    @if (empty($data['categ']))
                        <h1 class="titlePage"><?= trans(\Config::get('app.theme') . '-app.blog.principal_title') ?>
                        </h1>
                    @else
                        <h1 class="titlePage">{{ $data['categ']->title_category_blog_lang }}</h1>
                    @endif

                    @include('includes.breadcrumb')

                </div>
            </div>
        </div>
    </section>

    <!-- Posts -->
    <section class="post_content">
        <div class="container">
            <div class="blog-grid">
				@foreach ($noticias as $noticia)
				<article class="card card-blog h-100">
					<a
						href="{{ $noticia->url }}">
						<img class="card-img-top" src="{{ $noticia->img_web_blog }}"
							alt="Imagen para artículo {{ $noticia->titulo_web_blog_lang }}">
					</a>
					<div class="card-body">
						<p class="card-title">
							<a
								href="{{ $noticia->url }}">
								{{ $noticia->titulo_web_blog_lang }}
							</a>
						</p>
					</div>
					<div class="card-footer">
						<p class="card-subtitle">
							<a
								href="{{ $noticia->category_url }}">
								{{ $noticia->name_category_blog_lang }}
							</a>
						</p>
					</div>
				</article>
				@endforeach
			</div>
        </div>
    </section>

    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center pagination_blog">

                @if (count($data['noticias']) != 0)
                    {!! $data['noticias']->links() !!}
                @endif
            </div>
        </div>
    </div>

    <section>
        <div id='seo_content' class='container content'>
            <div class='row'>
                <div class="col-sm-12">
                    @if (empty($data['categ']))
                        <?php
                        $key = 'info_h2_blog_' . strtoupper(Config::get('app.locale'));
                        $html = '{html}';
                        $content = \Tools::slider($key, $html);
                        ?>
                        <?= $content ?>
                    @else
                        <?= $data['categ']->metacont_category_blog_lang ?>
                    @endif
                </div>
            </div>
        </div>
    </section>

@stop
