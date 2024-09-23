@php
/**
 * @var string $texto
 * @var string $url
 * @var file $images
 * @var color $color
 */
@endphp

<div class="position-relative" data-invert="true" @if(!empty($color)) data-color="{{ $color }}" @endif>
	<picture class="slider-img">
		<source srcset="{{ $images['desktop'] ?? '' }}" media="(min-width: 600px)">
		<img src="{{ $images['mobile'] ?? '' }}" alt="{{ $texto ?? '' }}">
	</picture>

	@if (!empty($url))
		<a class="stretched-link" href="{{ $url }}"></a>
	@endif
</div>
