@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.galery.exhibitions') }}
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

@php
    use App\Models\V5\FgAsigl0;
    use App\Models\V5\FgSub;

    $auctions = $exhibitions->pluck('cod_sub');

    #cojer todos los primeros lotes para poder sacar las iamgenes.
    $lots = FgAsigl0::select('cod_sub, numhces_asigl0, linhces_asigl0')
        ->joinSubastaAsigl0()
        ->where('ref_asigl0', 1)
        ->wherein('cod_sub', $auctions)
        ->get();

    $exhibitionsFormat = $exhibitions->map(function ($exhibition) use ($lots, $artist) {
        $theme = Config::get('app.theme');

        $exposure = new FgSub($exhibition->toArray());

        $lot = $lots->where('cod_sub', $exposure->cod_sub)->first();

        $exposure->name_artist = $artist->name_artist ?? trans("$theme-app.galery.collective");
        $exposureFormat = $exposure->getExhibitionFieldsAttribute();

        if ($lot) {
            $exposureFormat->image = Tools::url_img('square_medium', $lot->numhces_asigl0, $lot->linhces_asigl0);
        }
        return $exposureFormat;
    });
@endphp

@section('content')

    @include('includes.galery.subnav')

    <main class="artist-page">
        <h1 class="page-title">
            {{ $artist->name_artist }}
        </h1>

        <div class="container">
            <div class="row row-cols-1 row-cols-lg-3 gx-0 gx-lg-5 gy-4">
                @foreach ($exhibitionsFormat as $exhibition)
                    <div class="col">
                        @include('includes.galery.exhibition', [
                            'lazyLoad' => false,
                            'exhibition' => $exhibition,
                        ])
                    </div>
                @endforeach
            </div>
        </div>

    </main>
@stop
