@extends('admin::layouts.logged')
@section('content')

@push('admin-css')
	<link href="{{ $base_url }}/vendor/grapejs/css/grapes.min.css" rel="stylesheet" />
	{{-- <link href="/vendor/grapejs/grapesjs-preset-webpage.min.css" rel="stylesheet" /> --}}
    {{-- <link href="/vendor/grapejs/grapesjs-preset-newsletter.css" rel="stylesheet" /> --}}
@endpush

@push('admin-js')
	{{-- <script src="{{ $base_url }}/js/grapeConfig.js"></script> --}}
	<script src="{{ $base_url }}/vendor/grapejs/grapes.min.js"></script>
	{{-- <script src="/vendor/grapejs/grapesjs-blocks-basic.min.js"></script> --}}
	{{-- <script src="/vendor/grapejs/grapesjs-plugin-ckeditor.min.js"></script> --}}
	{{-- <script src="/vendor/grapejs/grapesjs-preset-webpage.min.js"></script> --}}
	<script src="{{ $base_url }}/vendor/grapejs/grapesjs-preset-newsletter.min.js"></script>
@endpush

<section role="main" class="content-body">
	@include('admin::includes.header_content')

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-9">
			<h1>{{ trans("admin-app.button.new_fem") }} {{ trans_choice("admin-app.title.page", 0) }}</h1>
		</div>
		<div class="col-xs-3">
			<a href="{{ route('static-pages.index') }}" class="btn btn-primary right">{{ trans("admin-app.button.return") }}</a>
		</div>
	</div>

	<div class="row well">
		<form action="{{ route('static-pages.update', ['static_page' => $webPage->id_web_page]) }}" method="POST" enctype="multipart/form-data" name="staticPagesForm">
			@method('PUT')
			@csrf
			@include('admin::pages.contenido.pages._form', compact('form', 'webPage'))
		</form>
	</div>

	<div class="row well p-0">
		@include('admin::pages.contenido.pages._content', compact('webPage'))
	</div>

	<div class="row well">
		<div class="col-xs-12 col-md-3">
			<button id="staticPageSave" type="submit" class="btn btn-success mt-2" value="Guardar">{{ trans("admin-app.button.save") }}</button>
		</div>
	</div>

@stop
