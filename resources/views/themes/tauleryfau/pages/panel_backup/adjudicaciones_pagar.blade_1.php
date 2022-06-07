@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

<?php
$all_adj = array();
$sub = new \App\Models\Subasta;
$all_adj=array();
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
            <div class="col-xs-2 col-sm-2 col-md-3">
                <?php $tab="allotments";?> @include('pages.panel.menu_micuenta')
            </div>
            <div class="col-xs-10 col-sm-10 col-md-9">
                <div class="user-datas-title flex">
                    <p>{{ trans(\Config::get('app.theme').'-app.user_panel.allotments') }}</p>
                    <div class="col_reg_form"></div>   
                    <div class="btns-pay flex">
                        <div>
                        <a href="{{ \Routing::slug('user/panel/allotments/outstanding') }}" class="btn-color btn-forpay">{{ trans(\Config::get('app.theme').'-app.user_panel.still_paid') }}</a>
                        </div>
                        <div>
                        <a href="{{ \Routing::slug('user/panel/allotments/paid') }}" class="btn-payed"  style="color: #283747">{{ trans(\Config::get('app.theme').'-app.user_panel.bills') }}</a>
                        </div>
                    </div>
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
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        {{$all_inf['inf']->name}}
                                    </h4>
                                    <i class="fas fa-sort-down"></i>
                                </div>
                            </a>
                            <div id="{{$all_inf['inf']->cod_sub}}"  class="table-responsive-custom panel-collapse collaps in">
                        
                            <!-- Cabeceras grices con titulos-->
                                <div class="custom-head-wrapper flex">
                                    <div class="table-data-check flex hidden">

                                    </div>
                                    <div class="img-data-customs flex "></div>
                                    <div class="lot-data-custon">
                                        <p>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}</p>
                                    </div>
                                    <div class="name-data-custom">
                                        <p style="font-weight: 900">{{ trans(\Config::get('app.theme').'-app.lot.description') }}</p>
                                    </div>
                                    <div class="remat-data-custom">
                                        <p>{{ trans(\Config::get('app.theme').'-app.user_panel.price') }}</p>
                                    </div>
                                    <div class="auc-data-custom">
                                        <p>{{ trans(\Config::get('app.theme').'-app.user_panel.price_comision') }}</p>
                                    </div>
                                    <div class="auc-data-custom">
                                        <p>{{ trans(\Config::get('app.theme').'-app.user_panel.price_clean') }}</p>
                                    </div>
                                </div>
                             <form id="pagar_lotes_{{$all_inf['inf']->cod_sub}}" >
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
                            

                            
                             <!-- Lotes en vista desktop-->
                                 <div class="custom-wrapper flex  valign">
                                    <div class="table-data-check flex hidden">
                                    @if( empty(\Config::get( 'app.pasarela_web' )))    
                                    <input type="checkbox" checked="" id="{{$i}}" class="filled-in add-carrito form-control" name="carrito[{{$inf_lot->sub_csub}}][{{$inf_lot->ref_csub}}][pagar]" >
                                        <label for="{{$i}}"></label>
                                    @endif
                                    </div>

                                    <div class="img-dat img-data-customs flex valign" role="button" id="">                                                    
                                        <img class="img-responsive" src="/img/load/lote_medium/{{ $inf_lot->imagen }}">
                                    </div>
                                    <div class="lot-data-custon">
                                        <p>{{$inf_lot->ref_asigl1}}</p>
                                    </div>
                                    <div class="name-data-custom">
                                        <?= $inf_lot->desc_hces1?>
                                    </div>

                                    <div class="remat-data-custom">
                                        <p><?= $precio_remapte ?> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</p>
                                    </div>
                                    <div class="auc-data-custom">
                                        <p><?=  $comision ?> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</p>
                                    </div>
                                     <div class="auc-data-custom">
                                         <p><?= \Tools::moneyFormat($precio_limpio_calculo,false,2); ?> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</p>
                                     </div>
                                    <input class="hide" type="hidden" name="carrito[{{$inf_lot->sub_csub}}][{{$inf_lot->ref_csub}}][envios]" value='1'>

                                </div>
                                <!-- /Lotes en vista desktop-->
                              <?php $i++ ?>
                            @endforeach
                            @if($data['user']->envcorr_cli != 'B')
                                <div class="adj" >   
                                    <h1 class="titlecat"><span class="precio_final_{{$all_inf['inf']->cod_sub}}">0</span> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</h1>
                                    @if($all_inf['inf']->compraweb_sub == 'S')
                                    <button type="button" class="submit_carrito btn btn-step-reg"  cod_sub="{{$all_inf['inf']->cod_sub}}" class="btn btn-step-reg" disabled>{{ trans(\Config::get('app.theme').'-app.user_panel.pay') }}</button>
                                    @endif
                                    <div class="info-modal-pay">
                                        <div  class="open-modal-info btn info">{{ trans(\Config::get('app.theme').'-app.user_panel.info_modal') }}</div>
                                        <div class="info-pay-modal" style="display: none">
                                            <div class="info-pay-modal-close" role="button"><i class="fas fa-times"></i></div>
                                            <div class="price flex">
                                                <div class="title">{{ trans(\Config::get('app.theme').'-app.user_panel.price') }}</div>
                                                <div class="money">{{\Tools::moneyFormat($total_remate,false,2)}} {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</div>
                                            </div>
                                            <div class="price flex">
                                                <div class="title">{{ trans(\Config::get('app.theme').'-app.user_panel.base') }}</div>
                                                <div class="money">{{\Tools::moneyFormat($total_base,false,2)}} {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</div>
                                            </div>
                                            <div class="price flex">
                                                <div class="title">{{ trans(\Config::get('app.theme').'-app.user_panel.tax') }}</div>
                                                <div class="money">{{\Tools::moneyFormat($total_iva,false,2)}} {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</div>
                                            </div>
                                            <div class="price flex">
                                                <div class="title">{{ trans(\Config::get('app.theme').'-app.user_panel.ship_tax') }}</div>
                                                <div class="money"><p class='text-gasto-envio-{{$all_inf['inf']->cod_sub}}'></p></div>
                                            </div>
                                            <div class="price flex text-bold">
                                                <div class="title"><b>{{ trans(\Config::get('app.theme').'-app.user_panel.total') }}</b></div>
                                                <div class="money flex"><b style="display: inline-block" class='precio_final_{{$all_inf['inf']->cod_sub}}'></b><b style="display: inline-block"> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</b></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($data['user']->envcorr_cli == 'B')
                            <div class="adj"><p style="color: #283747;font-size: 18px;">{{ trans(\Config::get('app.theme').'-app.user_panel.contact_tauler') }}</p></div>
                            @endif
                        </div>
                        </form>
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
