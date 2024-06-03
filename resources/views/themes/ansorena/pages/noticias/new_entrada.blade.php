@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
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

<!-- titlte & breadcrumb -->
@php
    use App\Models\V5\Web_Content_Page;
	use Carbon\Carbon;

    $post = $data['news'];
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

			@if(Tools::fileNameIsImage($post->img_web_blog))
            	<img src="{{ $post->img_web_blog }}" alt="">
			@else
				<video src="{{ $post->img_web_blog }}" autoplay muted loop controls></video>
			@endif

            <div class="container text-center blog-container">
                <div class="blog-categoires py-4 py-md-5">
                    <ul class="list-inline d-flex justify-content-center gap-4">

                        <li class="list-inline-item">
                            <a href="{{ Routing::translateSeo("blog/{$category->url_category_blog_lang}") }}">{{ $category->name_category_blog_lang }}
                            </a>
                        </li>

						<li class="list-inline-item opacity-25">
							{{ $fecha }}
                        </li>
                    </ul>
                </div>

                <h1 class="ff-highlight font-clamp mb-4 mb-md-5">{{ $post->titulo_web_blog_lang }}</h1>

                <h2 class="ff-highlight font-clamp">{!! $post->cita_web_blog_lang !!}</h2>

                <div class="redes">
					<div class="d-flex align-items-center justify-content-center gap-2 share-links py-5">
						<a class="share-icon" target="_blank" title="facebook" href="http://www.facebook.com/sharer.php?u={{URL::full()}}">
							<svg width="8" height="13" viewBox="0 0 8 13" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path
									d="M7.50313 0.00266311L5.84335 0C3.97864 0 2.77358 1.23635 2.77358 3.14993V4.60226H1.10474C0.960528 4.60226 0.84375 4.71917 0.84375 4.86338V6.96764C0.84375 7.11185 0.960661 7.22863 1.10474 7.22863H2.77358V12.5383C2.77358 12.6826 2.89035 12.7993 3.03456 12.7993H5.21192C5.35613 12.7993 5.47291 12.6824 5.47291 12.5383V7.22863H7.42417C7.56838 7.22863 7.68516 7.11185 7.68516 6.96764L7.68596 4.86338C7.68596 4.79414 7.65839 4.72783 7.60953 4.67883C7.56066 4.62983 7.49408 4.60226 7.42484 4.60226H5.47291V3.3711C5.47291 2.77936 5.61392 2.47896 6.38476 2.47896L7.50287 2.47856C7.64694 2.47856 7.76372 2.36165 7.76372 2.21758V0.263648C7.76372 0.119707 7.64708 0.00292943 7.50313 0.00266311Z"
									fill="currentColor" />
							</svg>
						</a>
						<a class="share-icon" target="_blank" title="twitter" href="http://twitter.com/share?text={{URL::full()}}&url={{URL::full()}}">
							@include('components.x-icon', ['size' => '12'])
						</a>
						<a class="share-icon" target="_blank" title="linkedin" href="https://www.linkedin.com/sharing/share-offsite/?url={{URL::full()}}">
							<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path
									d="M3.13068 1.60508C3.13068 2.23819 2.62181 2.75118 1.99375 2.75118C1.36569 2.75118 0.856825 2.23819 0.856825 1.60508C0.856825 0.972436 1.36569 0.458984 1.99375 0.458984C2.62181 0.458984 3.13068 0.972436 3.13068 1.60508ZM3.13985 3.66806H0.847656V11.0031H3.13985V3.66806ZM6.79911 3.66806H4.52159V11.0031H6.79957V7.15265C6.79957 5.01174 9.56349 4.83662 9.56349 7.15265V11.0031H11.8502V6.35863C11.8502 2.74614 7.76 2.87771 6.79911 4.65599V3.66806Z"
									fill="currentColor" />
							</svg>
						</a>
					</div>
				</div>
            </div>
        </section>

        <section class="content-blocks">
            @foreach ($data['contents'] as $content)
                @if (in_array($content->type_content_page, [
                        Web_Content_Page::TYPE_CONTENT_PAGE_HTML,
                        Web_Content_Page::TYPE_CONTENT_PAGE_TEXT,
                    ]))
                    <div class="content_{{ $content->id_content_page }}">
                        {!! $content->content !!}
                    </div>
                @elseif ($content->type_content_page === Web_Content_Page::TYPE_CONTENT_PAGE_BANNER)
                    <div class="content_{{ $content->id_content_page }}">
                        {!! BannerLib::bannerPorId($content->type_id_content_page) !!}
                    </div>
                @elseif ($content->type_content_page === Web_Content_Page::TYPE_CONTENT_PAGE_IMAGE)
                    <div class="content_{{ $content->id_content_page }} container">
                        <img class="img-responsive center-block" src="{{ $content->content }}">
                    </div>
                @elseif($content->type_content_page === Web_Content_Page::TYPE_CONTENT_PAGE_VIDEO)
                    <div class="content_{{ $content->id_content_page }} container">
                        <div class="embed-responsive embed-responsive-16by9">
                            <video src="{{ $content->content }}" autoplay muted controls></video>
                        </div>
                    </div>
                @elseif($content->type_content_page === Web_Content_Page::TYPE_CONTENT_PAGE_IFRAME)
                    <div class="content_{{ $content->id_content_page }} container">
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe src="{{ $content->content }}" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                @endif
            @endforeach
        </section>

        <section class="post-relacionados container">
            <p class="fs-24 ff-highlight mb-3 mb-lg-5">{{ trans("$theme-app.blog.post_related") }}</p>

            <div class="row row-cols-1 row-cols-lg-3">
                @foreach ($data['relationship_new'] as $noticia)
                    <div class="col">
                        <article class="card card-blog h-100">
                            <a
                                href="{{ Routing::translateSeo("blog/{$data['categorys'][$noticia->primary_category_web_blog]->url_category_blog_lang}/{$noticia->url_web_blog_lang}") }}">
								@if (Tools::fileNameIsImage($noticia->img_web_blog))
									<img class="card-img-top" src="{{ $noticia->img_web_blog }}"
										alt="Imagen para artículo {{ $noticia->titulo_web_blog_lang }}">
								@else
									<video class="card-img-top" src="{{ $noticia->img_web_blog }}" controls autoplay loop playsinline muted></video>
								@endif
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
@stop
