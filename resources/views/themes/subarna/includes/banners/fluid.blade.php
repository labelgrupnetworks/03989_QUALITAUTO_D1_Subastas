@if ($banner)
    @php
        $slide = uniqid('slide-');
        $title = $content['title'] ?? '';
    @endphp

    <style>
        section.fluid-banner img {
            width: 100vw;
            height: 100vh;
            object-fit: cover;
        }

        section.fluid-banner .slick-vertical .slick-slide {
            border: none;
        }

        section.fluid-banner .slick-dots {
            bottom: 25px;
        }

        section.fluid-banner .slick-dotted.slick-slider {
            margin-bottom: 0;
        }
    </style>

    <section class="fluid-banner">
        @if (!empty($title))
            {!! $title !!}
        @endif
        <div class="slider {{ $slide }}">

            @foreach ($banner->activeItems as $bannerItem)
                <div class="position-relative" data-invert="true">
                    <picture class="slider-img">
                        <source srcset="{{ $bannerItem->images['desktop'] }}" media="(min-width: 600px)">
                        <img src="{{ $bannerItem->images['mobile'] }}" alt="{{ $bannerItem->texto }}">
                    </picture>

                    @if (!empty($bannerItem->url))
                        <a class="stretched-link" href="{{ $bannerItem->url }}"></a>
                    @endif
                </div>
            @endforeach

            @php
                $cutLine = '<p><strong>&nbsp;</strong></p>';
                $text = trans("$theme-app.home.seo");
                $seoBlocks = explode($cutLine, $text);
            @endphp


            @foreach ($seoBlocks as $seo)
                <div class="footer-banner">
                    <div class="wrapper-footer">
                        <div class="container">
                            <div class="seo-container">
                                {!! $seo !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach


            <div class="footer-banner">
                <div class="wrapper-footer">
                    @include('includes.newsletter')

                    @include('includes.footer-section')
                </div>
            </div>

        </div>
    </section>

    <script>
        const bladeOptions = @json($options);
        const options = {
            vertical: true,
            verticalSwiping: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            dots: true,
            infinite: false,
            autoplay: false,
            ...bladeOptions
        };

        const slider = $(".{{ $slide }}").slick(options);

        const debounceDelay = 700;
        let lastScrollTime = 0;

        slider.on('wheel', (function(e) {
            e.preventDefault();

            let currentTime = new Date().getTime();
            if ((currentTime - lastScrollTime) < debounceDelay) {
                return;
            }

            lastScrollTime = currentTime;

            if (e.originalEvent.deltaY < 0) {
                $(this).slick('slickPrev');
            } else {
                $(this).slick('slickNext');
            }

        }));

        //slider.on('afterChange', invertHeaderColors);
        slider.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
            const $nextSlideDom = $(slick.$slides.get(nextSlide));
            invertHeaderColors($nextSlideDom[0]);
        });

        invertHeaderColors(document.querySelector('.slick-current'));

        function invertHeaderColors(domElement) {
            const isInvert = domElement.hasAttribute("data-invert");
            document.querySelector('.navbar-custom').classList.toggle('inverted', isInvert);

            $('.wrapper-footer').css('padding-top', $('header').height());
        }
    </script>
@endif
