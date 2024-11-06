@php
 $bannerContent = BannerLib::getOnlyContentForBanner($banner);

 $bannerTemplete = [
	'text' => $bannerContent['texts']->first() ?? '',
	'image' => $bannerContent['images'][0] ?? null,
];
$loading = $options['loading'] ?? 'eager';
@endphp

<div class="row">
	<div class="col-12 col-md-6">
		<picture class="row-banner-image">
			<source srcset="{{ $bannerTemplete['image']['desktop'] }}" media="(min-width: 992px)">
			<img src="{{ $bannerTemplete['image']['mobile'] }}" alt="" loading="{{ $loading }}">
		</picture>
	</div>
	<div class="col-12 col-md-6">
		<p>{!! $bannerTemplete['text'] !!}</p>
	</div>
</div>
