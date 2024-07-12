@extends('admin::layouts.logged')

@section('page-modal')
	banner-page-modal
@stop

@section('content')


<section role="main" class="content-body banner-page">

	@include('admin::includes.header_content')

	@include('admin::pages.contenido.banner._editar')

</section>
@stop
