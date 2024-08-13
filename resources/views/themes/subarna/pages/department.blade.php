@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@php
	$imageExpertSection = match($ortsec->lin_ortsec0) {
		1 => "/storage/themes/$theme/uploads/JOYAS-dept-Tasa.jpg",
		3 => "/storage/themes/$theme/uploads/Oriental-dept-tasa.jpg",
		4 => "/storage/themes/$theme/uploads/Artes-deco-dept-tasa.jpg",
		5 => "/storage/themes/$theme/uploads/pint-dep-tasa.jpg",
		6 => "/storage/themes/$theme/uploads/escult-dept-tasa.jpg",
		default => "/themes/$theme/assets/img/tasacion.png",
	}
@endphp

@section('content')

    <main class="department-page">
        <section class="department-page_header">
            <div class="department-page_title">
                <h1 class="ff-highlight">{{ $ortsec->des_ortsec0 }}</h1>
            </div>
            <img class="department-page_img"
                src="{{ "/themes/$theme/assets/img/departamentos/department{$ortsec->lin_ortsec0}_large.jpg" }}"
                alt="{{ $ortsec->des_ortsec0 }}">
        </section>

        <section class="container">
            <div class="department-page_seo">
                <p>{!! $ortsec->meta_contenido_ortsec0 !!}</p>
            </div>
        </section>

        @include('includes.expert-contact', [
            'title' => trans("$theme-app.valoracion_gratuita.need_contact"),
			'image' => $imageExpertSection,
            'specialist' => $especialistas->first(),
        ])

        <section class="lotes_destacados" id="lotes_departamentos-content">
            <div class="container">
                <h3 class="title_lotes_destacados">
                    {{ trans("$theme-app.valoracion_gratuita.destacados") }}
                </h3>

                <div>
                    <div class="lds-ellipsis loader">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                    <div class="owl-theme owl-carousel" id="lotes_departamentos"></div>
                </div>
            </div>
        </section>

    </main>

    @php
        $lang = Config::get('app.locale');
        $replace = [
            'departamento' => $ortsec->lin_ortsec0,
            'lang' => Config::get('app.language_complete')[Config::get('app.locale')],
            'emp' => Config::get('app.emp'),
            'gemp' => Config::get('app.gemp'),
        ];
    @endphp

    <script type="text/javascript">
        const replace = @json($replace);
        const lang = @json($lang);
        $(document).ready(function() {
            ajax_newcarousel('lotes_departamentos', replace, lang);
        });
    </script>

@stop
