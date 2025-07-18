@php
    $responsiveSettings = [
        [
            'breakpoint' => 1200,
            'settings' => [
                'slidesToShow' => 4,
                'slidesToScroll' => 4,
                'infinite' => true,
                'rows' => 1,
                'slidesPerRow' => 1,
            ],
        ],
        [
            'breakpoint' => 1024,
            'settings' => [
                'slidesToShow' => 3,
                'slidesToScroll' => 3,
                'infinite' => true,
                'rows' => 1,
                'slidesPerRow' => 1,
            ],
        ],
        [
            'breakpoint' => 770,
            'settings' => [
                'slidesToShow' => 2,
                'slidesToScroll' => 2,
                'rows' => 1,
                'arrows' => false,
            ],
        ],
        [
            'breakpoint' => 480,
            'settings' => [
                'slidesToShow' => 1,
                'slidesToScroll' => 1,
                'slidePerRow' => 1,
                'rows' => 1,
                'arrows' => false,
            ],
        ],
    ];

@endphp

<div class="container">
    <p class="home-section_subtitle">{{ trans("$theme-app.home.seo_home_subtitle") }}</p>
    <h2 class="home-section_title">{{ trans("$theme-app.home.seo_home_title") }}</h2>
    <p class="home-section_desc">{{ trans("$theme-app.home.seo_home_text") }}</p>

    <div class="categories-banner">
        {!! BannerLib::bannersPorKey(
            'home-categories',
            'home-categories',
            [
                'dots' => false,
                'infinite' => false,
                'autoplaySpeed' => 5000,
                'slidesToScroll' => 1,
                'slidesToShow' => 5,
                'arrows' => true,
                'prevArrow' => $prevArrowSvg,
                'nextArrow' => $nextArrowSvg,
                'responsive' => $responsiveSettings,
            ],
            null,
            false,
            ''
        ) !!}
    </div>
</div>
