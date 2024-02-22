@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@php
	//No debe aparecer contextra en ningun punto de la ficha
	$data["js_item"]['lote_actual']->contextra_hces1 = '';

    $lote_actual = $data['subasta_info']->lote_actual;

    //gardamos el código de licitador para saber is esta logeado o no y mostrar mensaje de que debe logearse
    if (!empty($data['js_item']) && !empty($data['js_item']['user']) && !empty($data['js_item']['user']['cod_licit'])) {
        $cod_licit = $data['js_item']['user']['cod_licit'];
    } else {
        $cod_licit = 'null';
    }

    $locale = Config::get('app.locale');
    $subasta_galeria = $lote_actual->tipo_sub == 'E' || $lote_actual->tipo_sub == 'F';
    $menuEstaticoHtml = (new App\Models\Page())->getPagina(mb_strtoupper($locale), 'MENUSUBASTAS');
@endphp

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
    <link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/autocomplete/jquery.auto-complete.css') }}" />

    <script src="{{ URL::asset('vendor/tiempo-real/node_modules/socket.io/node_modules/socket.io-client/socket.io.js') }}">
    </script>
    <script src="{{ URL::asset('vendor/tiempo-real/autocomplete/jquery.auto-complete.min.js') }}"></script>
    @if (strtoupper($lote_actual->tipo_sub) == 'O' ||
            strtoupper($lote_actual->tipo_sub) == 'P' ||
            $lote_actual->subabierta_sub == 'P')
        <script src="{{ Tools::urlAssetsCache('/vendor/tiempo-real/tr_main.js') }}"></script>
        <script src="{{ URL::asset('js/hmac-sha256.js') }}"></script>
    @endif
    <script src="{{ URL::asset('js/openseadragon.min.js') }}"></script>
    <script src="{{ URL::asset('vendor/jquery-print/jQuery.print.js') }}"></script>

    @if (\Config::get('app.exchange'))
        <script src="{{ URL::asset('js/default/divisas.js') }}"></script>
    @endif

    <script>
        var auction_info = @json($data['js_item']);

        @if (\Config::get('app.exchange'))
            var currency = @json($data['divisas']);
        @endif

        var cod_sub = '{{ $lote_actual->cod_sub }}';
        var ref = '{{ $lote_actual->ref_asigl0 }}';
        var imp = '{{ $lote_actual->impsalhces_asigl0 }}';
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
        $artistaFondoGaleria = request('artistaFondoGaleria');
        $caracteristicas = App\Models\V5\FgCaracteristicas_Hces1::getByLot($lote_actual->num_hces1, $lote_actual->lin_hces1);

        $autor = $caracteristicas[1]->value_caracteristicas_hces1 ?? '';
        $bread = [];

        if ($artistaFondoGaleria && !empty($autor)) {
            $nameAutor = explode(',', $autor);
            $autor = '';

            if (count($nameAutor) == 2) {
                $autor = $nameAutor[1] . ' ';
            }
            $autor .= $nameAutor[0];
            //ponemos bien el nombre del autor para que se vea bien en la ficha del lote
            $caracteristicas[1]->value_caracteristicas_hces1 = $autor;

            $name = trans($theme . '-app.galery.volverArtistaFondoGaleria') . ' ' . $autor;
            $bread[] = ['url' => route('artistaFondoGaleria', ['id_artist' => $artistaFondoGaleria]), 'name' => $name];
        } elseif (!empty($lote_actual)) {
            if ($lote_actual->tipo_sub == 'E') {
                $url = route('exposicion', ['texto' => \Str::slug($lote_actual->des_sub), 'cod' => $lote_actual->cod_sub, 'reference' => $lote_actual->reference]);
                $bread[] = ['url' => $url, 'name' => $lote_actual->des_sub];
            } else {
                $bread[] = ['url' => $lote_actual->url_subasta, 'name' => $lote_actual->title_url_subasta];
                if (!empty($data['seo']->meta_title)) {
                    $bread[] = ['name' => $data['seo']->meta_title];
                } else {
                    $bread[] = ['name' => $lote_actual->descweb_hces1];
                }
            }
        }
    @endphp

    @if ($subasta_galeria)
        @include('includes.galery.subnav')
    @else
        {!! $menuEstaticoHtml->content_web_page !!}
    @endif

	<main class="{{$subasta_galeria ? "gallery-ficha" : "ficha"}}">
        @if (!$subasta_galeria)
            <div class="container">
                @include('includes.bread')
            </div>
        @endif

        @include('content.ficha')
    </main>
@stop
