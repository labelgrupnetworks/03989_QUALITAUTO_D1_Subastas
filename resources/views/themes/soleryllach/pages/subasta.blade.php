@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@php
    use App\Services\Content\CookieService;
    $styleLotSeeConfiguration = (new CookieService())->getLotConfiguration();

    if (empty($data['type']) && !empty($data['sub_data'])) {
        $sub_data = $data['sub_data'];
        $url_subasta = \Routing::translateSeo('info-subasta') . $sub_data->cod_sub . '-' . str_slug($sub_data->des_sub);

        $url_indice =
            \Routing::translateSeo('indice-subasta') .
            $sub_data->cod_sub .
            '-' .
            str_slug($sub_data->des_sub . '-' . $sub_data->id_auc_sessions);
        $indice = trans(\Config::get('app.theme') . '-app.lot_list.indice_auction');
        $name = trans(\Config::get('app.theme') . '-app.subastas.auctions');

        if ($data['sub_data']->subc_sub == 'H') {
            $url = \Routing::translateSeo('subastas-historicas');
        } elseif ($data['sub_data']->tipo_sub == 'W') {
            if (strtotime($data['sub_data']->end) <= time()) {
                $url = \Routing::translateSeo('todas-subastas') . '?finished=true';
            } else {
                $url = \Routing::translateSeo('todas-subastas') . '?finished=false';
            }
        } elseif ($data['sub_data']->tipo_sub == 'O') {
            $url = \Routing::translateSeo('subastas-online');
        } elseif ($data['sub_data']->tipo_sub == 'V') {
            $url = \Routing::translateSeo('venta-directa');
            $indice = trans(\Config::get('app.theme') . '-app.lot_list.indice_venta_directa');
            $name = trans(\Config::get('app.theme') . '-app.foot.direct_sale');
        }
    }

    foreach ($data['subastas'] as $k => $item) {
        $data['subastas'][$k]->total_pujas = 0;
        $data['subastas'][$k]->total_postores = 0;
        $aux_postores = [];
        if (isset($item->pujas)) {
            $data['subastas'][$k]->total_pujas = sizeof($item->pujas);
            foreach ($item->pujas as $key => $value) {
                $aux_postores[$value->cod_licit] = $value->cod_licit;
            }
        }

        if (isset($item->ordenes)) {
            $data['subastas'][$k]->total_pujas += sizeof($item->ordenes);
            foreach ($item->ordenes as $key => $value) {
                $aux_postores[$value->cod_licit] = $value->cod_licit;
            }
        }

        $data['subastas'][$k]->total_postores = sizeof($aux_postores);

        $favorites = [];
        if (Session::has('user') && !empty($data['favs']['lot'][$item->cod_sub])) {
            $favorites = array_keys($data['favs']['lot'][$item->cod_sub]);
        }
    }

    $codSub = $data['cod_sub'] ?? '';
    $idAucSession = $data['id_auc_sessions'] ?? '';
@endphp

@section('content')
    <main class="grid">
        <input name="lot_see_configuration" type="hidden" value="{{ $styleLotSeeConfiguration }}">

        @include('content.subasta')

		@include('includes.register_modal', ['whenScroll' => true])
    </main>
@stop
