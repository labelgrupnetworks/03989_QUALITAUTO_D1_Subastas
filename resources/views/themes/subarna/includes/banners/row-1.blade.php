@if ($banner)

    <div class="about-us-slider">
		@foreach ($banner->activeItems as $bannerItem)
            <div class="slider-item">
                <div class="slider-item-content">
                    <div class="slider-item-info">
						{!! $bannerItem->texto !!}
                    </div>
                    <div class="slider-item-image">
						<picture class="slider-img">
							<source srcset="{{ $bannerItem->images['desktop'] }}" media="(min-width: 600px)">
							<img src="{{ $bannerItem->images['mobile'] }}">
						</picture>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

	<script>
        const options = {
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            dots: false,
            infinite: false,
            autoplay: false,
			adaptiveHeight: true
        };

        const slider = $(".about-us-slider").slick(options);
	</script>
@endif
