@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
	@include('admin::includes.header_content')

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-12">
			<h1>{{ trans("admin-app.button.edit") }} {{ trans("admin-app.title.lot") }} - {{$fgAsigl0->ref_asigl0}}</h1>
		</div>
		<div class="col-xs-6 text-right">

			@if(session('success'))
					<a href="{{ route('subastas_concursales.lotes_concursales.create', ['cod_sub' => $cod_sub ,'menu' => 'subastas']) }}"
						class="btn btn-primary">{{ trans("admin-app.button.new") }}
						{{ trans("admin-app.title.lot") }}</a>
			@endif

			@if ($render)
			<a href="{{ route('subastas_concursales.show', [$cod_sub]) }}" class="btn btn-primary">{{ trans("admin-app.button.return") }}</a>
			@else
			<a href="{{ route('subastas_concursales.lotes_concursales.index', [$cod_sub]) }}" class="btn btn-primary">{{ trans("admin-app.button.return") }}</a>
			@endif

			@if ($anterior)
			<a href="{{ route('subastas_concursales.lotes_concursales.edit', ['cod_sub' => $cod_sub, 'lote' => $anterior,'menu' => 'subastas', 'render' => $render ] ) }}"
				class="btn btn-warning">{{ trans("admin-app.button.prev") }}</a>
			@endif
			@if ($siguiente)
			<a href="{{ route('subastas_concursales.lotes_concursales.edit', ['cod_sub' => $cod_sub, 'lote' => $siguiente,'menu' => 'subastas', 'render' => $render]) }}"
				class="btn btn-warning">{{ trans("admin-app.button.next") }}</a>
			@endif


		</div>
	</div>

	<form action="{{ route('subastas_concursales.lotes_concursales.update', [$cod_sub, $fgAsigl0->ref_asigl0]) }}" method="POST"
		id="loteUpdate" enctype="multipart/form-data">
		@method('PUT')
		@csrf

		<div class="row well">
			@include('admin::pages.subasta.lotes_concursales._form', compact('formulario', 'fgAsigl0'))
		</div>

		@if(!in_array("TRANSL", explode(',', config("app.HideEditLotOptions"))) && !empty($formulario->translates))
		<div class="row well">
			@include('admin::pages.subasta.lotes._translates', compact('formulario'))
		</div>
		@endif

		@if(in_array("FEATURE", explode(',', config("app.ShowEditLotOptions"))))
		<div class="row well">
			@include('admin::pages.subasta.lotes._lot_features', compact('cod_sub'))
		</div>
		@endif

		<div class="row well">
			@include('admin::pages.subasta.lotes._lot_images', compact('images'))
		</div>

		<div class="row well">
			@include('admin::pages.subasta.lotes._lot_files', compact('formulario', 'files', 'fgAsigl0'))
		</div>

		<div class="row">
			<div class="col-xs-12 text-center">
				{!! $formulario->submit !!}
			</div>
		</div>
	</form>

</section>

<script>
	$("input[name=starthour]").val('{{$fgAsigl0->hini_asigl0}}');
	$("input[name=endhour]").val('{{$fgAsigl0->hfin_asigl0}}');
</script>

@stop
