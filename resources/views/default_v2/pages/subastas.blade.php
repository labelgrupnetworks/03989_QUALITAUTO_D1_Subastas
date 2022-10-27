@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@section('content')
    <?php
    $bread[] = ['name' => $data['name']];
    if (empty($data['type']) && !empty($data['sub_data'])) {
        $sub_data = $data['sub_data'];
        $url_subasta = \Routing::translateSeo('info-subasta') . $sub_data->cod_sub . '-' . str_slug($sub_data->des_sub);

        $url_indice = \Routing::translateSeo('indice-subasta') . $sub_data->cod_sub . '-' . str_slug($sub_data->des_sub . '-' . $sub_data->id_auc_sessions);
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
        $bread[] = ['url' => $url, 'name' => $name];
        $bread[] = ['url' => $url_subasta, 'name' => $sub_data->des_sub];
        $bread[] = ['url' => $url_indice, 'name' => $indice];
        $bread[] = ['name' => 'Lotes'];
    } elseif (!empty($data['seo']->webname)) {
        if (!empty($data['seo']->subcategory)) {
            $bread[] = ['url' => $data['seo']->url, 'name' => $data['seo']->webname];
            $bread[] = ['name' => $data['seo']->subcategory];
        } else {
            $bread[] = ['name' => $data['seo']->webname];
        }
    }

    ?>
    <main class="subastas">
        <div class="container">
            <div class="row">
                <div class="col">
                    @include('includes.breadcrumb')
                </div>
            </div>
        </div>


        <div class="container">
            <div class="row">
                <div class="col">
                    <h1 class="titlePage"> {{ $data['name'] }}</h1>
                </div>
            </div>
        </div>

        @include('content.subastas')
    </main>
@stop
