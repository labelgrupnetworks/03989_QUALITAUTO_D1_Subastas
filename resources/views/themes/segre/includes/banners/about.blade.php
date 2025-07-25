@php
    $bannerContent = BannerLib::getOnlyContentForBanner($banner);
@endphp

<div class="section-social-content">
	<div class="section-social-content-text">
		<x-icon.logo />

		@foreach ($bannerContent['texts'] ?? [] as $texts)
			{!! $texts !!}
		@endforeach

	</div>
	<div class="section-social-content-media">
		@foreach ($bannerContent['images'] ?? [] as $images)
			<picture>
				<source srcset="{{ $images['desktop'] }}" media="(min-width: 992px)">
				<img src="{{ $images['mobile'] }}" alt="" loading="lazy">
			</picture>
		@endforeach
	</div>
</div>
