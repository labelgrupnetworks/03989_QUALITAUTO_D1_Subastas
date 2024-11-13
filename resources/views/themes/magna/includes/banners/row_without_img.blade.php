@php
 $bannerContent = BannerLib::getOnlyContentForBanner($banner);

 $bannerTemplete = [
	'text' => $bannerContent['texts']->first() ?? '',
	'image' => $bannerContent['images'][0] ?? null,
];
$loading = $options['loading'] ?? 'eager';
@endphp

<div class="row">
	<div class="col-12 col-md-4"></div>
	<div class="col-12 col-md-8">
		<p>{!! $bannerTemplete['text'] !!}</p>
	</div>
</div>
