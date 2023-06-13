@extends('layouts.tiempo_real')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

<?php

# Fecha hasta
$horah       = $data['subasta_info']->lote_actual->end_session;
$hastah      = substr($data['subasta_info']->lote_actual->end_session,0,10);
$hastah      = str_replace('-', '/', $hastah);
$fecha_finh  = $hastah.$horah;

$withExchange = config('app.exchange', false);
if($withExchange) {
	$currency = new App\libs\Currency();
	$divisas = $currency->getAllCurrencies($data['js_item']['subasta']['currency']->name);
}

?>

<script>
const withExchange = '{{$withExchange}}';
var currency = (Boolean(withExchange)) ? @json($divisas) : null;

<?php if(!empty($data['js_item']['user']['is_gestor'])){ ?>
    var licitadores = {
    <?php foreach ($data['licitadores'] as $key => $value) : ?>
            '<?php echo $key; ?>': '<?php echo str_replace("'", "",$value);?>',
    <?php endforeach; ?>
    };
<?php } ?>




$(function() {


    // MODO RESPONSIVE PARA DATOS
        // añadir este jquery
        //añadir codigo html identificado
        //añadir clase global-content dos div despues de #ficha
        //añadir clase pactual al div de puja actual
    if($(window).width() < 768){

        $('#tupuja').parent().hide()
        $('#tuorden').parent().hide()


        $('#precioSalida').appendTo('#salidaResponsive')
        $('#precioestimado').appendTo('#estimadoResponsive')
        $('.pactual').appendTo('#actualResponsive')
        $('#bid_amount').appendTo('#inputResponsive')
        $('.add_bid').appendTo('#btnPujarResponsive')
        $('.add_bid').appendTo('#btnPujarResponsive')

        $('#tupuja').appendTo('#tusOrdenes p.yourBid')
        $('#tuorden').appendTo('#tusOrdenes p.yourOrder')

        $('#tupuja').parent('.col-lg-6').hide()

    }

    // FIN MODO RESPONSIVE PARA DATOS


	<?php if($data['subasta_info']->status == 'stopped' || $data['subasta_info']->status == 'reload') {
        $tiempo =  $data['subasta_info']->reanudacion;
      } elseif($data['subasta_info']->status != 'in_progress') {
         $tiempo =  $data['subasta_info']->lote_actual->start_session;
  }  else {
        $tiempo =  $data['subasta_info']->lote_actual->start_session;

  ?>
        $('#clock, button.start').hide();

        $('.started').removeClass('hidden');

        // si aun no esta iniciada se verá la imagen en grande
        $('.colimagen').addClass('col-lg-6');
        $('.colimagen').removeClass('col-lg-12');
    <?php } ?>

    $(document).ready(function() {
    $(".tiempo").data('ini', new Date().getTime());
    countdown_timer($(".tiempo"));
});



    <?php
    # Subasta finalizada
    if($data['subasta_info']->status == 'ended') {
    ?>
        $('.tiempo').countdown('stop');
        $('.tiempo').html(messages.neutral.auction_end);
        $('button.start').hide();
    <?php } ?>

});
</script>
<?php
# Configuración de vista en caso de no ser vista de proyector
if(!Route::current()->parameter('proyector')) {
    $col = '4';
    $col_pujas = 2;
} else {
    $col = '6';
    $col_pujas = 4;
}

if(!Session::has('user')) {
    $col_pujas = 4;
}

?>
<style>
body, html {
    margin: 0;
    padding: 0;
}
.fondo1 {
    /*border: 1px solid black;*/
    height: 451px;
}

.img-lot {
    /*max-width: 500px*/;
    max-height: 395px;
}

@media (min-width: 1200px) {
    .fondo1 {
        display: flex;
        justify-content: center; /* align horizontal */
        align-items: center; /* align vertical */
    }
}

/* dispositivos moviles */
@media (max-width: 1200px) {
    .fondo1 {
        height: 100% !important;
    }

    * {
        /*background:gray;*/
    }
}
.fondo2 {
    /*background-color:#2b373a;*/
}
</style>
<div id="ficha" style="background:white">

