@foreach ($banner->activeItems as $bannerItem)
<div class='item-carousel'>
    <a href="{{ $bannerItem->url }}" target="_blank">
		 <picture>
			<source srcset="{{ $bannerItem->images['desktop'] }}" media="(min-width: 600px)">
			<img src="{{ $bannerItem->images['mobile'] }}" alt="{{ $bannerItem->texto }}" class="img-responsive">
		</picture>
    </a>
</div>
@endforeach

