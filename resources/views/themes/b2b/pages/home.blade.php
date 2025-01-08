@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@section('content')
<main class="home">
	{!! BannerLib::bannersPorKey('home', 'home-banner') !!}
</main>
@stop
