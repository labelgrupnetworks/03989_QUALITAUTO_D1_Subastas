@php
    $prevArrowSvg =
        '<svg xmlns="http://www.w3.org/2000/svg" class="slick-prev" viewBox="0 0 512 512" fill="currentColor"><path d="M177.5 414c-8.8 3.8-19 2-26-4.6l-144-136C2.7 268.9 0 262.6 0 256s2.7-12.9 7.5-17.4l144-136c7-6.6 17.2-8.4 26-4.6s14.5 12.5 14.5 22l0 72 288 0c17.7 0 32 14.3 32 32l0 64c0 17.7-14.3 32-32 32l-288 0 0 72c0 9.6-5.7 18.2-14.5 22z"/></svg>';
    $nextArrowSvg =
        '<svg xmlns="http://www.w3.org/2000/svg" class="slick-next" viewBox="0 0 512 512" fill="currentColor"><path d="M334.5 414c8.8 3.8 19 2 26-4.6l144-136c4.8-4.5 7.5-10.8 7.5-17.4s-2.7-12.9-7.5-17.4l-144-136c-7-6.6-17.2-8.4-26-4.6s-14.5 12.5-14.5 22l0 72L32 192c-17.7 0-32 14.3-32 32l0 64c0 17.7 14.3 32 32 32l288 0 0 72c0 9.6 5.7 18.2 14.5 22z"/></svg>';

    $auctions = App\Models\Subasta::auctionsToViews();
    $activeAuctions = collect(data_get($auctions, 'S.W', []))->flatten();
@endphp


@if ($activeAuctions->isNotEmpty())
	@include('includes.home.auction_banner', ['auctions' => $activeAuctions])
@else
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
@endif

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
                'prevArrow' => $prevArrowSvg,
                'nextArrow' => $nextArrowSvg,
                'responsive' => [
                    [
                        'breakpoint' => 768,
                        'settings' => [
                            'slidesToShow' => 1,
                            'slidesToScroll' => 1,
                            'arrows' => false,
                        ],
                    ],
                ],
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
            {!! BannerLib::bannerWithView('home_block_3', 'row_without_img') !!}
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
