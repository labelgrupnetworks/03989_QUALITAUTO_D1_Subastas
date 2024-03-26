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
            <img class="about-us_img" src="/themes/{{ $theme }}/assets/img/about_asset1.png"
                alt="Sala de subastas Subarna">
        </section>

        <section class="about-us_history">
            <h1>{{ trans("$theme-app.about_us.history_title") }}</h1>
            {{-- Syncing banner... --}}
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
                        <img src="/themes/{{ $theme }}/assets/img/about_asset3.png"
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
@stop
