@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@section('assets_components')
    <link href="{{ Tools::urlAssetsCache('/css/default/noticias.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css"
        href="{{ Tools::urlAssetsCache('/themes/' . env('APP_THEME') . '/css/noticias.css') }}">;
@endsection

@section('content')

    <!-- titlte & breadcrumb -->
    @php

		use App\Models\V5\Web_Content_Page;

        $bread[] = ['name' => trans($theme . '-app.blog.name'), 'url' => \Routing::slugSeo('blog')];
        $titulo_post = $data['news']->titulo_web_blog_lang;
        $bread[] = ['name' => $titulo_post];

        $fecha = strftime('%d %b %Y', strtotime($data['news']->publication_date_web_blog));

        if (\App::getLocale() != 'en') {
            $array_fecha = explode(' ', $fecha);
            $array_fecha[1] = \Tools::get_month_lang($array_fecha[1], trans($theme . '-app.global.month_large'));
            $fecha = $array_fecha[0] . ' ' . $array_fecha[1] . ' ' . $array_fecha[2];
        }
    @endphp

    <section>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h1 class="titlePage">{{ $data['news']->titulo_web_blog_lang }}</h1>

                    @include('includes.breadcrumb')

					<div class="date-post-principal article-data">
                        <p>{{ $fecha }}</p>
                    </div>

                </div>
            </div>
        </div>
    </section>

	<section style="display: flex; flex-direction: column; gap: 4rem;">
		@foreach ($data['contents'] as $content)

			@if (in_array($content->type_content_page, [Web_Content_Page::TYPE_CONTENT_PAGE_HTML, Web_Content_Page::TYPE_CONTENT_PAGE_TEXT]))
			<div class="content_{{ $content->id_content_pages }}">
				{!! $content->content !!}
			</div>
			@elseif ($content->type_content_page === Web_Content_Page::TYPE_CONTENT_PAGE_BANNER)
			<div class="content_{{ $content->id_content_pages }}">
				{!! BannerLib::bannerPorId($content->type_id_content_pages) !!}
			</div>
			@elseif ($content->type_content_page === Web_Content_Page::TYPE_CONTENT_PAGE_IMAGE)
			<div class="content_{{ $content->id_content_pages }} container">
				<img class="img-responsive center-block" src="{{ $content->content }}">
			</div>
			@elseif($content->type_content_page === Web_Content_Page::TYPE_CONTENT_PAGE_VIDEO)
			<div class="content_{{ $content->id_content_pages }} container">
				<div class="embed-responsive embed-responsive-16by9">
					<video src="{{ $content->content }}" autoplay muted controls></video>
				</div>
			</div>
			@elseif($content->type_content_page === Web_Content_Page::TYPE_CONTENT_PAGE_IFRAME)
			<div class="content_{{ $content->id_content_pages }} container">
				<div class="embed-responsive embed-responsive-16by9">
					<iframe src="{{ $content->content }}" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>
			@endif

		@endforeach
	</section>

    <section class="entradas-realacionadas">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="article-categoria-titulo">
                        {{ trans($theme . '-app.blog.post_related') }}:
                    </div>
                    <div class="entradas-relacionadas-lista">
                        @foreach ($data['relationship_new'] as $key => $rel_link)
                            <?php
                            $url = \Routing::slug('blog') . '/' . $data['categorys'][$rel_link->primary_category_web_blog]->url_category_blog_lang . '/' . $rel_link->url_web_blog_lang;
                            ?>
                            <div
                                class="col-md-4 entrada-relacionada-item col-xs-6 d-flex flex-direction-column justify-content-space-between">
                                <div class="entrada-relacionada-title">
                                    {{ $rel_link->titulo_web_blog_lang }}
                                </div>
                                <div class="img-related-post">
                                    <img class="img-responsive center-block" src="{{ $rel_link->img_web_blog }}">
                                </div>
                                <div class="button-post">
                                    <a href="{{ $url }}"
                                        role="button"><?= trans($theme . '-app.blog.more') ?></a>
                                </div>
                            </div>

                            {{-- Salimos del bucle una vez mostrados los 3 elementos  --}}
                            @if ($key == 2)
                            @break
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    <?php
    $sub_categories_web = str_replace(',', "','", $data['news']->lot_sub_categories_web_blog);

    $key = 'relacionados_noticia';
    $replace = [
        'sec' => $sub_categories_web,
        'lang' => Config::get('app.language_complete')[Config::get('app.locale')],
        'emp' => Config::get('app.emp'),
    ];
    ?>
    var replace = <?= json_encode($replace) ?>;
    var key = "<?= $key ?>";
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
</script>
@stop
