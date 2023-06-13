@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

<?php
$all_adj = array();
$sub = new \App\Models\Subasta;
$all_adj=array();
$tipo_pago_global = false; //cuando el tipo de pago es glogal o por subastas
foreach($data['adjudicaciones'] as $temp_adj){
    $all_adj[$temp_adj->cod_sub]['lotes'][]=$temp_adj;
}
foreach($all_adj as $key_inf => $value){
    $sub->cod = $key_inf;
    $all_adj[$key_inf]['inf'] = $sub->getInfSubasta();
}

$all_adj_pag = array();

$all_adj_pag=array();
foreach($data['adjudicaciones_pag'] as $temp_adj){
    $all_adj_pag[$temp_adj->cod_sub]['lotes'][]=$temp_adj;
}
foreach($all_adj_pag as $key_inf => $value){
    $sub->cod = $key_inf;
    $all_adj_pag[$key_inf]['inf'] = $sub->getInfSubasta();
}

?>
<script src="{{ URL::asset('js/payment.js')}}"></script>
<script>
    var info_lots = $.parseJSON('<?php echo str_replace("\u0022","\\\\\"",json_encode($data["js_item"],JSON_HEX_QUOT)); ?>');
</script>


<div class="color-letter">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                <h1 class="titlePage">{{ trans(\Config::get('app.theme').'-app.user_panel.mi_cuenta') }}</h1>
                </div>
            </div>
        </div>
    </div>

