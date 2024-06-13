@extends('layouts.default')

@section('framework-css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/bootstrap/5.2.0/css/bootstrap.min.css') }}">
@endsection

@section('framework-js')
    <script src="{{ URL::asset('vendor/bootstrap/5.2.0/js/bootstrap.bundle.min.js') }}"></script>
@endsection

@section('custom-css')
    <link href="{{ Tools::urlAssetsCache('/themes/' . $theme . '/css/global.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Tools::urlAssetsCache("/themes/$theme/css/style.css") }}" rel="stylesheet" type="text/css">

    <link href="{{ Tools::urlAssetsCache('/themes/' . $theme . '/css/header.css') }}" rel="stylesheet" type="text/css">
@endsection

@php
    $managers = $data['specialists']->where('lin_especial1', 1);
    $specialists = $data['specialists']->where('lin_especial1', '!=', 1);
	$gemp = Config::get('app.gemp');
@endphp

@section('content')
    <main class="team-page">

		<div class="container text-center">
			<div class="aboutus-section-nav">
				<h1 class="ff-highlight">
					{{ trans("$theme-app.foot.about_us") }}
				</h1>

				<div class="about-us-pages">
					<ul class="list-inline d-flex ff-highlight">
						<li class="list-inline-item">
							<a href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.historia') }}">{{ trans("$theme-app.foot.history") }}</a>
						</li>
						<li class="list-inline-item flex-grow-1">
							<a class="lb-link-underline" href="{{ Routing::translateSeo('equipo') }}">{{ trans("$theme-app.foot.team") }}</a>
						</li>
						<li class="list-inline-item">
							<a href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.careers') }}">{{ trans("$theme-app.foot.work_with_us") }}</a>
						</li>
					</ul>
				</div>
			</div>
		</div>


        <div class="container">
            <div class="row">
                <div class="col-lg-9 offset-lg-3">
                    <div class="row row-cols-1 row-cols-lg-3 pb-5 border-bottom">
                        @foreach ($managers as $manager)
                            <div class="col">
                                <div class="card team-card">
                                    {{-- <img class="card-img-top" src="/img/PER/{{"{$gemp}{$manager->per_especial1}.jpg"}}" alt="Fotografía de {{ $manager->nom_especial1 }}"> --}}
                                    <div class="card-body">
                                        <h3 class="ff-highlight fs-24-32">
                                            {{ $manager->nom_especial1 }}
                                        </h3>
                                        <div class="card-text">
                                            <p class="pos-especial mb-4">
                                                <span class="titulo-especial ff-highlight">
                                                    {{ $manager->desc_especial1 }}
                                                </span>
                                            </p>
											@if(!empty($manager->email_especial1))
                                            <p>
                                                ✉ {{ strtolower($manager->email_especial1) }}
                                            </p>
											@endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

        <div class="container">
            <div class="row gx-0 pb-5 border-bottom">
                <div class="col-12 col-lg-3 pt-lg-5 pe-lg-4 select-sticky">
                    <div class="select-sticky-lg">
                        <div class="select-container order-select">

                            <div class="order-select-icon">
                                <svg width="26" height="22" viewBox="0 0 26 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <line x1="1.5" y1="2.28965e-08" x2="1.5" y2="22" stroke="#0F0E0D">
                                    </line>
                                    <line x1="12.5" y1="2.28965e-08" x2="12.5" y2="22" stroke="#0F0E0D">
                                    </line>
                                    <line x1="24.5" y1="2.28965e-08" x2="24.5" y2="22" stroke="#0F0E0D">
                                    </line>
                                    <rect y="15" width="3" height="3" fill="#0F0E0D"></rect>
                                    <rect x="11" y="4" width="3" height="3" fill="#0F0E0D"></rect>
                                    <rect x="23" y="15" width="3" height="3" fill="#0F0E0D"></rect>
                                </svg>
                                <span class="text-uppercase">{{ trans("$theme-app.global.filter") }}</span>
                            </div>
                            <div class="order-select-container" id="filter-select-container">
                                <select name="order_dir" id="filter-select">
                                    <option value="ALL">
                                        ALL
                                    </option>
                                    @foreach ($data['specialties'] as $specialtie)
                                        <option value="{{ $specialtie }}">
                                            {{ $specialtie }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-12 col-lg-9 py-1 py-lg-5">
					@foreach ($data['specialties'] as $speciality)
                    <div class="row row-cols-1 row-cols-lg-3 gy-lg-5 mb-lg-5">
                        @foreach ($specialists->where('titulo_especial0', $speciality) as $specialist)
                            <div class="col" data-titulo="{{ $specialist->titulo_especial0 }}">
                                <div class="card team-card">
									{{-- <img class="card-img-top" src="/img/PER/{{"{$gemp}{$specialist->per_especial1}.jpg"}}" alt="Fotografía de {{ $specialist->nom_especial1 }}"> --}}
                                    <div class="card-body">
                                        <h3 class="ff-highlight fs-24-32">
                                            {{ $specialist->nom_especial1 }}
                                        </h3>
                                        <div class="card-text">
                                            <p class="titulo-especial ff-highlight mb-4">
                                                <span>
                                                    {{ $specialist->titulo_especial0 }}
                                                </span>
                                                <span class="ms-1 opacity-50">
                                                    {{ $specialist->desc_especial1 }}
                                                </span>
                                            </p>
											@if(!empty($specialist->email_especial1))
                                            <p>
												<a href="mailto:{{ $specialist->email_especial1 }}">
													✉ {{ strtolower($specialist->email_especial1) }}
												</a>
                                            </p>
											@endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
					@endforeach
                </div>
            </div>

            @include('includes.work_section')
        </div>

    </main>
    <script>
        $('#filter-select').select2({
            minimumResultsForSearch: Infinity,
            width: '100%',
            dropdownParent: $('#filter-select-container')
        })

		$('.order-select-icon').on('click', function(event) {
			$('#filter-select').select2('open')
		})
    </script>
@stop
