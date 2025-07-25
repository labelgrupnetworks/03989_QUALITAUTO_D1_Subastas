<main class="main-page">
	<div class="home-slider">
		{!! BannerLib::bannersPorKey('home-top-banner', 'home-top-banner', ['dots' => false, 'autoplay' => true, 'autoplaySpeed' => 5000, 'slidesToScroll' => 1, 'arrows' => false], null, false, '') !!}
	</div>

    <section class="section-sessions">
        @include('includes.home.categories')
    </section>

    <section class="section-next-auction py-5 overflow-hidden">
        @include('includes.home.next-auction')
    </section>

    <section class="section-destacados" id="section-destacados">
        @include('includes.home.destacados')
    </section>

    <section class="section-social">
        @include('includes.home.about')
    </section>

    <section class="section-blog">
        @include('includes.home.blog')
    </section>
</main>