<div class="account-user color-letter  panel-user">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-3 col-lg-3 account-user-menu">
                <?php $tab="allotments";?>
                @include('pages.panel.menu_micuenta')
            </div>
            <div class="col-xs-12 col-md-9 col-lg-9 ">
                <div class="user-account-title-content">
                    <div class="user-account-menu-title">{{ trans(\Config::get('app.theme').'-app.user_panel.allotments') }}</div>
                </div>
                <div class="user-accounte-titles-link">
                    <ul class="ul-format d-flex justify-content-space-between flex-wrap" role="tablist">
                        <li role="pagar"class="active" >
                            <a data-toggle="collapse" href="#lot_pend" class="color-letter" href="{{ \Routing::slug('user/panel/allotments/outstanding') }}" >{{ trans(\Config::get('app.theme').'-app.user_panel.still_paid') }}</a></li>
                    </ul>
                </div>
                <div class="col-xs-12 no-padding in" aria-expanded="true" id="lot_pend">




                    <div class="panel-group" id="accordion">
                        <div class="panel panel-default">

                            @if($tipo_pago_global)
                            <form  id="pagar_lotes_global">
                            @endif

                            <?php $i=0 ?>
                            @foreach($all_adj as $key_sub => $all_inf)
                            <?php
                                $total_remate = 0;
                                $total_base = 0;
                                $total_iva = 0;
                                $precio_global=0;
                            ?>
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <a class="d-flex justify-content-space-between" data-toggle="collapse" href="#{{$all_inf['inf']->cod_sub}}">
                                        <div>
                                            <span>{{$all_inf['inf']->name}}</span>
                                        @if($all_inf['inf']->compraweb_sub == 'S')
                                        <span class="total-price-sup"> / {{ trans(\Config::get('app.theme').'-app.user_panel.total_price') }} <span class="precio_final_{{$all_inf['inf']->cod_sub}}">0</span> {{ trans(\Config::get('app.theme').'-app.lot.eur') }} </span>
                                        @endif
                                        </div>
                                        <img width=10 src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCIgdmlld0JveD0iMCAwIDk2LjE1NCA5Ni4xNTQiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDk2LjE1NCA5Ni4xNTQ7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8cGF0aCBkPSJNMC41NjEsMjAuOTcxbDQ1Ljk1MSw1Ny42MDVjMC43NiwwLjk1MSwyLjM2NywwLjk1MSwzLjEyNywwbDQ1Ljk1Ni01Ny42MDljMC41NDctMC42ODksMC43MDktMS43MTYsMC40MTQtMi42MSAgIGMtMC4wNjEtMC4xODctMC4xMjktMC4zMy0wLjE4Ni0wLjQzN2MtMC4zNTEtMC42NS0xLjAyNS0xLjA1Ni0xLjc2NS0xLjA1NkgyLjA5M2MtMC43MzYsMC0xLjQxNCwwLjQwNS0xLjc2MiwxLjA1NiAgIGMtMC4wNTksMC4xMDktMC4xMjcsMC4yNTMtMC4xODQsMC40MjZDLTAuMTUsMTkuMjUxLDAuMDExLDIwLjI4LDAuNTYxLDIwLjk3MXoiIGZpbGw9IiMwMDAwMDAiLz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K" />
                                        </a>
                                    </div>
                                    <div id="{{$all_inf['inf']->cod_sub}}"  class="table-responsive panel-collapse collaps {{$all_inf['inf']->compraweb_sub == 'S' ? 'in': 'collapse'}}">
                                        @if(!$tipo_pago_global)
                                    <form id="pagar_lotes_{{$all_inf['inf']->cod_sub}}" >
                                        @endif
                                        <div class="user-account-heading hidden-xs d-flex align-items-center justify-content-space-between">
                                            <div class="col-xs-12 col-sm-7 col-lg-8 col-one user-account-item">
                                                    {{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}
                                            </div>
                                            <div class="col-xs-12 col-sm-2 col-one user-account-fecha">
                                                    {{ trans(\Config::get('app.theme').'-app.user_panel.price') }}
                                            </div>
                                            <div class="col-xs-12 col-sm-3 col-lg-2 col-one user-account-max-bid">
                                                    {{ trans(\Config::get('app.theme').'-app.user_panel.price_clean') }}
                                            </div>
                                        </div>
                                        @if(!$tipo_pago_global)
                                                <div class="adj color-letter d-flex align-items-center justify-content-space-between">
                                                    @if($all_inf['inf']->compraweb_sub == 'S' && !empty(Config::get('app.merchantIdUP2')))
                                                        <div class="total-price">{{ trans(\Config::get('app.theme').'-app.user_panel.total_price') }} <span class="precio_final_{{$all_inf['inf']->cod_sub}}">0</span> {{ trans(\Config::get('app.theme').'-app.lot.eur') }} </div>
                                                        <button style="margin-left: 15px;" type="button" class="secondary-button   submit_carrito btn btn-step-reg2"  cod_sub="{{$all_inf['inf']->cod_sub}}" class="btn btn-step-reg" disabled>{{ trans(\Config::get('app.theme').'-app.user_panel.pay') }}</button>
                                                    @endif
                                                </div>
                                        @endif
                                        <div class="user-accout-items-content">

                                                @foreach($all_inf['lotes'] as $inf_lot)
                                                <?php
                                                $url_friendly = str_slug($inf_lot->titulo_hces1);
                                                $url_friendly = \Routing::translateSeo('lote').$inf_lot->cod_sub."-".str_slug($inf_lot->name).'-'.$inf_lot->id_auc_sessions."/".$inf_lot->ref_asigl0.'-'.$inf_lot->num_hces1.'-'.$url_friendly;
                                                $precio_remapte = \Tools::moneyFormat($inf_lot->himp_csub);
                                                $precio_limpio = \Tools::moneyFormat($inf_lot->base_csub,false,2);
                                                $comision = \Tools::moneyFormat($inf_lot->base_csub + $inf_lot->base_csub_iva,false,2);

                                               $precio_limpio_calculo =  number_format($inf_lot->himp_csub + $inf_lot->base_csub + $inf_lot->base_csub_iva, 2, '.', '');
                                                $calc_envio = number_format($inf_lot->himp_csub + $inf_lot->base_csub, 2, '.', '');


                                                //Calculo total
                                                $total_remate = $total_remate + $inf_lot->himp_csub;
                                                $total_base = $total_base + $inf_lot->base_csub;
                                                $total_iva = $total_iva + $inf_lot->base_csub_iva;
                                            ?>
                                                    <div class="user-accout-item-wrapper  col-xs-12 no-padding">
                                                        <div class="d-flex">
                                                        <div class="col-xs-12 col-sm-7 col-lg-8 col-one user-account-item ">
                                                            <div class="col-xs-12 col-sm-1 no-padding">
                                                                    <div class="checkbox" style="margin-top: 0px;">
                                                                        @if($all_inf['inf']->compraweb_sub == 'S')
                                                                            <input type="checkbox" checked="" id="add-carrito-{{$inf_lot->cod_sub}}-{{$inf_lot->ref_asigl0}}" class="filled-in add-carrito form-control" name="carrito[{{$inf_lot->sub_csub}}][{{$inf_lot->ref_csub}}][pagar]" >
                                                                        @endif
                                                                    </div>
                                                            </div>
                                                            <div class="col-xs-12 col-sm-2 no-padding ">
                                                                <img src="{{ \Tools::url_img("lote_small", $inf_lot->num_hces1, $inf_lot->lin_hces1) }}" class="img-responsive">
                                                            </div>
                                                            <div class="col-xs-12 col-sm-8 col-sm-offset-1 no-padding">
                                                                    @if(strtoupper($inf_lot->tipo_sub) == 'O' || strtoupper($inf_lot->tipo_sub) == 'P')
                                                                        <div class="user-account-item-auction text-right"><small>{{ trans(\Config::get('app.theme').'-app.user_panel.auctions_online') }}</small></div>
                                                                    @endif
                                                                    <div class="user-account-item-title">{{$inf_lot->titulo_hces1}}</div>

                                                                    <div class="user-account-item-lot"><span>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }} {{$inf_lot->ref_asigl0}}</span></div>
                                                                    <div class="user-account-item-text"><div>{{$inf_lot->cod_sub}}</div></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-2 col-lg-2 account-item-border">
                                                            <div class="user-account-item-date d-flex flex-direction-column align-items-center justify-content-center">
                                                                <div class="visible-xs">{{ trans(\Config::get('app.theme').'-app.user_panel.date') }}</div>
                                                                <p><?= $precio_remapte ?> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</p>
                                                                <small class="comision-title">{{ trans(\Config::get('app.theme').'-app.user_panel.price_comision') }}</small>
                                                                <div>+ <?=  $comision ?> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-3 col-lg-2 account-item-border">
                                                                <div class="user-account-item-price  d-flex align-items-center justify-content-center">

                                                                        <div class="visible-xs">{{ trans(\Config::get('app.theme').'-app.user_panel.mi_puja') }}</div>
                                                                <div><strong><?= \Tools::moneyFormat($precio_limpio_calculo,false,2); ?> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</strong></div>
                                                                </div>
                                                            </div>
                                                            <input class="hide" type="hidden" name="carrito[{{$inf_lot->sub_csub}}][{{$inf_lot->ref_csub}}][envios]" value='1'>
                                                    </div>
                                                    </div>

                                            @endforeach
                                        </div>
                                @if(!$tipo_pago_global)
                                    </form>
                                @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @if($tipo_pago_global)
                                <div class="adj color-letter d-flex align-items-center justify-content-space-between">
                                    <div class="total-price">{{ trans(\Config::get('app.theme').'-app.user_panel.total_price') }} <span class="precio_final_global">0</span> {{ trans(\Config::get('app.theme').'-app.lot.eur') }} </div>
                                        @if(!empty(Config::get('app.merchantIdUP2')))
                                            <button style="margin-left: 15px;" type="button" class="secondary-button   submit_carrito btn btn-step-reg2"  cod_sub="global" class="btn btn-step-reg" disabled>{{ trans(\Config::get('app.theme').'-app.user_panel.pay') }}</button>
                                        @endif
                                </div>
                            </form>
                        @endif
                        </div>
                    </div>

                    <div class="user-accounte-titles-link col-xs-12 no-padding">
                        <ul class="ul-format d-flex justify-content-space-between flex-wrap" role="tablist">
                            <li class="panel-collapse align-items-center d-flex justify-content-space-between w-100" role="pagadas"  data-toggle="collapse"  href="#lot_pag" >
                                <a class="color-letter" >
                                    <div>{{ trans(\Config::get('app.theme').'-app.user_panel.bills') }}</div>

                                </a>
                                <div class="toggle-open-close">
                                        <span class="toggle-open">{{ trans(\Config::get('app.theme').'-app.user_panel.open') }}</span>
                                        <span class="toggle-close" style="display: none">{{ trans(\Config::get('app.theme').'-app.user_panel.hide') }}</span>
                                    </div>

                            </li>
                        </ul>
                    </div>


                    <div class="col-xs-12 no-padding collapse" aria-expanded="false" id="lot_pag">

                        <div class="panel-group" id="accordion">
                            <div class="panel panel-default">

                                <?php $i=0 ?>
                                @foreach($all_adj_pag as $key_sub => $all_inf)
                                <?php
                                    $total_remate = 0;
                                    $total_base = 0;
                                    $total_iva = 0;
                                    $precio_global=0;
                                ?>
                                <div class="panel-heading">
                                    <div class="panel-title">
                                        <a class="d-flex justify-content-space-between" data-toggle="collapse" href="#{{$all_inf['inf']->cod_sub}}_pag">
                                            <div>
                                                <span>{{$all_inf['inf']->name}}</span>

                                            </div>
                                            <img width=10 src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCIgdmlld0JveD0iMCAwIDk2LjE1NCA5Ni4xNTQiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDk2LjE1NCA5Ni4xNTQ7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8cGF0aCBkPSJNMC41NjEsMjAuOTcxbDQ1Ljk1MSw1Ny42MDVjMC43NiwwLjk1MSwyLjM2NywwLjk1MSwzLjEyNywwbDQ1Ljk1Ni01Ny42MDljMC41NDctMC42ODksMC43MDktMS43MTYsMC40MTQtMi42MSAgIGMtMC4wNjEtMC4xODctMC4xMjktMC4zMy0wLjE4Ni0wLjQzN2MtMC4zNTEtMC42NS0xLjAyNS0xLjA1Ni0xLjc2NS0xLjA1NkgyLjA5M2MtMC43MzYsMC0xLjQxNCwwLjQwNS0xLjc2MiwxLjA1NiAgIGMtMC4wNTksMC4xMDktMC4xMjcsMC4yNTMtMC4xODQsMC40MjZDLTAuMTUsMTkuMjUxLDAuMDExLDIwLjI4LDAuNTYxLDIwLjk3MXoiIGZpbGw9IiMwMDAwMDAiLz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K" />
                                            </a>
                                        </div>
                                        <div id="{{$all_inf['inf']->cod_sub}}_pag"  class="table-responsive panel-collapse collapse">

                                            <div class="user-account-heading hidden-xs d-flex align-items-center justify-content-space-between">
                                                <div class="col-xs-12 col-sm-7 col-lg-8 col-one user-account-item">
                                                        {{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}
                                                </div>
                                                <div class="col-xs-12 col-sm-2 col-one user-account-fecha">
                                                        {{ trans(\Config::get('app.theme').'-app.user_panel.price') }}
                                                </div>
                                                <div class="col-xs-12 col-sm-3 col-lg-2 col-one user-account-max-bid">
                                                        {{ trans(\Config::get('app.theme').'-app.user_panel.price_clean') }}
                                                </div>
                                            </div>

                                            <div class="user-accout-items-content">

                                                    @foreach($all_inf['lotes'] as $inf_lot)
                                                    <?php
                                                    $url_friendly = str_slug($inf_lot->titulo_hces1);
                                                    $url_friendly = \Routing::translateSeo('lote').$inf_lot->cod_sub."-".str_slug($inf_lot->name).'-'.$inf_lot->id_auc_sessions."/".$inf_lot->ref_asigl0.'-'.$inf_lot->num_hces1.'-'.$url_friendly;
                                                    $precio_remapte = \Tools::moneyFormat($inf_lot->himp_csub);
                                                    $precio_limpio = \Tools::moneyFormat($inf_lot->base_csub,false,2);
                                                    $comision = \Tools::moneyFormat($inf_lot->base_csub + $inf_lot->base_csub_iva,false,2);

                                                   $precio_limpio_calculo =  number_format($inf_lot->himp_csub + $inf_lot->base_csub + $inf_lot->base_csub_iva, 2, '.', '');
                                                    $calc_envio = number_format($inf_lot->himp_csub + $inf_lot->base_csub, 2, '.', '');


                                                    //Calculo total
                                                    $total_remate = $total_remate + $inf_lot->himp_csub;
                                                    $total_base = $total_base + $inf_lot->base_csub;
                                                    $total_iva = $total_iva + $inf_lot->base_csub_iva;
                                                ?>
                                                        <div class="user-accout-item-wrapper  col-xs-12 no-padding">
                                                            <div class="d-flex">
                                                            <div class="col-xs-12 col-sm-7 col-lg-8 col-one user-account-item ">
                                                                <div class="col-xs-12 col-sm-1 no-padding">
                                                                        <div class="checkbox" style="margin-top: 0px;">

                                                                        </div>
                                                                </div>
                                                                <div class="col-xs-12 col-sm-2 no-padding ">
                                                                    <img src="{{ \Tools::url_img("lote_small", $inf_lot->num_hces1, $inf_lot->lin_hces1) }}" class="img-responsive">
                                                                </div>
                                                                <div class="col-xs-12 col-sm-8 col-sm-offset-1 no-padding">
                                                                        <div class="user-account-item-title">{{$inf_lot->titulo_hces1}}</div>

                                                                        <div class="user-account-item-lot"><span>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }} {{$inf_lot->ref_asigl0}}</span></div>
                                                                        <div class="user-account-item-text"><div>{{$inf_lot->cod_sub}}</div></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-12 col-sm-2 col-lg-2 account-item-border">
                                                                <div class="user-account-item-date d-flex flex-direction-column align-items-center justify-content-center">
                                                                    <div class="visible-xs">{{ trans(\Config::get('app.theme').'-app.user_panel.date') }}</div>
                                                                    <p><?= $precio_remapte ?> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</p>
                                                                    <small class="comision-title">{{ trans(\Config::get('app.theme').'-app.user_panel.price_comision') }}</small>
                                                                    <div>+ <?=  $comision ?> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-12 col-sm-3 col-lg-2 account-item-border">
                                                                    <div class="user-account-item-price  d-flex align-items-center justify-content-center">

                                                                            <div class="visible-xs">{{ trans(\Config::get('app.theme').'-app.user_panel.mi_puja') }}</div>
                                                                    <div><strong><?= \Tools::moneyFormat($precio_limpio_calculo,false,2); ?> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</strong></div>
                                                                    </div>
                                                                </div>
                                                                <input class="hide" type="hidden" name="carrito[{{$inf_lot->sub_csub}}][{{$inf_lot->ref_csub}}][envios]" value='1'>
                                                        </div>
                                                        </div>

                                                @endforeach
                                            </div>

                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>

















                </div>
            </div>
        </div>
    </div>



<script>

    $( document ).ready(function() {
         reload_carrito();
    });

</script>


@stop
