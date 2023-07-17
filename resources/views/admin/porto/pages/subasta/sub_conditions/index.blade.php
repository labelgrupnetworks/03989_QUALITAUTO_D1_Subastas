@extends('admin::layouts.logged')
@section('content')

	<section role="main" class="content-body">
		@include('admin::includes.header_content')

		<div class="row well header-well d-flex align-items-center">
			<div class="col-xs-12">
				<h1>Registro aceptación bases de subasta</h1>
			</div>

		</div>

		<div class="row well">
			@include('admin::pages.subasta.sub_conditions.table')
		</div>
	</section>
@stop
