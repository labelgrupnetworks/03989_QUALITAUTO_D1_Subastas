@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@php
    //$name_archive = '/img/PER/' . Config::get('app.gemp') . $esp->per_especial1 . '.jpg';
    if (empty($especialistas)) {
        $especialistas = [
            (object) [
                'nom_especial1' => 'Nombre del experto',
                'pos_especial1' => 'Posición del experto',
                'email_especial1' => 'email@email.es',
                'phone_especial1' => '+34 666 77 88 99',
                'desc_especial1' =>
                    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ut quia aliquid accusantium. Laboriosam soluta laudantium nulla quas doloribus. Dolorem nesciunt cupiditate minus officia inventore reprehenderit dolores aspernatur autem perferendis reiciendis?',
                'per_especial1' => '1',
            ],
        ];
    }

    $specialist = collect($especialistas)->first();
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

        <section class="container-fluid expert-section">

            <h1>¿NECESITAS CONTACTAR CON UN EXPERTO?</h1>

            <div class="expert-section_body">
                <div class="">
                    <img class="expert-section_generic" src="/themes/subarna/assets/img/placeholder_rectangle_v.png"
                        alt="">
                </div>

                <div class="expert-card">
                    <img src="/themes/subarna/assets/img/placeholder_round.svg" alt="">
                    <div class="expert-card_name">
                        <h2>{{ $specialist->nom_especial1 }} </h2>
                        <p>{{ $specialist->pos_especial1 }}</p>
                    </div>
                    <div class="expert-card_desc">
                        <p>
                            {{ $specialist->desc_especial1 }}
                        </p>
                    </div>
                    <p class="expert-card_contact">
                        <a href="mailto:{{ $specialist->email_especial1 }}">{{ $specialist->email_especial1 }}</a>
                        <br>
                        <a href="tel:{{ $specialist->phone_especial1 }}">{{ $specialist->phone_especial1 }}</a>
                    </p>
                </div>
            </div>

            <div class="text-center p-2">
                <a class="btn btn-xl btn-lb-primary" href="{{ Routing::translateSeo('valoracion-articulos') }}">
					Tasación online
				</a>
            </div>

        </section>

		<section id="lotes_departamentos-content" class="lotes_destacados">
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
        $replace = [
            'departamento' => $ortsec->lin_ortsec0,
            'lang' => Config::get('app.language_complete')[Config::get('app.locale')],
            'emp' => Config::get('app.emp'),
            'gemp' => Config::get('app.gemp'),
        ];
    @endphp

    <script type="text/javascript">
        const replace = @json($replace);
        $(document).ready(function() {
            ajax_newcarousel('lotes_departamentos', replace);
        });
    </script>

@stop
