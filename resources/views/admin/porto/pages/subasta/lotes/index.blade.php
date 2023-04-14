@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
	@include('admin::includes.header_content')

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-12">
			<h1>{{ trans("admin-app.title.lots") }}</h1>
		</div>
		<div class="col-xs-12 text-right" style="margin-top: 2rem">
			<a href="{{ route("$parent_name.$resource_name.create", ['cod_sub' => $cod_sub ,'menu' => 'subastas']) }}"
				class="btn btn-primary">{{ trans("admin-app.button.new") }}
				{{ trans("admin-app.title.lot") }}</a>
		</div>
	</div>

	<div class="row well">
			@include('admin::pages.subasta.lotes._table', ['fgAsigl0' => $fgAsigl0 ?? null])
	</div>


</section>
@stop
