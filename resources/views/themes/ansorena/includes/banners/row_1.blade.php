@php
    $bannerContent = BannerLib::getOnlyContentForBanner($banner);

    $bannerTemplete = [
        'id' => $banner->key ?? $content['id'],
        'title1' => $content['title1'] ?? '',
        'title2' => $content['title2'] ?? '',
        'text1' => $bannerContent['texts'][0] ?? $content['text1'] ?? '',
        'text2' => $bannerContent['texts'][1] ?? $content['text2'] ?? '',
        'image1' => $bannerContent['images'][0] ?? $content['images1'] ?? null,
        'image2' => $bannerContent['images'][1] ?? $content['images2'] ?? null,
        'url1' => $content['url1'] ?? '',
        'url2' => $content['url2'] ?? '',
    ];
    $loading = $options['loading'] ?? 'eager';
@endphp

<section id="{{ $bannerTemplete['id'] }}" class="row-banner row-banner-1">
    <div class="banner-col">
        <h2 class="row-banner-title">{{ $bannerTemplete['title1'] }}</h2>
        <p class="row-banner-description">{!! $bannerTemplete['text1'] !!}</p>

		@if(!empty($bannerTemplete['image1']))
        <a href="{{ $bannerTemplete['url1'] }}">
            <picture class="row-banner-image">
                <source srcset="{{ $bannerTemplete['image1']['desktop'] }}" media="(min-width: 992px)">
                <img src="{{ $bannerTemplete['image1']['mobile'] }}" alt="" loading="{{ $loading }}">
            </picture>
        </a>
		@endif

        <p class="row-banner-seemore"><a
                href="{{ $bannerTemplete['url1'] }}">{{ trans("$theme-app.global.see_more") }}</a></p>
    </div>

    <div class="banner-col">
        <h2 class="row-banner-title">{{ $bannerTemplete['title2'] }}</h2>
        <p class="row-banner-description">{!! $bannerTemplete['text2'] !!}</p>

		@if(!empty($bannerTemplete['image2']))
        <a href="{{ $bannerTemplete['url2'] }}">
            <picture class="row-banner-image">
                <source srcset="{{ $bannerTemplete['image2']['desktop'] }}" media="(min-width: 992px)">
                <img src="{{ $bannerTemplete['image2']['mobile'] }}" alt="" loading="{{ $loading }}">
            </picture>
        </a>
		@endif

        <p class="row-banner-seemore"><a
                href="{{ $bannerTemplete['url2'] }}">{{ trans("$theme-app.global.see_more") }}</a></p>
    </div>

</section>
