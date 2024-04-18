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
							<h1 class="fs-big-title default-bold">
								Tasa <span class="ff-highlight bold fs-italic">online</span> tus <br class="title-spacing">
								joyas, relojes, <br class="title-spacing">
								pintura y <br class="title-spacing">
								antigüedades
							</h1>
						</div>
					</div>
					<div class="col-xs-5 col-lg-3">
						<img class="header-image height-image img-responsive"
							src="/themes/{{ $theme }}/assets/img/top_banner_image_rounded.png">
					</div>
				</div>
			</div>
		</div>




	</div>





	<div id="">
		<div class="container" id="return-valoracion">
			<div class="">
				<h1 class="titleSingle_corp">{{ trans($theme . '-app.valoracion_gratuita.solicitud_valoracion') }}</h1>
			</div>
			<br>
			<?= trans($theme . '-app.valoracion_gratuita.desc_assessment') ?>
			<form class="form" id="form-valoracion-adv">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<br>
				<textarea class="form-control" id="exampleTextarea" rows="3" name="descripcion" required
				 placeholder="{{ trans($theme . '-app.valoracion_gratuita.description') }}"></textarea>
				<br>
				<div clas="row">
					<div class="col-md-4">
						<br>
						<input id="files" type="file" accept="image/png, image/jpeg" name="imagen[]" multiple required>
					</div>
					<div class="col-md-8">
						<?= trans($theme . '-app.valoracion_gratuita.desc_img') ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 form-group">
						<br>
						<br>
						<?= trans($theme . '-app.valoracion_gratuita.name') ?>
						<input type="text" name="name" required class="form-control">
					</div>
					<div class="col-md-6 form-group">
						<?= trans($theme . '-app.valoracion_gratuita.email') ?>
						<input type="email" name="email" required class="form-control">
					</div>
					<div class="col-md-6 form-group">
						<?= trans($theme . '-app.valoracion_gratuita.telf') ?>
						<input type="text" name="telf" required class="form-control">
					</div>
				</div>
				<input type="hidden" value="tasaciones@subarna.net" name="email_category">
				<div clas="row">
					<div class="col-md-12">
						<br>
						<button type="submit" id="valoracion-adv" class="btn-valoracion  btn btn-primary">
							<div class='loader hidden'></div>{{ trans($theme . '-app.valoracion_gratuita.send') }}
						</button>
						<br><br>
						<h4 class="valoracion-h4 hidden msg_valoracion">{{ trans($theme . '-app.valoracion_gratuita.error') }}</h4>
						<br>
					</div>
				</div>
			</form>
		</div>
	</div>
@stop
