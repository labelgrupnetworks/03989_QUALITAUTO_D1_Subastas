@extends('layouts.default')

@section('seo')
@php
	$data['seo'] = new \stdClass();
	$data['seo']->meta_title = trans(\Config::get('app.theme').'-app.metas.title_'. $data['banner']);
	$data['seo']->meta_description = trans(\Config::get('app.theme').'-app.metas.description_' . $data['banner']);
@endphp
@endsection

@section('title')
{{ $data['name_web_page'] }}
@stop

@section('content')
    <main id="page-banner" class="page-banner">

        <div class="container">
            <h1> {{ $data['name_web_page'] }}</h1>
        </div>

        <div id="content-page-banner" class="content-page-banner mb-5">
			{!! \BannerLib::bannersPorKey($data['banner'], $data['banner'], ['dots' => false]) !!}
		</div>

    </main>
@stop
