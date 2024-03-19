@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@php
    $count_lots = 0;
    foreach ($tipos_sub as $typeSub => $desType) {
        $numLots = Tools::showNumLots($numActiveFilters, $filters, 'typeSub', $typeSub);

        if (empty($filters['typeSub'])) {
            $count_lots += $numLots;
        } elseif ($typeSub == $filters['typeSub']) {
            $count_lots = $numLots;
        }
    }
@endphp



@section('assets_components')
    <link type="text/css" href="{{ Tools::urlAssetsCache('/css/default/grid.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ Tools::urlAssetsCache('/themes/' . $theme . '/grid.css') }}" rel="stylesheet">
@endsection

@section('content')

    <main class="grid-page">
        <div class="container-fluid">
            <h1 class="ff-highlight bold">{{ $seo_data->h1_seo }}</h1>

			<p class="fs-small bold">
				{{ Tools::numberformat($count_lots) . ' ' . trans("$theme-app.lot_list.results") }}
			</p>

            @include('content.grid')
        </div>
    </main>
@stop
