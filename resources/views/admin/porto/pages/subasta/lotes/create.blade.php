@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
	@include('admin::includes.header_content')

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-12">
			<h1>{{ trans("admin-app.button.new") }} {{ trans("admin-app.title.lot") }}</h1>
		</div>
		<div class="col-xs-3 text-right">

			<a href="{{ url()->previous()}}"
				class="btn btn-primary right">{{ trans("admin-app.button.return") }}</a>
		</div>
	</div>

	<form action="{{ route("$parent_name.$resource_name.store", compact('cod_sub')) }}" method="POST" id="loteStore" enctype="multipart/form-data">
		@csrf

		<div class="row well">
			@include("admin::pages.subasta.$resource_name._form", compact('formulario', 'fgAsigl0'))
		</div>

		@if(!in_array("TRANSL", explode(',', config("app.HideEditLotOptions"))) && !empty($formulario->translates))
		<div class="row well">
			@include('admin::pages.subasta.lotes._translates', compact('formulario'))
		</div>
		@endif

		<div class="row">
			<div class="col-xs-12 text-center">
				{!! $formulario->submit !!}
			</div>
		</div>
	</form>

</section>

<script>
$("input[name=reflot]").on('change', function(e){
	$("input[name=idorigin]").val("{{$cod_sub}}-" + $(this).val());
});
</script>

@stop
