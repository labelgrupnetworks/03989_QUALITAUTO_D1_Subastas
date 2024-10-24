<section class="fluid-banner">
    {!! BannerLib::bannersPorKey(
        'home',
        'home-top-banner',
        ['dots' => false, 'autoplay' => true, 'autoplaySpeed' => 5000, 'slidesToScroll' => 1, 'arrows' => false],
        null,
        false,
        '',
        $page_settings,
    ) !!}
</section>

<section class="home-section home-seo">
    @include('includes.home.seo_home')
</section>

<section class="home-section">
    <div class="container">
        {!! BannerLib::bannersPorKey(
            'home_block_doble',
            'home_block_doble',
            [
                'dots' => false,
                'autoplay' => true,
                'autoplaySpeed' => 5000,
                'slidesToScroll' => 2,
                'slidesToShow' => 2,
                'arrows' => true,
				'responsive' => [
					[
						'breakpoint' => 768,
						'settings' => [
							'slidesToShow' => 1,
							'slidesToScroll' => 1,
							'arrows' => false,
						],
					],
				]
            ],
            null,
            false,
            '',
            $page_settings,
        ) !!}
    </div>
</section>

<section class="home-section home-seo">
    <div class="container">
        <div class="banner-seo">
            {!! BannerLib::bannerWithView('home_block_3', 'row_1') !!}
        </div>
    </div>
</section>

<section class="home-section home-destacados">
    @include('includes.home.lotes_destacados')
</section>

<section class="home-section home-categories">
    @include('includes.home.categories')
</section>

@include('includes.newsletter')
