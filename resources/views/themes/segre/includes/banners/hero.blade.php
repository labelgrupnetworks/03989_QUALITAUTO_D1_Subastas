@php
    $bannerContent = BannerLib::getOnlyContentForBanner($banner);
	$bannerTemplate = [
		'title' => $content['title'] ?? '',
		'breadcrumb' => $content['breadcrumb'] ?? '',
	]
@endphp

<div class="hero-banner">
    @foreach ($bannerContent['images'] ?? [] as $images)
        <picture>
            <source srcset="{{ $images['desktop'] }}" media="(min-width: 992px)">
            <img src="{{ $images['mobile'] }}" class="hero-image" alt="{{ $bannerTemplate['title'] }} page hero background">
        </picture>
    @endforeach

    <div class="hero-content">
        <h1 class="hero-title">
            {{ $bannerTemplate['title'] }}
        </h1>
		{!! $bannerTemplate['breadcrumb'] !!}
    </div>
</div>
