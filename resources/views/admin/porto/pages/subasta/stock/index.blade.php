@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-12">
			<h1>{{ trans("admin-app.title.stock") }}</h1>
		</div>
	</div>

	<div class="row well">
			@include('admin::pages.subasta.stock._table', ['fgAsigl0' => $fgAsigl0 ?? null])
	</div>


</section>
@stop
