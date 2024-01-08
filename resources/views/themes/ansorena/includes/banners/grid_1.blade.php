@php
    $bannerContent = BannerLib::getOnlyContentForBanner($banner);

    $bannerTemplete = [
        'id' => $banner->key ?? $content['id'],
        'title' => $content['title'],
        'text1' => $bannerContent['texts'][0] ?? $content['text1'] ?? '',
        'text2' => $bannerContent['texts'][1] ?? $content['text2'] ?? '',
        'image1' => $bannerContent['images'][0] ?? $content['images1'] ?? null,
        'image2' => $bannerContent['images'][1] ?? $content['images2'] ?? null,
        'url' => $bannerContent['links'][0] ?? $content['url'] ?? '',
    ];

    $loading = $options['loading'] ?? 'eager';
@endphp

<section id="{{ $bannerTemplete['id'] }}" class="px-3 px-lg-0 grid-banner grid-banner-1">
    <h2 class="grid-banner-title">{{ $bannerTemplete['title'] }}</h2>

    <h3 class="grid-banner-text-highlight">
        {!! $bannerTemplete['text1'] !!}
    </h3>
    <p class="grid-banner-description">{!! $bannerTemplete['text2'] !!}</p>
    <p class="grid-banner-seemore"><a href="{{ $bannerTemplete['url'] }}">{{ trans("$theme-app.global.see_more") }}</a>
    </p>

	@if(!empty($bannerTemplete['image1']))
    <a class="grid-banner-image grid-banner-image-1" href="{{ $bannerTemplete['url'] }}">
        <picture>
            <source srcset="{{ $bannerTemplete['image1']['desktop'] }}" media="(min-width: 992px)">
            <img src="{{ $bannerTemplete['image1']['mobile'] }}" alt="" loading="{{ $loading }}">
        </picture>
    </a>
	@endif
	@if(!empty($bannerTemplete['image2']))
    <a class="grid-banner-image grid-banner-image-2" href="{{ $bannerTemplete['url'] }}">
        <picture>
            <source srcset="{{ $bannerTemplete['image2']['desktop'] }}" media="(min-width: 992px)">
            <img src="{{ $bannerTemplete['image2']['mobile'] }}" alt="" loading="{{ $loading }}">
        </picture>
    </a>
	@endif
</section>
