@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.foot.faq') }}
@stop

@section('content')

    <main class="contact-page">

        <section class="fluid-banner">
            {!! BannerLib::bannersPorKey(
                'contacto',
                '',
                ['dots' => false, 'autoplay' => true, 'autoplaySpeed' => 5000, 'slidesToScroll' => 1, 'arrows' => false],
                null,
                false,
                ''
            ) !!}
        </section>

        <section class="py-4">
            <div class="container text-center">
                <p class="seo-block-subtitle">{{ trans("$theme-app.pages.contact_seo_subtitle") }}</p>
                <h1 class="seo-block-title">{{ trans("$theme-app.pages.contact_seo_title") }}</h1>
                <h2 class="seo-block-content">{{ trans("$theme-app.pages.contact_seo_text") }}</h2>
            </div>
        </section>

        <section class="py-4">
            <div class="container container-short">
                <x-contact-section>
					<x-slot:topAddress>
						<p class="text-block mb-3">{{ trans("$theme-app.pages.contact_label1") }}</p>
					</x-slot:topAddress>

					<x-slot:topForm>
						<p class="text-block mb-3">{{ trans("$theme-app.pages.contact_label2") }}</p>
					</x-slot:topForm>
				</x-contact-section>
            </div>
        </section>


    </main>

@stop
