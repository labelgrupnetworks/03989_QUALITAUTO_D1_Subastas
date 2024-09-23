@php
    //$name_archive = '/img/PER/' . Config::get('app.gemp') . $esp->per_especial1 . '.jpg';
	$image = $image ?? '/themes/subarna/assets/img/tasacion.png';
    $title = $title ?? trans("$theme-app.valoracion_gratuita.need_contact");
@endphp

<section class="container-fluid expert-section">

    <h1>{{ $title }}</h1>

    <div class="expert-section_body">
        <div class="expert-section_img-container visible-lg">
            <img class="expert-section_generic" src="{{ $image }}"
                alt="muestra un ejemplo de tasación" loading="lazy">
        </div>

		@if($specialist)
			<div class="">
				@include('includes.expert', ['specialist' => $specialist])
			</div>
		@endif
    </div>

    <div class="text-center p-2 expert-section_btn-container">
        <a class="btn btn-xl btn-lb-primary" href="{{ Routing::translateSeo('valoracion-articulos') }}">
			{{ trans("$theme-app.valoracion_gratuita.online_appraisal") }}
        </a>
    </div>

</section>
