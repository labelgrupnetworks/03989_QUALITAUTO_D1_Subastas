@if ($banner)
@php
    $slide = uniqid('slide-');
    $title = $content['title'] ?? '';
@endphp

<style>
    .column_banner {
        position: relative;
    }

    .fluid-banner .slick-dots {
        position: absolute;
        bottom: 30px;
    }

    .fluid-banner .slick-dots li {
        display: inline-block;
        vertical-align: middle;
        padding: 0 2px;
        width: initial;
        height: initial;
        margin: 0 3px;
        padding: 0;
        cursor: pointer;
    }

    .fluid-banner .slick-dots li button {
        width: 90px;
        height: 2px;
        background-color: #F0EEE6;
        border-radius: 8px;
        border: 0;
        transition: all 0.5s;
        display: inline-block;
        opacity: 0.5;
        padding: 0;
        color: transparent;
    }

    .fluid-banner .slick-dots li button::before {
        content: none;
    }

    .fluid-banner .slick-dots li.slick-active button {
        background: #FFF;
        opacity: 1;
    }

    /*  section.fluid-banner {
        height: 860px;
    } */
    section.fluid-banner img {
        width: 100%;
        height: calc(100vh - 72px);
        object-fit: cover;
    }

    section.fluid-banner .slider-text,
    section.fluid-banner .slider-title {
        font-family: var(--font-prominent);
        font-weight: 100;
        line-height: 100%;
        color: var(--lb-color-secondary);
        position: absolute;
        left: 0;
        width: 100%;
        bottom: 5rem;
        text-align: center
    }

    section.fluid-banner .slider-title {
        font-size: clamp(2.5rem, 2.0385rem + 2.0513vw, 4.5rem);
        z-index: 1;
    }

    section.fluid-banner .slider-text>* {
        font-size: clamp(2.5rem, 2.0385rem + 2.0513vw, 4.5rem);
        text-transform: uppercase;
    }

	.fluid-banner .slick-dotted.slick-slider {
		margin-bottom: 0;
	}

	.fluid-banner .stretched-link::after {
		background: linear-gradient(to bottom, rgba(255, 0, 0, 0) 70%, rgba(0, 0, 0, .4));
	}

    @media(min-width: 992px) {
        section.fluid-banner img {
            height: calc(100vh - 180px);
        }
    }
</style>

<section class="fluid-banner position-relative">
    @if (!empty($title))
		{!! $title !!}
    @endif
    <div class="slider {{ $slide }}">
        @foreach ($banner->activeItems as $bannerItem)
            <div class="position-relative">
                <picture class="slider-img">
                    <source srcset="{{ $bannerItem->images['desktop'] }}" media="(min-width: 600px)">
                    <img src="{{ $bannerItem->images['mobile'] }}" alt="{{ $bannerItem->texto }}">
                </picture>
                <div class="slider-text">
                    {!! $bannerItem->texto ?? '' !!}
                </div>
                @if (!empty($bannerItem->url))
                    <a href="{{ $bannerItem->url }}" class="stretched-link"></a>
                @endif
            </div>
        @endforeach
    </div>
</section>

<script>
    const bladeOptions = @json($options);
    const options = {
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        dots: true,
        infinite: true,
        autoplay: true,
        autoplaySpeed: 3000,
        ...bladeOptions
    };

    $(".{{ $slide }}").slick(options);
</script>
@endif
