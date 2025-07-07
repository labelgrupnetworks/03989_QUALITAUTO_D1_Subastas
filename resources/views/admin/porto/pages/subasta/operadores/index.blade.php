@extends('admin::layouts.logged')
@section('content')

    <section class="content-body" role="main">
        @include('admin::includes.header_content')

        <div class="row well header-well d-flex align-items-center">
            @if ($fgSub)
                <div class="col-xs-9">
                    @if (config('app.admin_show_auction_code', true))
                        <h1>{{ trans('admin-app.title.auction') }} {{ $fgSub->cod_sub }}</h1>
                    @endif
                    <h3>{{ $fgSub->des_sub }}</h3>
                </div>
                <div class="col-xs-3">
                    <a class="btn btn-primary right"
                        href="{{ route('subastas.index') }}">{{ trans('admin-app.button.return') }}</a>
                </div>
            @else
                <h1>Operadores</h1>
            @endif
        </div>

        <div class="row well p-0">
            <div class="col-xs-12">

                @if ($fgSub)
                    <div class="row">
                        <div class="col-xs-12">
                            @include('admin::pages.subasta.subastas._tabs_with_links', [
                                'cod_sub' => $fgSub->cod_sub,
                                'active' => 'operadores',
                            ])
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-xs-12">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active in" id="operadores" role="tabpanel"
                                aria-labelledby="operadores-tab">
                                <div class="row">
                                    @include('admin::pages.subasta.operadores.table')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </section>

@stop
