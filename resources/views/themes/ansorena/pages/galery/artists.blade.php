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

    <main class="artist-page">

        <div class="container">

			<h1 class="page-title">
				{{ trans("$theme-app.galery.artists") }}
			</h1>

			<h2 class="page-subtitle mb-5">
				{!! trans("$theme-app.galery.texto_artistas") !!}
			</h2>

            <form class="top-filters-wrapper justify-content-between py-3">

                <input type="hidden" name="page" value="{{ request('page') }}">

                @include('includes.components.order')

                @include('includes.components.search')
            </form>

            <div class="row row-cols-1 row-cols-lg-3 gx-0 gx-lg-5 gy-5">
                @foreach ($artists as $artist)
                    <div class="col">
						@include('includes.galery.artist')
                    </div>
                @endforeach
            </div>

        </div>
    </main>
@stop
