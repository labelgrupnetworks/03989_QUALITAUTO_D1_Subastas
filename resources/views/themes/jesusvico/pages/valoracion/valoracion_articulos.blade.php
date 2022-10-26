@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@section('content')

	@php
		$bread[] = ['name' => $data['title']];
	@endphp

	<div class="container valoracion-page mb-5">

		@include('includes.breadcrumb')

		{{-- <h1 class="titlePage">{{ trans("$theme-app.valoracion_gratuita.solicitud_valoracion") }}</h1>

		<p class="optimal-text-lenght">{!! trans("$theme-app.valoracion_gratuita.desc_assessment") !!}</p> --}}

		<div class="row">
			<div class="col-12 col-md-8">

				<div class="decoration margin-video">
					<div class="ratio ratio-16x9">
						<video style="height: auto; padding: 0 10px;" controls=""
							poster="/themes/jesusvico/assets/img/how_to_sell_poster.jpg" autoplay="">
							<source src="/files/videos/jesusvico_tasaciones.mp4" type="video/mp4">
							Tu navegador no soporta HTML5 video.
						</video>
					</div>
				</div>

			</div>
			<div class="col-12 col-md-4">

				<div class="flex-align-center" style="margin-bottom: 30px">
					<div class="mb-5">
						<h3>¿Desea obtener una valoración de sus artículos?</h3>
						<p>Uno de nuestros expertos valorará los objetos que detalle a continuación, y se podrá en contacto con usted para
							transmitirle los resultados.</p>
					</div>

					{{-- Flecha --}}
					<svg class="margin-arrow-svg" xmlns="http://www.w3.org/2000/svg" width="49.162" height="40.404"
						viewBox="0 0 49.162 40.404">
						<g id="Grupo_40" data-name="Grupo 40" transform="translate(-240.156 -563.202)">
							<path id="Trazado_2" data-name="Trazado 2" d="M140.429,44.608,164.3,68.482l23.874-23.874"
								transform="translate(100.433 533.71)" fill="none" stroke="#b9b13c" stroke-width="2" />
							<path id="Trazado_3" data-name="Trazado 3" d="M140.429,44.608,164.3,68.482l23.874-23.874"
								transform="translate(100.433 519.301)" fill="none" stroke="#b9b13c" stroke-width="2" opacity="0.398" />
						</g>
					</svg>
				</div>
			</div>
		</div>


		<div class="row">

			<div class="pt-5 mt-5 mb-5">
				<p>Por favor, añada la máxima información posible para garantizar una revisión más precisa.</p>
			</div>

			<form id="form-valoracion-adv" action="" class="mt-3">
				@csrf
				<p class="text-danger h4 hidden msg_valoracion">
					{{ trans(\Config::get('app.theme') . '-app.valoracion_gratuita.error') }}</p>

				<div class="row">
					<div class="col-12">
						<div class="mb-3">
							<label for="name" class="form-label">{{ trans("$theme-app.valoracion_gratuita.name") }}</label>
							<input type="text" class="form-control input-text-style" id="name" name="name" {{-- placeholder="{{ trans("$theme-app.valoracion_gratuita.name") }}" --}}
								required>
						</div>
					</div>

					<div class="col-12 col-md-6">
						<div class="mb-3">
							<label for="email" class="form-label">{{ trans("$theme-app.valoracion_gratuita.email") }}</label>
							<input type="email" class="form-control input-text-style" id="email" name="email" {{-- placeholder="{{ trans("$theme-app.valoracion_gratuita.email") }}" --}}
								required>
						</div>
					</div>

					<div class="col-12 col-md-6">
						<div class="mb-3">
							<label for="telf" class="form-label">{{ trans("$theme-app.valoracion_gratuita.telf") }}</label>
							<input type="phone" class="form-control input-text-style" id="telf" name="telf" {{-- placeholder="{{ trans("$theme-app.valoracion_gratuita.telf") }}" --}}
								required>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col">
						<div class="mb-3">
							<label for="descripcion" class="form-label">{{ trans("$theme-app.user_panel.description") }}</label>
							<textarea class="form-control input-text-style" rows="10" name="descripcion" required {{-- placeholder="{{ trans("$theme-app.valoracion_gratuita.description") }}" --}}></textarea>
						</div>
					</div>
				</div>

				{{-- <div class="row">
			<div class="col-md-4">
				<div class="mb-3">
					<label for="name" class="form-label">{{ trans("$theme-app.valoracion_gratuita.name") }}</label>
					<input type="text" class="form-control" id="name" name="name" placeholder="{{ trans("$theme-app.valoracion_gratuita.name") }}" required>
				</div>
				<div class="mb-3">
					<label for="email" class="form-label">{{ trans("$theme-app.valoracion_gratuita.email") }}</label>
					<input type="email" class="form-control" id="email" name="email" placeholder="{{ trans("$theme-app.valoracion_gratuita.email") }}" required>
				</div>
				<div class="mb-3">
					<label for="telf" class="form-label">{{ trans("$theme-app.valoracion_gratuita.telf") }}</label>
					<input type="phone" class="form-control" id="telf" name="telf" placeholder="{{ trans("$theme-app.valoracion_gratuita.telf") }}" required>
				</div>
			</div>

			<div class="col-md-6">
				<div class="mb-3">
					<label for="descripcion" class="form-label">{{ trans("$theme-app.user_panel.description") }}</label>
					<textarea class="form-control" rows="10" name="descripcion" required placeholder="{{ trans("$theme-app.valoracion_gratuita.description") }}"></textarea>
				</div>
			</div>
		</div> --}}

				<div class="row mb-3">
					<div class="col-md-12">
						<div id="dropzone" class="position-relative">
							<p class="text-danger error-dropzone" style="display:none">
								<small>{{ trans("$theme-app.msg_error.max_size") }}</small>
							</p>
							<p class="text-dropzone">{!! trans("$theme-app.valoracion_gratuita.adj_IMG") !!}</p>
							<div class="mini-file-content d-flex align-items-center position-relative gap-2 mt-1"></div>
							<input id="images" type="file" name="imagen[]" multiple />
						</div>
					</div>
				</div>

				<button type="submit" id="valoracion-adv"
					class="button-send-valorate btn btn-lb-primary">{{ trans("$theme-app.valoracion_gratuita.send") }}</button>
			</form>
		</div>
	</div>

	<script>
		var imagesarr = [];

		function myFunction(el) {
			$(el).remove()
		}
		$(function() {

			$('.mini-upload-image').click(function() {
				alert()
			})


		});
	</script>
@stop
