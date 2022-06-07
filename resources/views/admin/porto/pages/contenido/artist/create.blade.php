@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
	@include('admin::includes.header_content')

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-9">
			<h1>{{ trans("admin-app.button.new") }} {{ trans("admin-app.title.artist") }}</h1>
		</div>
		<div class="col-xs-3">
			<a href="{{ route('artist.index', ['menu' => 'subastas']) }}" class="btn btn-primary right">{{ trans("admin-app.button.return") }}</a>
		</div>
	</div>


	<div class="row well">

		<form action="{{ route('artist.store') }}" method="POST" enctype="multipart/form-data">
			@csrf

			<div class="col-xs-12 mt-3 mb-2 text-center" >
				<div><input class="form-control effect-16" type="file" name="img_artist"></div>
			</div>

			@include('admin::pages.contenido.artist._form', compact('formulario', 'fgArtist'))
		</form>

	</div>
<script>
	$("[name='id_artist']").on("change",function() {
		$("[name='name_artist']").val($("[name='id_artist'] option:selected").text());
	})

</script>
	@stop
