@php
    $relationSlide = uniqid('relation-slide-');
    $controlSlide = uniqid('slider-nav-');
    $lowerKey = mb_strtolower($banner->key);
@endphp

<section class="syncing-banner">
    <div class="slider-for {{ $relationSlide }}">
        @foreach ($banner->activeItems as $bannerItem)
            <picture>
                <source srcset="{{ $bannerItem->images['desktop'] }}" media="(min-width: 992px)">
                <img src="{{ $bannerItem->images['mobile'] }}" alt="">
            </picture>
        @endforeach
    </div>
    <div class="text-block">
        <h2 class="syncing-title">{{ trans("$theme-app.metas.$lowerKey") }}</h2>

        <div class="{{ $relationSlide }} text-banner">
            @foreach ($banner->activeItems as $key => $bannerItem)
                <div>{!! $bannerItem->texto !!}</div>
            @endforeach
        </div>

        <div class="slider-nav {{ $controlSlide }}">
            @foreach ($banner->activeItems->take(5) as $bannerItem)
                <div>
                    <picture>
                        {{-- <source srcset="{{$bannerItem->images['desktop']}}" media="(min-width: 600px)"> --}}
                        <img src="{{ $bannerItem->images['mobile'] }}" alt="MDN">
                    </picture>
                </div>
            @endforeach
        </div>
    </div>
</section>

<script>
    $(".{{ $relationSlide }}").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: ".{{ $controlSlide }}, .{{ $relationSlide }}"
    });
    $(".{{ $controlSlide }}").slick({
        slidesToShow: 5,
        slidesToScroll: 5,
        asNavFor: ".{{ $relationSlide }}",
        //dots: true,
        //centerMode: true,
        focusOnSelect: true
    });
</script>
