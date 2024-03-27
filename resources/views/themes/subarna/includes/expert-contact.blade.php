@php
    //$name_archive = '/img/PER/' . Config::get('app.gemp') . $esp->per_especial1 . '.jpg';
    $title = $title ?? trans("$theme-app.valoracion_gratuita.need_contact");
@endphp

<section class="container-fluid expert-section">

    <h1>{{ $title }}</h1>

    <div class="expert-section_body">
        <div class="">
            <img class="expert-section_generic" src="/themes/subarna/assets/img/tasacion.png"
                alt="muestra un ejemplo de tasación" loading="lazy">
        </div>

		@includeWhen($specialist, 'includes.expert', ['specialist' => $specialist])

    </div>

    <div class="text-center p-2">
        <a class="btn btn-xl btn-lb-primary" href="{{ Routing::translateSeo('valoracion-articulos') }}">
			{{ trans("$theme-app.valoracion_gratuita.online_appraisal") }}
        </a>
    </div>

</section>
