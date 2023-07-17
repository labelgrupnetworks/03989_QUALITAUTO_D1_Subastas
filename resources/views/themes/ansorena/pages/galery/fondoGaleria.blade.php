@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.galery.artists') }}
@stop

@section('framework-css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/bootstrap/5.2.0/css/bootstrap.min.css') }}">
@endsection

@section('framework-js')
    <script src="{{ URL::asset('vendor/bootstrap/5.2.0/js/bootstrap.bundle.min.js') }}"></script>
@endsection

@section('custom-css')
    <link href="{{ Tools::urlAssetsCache('/themes/' . $theme . '/css/global.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Tools::urlAssetsCache("/themes/$theme/css/style.css") }}" rel="stylesheet" type="text/css">
    <link href="{{ Tools::urlAssetsCache('/themes/' . $theme . '/css/header.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')

    @include('includes.galery.subnav')

    <main class="artist-collection-page">

        <div class="container medium-container">

			<h1 class="page-title">
				{{ trans("$theme-app.galery.gallery_collection") }}
			</h1>

			<h2 class="page-subtitle">
				{!! trans("$theme-app.galery.texto_gallery_collection") !!}
			</h2>

            <div class="search-filter-block mb-4">
                <form id="fromSearchExhibitions">
                    <input type="hidden" name="online" value="{{ request('online') }}">

					@include('includes.components.search')
                </form>
            </div>

            <div class="row row-cols-1 row-cols-lg-3 gx-0 gx-lg-5 gy-4">
                @foreach ($artists as $artist)
                    <div class="col">
						@include('includes.galery.gallery_collection', ['galleryCollection' => $artist])
                    </div>
                @endforeach
            </div>
        </div>
    </main>
@stop
