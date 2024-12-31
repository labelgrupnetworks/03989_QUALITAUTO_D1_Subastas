@extends('layouts.default')
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
    $lots = (new FgAsigl0())->ventasDestacadas('orden_destacado_asigl0', request('order_dir', 'asc'));
@endphp
@section('content')

	@include('includes.menus.menu_subastas')

    <main class="grid-prominent-sales">
        <h1 class="ff-highlight grid-page-tile">
            {{ trans("$theme-app.lot_list.featured-sales") }}</h1>

        <section class="grid-section">
            <div class="container">
                <form class="top-filters-wrapper pb-4">

                    @include('includes.components.order')

                    <p class="filters-number-result opacity-50">
                        {{ Tools::numberformat($lots->total()) . ' ' . trans("$theme-app.lot_list.results") }}
                    </p>
                </form>

                @if (empty($lots))
                    <h3 class="text-center">{{ trans($theme . '-app.lot_list.no_results') }}</h3>
                @else
                    <div class="section-grid-lots row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xxl-4 gy-4">

                        @foreach ($lots as $lot)
                            @php
                                $titulo = trans("$theme-app.subastas.auctions") . ' ' . $lot->sub_asigl0 . ' / ' . trans("$theme-app.lot.lot-name") . ' ' . $lot->ref_asigl0;
								$url = Tools::url_lot($lot->sub_asigl0, $lot->auc_session, $lot->name, $lot->ref_asigl0, $lot->num_hces1, $lot->webfriend_hces1, $lot->titulo_hces1)
                            @endphp

                            @include('includes.grid.lot_venta_destacada')
                        @endforeach
                    </div>
                    <div class="pagination-wrapper">
                        {!! $lots->appends(Request::query())->links('front::includes.grid.paginator_pers') !!}
                    </div>
                @endif
            </div>
        </section>
    </main>

@stop
