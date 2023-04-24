<style>
    section {
        height: 600px;
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    @media(max-width: 720px) {
        section {
            height: auto;
            display: grid;
            grid-template-columns: 1fr;
            grid-template-rows: 1fr 1fr;
        }
    }

    section .slider-for,
    .slider-for div,
    section .text-block {
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    .slider-for img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        overflow: hidden;
    }

    .text-block {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        /* align-items: center; */
        padding: 50px 4rem 60px;
        text-align: center;
    }

    .slick-track {
        transform: translate3d(0, 0, 0) !important;
    }

    .slider-nav .slick-track {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .slider-nav .slick-slide {
        width: 84px !important;
        height: 94.5px;
        overflow: hidden;
        border-radius: 4px;
    }

    .slider-nav .slick-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        cursor: pointer;
    }
</style>

@php
    $relationSlide = uniqid('relation-slide-');
    $controlSlide = uniqid('slider-nav-');
@endphp

<section>
    <div class="slider-for {{ $relationSlide }}">
        @foreach ($banner->activeItems as $bannerItem)
            <picture>
                <source srcset="{{ $bannerItem->images['desktop'] }}" media="(min-width: 600px)">
                <img src="{{ $bannerItem->images['mobile'] }}" alt="MDN">
            </picture>
        @endforeach
    </div>
    <div class="text-block">
        <h2>test</h2>

        <div class="{{ $relationSlide }}">
            @foreach ($banner->activeItems as $key => $bannerItem)
                <p><strong>{{ $key + 1 }} .- </strong> Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    Mollitia perspiciatis voluptatibus suscipit,
                    laboriosam officia accusamus dolores quia optio voluptatem laborum repellendus! Sit maxime
                    voluptatum assumenda earum molestiae atque dicta velit.</p>
            @endforeach
        </div>

        <div class="slider-nav {{ $controlSlide }}">
            @foreach ($banner->activeItems as $bannerItem)
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
        asNavFor: ".{{ $controlSlide }}"
    });
    $(".{{ $controlSlide }}").slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        asNavFor: ".{{ $relationSlide }}",
        //dots: true,
        //centerMode: true,
        focusOnSelect: true
    });
</script>
