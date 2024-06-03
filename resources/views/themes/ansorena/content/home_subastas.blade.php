

{!! BannerLib::bannerWithView('home-video', 'video', ['src' => "/uploads/videos/Nuevo proyecto4.mp4"]) !!}
{!! BannerLib::bannerWithView('home-top-banner', 'fluid', ['title' => "<p class='slider-title'>" . trans("$theme-app.home.banner_title") . '</p>'], ['autoplay' => true]) !!}
{!! BannerLib::bannerWithView('home-joyeria', 'grid_1', ['title' => trans("$theme-app.foot.joyeria")], ['loading' => 'lazy']) !!}

{!! BannerLib::bannerWithView('home-subastas','grid_2', ['title' => trans("$theme-app.subastas.auctions")], ['loading' => 'lazy'] ) !!}

{!! BannerLib::bannerWithView('home-galeria', 'grid_2', ['title' => trans("$theme-app.galery.galery")]) !!}

{!! BannerLib::bannerWithView(
    'home-tasaciones',
    'row_1',
    [
        'id' => 'banner-tasaciones',
        'title1' => trans("$theme-app.home.valuations-title"),
        'url1' => Routing::translateSeo('valoracion-articulos', null, $domain),
        'title2' => trans("$theme-app.foot.condecoraciones"),
        'url2' => $pagina . trans("$theme-app.links.condecoraciones"),
    ],
    ['loading' => 'lazy']
) !!}

<div class="marquesina-wrapper">
	<section id="marquesina" class="marquesina">
		<div class="">
			<img src="/themes/{{ $theme }}/assets/img/marquesina_1.webp" alt="">
			<span>ANSORENA</span>
			<img src="/themes/{{ $theme }}/assets/img/marquesina_2.webp" alt="">
			<span>STORIES</span>
			<img src="/themes/{{ $theme }}/assets/img/marquesina_3.webp" alt="">
			<span>ANSORENA</span>
			<img src="/themes/{{ $theme }}/assets/img/marquesina_4.webp" alt="">
			<span>STORIES</span>
		</div>
		<div class="">
			<img src="/themes/{{ $theme }}/assets/img/marquesina_1.webp" alt="">
			<span>ANSORENA</span>
			<img src="/themes/{{ $theme }}/assets/img/marquesina_2.webp" alt="">
			<span>STORIES</span>
			<img src="/themes/{{ $theme }}/assets/img/marquesina_3.webp" alt="">
			<span>ANSORENA</span>
			<img src="/themes/{{ $theme }}/assets/img/marquesina_4.webp" alt="">
			<span>STORIES</span>
		</div>
	</section>
</div>

@php
    $blogs = (new App\Models\Blog())->getHomeNotices(3, false);
@endphp
<section class="blog-home">
    <div class="container blog-home-container text-center">
        <p class="blog-title">{{ trans("$theme-app.home.blog_title") }}</p>
        <div class="row row-cols-1 row-cols-lg-3 gx-lg-5 gy-5 gy-lg-0 px-lg-4">
            @foreach ($blogs as $blog)
                <article class="blog-article position-relative">
                    <div class="blog-image-wrapper">
                        <img class="blog-image" src="{{ $blog->img_web_blog }}"
                            alt="{{ $blog->localeLang->titulo_web_blog_lang }}" loading="lazy">
                    </div>
                    <h3 class="blog-article-title">{{ $blog->localeLang->titulo_web_blog_lang }}</h3>
                    <span>{{ $blog->category_name }}</span>
					<a href="{{ Routing::translateSeo("blog/{$blog->principalCategory->languages->first()->url_category_blog_lang}/{$blog->localeLang->url_web_blog_lang}") }}"
						class="stretched-link"></a>
                </article>
            @endforeach
        </div>
        <a class="btn btn-outline-lb-primary btn-medium mt-5"  href="{{ Config::get('app.locale') . '/blog/'}}">{{ trans("$theme-app.global.see_more") }}</a>
    </div>
</section>

@include('includes.newsletter')
