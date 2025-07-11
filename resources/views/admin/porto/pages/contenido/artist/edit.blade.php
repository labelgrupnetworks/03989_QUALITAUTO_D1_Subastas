@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-9">
			<h1>{{ trans("admin-app.button.edit") }} {{ trans("admin-app.title.artist") }}</h1>
		</div>
		<div class="col-xs-3">
			<a href="{{ route('artist.index') }}" class="btn btn-primary right">{{ trans("admin-app.button.return") }}</a>
		</div>
	</div>


	<div class="row well">

		<form action="{{ route('artist.update', $webArtist->id_artist) }}" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="id_artist"  name="id_artist" value="{{$webArtist->id_artist}}">
			@method('PUT')
			@csrf

			<div class="col-xs-12 mt-3 mb-2 text-center" >
				@if (file_exists(public_path($imgPath)))
					<img src="{{$imgPath}}" width="300px">
				@endif
				<div><input class="form-control effect-16" type="file" name="img_artist"></div>
			</div>

			@include('admin::pages.contenido.artist._form', compact('formulario', 'webArtist'))




		</form>

	</div>

	<div class="row well">
		{{ trans("admin-app.title.related_articles") }}
		<form id="artistArticleFrm" method="POST" enctype="multipart/form-data">
			<div class="col-xs-12" style="text-align: right;margin-top: 1rem">
				<input type="button" id="artistCreate_JS" class="btn btn-success" value="Nuevo" >
			</div>
			<div id="articlesList" class="col-xs-12">

			</div>
		<div class="col-xs-12" style="margin-top: 1rem">

			<input type="button" id="artistSave_JS" class="btn btn-success" value="Guardar" >
		</div>

		</form>
	</div>
	@stop
