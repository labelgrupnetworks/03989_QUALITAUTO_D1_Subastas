@extends('layouts.default')

@section('title')
    {{ trans('web.head.title_app') }}
@stop

@push('stylesheets')
    <link href="{{ URL::asset('vendor/tiempo-real/autocomplete/jquery.auto-complete.css') }}" rel="stylesheet" />
    <link type="text/css" href="/css/hint.css" rel="stylesheet">
@endpush

@push('scripts')

    <script src="{{ URL::asset('vendor/tiempo-real/node_modules/socket.io/node_modules/socket.io-client/socket.io.js') }}">
    </script>
    <script src="{{ URL::asset('vendor/tiempo-real/autocomplete/jquery.auto-complete.min.js') }}"></script>

    @if (strtoupper($data['subasta_info']->lote_actual->tipo_sub) == 'M' ||
            strtoupper($data['subasta_info']->lote_actual->tipo_sub) == 'I' ||
            strtoupper($data['subasta_info']->lote_actual->tipo_sub) == 'O' ||
            strtoupper($data['subasta_info']->lote_actual->tipo_sub) == 'P' ||
            $data['subasta_info']->lote_actual->subabierta_sub == 'P')
        <script src="{{ Tools::urlAssetsCache('/vendor/tiempo-real/tr_main.js') }}"></script>
        <script src="{{ URL::asset('js/hmac-sha256.js') }}"></script>
    @endif

    <script defer src="{{ Tools::urlAssetsCache('/vendor/openseadragon/openseadragon.js') }}"></script>

    @if (Config::get('app.exchange'))
		<script defer src="{{ URL::asset('js/numeral.js') }}"></script>
        <script src="{{ URL::asset('js/default/divisas.js') }}"></script>

		@section('header-extend-buttons')
		<div>
			<label class="form-label d-inline-flex align-items-center gap-2 mb-0">
				{{ trans('web.lot.foreignCurrencies') }}
				<select id="currencyExchange" class="form-select">
					@foreach($data['divisas'] as $divisa)
						<option value='{{ $divisa->cod_div }}'
							@selected($data['js_item']['subasta']['cod_div_cli'] == $divisa->cod_div || ($divisa->cod_div == 'USD' &&  ($data['js_item']['subasta']['cod_div_cli'] == 'EUR'  || $data['js_item']['subasta']['cod_div_cli'] == '')))
							>
							{{ $divisa->cod_div }}
						</option>
					@endforeach
				</select>
			</label>
		</div>
		@endsection
    @endif

@endpush


@section('content')

    <script>
        var auction_info = @json($data['js_item']);

        @if (\Config::get('app.exchange'))
            var currency = @json($data['divisas']);
        @endif

        var cod_sub = '{{ $data['subasta_info']->lote_actual->cod_sub }}';
        var ref = '{{ $data['subasta_info']->lote_actual->ref_asigl0 }}';
        var imp = '{{ $data['subasta_info']->lote_actual->impsalhces_asigl0 }}';

        @php
            //gardamos el código de licitador para saber is esta logeado o no y mostrar mensaje de que debe logearse
            if (!empty($data['js_item']) && !empty($data['js_item']['user']) && !empty($data['js_item']['user']['cod_licit'])) {
                $cod_licit = $data['js_item']['user']['cod_licit'];
            } else {
                $cod_licit = 'null';
            }
        @endphp

        var cod_licit = {{ $cod_licit }};
        routing.node_url = '{{ Config::get('app.node_url') }}';
        routing.comprar = '{{ $data['node']['comprar'] }}';
        routing.ol = '{{ $data['node']['ol'] }}';
        routing.favorites = '{{ Config::get('app.url') . '/api-ajax/favorites' }}';

        $(document).ready(function() {
            $('.add_bid').removeClass('loading');
        });
    </script>
    @php
        $lote_actual = $data['subasta_info']->lote_actual;

        if (!empty($lote_actual)) {
            $bread = [];
            $bread[] = ['url' => $lote_actual->url_subasta, 'name' => $lote_actual->title_url_subasta];
            if (!empty($data['seo']->meta_title)) {
                $bread[] = ['name' => $data['seo']->meta_title];
            } else {
                $bread[] = ['name' => $lote_actual->titulo_hces1];
            }
        }

    @endphp
    <main class="ficha">
        <div class="container ficha-bread-header">
            <div class="row">
                <div class="col-12">
                    @include('includes.breadcrumb')
                </div>
            </div>
        </div>

        @include('content.ficha')
    </main>
@stop
