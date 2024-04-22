@extends('layouts.default')

@section('title')
	{{ trans($theme . '-app.head.title_app') }}
@stop

@section('content')
	<?php

	$bread[] = ['name' => $data['title']];
	?>
	@include('includes.breadcrumb')

	<div class="tasaciones">
		<div class="background-light-green">

			<div class="container padding-contact-sections">
				<div class="row top-align-items-center">
					<div class="col-xs-12 col-md-7 col-lg-8">
						<div class="title-container">
							<h1 class="fs-big-title bold">
								{!! trans("$theme-app.valoracion_gratuita.page_title") !!}
							</h1>
						</div>
					</div>
					<div class="col-xs-0 col-md-5 col-lg-4">
						<img class="header-image height-image img-responsive"
							src="/themes/{{ $theme }}/assets/img/top_banner_image_rounded.png">
					</div>
				</div>
			</div>

			<div class="container">
				<div class="flex-tasaciones-info mb-2">
					<section>
						<img class="img-responsive image-column"
							src="/themes/{{ $theme }}/assets/img/static_pages/smart_cropped.png">
						<div class="ff-highlight fs-xxxlarge">
							<p class="text-center">
								{!! trans("$theme-app.valoracion_gratuita.first_column_desc_info") !!}
							</p>
						</div>
					</section>
					<section>
						<img class="img-responsive image-column" src="/themes/{{ $theme }}/assets/img/static_pages/pc.png">
						<div class="ff-highlight fs-xxxlarge">
							<p class="text-center">
								{!! trans("$theme-app.valoracion_gratuita.second_column_desc_info") !!}
							</p>
						</div>
					</section>
					<section>
						<img class="img-responsive image-column" src="/themes/{{ $theme }}/assets/img/static_pages/up-hand.png">
						<div class="ff-highlight fs-xxxlarge">
							<p class="text-center">
								{!! trans("$theme-app.valoracion_gratuita.third_column_desc_info") !!}
							</p>
						</div>
					</section>
				</div>
			</div>

		</div>

		<div class="background-more-light-green">
			<div class="container padding-contact-sections pb-0">
				<form class="form" id="form-valoracion-adv">
					<div class="hidden hidden-inputs">
						<input type="hidden" value="tasaciones@subarna.net" name="email_category">
					</div>
					<div class="row">
						<div class="col-xs-12">
							<div class="input-margin">
								<label class="d-block" for="texto__1__name">
									{{ trans("$theme-app.contact.form_field_name") }}
								</label>
								<input id="texto__1__name" class="form-control" type="text" name="name" required>
							</div>
						</div>
						<div class="col-xs-12 col-md-6">
							<div class="input-margin">
								<label class="d-block" for="email__1__email">
									{{ trans("$theme-app.contact.form_field_email") }}
								</label>
								<input id="email__1__email" class="form-control" type="email" name="email" required>
							</div>
						</div>
						<div class="col-xs-12 col-md-6">
							<div class="input-margin">
								<label class="d-block" for="texto__1__telf">
									{{ trans("$theme-app.contact.form_field_tlfnum") }}
								</label>
								<input id="texto__1__telf" class="form-control" type="text" name="telf" required>
							</div>
						</div>
						<div class="col-xs-12">
							<div class="input-margin">
								<label class="fs-15em">
									<p>{{ trans("$theme-app.valoracion_gratuita.form_field_files") }}</p>
								</label>
								<div class="input-files-contact">
									<label class="btn btn-lb-primary" for="files">
										{{ trans("$theme-app.contact.form_field_files_button") }}
									</label>
									<div>
										<p>{!! trans("$theme-app.contact.form_field_files_info") !!}</p>
									</div>
								</div>
								<input class="hidden" id="files" type="file" accept="image/png, image/jpeg" name="imagen[]" multiple
									required>
								<div class="contact-images-preview"></div>
							</div>
						</div>
						<div class="col-xs-12">
							<div class="input-margin">
								<label class="d-block fw-normal" for="textogrande__1__descripcion">
									{{ trans("$theme-app.valoracion_gratuita.form_field_description") }}
								</label>
								<textarea class="form-control effect-16" name="descripcion" rows="10" id="textogrande__1__descripcion"
								 placeholder="" autocomplete="off"></textarea>
							</div>
						</div>
						<div class="col-xs-12 text-center">
							<button type="submit" id="valoracion-adv" class="button-principal submitButton btn btn-lb-primary">
								<div class='loader hidden'></div>{{ trans($theme . '-app.valoracion_gratuita.send') }}
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>

		@if (!empty($data['especialistas']) && $data['especialistas']->count() > 0)
			@include('includes.expert-contact', [
				'title' => trans("$theme-app.valoracion_gratuita.need_contact"),
				'specialist' => $data['especialistas']->first(),
			])
		@endif

	</div>


@stop