<div class="">
    <div style="margin-top:20px; background:white" class="global-content">

            <div id="clock" style="display: block">

                <div style="margin-top: 10px;border-bottom: 1px solid #283747">
                    <img style="display: block;max-width: 300px; margin: 0 auto" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo.png"  alt="{{(\Config::get( 'app.name' ))}}">
                </div>

                 <div data-countdown="{{strtotime($tiempo) - getdate()[0] }}"  data-format="%D {{trans(\Config::get('app.theme')."-app.msg_neutral.days")}} <br> %H:%M:%S  {{trans(\Config::get('app.theme')."-app.msg_neutral.hours")}}" data-txtend ="{{trans(\Config::get('app.theme')."-app.msg_neutral.auction_coming_soon")}}" class="tiempo wait-time text-center"></div>


            </div>

            @if(!empty($data['js_item']['user']['is_gestor']))
            <div class="botonclock">
                <button class="btn btn-primary btn-lg start" data-to="iniciar_subasta"  start='1'>{{ trans(\Config::get('app.theme').'-app.sheet_tr.start_auction') }}</button>
            </div>
            @endif

            <!-- imagen y descripcion -->
            <div class="col-lg-8 col-md-8 ">
                <div class="fondo1 aside" style="margin-top:0;">

                    <div class="col-lg-6" style="border-right:0;">
                        <!-- imagen -->
                        <div class="" id="main_lot_box" style="position:relative;">
                            <div id="main_image_box">

                                <!-- INICIADA -->
                                <h4 class="pull-right"><strong></strong> </h4>

                                <div class="img" >
                                    <img width="100%" class="img-lot img-responsive" src="data:image/jpg;base64,{{ $data['subasta_info']->lote_actual->imagen }}" style="display: inline">
                                </div>

                                <div id="count_down_msg" class="hidden notranslate">
                                    <p></p>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-lg-6" style="border-left:0;">
                        <span id="actual_titulo" style="font-size: 20px;">
                            <?php echo  $data['text_lang'][$data['js_item']['lang_code']]->titulo_hces1 ?>
                        </span>
                        <!-- desc-->
                        <div class=" descripcion started hidden">
                            <span id="lote_actual_main" class="" style="display:block">{{ trans(\Config::get('app.theme').'-app.sheet_tr.lot') }} <strong><span id="info_lot_actual">{{ $data['subasta_info']->lote_actual->ref_asigl0 }}</span></strong> </span>
                            <?php // MODO RESPONSIVE PARA DATOS */ ?>
                            @if(empty($data['js_item']['user']['is_gestor']))

                            <div id="salidaResponsive" class="salidaResponsive hidden-sm hidden-md hidden-lg"></div>

                            <div id="tusOrdenes" class="tusOrdenes hidden-sm hidden-md hidden-lg">
                                <p class="yourBid">{{ trans(\Config::get('app.theme').'-app.sheet_tr.your_actual_bid') }}: </p>
                                <p class="yourOrder">{{ trans(\Config::get('app.theme').'-app.sheet_tr.your_actual_order') }}: </p>
                            </div>
                            <div id="actualResponsive" class="actualResponsive hidden-sm hidden-md hidden-lg"></div>
                            <div id="" class="pujarResponsive">
                                <div id="inputResponsive"></div>
                                <div id="btnPujarResponsive"></div>
                            </div>
                            <div id="estimadoResponsive" class="estimadoResponsive hidden-sm hidden-md hidden-lg"></div>
                               @endif
                            <?php // FIN MODO RESPONSIVE PARA DATOS */ ?>

                            <div id="actual_descripcion" style="">

                                     <?php echo  $data['text_lang'][$data['js_item']['lang_code']]->desc_hces1 ?>
                            </div>
                            <?php /* quitar preico estimado
                             <div id="precioestimado" class="text-center" style=" padding-top: 10px;">
                                <p>
                                    <strong>{{ trans(\Config::get('app.theme').'-app.subastas.estimate') }}:</strong>
                                    <span id="imptas" >{{ $data['subasta_info']->lote_actual->formatted_imptas_asigl0}} </span>-<span id="imptash" >  {{ $data['subasta_info']->lote_actual->formatted_imptash_asigl0}} </span> {{ $data['js_item']['subasta']['currency']->symbol }}
                                </p>
                            </div>
                            */ ?>
                            <div id="precioSalida" class="precioSalida salida text-center">
                                <p>
                                    <strong>{{ trans(\Config::get('app.theme').'-app.sheet_tr.start_price') }}:</strong>
                                    <span>{{ $data['subasta_info']->lote_actual->formatted_impsalhces_asigl0 }}</span> {{ $data['js_item']['subasta']['currency']->symbol }}
                                </p>
								@if(\Config::get("app.exchange"))
								| <span id="startPriceExchange_JS" class="exchange"> </span>
								@endif
                            </div>



                            <!-- puja actual -->
                            <div class="pactual salida text-center">
                                <p>
                                    <span id="text_actual_max_bid" class="<?= count($data['subasta_info']->lote_actual->pujas) > 0? '' : 'hidden' ?> ">
                                            {{ trans(\Config::get('app.theme').'-app.sheet_tr.max_actual_bid') }}
                                    </span>
                                    <span id="text_actual_no_bid" class="<?= count($data['subasta_info']->lote_actual->pujas) > 0? 'hidden' : '' ?> ">
                                        {{ trans(\Config::get('app.theme').'-app.sheet_tr.pending_bid') }}
                                    </span>

                                    <span id="actual_max_bid" class="@if (!empty($data['js_item']['user']) && !empty($data['subasta_info']->lote_actual->max_puja) &&  $data['subasta_info']->lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit']) mine @else other @endif">
                                        @if( count($data['subasta_info']->lote_actual->pujas) >0 )
                                            {{ \Tools::moneyFormat($data['subasta_info']->lote_actual->actual_bid) }} {{ $data['js_item']['subasta']['currency']->symbol }}

                                        @endif

                                    </span>

									@if(\Config::get("app.exchange"))
									| <span id="actualBidExchange_JS" class="exchange"> </span>
									@endif

                                    @if(Session::has('user') && $data['js_item']['user']['is_gestor'])

                                        <span id="cancelarPuja" >{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel_bid') }}</span>
                                        <span id="cancelarOrden" >{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel_order') }}</span>

                                    @endif
                                    @if(\Config::get('app.tr_show_canel_bid_client') && Session::has('user') && !$data['js_item']['user']['is_gestor'])

                                    <span id="cancelarPujaUser" class="@if (!empty($data['js_item']['user']) && !empty($data['subasta_info']->lote_actual->max_puja) &&  $data['subasta_info']->lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit'])  @else hidden  @endif" >{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel_bid') }}</span>
                                    @endif
                                </p>

                            </div>



                            @if(Session::has('user') && !$data['js_item']['user']['is_gestor'])
                                <div class="col-lg-6">{{ trans(\Config::get('app.theme').'-app.sheet_tr.your_actual_bid') }}: <span id="tupuja"><?php if (!empty($data['js_item']['user']['maxPuja']))  { echo $data['js_item']['user']['maxPuja']->formatted_imp_asigl1; } ?></span></div>

                                <div class="col-lg-6">{{ trans(\Config::get('app.theme').'-app.sheet_tr.your_actual_order') }}: <span id="tuorden"><?php if (!empty($data['js_item']['user']['maxOrden']))  { echo $data['js_item']['user']['maxOrden']->himp_orlic; } ?></span></div>
								@if(\Config::get("app.exchange"))
								|	<span  id="yourOrderExchange_JS" class="exchange"> </span>
								@endif
							@endif

                            <!-- controles -->
                            <div class="row started hidden">
                                <div class="col-lg-12">
                                    <div class="col-lg-6">
                                        @if(Session::has('user'))
                                        <?php //deshabilitamos el input para que el usuario no pueda cambiar de importe ?>
                                            <input id="bid_amount"  autocomplete="off" type="text" class="form-control bid_amount_gestor" value="{{ $data['subasta_info']->lote_actual->importe_escalado_siguiente }}">
                                        @endif
                                    </div>

                                        <div class="noti">

                                            @if(!empty($data['js_item']['user']['is_gestor']))
                                            <div class="col-lg-6" style="padding:0; ">

                                                <!-- Panel de puja del gestor -->
                                                <div id="controles_puja_gestor">

                                                    <div class="controles_tipo_puja">
                                                        <i data-type="S" class="add_bid fa fa-3x fa-hand-paper-o" aria-hidden="true"></i>
                                                        <i data-type="I" style="padding:0 30px 0 30px" class="add_bid fa fa-3x fa-globe" aria-hidden="true"></i>
                                                        <i data-type="T" class="add_bid fa fa-3x fa-phone" aria-hidden="true"></i>
                                                    </div>

                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6">

                                                    <div class="gestor_radios">
                                                        <label class="radio">
                                                            <input checked="checked" type="radio" name="puja_opts" value="normal"> {{ trans(\Config::get('app.theme').'-app.sheet_tr.order_bid') }}
                                                        </label>
                                                        <label class="radio">
                                                            <input type="radio" name="puja_opts" value="firme"> {{ trans(\Config::get('app.theme').'-app.sheet_tr.direct_bid') }}
                                                        </label>
                                                    </div>

                                                </div>

                                                <div class="col-lg-6" style="margin-top:20px;">
                                                    <input type="text" class="form-control" id="ges_cod_licit" name="ges_cod_licit" placeholder="nº Licitador"/>
                                                </div>

                                            </div>
                                            @endif


                                            @if(empty($data['js_item']['user']['is_gestor']) and Session::has('user'))
                                            <div class="col-lg-6">
                                                <a class="add_bid btn btn-success btn-custom-save"><i class="fa fa-gavel"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.place_bid') }}</a>
                                                <input type="hidden" id="tiempo_real" value="1" readonly>
                                            </div>
                                            @endif

                                        </div>

                                </div>
                            </div>
                            <!-- fin controles -->

                        </div>
                    </div>
                </div>
            </div>

            <!-- inicio pujas -->
            <div class="col-sm-4 col-lg-4 fondo2">
                @if (\Config::get('app.tr_show_pujas'))
                <div class="started hidden">
                    <div class="aside pujas">

                        <h2>{{ trans(\Config::get('app.theme').'-app.sheet_tr.last_bids') }}</h2>
                            <div id="pujas_list">

                            <?php
                            $ultima_orden =false;
                            foreach ($data['subasta_info']->lote_actual->pujas as $puja) : ?>

                                <?php

                                    /*Nombre de los licitadores*/
                                    $name_licit = '-';
                                    if(!empty($data['licitadores']) && !empty($data['js_item']['user']['is_gestor']) && $puja->cod_licit != Config::get('app.dummy_bidder')  ){
                                        $name_licit = !empty($data['licitadores'][$puja->cod_licit])? $data['licitadores'][$puja->cod_licit] : "-" ;
                                    }
                                    /*Fin de nombre de los licitadores*/

                                ?>
                                    <div class="pujas_model col-xs-12">
                                        <div class="col-lg-6 tipoPuja">
                                            <p data-type="I" @if ($puja->pujrep_asigl1 != 'I')class="hidden" @endif><i class="fa fa-globe" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bid-internacional') }}</p>
                                            <p data-type="S" @if ($puja->pujrep_asigl1 != 'S')class="hidden" @endif><i class="fa fa-hand-paper-o" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bid-sala') }}</p>
                                            <p data-type="T" @if ($puja->pujrep_asigl1 != 'T' && $puja->pujrep_asigl1 != 'B')class="hidden" @endif><i class="fa fa-phone" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bid-telf') }}</p>
                                            <p data-type="E" @if ($puja->pujrep_asigl1 != 'E' && $puja->pujrep_asigl1 != 'P') class="hidden" @endif><i class="fa fa-desktop" aria-hidden="true"></i>  {{ trans(\Config::get('app.theme').'-app.sheet_tr.books_bid') }}</p>
                                            <p data-type="W" @if ($puja->pujrep_asigl1 != 'W')class="hidden" @endif><i class="fa fa-wikipedia-w" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bid-web') }}</p>
                                            <p data-type="O" @if ($puja->pujrep_asigl1 != 'O')class="hidden" @endif><i class="fa fa-desktop" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.books_bid') }}</p>
                                            <p data-type="U" @if ($puja->pujrep_asigl1 != 'U')class="hidden" @endif><i class="fa fa-desktop" aria-hidden="true"></i> Subalia</p>
                                        </div>
                                        <div class="col-lg-6 importePuja">
                                            <p>
                                            <span>{{ $puja->formatted_imp_asigl1 }}</span> <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span>
                                            @if(!empty($data['js_item']['user']['is_gestor']))
                                             <span class="licitadorPuja">({{ $puja->cod_licit }})<span style="font-size: 12px;"> {{$name_licit}}</span></span>
                                            @endif
                                            </p>
                                        </div>

                                    </div>

                            <?php endforeach;?>

                            <div class="pujas_model hidden col-xs-12" id="type_bid_model">
                                <div class="col-lg-6 tipoPuja">
                                    <p data-type="I"><i class="fa fa-globe" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bid-internacional') }}</p>
                                    <p data-type="S" class="hidden"><i class="fa fa-hand-paper-o" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bid-sala') }}</p>
                                    <p data-type="T" class="hidden"><i class="fa fa-phone" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bid-telf') }}</p>
									<p data-type="B" class="hidden"><i class="fa fa-phone" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bid-telf') }}</p>
                                    <p data-type="E" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.books_bid') }}</p>
                                    <p data-type="P" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.books_bid') }}</p>
                                    <p data-type="W" class="hidden"><i class="fa fa-wikipedia-w" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bid-web') }}</p>
                                    <p data-type="O" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.books_bid') }}</p>
                                    <p data-type="U" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i> Subalia</p>
                                </div>
                                <div class="col-lg-6 importePuja">
                                    <p>
                                        <span class="puj_imp"></span>
                                        <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span>
                                        @if(!empty($data['js_item']['user']['is_gestor']))
                                            <span class="licitadorPuja"></span>
                                        @endif

                                    </p>
                                </div>

                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <!-- fin pujas -->

            @if (\Config::get('app.tr_show_adjudicaciones') and Session::has('user') && empty($data['js_item']['user']['is_gestor']))
            <div class="col-sm-4 col-lg-4 started hidden">
                <div class="aside adjudicaciones">
                    <h2>{{ trans(\Config::get('app.theme').'-app.sheet_tr.your_adjudications') }}</h2>
                    <div id="adjudicaciones_list">
                        @if (!empty($data['js_item']['user']) && !empty($data['js_item']['user']['adjudicaciones']))
                            <?php foreach ($data['js_item']['user']['adjudicaciones'] as $key => $val): ?>
                                <div class="adjudicaciones_model">
                                    <div class="col-lg-6 adj_ref">
                                        <p>{{ trans(\Config::get('app.theme').'-app.sheet_tr.lot') }}</i> <span>{{ $val->ref_asigl1 }}</span></p>
                                    </div>
                                    <div class="col-lg-6">
                                        <p><span class="adj_imp">{{ $val->imp_asigl1 }}</span> <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>



                        @endif
                        <div class="adjudicaciones_model hidden" id="type_adj_model">
                                <div class="col-lg-6 adj_ref">
                                    <p>{{ trans(\Config::get('app.theme').'-app.sheet_tr.lot') }}</i> <span></span></p>
                                </div>
                                <div class="col-lg-6">
                                    <p><span class="adj_imp"></span> <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span></p>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            @endif

            @if (\Config::get('app.tr_show_ordenes_licitacion') and Session::has('user') && $data['js_item']['user']['is_gestor'])
            <!-- inicio lista ordenes de licitacion -->
            <div class="col-lg-4 started hidden">
                <div class="aside ol">

                    <h2>{{ trans(\Config::get('app.theme').'-app.sheet_tr.orders') }}</h2>
                        <div id="ol_list">

                        <?php foreach ($data['subasta_info']->lote_actual->ordenes as $orden) : ?>
                                <?php
                                /*Nombre de los licitadores*/
                                $name_licit = '-';
                                if(!empty($data['licitadores']) && !empty($data['js_item']['user']['is_gestor']) && $orden->cod_licit != Config::get('app.dummy_bidder')){
                                    $name_licit = !empty($data['licitadores'][$orden->cod_licit])? $data['licitadores'][$orden->cod_licit] : "-" ;
                                }
                                /*Fin de nombre de los licitadores*/
                                ?>
                                <div class="ol_model">
                                    <div class="col-lg-6 tipoOrden">
                                        <p data-type="I" @if ($orden->tipop_orlic != 'I')class="hidden" @endif><i class="fa fa-globe" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bid-internacional') }}</p>
                                        <p data-type="W" @if ($orden->tipop_orlic != 'W')class="hidden" @endif ><i class="fa fa-globe" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bid-web') }}</p>
                                        <p data-type="S" @if ($orden->tipop_orlic != 'S')class="hidden" @endif><i class="fa fa-hand-paper-o" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bid-sala') }}</p>
                                        <p data-type="T" @if ($orden->tipop_orlic != 'T')class="hidden" @endif><i class="fa fa-phone" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bid-telf') }}</p>
                                        <p data-type="E" @if ($orden->tipop_orlic != 'E' && $orden->tipop_orlic != 'P')class="hidden" @endif><i class="fa fa-desktop" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.books_bid') }}</p>
                                        <p data-type="O" @if ($orden->tipop_orlic != 'O')class="hidden" @endif><i class="fa fa-desktop" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.books_bid') }}</p>
                                        <p data-type="U" @if ($orden->tipop_orlic != 'O')class="hidden" @endif><i class="fa fa-desktop" aria-hidden="true"></i> Subalia</p>
                                 </div>

                                    <div class="col-lg-6 importeOrden">
                                        <p>
                                            <span class="puj_imp_order">{{ \Tools::moneyFormat($orden->himp_orlic) }}</span> <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span>
                                            @if(!empty($data['js_item']['user']['is_gestor']))
                                                <span class="licitadorOrden">({{ $orden->cod_licit }})  <span style="font-size: 12px;"> {{$name_licit}}</span></span>
                                            @endif

                                        </p>
                                    </div>
                                </div>

                        <?php endforeach;?>

                        <div class="ol_model hidden" id="type_bid_model_order">
                            <div class="col-lg-6 tipoOrden">
                                <p data-type="I"><i class="fa fa-globe" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bid-internacional') }}</p>
                                <p data-type="W"><i class="fa fa-globe" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bid-web') }}</p>
                                <p data-type="S" class="hidden"><i class="fa fa-hand-paper-o" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bid-sala') }}</p>
                                <p data-type="T" class="hidden"><i class="fa fa-phone" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bid-telf') }}</p>
                                <p data-type="E" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.books_bid') }}</p>
                                <p data-type="P" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.books_bid') }}</p>
                                <p data-type="O" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.books_bid') }}</p>
                                <p data-type="U" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i> Subalia</p>
                            </div>
                            <div class="col-lg-6 importeOrden">
                                <p>
                                    <?php if($data['js_item']['user']['is_gestor']) { ?>
                                        <span class="licitadorOrden"></span>
                                    <?php } ?>
                                    <span class="puj_imp_order"></span>
                                    <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- fin lista ordenes de licitacion -->
            @endif







    </div>

     <!-- mensajes en sala chat -->
            <div class="col-sm-4 col-lg-6 hidden started" >
            @if (\Config::get('app.tr_show_chat') and Session::has('user'))
                @include('content.tr.msg_sala')
            @endif
            </div>



            <!-- Buscador -->
            <div class="col-sm-12 col-lg-6 hidden started">
                @if (\Config::get('app.tr_show_buscador') and Session::has('user'))
                    @include('content.tr.buscador')
                @endif
            </div>

</div> <!-- row -->



<div class="row">
    <!-- Bloque 2 -->
    <div class="col-lg-12 col-xs-12">






    </div>
</div>

    @if(!empty($data['js_item']['user']['is_gestor']))
       <div id="controles_gestor_box">
           <div class="gestor_buttons">
               <p>{{ trans(\Config::get('app.theme').'-app.sheet_tr.user_conectet') }} <span id="users_conectet"></span></p>
                <button class="change_end_lot btn" data-status="end" type="button">{{ trans(\Config::get('app.theme').'-app.sheet_tr.end_lot') }}</button>
                <button class="change_end_lot btn hidden" data-status="cancel" type="button">{{ trans(\Config::get('app.theme').'-app.sheet_tr.cancel_end_lot') }}</button>

                <button class="change_auction_status btn @if ($data['js_item']['subasta']['status'] == 'stopped') hidden @endif" data-status="stopped" class="btn" type="button">{{ trans(\Config::get('app.theme').'-app.sheet_tr.stop_auction') }}</button>
                <button class="change_auction_status btn @if ($data['js_item']['subasta']['status'] == 'stopped') hidden @endif" data-status="stopped-time" class="btn" type="button">{{ trans(\Config::get('app.theme').'-app.sheet_tr.put_off_auction') }}</button>
                <button class="change_auction_status btn @if ($data['js_item']['subasta']['status'] == 'in_progress') hidden @endif" data-status="in_progress" class="btn" type="button">{{ trans(\Config::get('app.theme').'-app.sheet_tr.restart_lot') }}</button>

                <button id="msg_predef" class="btn" type="button">{{ trans(\Config::get('app.theme').'-app.sheet_tr.msg_predef') }}</button>
                <button id="show_stopped_lots" class="btn" type="button">{{ trans(\Config::get('app.theme').'-app.sheet_tr.show_stopped_lots') }}</button>
                <button id="show_stopped_lots_disabled" class="btn hidden" style="background:red" type="button">{{ trans(\Config::get('app.theme').'-app.sheet_tr.show_stopped_lots') }}</button>
                <button id="jump_to_lots" class="btn" type="button">{{ trans(\Config::get('app.theme').'-app.sheet_tr.jump_to_lots') }}</button>
                <button id="jump_to_lots_disabled" class="btn hidden" style="background:red" type="button">{{ trans(\Config::get('app.theme').'-app.sheet_tr.jump_to_lots') }}</button>
                <button id="baja_client" class="btn" type="button">{{ trans(\Config::get('app.theme').'-app.sheet_tr.baja_client') }}</button>
                <button id="baja_client_disabled" class="btn hidden" style="background:red" type="button">{{ trans(\Config::get('app.theme').'-app.sheet_tr.baja_client') }}</button>

                <?php /*automátic auctions */ ?>
                @if (\Config::get('app.tr_show_automatic_auction'))
                <div>
                    <button id="automatic_auction" style="background: #337ab7;    display: inline-block;    width: 170px;" class="btn" type="button">{{ trans(\Config::get('app.theme').'-app.sheet_tr.automatic_auction') }}</button>
                    <input style="display: inline-block; width: 55px;height: 34px;vertical-align: bottom;padding-left: 5px;" id="seconds_automatic_auctions" type="text" placeholder="Segundos" value="<?= !empty(Config::get('app.seconds_automatic_auction'))? Config::get('app.seconds_automatic_auction') : '5'  ?>" >
                </div>
                <div style="height: 20px">
                    <span id="msg_contador_automatico" ></span>
                </div>
                @endif
            </div>
           <div class="desplegable">
               <i class="fa fa-angle-right" aria-hidden="true"></i>
           </div>
       </div>
    @endif


</div>

@stop
