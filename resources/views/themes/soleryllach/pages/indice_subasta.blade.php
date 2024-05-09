@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@php
    $sub_data = $data['subasta'];
    $url_subasta = \Routing::translateSeo('info-subasta') . $sub_data->cod_sub . '-' . str_slug($sub_data->name);
    $name = trans(\Config::get('app.theme') . '-app.subastas.auctions');
    $indice = trans(\Config::get('app.theme') . '-app.lot_list.indice_auction');
    if ($data['subasta']->subc_sub == 'H') {
        $url = \Routing::translateSeo('subastas-historicas');
    } elseif ($data['subasta']->tipo_sub == 'W') {
        if (strtotime($data['subasta']->end) <= time()) {
            $url = \Routing::translateSeo('todas-subastas') . '?finished=true';
        } else {
            $url = \Routing::translateSeo('todas-subastas') . '?finished=false';
        }
    } elseif ($data['subasta']->tipo_sub == 'O') {
        $url = \Routing::translateSeo('subastas-online');
    } elseif ($data['subasta']->tipo_sub == 'V') {
        $url = \Routing::translateSeo('venta-directa');
        $name = trans(\Config::get('app.theme') . '-app.foot.direct_sale');
        $indice = trans(\Config::get('app.theme') . '-app.lot_list.indice_venta_directa');
    }
@endphp

@section('content')
    <main class="indice">
        @include('content.indice_subasta')
    </main>
@stop
