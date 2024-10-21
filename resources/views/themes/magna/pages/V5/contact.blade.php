@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.foot.faq') }}
@stop

@section('content')

    <main class="contact-page">

        <section>
            {!! BannerLib::bannersPorKey(
                'home',
                'home-top-banner',
                ['dots' => false, 'autoplay' => true, 'autoplaySpeed' => 5000, 'slidesToScroll' => 1, 'arrows' => false],
                null,
                false,
                '',
                $page_settings,
            ) !!}
        </section>

        <section class="py-4">
            <div class="container text-center">
                <p class="seo-block-subtitle">Estamos para ayudarte</p>
                <h1 class="seo-block-title">Contacta con nosotros</h1>
                <h2 class="seo-block-content">Illa nonsequ idebis sum int quia coremossi sin ra volorio sapiene tumque nus
                    pores dest
                    provitas est ipicidel im eaque sit dolendit eos doluptat volore, non natentius dus exerum
                    faccum qui aut incipsam, et lant labori atem qui unt everferum</h2>
            </div>
        </section>

        <section class="py-4">
            <div class="container container-short">
                <x-contact-section>
					<x-slot:topAddress>
						<p class="text-block mb-3">Dirección</p>
					</x-slot:topAddress>

					<x-slot:topForm>
						<p class="text-block mb-3">FORMULARIO DE CONTACTO</p>
					</x-slot:topForm>
				</x-contact-section>
            </div>
        </section>


    </main>

@stop
