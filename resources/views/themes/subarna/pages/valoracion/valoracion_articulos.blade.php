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
					<div class="col-xs-12 col-md-7 col-lg-9">
						<div class="title-container">
							<h1 class="fs-big-title bold">
								Tasa <span class="ff-highlight bold fs-italic">online</span> tus <br class="title-spacing">
								joyas, relojes, <br class="title-spacing">
								pintura y <br class="title-spacing">
								antigüedades
							</h1>
						</div>
					</div>
					<div class="col-xs-0 col-md-5 col-lg-3">
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
								Envianos fotos <br>
								y despcripciones <br>
								de tus piezas.
							</p>
						</div>
					</section>
					<section>
						<img class="img-responsive image-column" src="/themes/{{ $theme }}/assets/img/static_pages/pc.png">
						<div class="ff-highlight fs-xxxlarge">
							<p class="text-center">
								Nuestros expertos <br>
								estudiarán la cotización y <br>
								posibilidad de venta.
							</p>
						</div>
					</section>
					<section>
						<img class="img-responsive image-column" src="/themes/{{ $theme }}/assets/img/static_pages/up-hand.png">
						<div class="ff-highlight fs-xxxlarge">
							<p class="text-center">
								Si aceptas la propuesta <br>
								de precio de salida, <br>
								lo incluiremos a subasta.
							</p>
						</div>
					</section>
				</div>
			</div>

		</div>

		<div class="background-more-light-green">
			<div class="container padding-contact-sections">
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
								<label>
									<p>¿Qué quieres que valoremos?</p>
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
									Descripción (recomendamos medidas, información de procedencia, etc.)
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

		{{-- @include('includes.expert-contact') --}}

	</div>




	<script>
		const input = document.querySelector("input[type=file]#files");
		const preview = document.querySelector(".contact-images-preview");

		input.addEventListener("change", updateImageDisplay);

		function updateImageDisplay() {
			while (preview.firstChild) {
				preview.removeChild(preview.firstChild);
			}

			const curFiles = input.files;
			if (curFiles.length === 0) {
				const para = document.createElement("p");
				para.textContent = "No files currently selected for upload";
				preview.appendChild(para);
			} else {
				const list = document.createElement("ul");
				preview.appendChild(list);

				for (const file of curFiles) {
					const listItem = document.createElement("li");
					const para = document.createElement("p");
					if (validFileType(file)) {
						// para.textContent = `File name ${file.name}, file size ${returnFileSize(
					para.textContent = `File size ${returnFileSize(
								          					file.size,
								        				)}.`;
					const image = document.createElement("img");
					image.src = URL.createObjectURL(file);
					image.alt = image.title = file.name;

					listItem.appendChild(image);
					listItem.appendChild(para);
				} else {
					para.textContent = `File name ${file.name}: Not a valid file type. Update your selection.`;
					listItem.appendChild(para);
				}

				list.appendChild(listItem);
			}
		}
	}

	const fileTypes = [
		"image/apng",
		"image/bmp",
		"image/gif",
		"image/jpeg",
		"image/pjpeg",
		"image/png",
		"image/svg+xml",
		"image/tiff",
		"image/webp",
		"image/x-icon",
	];

	function validFileType(file) {
		return fileTypes.includes(file.type);
	}

	function returnFileSize(number) {
		if (number < 1024) {
			return `${number} bytes`;
		} else if (number >= 1024 && number < 1048576) {
			return `${(number / 1024).toFixed(1)} KB`;
		} else if (number >= 1048576) {
			return `${(number / 1048576).toFixed(1)} MB`;
			}
		}
	</script>

@stop
