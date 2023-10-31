@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@php
    use App\Models\Cookies;
    $styleLotSeeConfiguration = (new Cookies())->getLotConfiguration();
    if (empty($data['type']) && !empty($data['sub_data'])) {
        $sub_data = $data['sub_data'];
        $url_subasta = \Routing::translateSeo('info-subasta') . $sub_data->cod_sub . '-' . str_slug($sub_data->des_sub);
        $bread = [];
        $bread[] = ['url' => $url_subasta, 'name' => $sub_data->des_sub];
        $bread[] = ['name' => 'Lotes'];
    } elseif (!empty($data['seo']->webname)) {
        $bread = [];
        if (!empty($data['seo']->subcategory)) {
            $bread[] = ['url' => $data['seo']->url, 'name' => $data['seo']->webname];
            $bread[] = ['name' => $data['seo']->subcategory];
        } else {
            $bread[] = ['name' => $data['seo']->webname];
        }
    }
@endphp


@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 text-center">
                <?php //Si quieren mostrar nombre de la subasta o que se vea texto Lotes
                ?>
                @if (empty($data['subastas']))
                    <h1 class="titlePage-custom color-letter text-center">{{ $data['name'] }}</h1>
                @else
                    <h1 class="titlePage-custom color-letter">{{ trans(\Config::get('app.theme') . '-app.lot_list.lots') }}
                    </h1>
                @endif
                @include('includes.breadcrumb')
            </div>
        </div>
    </div>

    <input name="lot_see_configuration" type="hidden" value="{{ $styleLotSeeConfiguration }}">

    @include('content.subasta')
@stop
