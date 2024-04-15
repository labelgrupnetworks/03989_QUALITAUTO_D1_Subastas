@extends('layouts.default')

@section('title')
	{{ trans($theme . '-app.foot.faq') }}
@stop

@section('content')
	<?php
	$bread[] = ['name' => trans($theme . '-app.foot.contact')];
	?>

	<script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>

	<div class="contact-page">
		<div class="background-light-green">
			<div class="container padding-contact-sections">
				@include('includes.breadcrumb')
				<div class="row top-align-items-center">
					<div class="col-xs-12 col-md-6">
						<h1 class="fs-big-title">
							{!! trans("$theme-app.contact.title") !!}
						</h1>
					</div>
					<div class="col-xs-6">
						<img class="header-image height-image img-responsive"
							src="/themes/{{ $theme }}/assets/img/top_banner_image_rounded.png">
					</div>
				</div>
			</div>
		</div>

		<div class="container padding-contact-sections">
			<div class="row">
				<div class="col-xs-12">
					<div class="contacts-articles">
						<div class="column-contacts">
							<div class="single-contact">
								<p><span class="default-bold">{{ trans("$theme-app.contact.paint_title") }}</span></p>
								<p>
									{{ trans("$theme-app.contact.paint_mail") }} <br>
									{!! trans("$theme-app.contact.paint_num") !!}
								</p>
							</div>
							<div class="single-contact">
								<p><span class="default-bold">{{ trans("$theme-app.contact.logistic_title") }}</span></p>
								<p>
									{{ trans("$theme-app.contact.logistic_mail") }} <br>
									{{ trans("$theme-app.contact.logistic_num") }}
								</p>
							</div>
						</div>
						<div class="column-contacts">
							<div class="single-contact">
								<p><span class="default-bold">{{ trans("$theme-app.contact.antiques_title") }}</span></p>
								<p>
									{{ trans("$theme-app.contact.antiques_mail") }} <br>
									{!! trans("$theme-app.contact.antiques_num") !!}
								</p>
							</div>
							<div class="single-contact">
								<p><span class="default-bold">{{ trans("$theme-app.contact.administration_title") }}</span></p>
								<p>
									{{ trans("$theme-app.contact.antiques_mail") }} <br>
									{{ trans("$theme-app.contact.administration_num") }}
								</p>
							</div>
						</div>
						<div class="column-contacts">
							<div class="single-contact">
								<p><span class="default-bold">{{ trans("$theme-app.contact.jewels_title") }}</span></p>
								<p>
									{{ trans("$theme-app.contact.jewels_mail") }} <br>
									{!! trans("$theme-app.contact.jewels_num") !!}
								</p>
							</div>
							<div class="single-contact">
								<p><span class="default-bold">{{ trans("$theme-app.contact.valuations_title") }}</span></p>
								<p>
									{{ trans("$theme-app.contact.antiques_mail") }} <br>
									{{ trans("$theme-app.contact.valuations_num") }}
								</p>
							</div>
						</div>
						<div class="stretch-line"></div>
						<div class="column-contacts">
							<div class="single-contact">
								<p>
									{{ trans("$theme-app.contact.address_lin1") }} <br>
									{{ trans("$theme-app.contact.address_lin2") }} <br>
									{{ trans("$theme-app.contact.address_num") }}
								</p>
							</div>
							<div class="single-contact">
								<p>
									<span class="default-bold">{{ trans("$theme-app.contact.schedule_subtitle") }} </span> <br>
									{{ trans("$theme-app.contact.shedule_lin1") }} <br>
									{{ trans("$theme-app.contact.shedule_lin2") }} <br>
									{{ trans("$theme-app.contact.shedule_lin3") }}
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="background-contact-form">
			<div class="container padding-contact-sections">
				<h2 class="fs-xxxlarge">{{ trans("$theme-app.contact.form_title") }}</h2>
				<form name="contactForm" id="contactForm" method="post" action="javascript:sendContact()" enctype="multipart/form-data">
					{!! $data['formulario']['_token'] !!}
					<div class="row">
						<div class="col-xs-12">
							<div class="input-margin">
								<label class="d-block" for="texto__1__nombre">
									{{ trans("$theme-app.contact.form_field_name") }}
								</label>
								{!! $data['formulario']['nombre'] !!}
							</div>
						</div>
						<div class="col-xs-12 col-md-6">
							<div class="input-margin">
								<label class="d-block" for="email__1__email">
									{{ trans("$theme-app.contact.form_field_email") }}
								</label>
								{!! $data['formulario']['email'] !!}
							</div>
						</div>
						<div class="col-xs-12 col-md-6">
							<div class="input-margin">
								<label class="d-block" for="texto__1__telefono">
									{{ trans("$theme-app.contact.form_field_tlfnum") }}
								</label>
								{!! $data['formulario']['telefono'] !!}
							</div>
						</div>
						<div class="col-xs-12">
							<div class="input-margin">
								<label class="d-block" for="textogrande__1__comentario">
									{{ trans("$theme-app.contact.form_field_comment") }}
								</label>
								{!! $data['formulario']['comentario'] !!}
							</div>
						</div>
						<div class="col-xs-12">
							<div class="input-margin">
								<label>
									<p>{{ trans("$theme-app.contact.form_field_files") }}</p>
								</label>
								<div class="input-files-contact">
									<label class="btn btn-lb-primary" for="files">
										{{ trans("$theme-app.contact.form_field_files_button") }}
									</label>
									<div>
										<p>{{ trans("$theme-app.contact.form_field_files_info") }}</p>
									</div>
								</div>
								<input class="hidden" id="files" type="file" accept="image/png, image/jpeg" name="images[]" multiple=""
									required="">
								<div class="contact-images-preview"></div>
							</div>
						</div>
						<div class="col-xs-12">
							<div class="captcha-and-terms">
								<div class="check_term">
									<input type="checkbox" class="newsletter" name="condiciones" value="on" id="bool__1__condiciones"
										autocomplete="off">
									<label for="bool__1__condiciones"><?= trans($theme . '-app.emails.privacy_conditions') ?>
								</div>

								<div class="g-recaptcha" data-sitekey="{{ \Config::get('app.codRecaptchaEmailPublico') }}"
									data-callback="onSubmit">
								</div>
							</div>
						</div>

						<div class="col-xs-12 text-center">
							{!! $data['formulario']['SUBMIT'] !!}
						</div>
					</div>
				</form>
			</div>
		</div>
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
