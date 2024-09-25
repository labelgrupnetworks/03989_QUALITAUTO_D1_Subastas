@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop


@section('content')

    <main class="about-us-page">
        <section class="about-us_header">
            <h1 class="about-us_title">{!! trans("$theme-app.about_us.title_page") !!}</h1>
            <p class="about-us_subtitle">
                {{ trans("$theme-app.about_us.subtitle_page") }}
            </p>
            <img class="about-us_img" src="/storage/themes/{{ $theme }}/uploads/EQUI copia.jpg"
                alt="Sala de subastas Subarna">
			<picture>
				<source srcset="/storage/themes/{{ $theme }}/uploads/equipo-mobile.webp" type="image/webp" media="(max-width: 768px)">
				<source srcset="/storage/themes/{{ $theme }}/uploads/equipo-mobile.jpg" media="(max-width: 768px)">
				<source srcset="/storage/themes/{{ $theme }}/uploads/equipo.webp" type="image/webp" >
				<img class="about-us_img" src="/storage/themes/{{ $theme }}/uploads/equipo.jpg" alt="Equipo de Subarna">
			</picture>
        </section>

        <section class="about-us_history" style="padding-bottom: 0">
            <h1>{{ trans("$theme-app.about_us.history_title") }}</h1>

            @php
                $years = [1982, 1995, 2000, 2002, 2009, 2014, 2018, 2019, 2021];
            @endphp

            <div class="about-us_years">
                <div class="container">
                    <div class="slider-nav slider-nav-control">
                        @foreach ($years as $key => $year)
                            <div @class(['active' => $loop->first]) onclick="goToBanner('{{ $key }}')">
                                <span>{{ $year }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="about-us_banner">

                <button class="btn-lb btn-lb-icon" onclick="goToPrev()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 57.6">
                        <path
                            d="M1.058 26.258c-1.406 1.406 -1.406 3.69 0 5.096l21.6 21.6c1.406 1.406 3.69 1.406 5.096 0s1.406 -3.69 0 -5.096L8.696 28.8 27.742 9.742c1.406 -1.406 1.406 -3.69 0 -5.096s-3.69 -1.406 -5.096 0l-21.6 21.6z" />
                    </svg>
                </button>


                <div class="container position-relative">
                    <div class="about-us_year-container">
                        <span class="year-banner">{{ $years[0] }}</span>
                    </div>

                    {!! BannerLib::bannerWithView('about-us', 'row-1') !!}
                </div>

                <button class="btn-lb btn-lb-icon" onclick="goToNext()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 57.6">
                        <path
                            d="M34.943 26.258c1.406 1.406 1.406 3.69 0 5.096l-21.6 21.6c-1.406 1.406 -3.69 1.406 -5.096 0s-1.406 -3.69 0 -5.096L27.304 28.8 8.258 9.742c-1.406 -1.406 -1.406 -3.69 0 -5.096s3.69 -1.406 5.096 0l21.6 21.6z" />
                    </svg>
                </button>

            </div>
        </section>

        <section class="about-us_achieved">
            <h1>{{ trans("$theme-app.about_us.achieved_title") }}</h1>

            <div class="about-us_achieved_grid">
                <div class="achieved_row">
                    <div class="archived-item">
                        <h2>{{ trans("$theme-app.about_us.experience_title") }}</h2>
                        <p>{{ trans("$theme-app.about_us.experience_description") }}</p>
                    </div>
                    <div class="archived-item">
                        <img src="/themes/{{ $theme }}/assets/img/about_asset2.png"
                            alt="Logro conseguido en subarna">
                    </div>
                </div>

                <div class="achieved_row">
                    <div class="archived-item">
                        <h2>{{ trans("$theme-app.about_us.trust_title") }}</h2>
                        <p>{{ trans("$theme-app.about_us.trust_description") }}</p>
                    </div>
                    <div class="archived-item">
                        <img src="/storage/themes/{{ $theme }}/uploads/Confianza-quienes somos.jpg"
                            alt="Logro conseguido en subarna">
                    </div>
                </div>
            </div>
        </section>

        <section class="about-us_experts">
            <h1>{{ trans("$theme-app.about_us.our_team") }}</h1>

            <div class="experts-grid">
                @foreach ($specialists as $specialist)
                    @include('includes.expert', ['specialist' => $specialist])
                @endforeach
            </div>

        </section>

    </main>

    <script>
        $(document).ready(function() {
            $(".about-us-slider").on('beforeChange', function(event, slick, currentSlide, nextSlide) {
                activeYearCurrentBanner(nextSlide);
            });
        });

        function goToBanner(index) {
            $(".about-us-slider").slick('slickGoTo', index);
        }

        function goToPrev() {
            $(".about-us-slider").slick('slickPrev');
        }

        function goToNext() {
            $(".about-us-slider").slick('slickNext');
        }

        function activeYearCurrentBanner(currentBanner) {
            $(".year-banner").text($(".slider-nav-control div").eq(currentBanner).text());
            $(".slider-nav-control div").removeClass('active');
            $(".slider-nav-control div").eq(currentBanner).addClass('active');
        }
    </script>
@stop
