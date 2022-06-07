@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

<?php
$all_adj = array();
$sub = new \App\Models\Subasta;

foreach($data['adjudicaciones'] as $temp_adj){
    $all_adj[$temp_adj->cod_sub]['lotes'][]=$temp_adj;
}
foreach($all_adj as $key_inf => $value){
    $sub->cod = $key_inf;
    $all_adj[$key_inf]['inf'] = $sub->getInfSubasta();
}

?>
<script>
    var info_lots = $.parseJSON('<?php echo str_replace("\u0022","\\\\\"",json_encode($data["js_item"],JSON_HEX_QUOT)); ?>');
</script>

<section class="principal-bar no-principal">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="princiapl-bar-wrapper">
                    <div class="principal-bar-title">
                        <h3>{{ trans(\Config::get('app.theme').'-app.user_panel.mi_cuenta') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="account payment">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <?php $tab="allotments";?> @include('pages.panel.menu_micuenta')
            </div>
            <div class="col-xs-12">
                <div class="tabs">
                    <ul class="nav nav-tabs nav-justified" role="tablist">
                        <li role="pagar"class="active" ><a href="{{ \Routing::slug('user/panel/allotments/outstanding') }}" >{{ trans(\Config::get('app.theme').'-app.user_panel.still_paid') }}</a></li>
                        <li role="pagadas"  ><a href="{{ \Routing::slug('user/panel/allotments/paid') }}" >{{ trans(\Config::get('app.theme').'-app.user_panel.bills') }}</a></li>
                    </ul>
                </div>
                <div class="panel-group" id="accordion">
                    <div class="panel panel-default">
                        <?php $i=0 ?>
                        @foreach($all_adj as $key_sub => $all_inf)
                            <?php
                                $total_remate = 0;
                                $total_base = 0;
                                $total_iva = 0;
                            ?>
                        <a aria-expanded="true" data-toggle="collapse" href="#{{$all_inf['inf']->cod_sub}}">
                                <div class="panel-heading" style="position:relative; background: lightgrey;color: #101010">
                                    <h4 class="panel-title">
                                        {{$all_inf['inf']->name}}
                                    </h4>
                                </div>
                            </a>

                            <div id="{{$all_inf['inf']->cod_sub}}"  class="table-responsive panel-collapse collaps {{$all_inf['inf']->compraweb_sub == 'S' ? 'in': 'collapse'}}" style="padding: 10px;">
                          <!-- Cabeceras grices con titulos-->
                                <form id="pagar_lotes_{{$all_inf['inf']->cod_sub}}" >
                                    <table class="table table-hover" style="min-width: 650px;">
                                        <thead>
                                            <tr>
                                                <th class="">#</th>
                                                <th></th>
                                                <th style="font-weight: 100; color: grey;">{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}</th>
                                                <th style="font-weight: 100; color: grey;">{{ trans(\Config::get('app.theme').'-app.user_panel.name') }}</th>
                                                <th style="font-weight: 100; color: grey;">{{ trans(\Config::get('app.theme').'-app.user_panel.price') }}</th>
                                                <th style="font-weight: 100; color: grey;">{{ trans(\Config::get('app.theme').'-app.user_panel.price_comision') }}</th>
                                                <th style="font-weight: 100; color: grey;">{{ trans(\Config::get('app.theme').'-app.user_panel.price_clean') }}</th>
                                            </tr>
                                        </thead>
                                        <!-- /Cabeceras grices con titulos-->
                                        <?php $countBid=1; ?>
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
                                            <tbody>
                                                <tr>
                                                    <th class="">
                                                        @if($all_inf['inf']->compraweb_sub == 'S')
                                                            <input type="checkbox" checked="" id="add-carrito-{{$inf_lot->cod_sub}}-{{$inf_lot->ref_asigl0}}" class="filled-in add-carrito form-control" name="carrito[{{$inf_lot->sub_csub}}][{{$inf_lot->ref_csub}}][pagar]" >
                                                        @endif
                                                    </th>
                                                    <th>
                                                        <div class="img-dat img-data-customs flex valign" role="button" id="">
                                                            <img class="img-responsive" src="{{ \Tools::url_img("lote_small", $inf_lot->num_hces1, $inf_lot->lin_hces1) }}" style="width: 50px;">
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="lot-data-custon">
                                                            <p>{{$inf_lot->ref_asigl1}}</p>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="name-data-custom">
                                                            <?= $inf_lot->desc_hces1?>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="remat-data-custom">
                                                            <p><?= $precio_remapte ?> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</p>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="auc-data-custom">
                                                            <p><?=  $comision ?> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</p>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="auc-data-custom">
                                                            <p><?= \Tools::moneyFormat($precio_limpio_calculo,false,2); ?> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</p>
                                                        </div>
                                                        <input class="hide" type="hidden" name="carrito[{{$inf_lot->sub_csub}}][{{$inf_lot->ref_csub}}][envios]" value='1'>
                                                    </th>
                                                </tr>
                                            </tbody>
                                        @endforeach
                                    </table>
                                    @if($all_inf['inf']->compraweb_sub == 'S' && !empty(Config::get('app.merchantIdUP2')))
                                        <div class="adj d-flex justify-content-space-between" >
                                            <div class="d-flex align-items-center ">
                                                <span style="font-size: 18px;">{{ trans(\Config::get('app.theme').'-app.user_panel.total_price') }} </span>
                                                <div class="titlecat" style="font-size: 24px; margin-left: 10px"><span class="precio_final_{{$all_inf['inf']->cod_sub}}">0</span> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</div>
                                            </div>
                                            <div class="adj-inline">
                                                <button style="" type="button" class="submit_carrito btn btn-step-reg2"  cod_sub="{{$all_inf['inf']->cod_sub}}" class="btn btn-step-reg" disabled>{{ trans(\Config::get('app.theme').'-app.user_panel.pay') }}</button>
                                            </div>
                                        </div>
                                    @endif
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>

    $( document ).ready(function() {
         reload_carrito();
    });

</script>


@stop
