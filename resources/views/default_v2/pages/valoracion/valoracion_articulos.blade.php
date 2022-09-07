@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

@php
$bread[] = ["name" => $data['title']];
@endphp

<div class="container valoracion-page">

	@include('includes.breadcrumb')

	<h1 class="titlePage">{{ trans("$theme-app.valoracion_gratuita.solicitud_valoracion") }}</h1>

	<p class="optimal-text-lenght">{!! trans("$theme-app.valoracion_gratuita.desc_assessment") !!}</p>

	<form id="form-valoracion-adv" action="" class="mt-3">
		@csrf
		<p class="text-danger h4 hidden msg_valoracion">{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.error') }}</p>

		<div class="row">
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
		</div>

		<div class="row mb-3">
			<div class="col-md-10">
				<div id="dropzone" class="position-relative">
					<p class="text-danger error-dropzone" style="display:none"><small>{{ trans("$theme-app.msg_error.max_size") }}</small></p>
					<p class="text-dropzone">{!! trans("$theme-app.valoracion_gratuita.adj_IMG") !!}</p>
					<div class="mini-file-content d-flex align-items-center position-relative gap-2 mt-1"></div>
					<input id="images" type="file" name="imagen[]" multiple/>
				</div>
			</div>
		</div>

		<button type="submit" id="valoracion-adv" class="button-send-valorate btn btn-lb-primary">{{ trans("$theme-app.valoracion_gratuita.send") }}</button>
	</form>
</div>

<script>
      var imagesarr = [];
      function myFunction( el ) {
        $(el).remove()
    }
$(function() {

$('.mini-upload-image').click(function (){
    alert()
})


 });

</script>
@stop
