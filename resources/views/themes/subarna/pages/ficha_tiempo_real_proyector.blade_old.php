@extends('layouts.tiempo_real')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')

<?php

# Fecha hasta
$horah       = $data['subasta_info']->lote_actual->end_session;
$hastah      = substr($data['subasta_info']->lote_actual->end_session,0,10);
$hastah      = str_replace('-', '/', $hastah);
$fecha_finh  = $hastah.$horah;



?>

<script>
$(function() {

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
    display:block!important;
}
.object_title {

    font-size: 38px !important;
}

.img-lot {
    /*max-width: 500px*/;
    max-height: 395px;
}

.proyector #pujas_list{
    height:320px!important;
}
html .proyector,body .proyector
{
  height: 100% !important;
  font-size: 24px;
}
#actual_max_bid
{
    font-size: 49px;
}

header > nav.navbar.first .navbar-brand{
    height: 50px!important;
}

.aside_proyector  {
    background-color: #fff;
    border-top: 1px solid #ebebeb;
    border-left: 1px solid #ebebeb;
    border-right: 1px solid #ebebeb;
    padding: 20px;
    margin: 20px 0 0 0;
        margin-top: 20px;
}
.aside_proyector_desc{
    background-color: #fff;
    border-bottom: 1px solid #ebebeb;
    border-left: 1px solid #ebebeb;
    border-right: 1px solid #ebebeb;
    padding: 20px;
}

#actual_titulo{

    margin-left:15px;
    font-size: 30px;

}
#ficha #lote_actual_main{
    font-size: 38px;
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
<div id="ficha" class="proyector" style="background:white">

<div class="">
    <div style="margin-top:20px; background:white">

            <div id="clock">
               <div data-countdown="{{strtotime($tiempo) - getdate()[0] }}"  data-format="%D {{trans($theme."-app.msg_neutral.days")}} <br> %H:%M:%S  {{trans($theme."-app.msg_neutral.hours")}}" data-txtend ="{{trans($theme."-app.msg_neutral.auction_coming_soon")}}" class="tiempo"></div>
            </div>

            @if(!empty($data['js_item']['user']['is_gestor']))
            <div class="botonclock">
                <button class="btn btn-primary btn-lg start" data-to="iniciar_subasta"  start='1'>{{ trans($theme.'-app.sheet_tr.start_auction') }}</button>
            </div>
            @endif

            <!-- imagen y descripcion -->
            <div class="col-lg-8 col-md-8 ">

                <div class="fondo1 aside" style="margin-top:0;">

                   <div class="col-lg-11" style="border-right:0;">
                        <!-- imagen -->
                        <div class="" id="main_lot_box" style="position:relative;">
                            <div id="main_image_box">

                                <!-- INICIADA -->
                                <span id="lote_actual_main" class="pull-left">{{ trans($theme.'-app.sheet_tr.lot') }} <strong><span id="info_lot_actual">{{ $data['subasta_info']->lote_actual->ref_asigl0 }} </span></strong> <span id="actual_titulo"> <?php echo  $data['text_lang'][$data['js_item']['lang_code']]->titulo_hces1 ?>  </span></span>


                                <div class="img">
                                    <img width="100%" class="img-lot img-responsive" src="data:image/jpg;base64,{{ $data['subasta_info']->lote_actual->imagen }}" style="display: inline">
                                </div>

                                <div id="count_down_msg" class="hidden">
                                    <p></p>
                                </div>
                                <div class=" descripcion started hidden">



                            <div id="actual_descripcion" style="overflow:hidden;height:230px;max-height:230px">
                                    <?php echo  $data['text_lang'][$data['js_item']['lang_code']]->desc_hces1 ?>
                            </div>

                        </div>
                            </div>

                        </div>
                    </div>

                    <!--<div class="col-lg-4" style="border-left:0;">-->
                        <!-- desc-->

                    <!--</div>-->
                </div>

            </div>

            <!-- inicio pujas -->
            <div class="col-sm-4 col-lg-4 fondo2">
                <div class="aside" style="margin-bottom: 15px;margin-top: 0px;">
                        <?php /* quitar preico estimado
                      <div id="precioestimado" class="text-center" style=" padding-top: 10px;">
                                <p>
                                    <strong>{{ trans($theme.'-app.subastas.estimate') }}:</strong>
                                    <span id="imptas" >{{ $data['subasta_info']->lote_actual->formatted_imptas_asigl0}} </span>-<span id="imptash" >  {{ $data['subasta_info']->lote_actual->formatted_imptash_asigl0}} </span> {{ $data['js_item']['subasta']['currency']->symbol }}
                                </p>
                            </div>
                          */ ?>

                    <div id="precioSalida" class="precioSalida salida text-center">
                                <p >
                                    <strong>{{ trans($theme.'-app.sheet_tr.start_price') }}:</strong><br>
                                    <span>{{ $data['subasta_info']->lote_actual->formatted_impsalhces_asigl0 }}</span> {{ $data['js_item']['subasta']['currency']->symbol }}
                                </p>
                            </div>

                            <!-- puja actual -->
                            <div class="salida text-center">
                                <p style="font-size: 32px!important;">
                                    <span id="text_actual_max_bid" class="<?= count($data['subasta_info']->lote_actual->pujas) > 0? '' : 'hidden' ?> ">
                                            {{ trans($theme.'-app.sheet_tr.max_actual_bid') }}
                                    </span>
                                    <span id="text_actual_no_bid" class="<?= count($data['subasta_info']->lote_actual->pujas) > 0? 'hidden' : '' ?> ">
                                        {{ trans($theme.'-app.sheet_tr.pending_bid') }}
                                    </span>
                                    <br/>
                                    <span id="actual_max_bid" class="@if (!empty($data['js_item']['user']) && !empty($data['subasta_info']->lote_actual->max_puja) &&  $data['subasta_info']->lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit']) mine @else other @endif">
                                        @if( count($data['subasta_info']->lote_actual->pujas) >0 )
                                            {{ \Tools::moneyFormat($data['subasta_info']->lote_actual->actual_bid) }} {{ $data['js_item']['subasta']['currency']->symbol }}

                                        @endif

                                    </span>
                                </p>

                            </div>
                </div>
                @if (\Config::get('app.tr_show_pujas'))
                <div class="started hidden">
                    <div class="aside pujas">

                        <h2>{{ trans($theme.'-app.sheet_tr.last_bids') }}</h2>
                            <div id="pujas_list" style="height: 320px !important;">

                            <?php foreach ($data['subasta_info']->lote_actual->pujas as $puja) : ?>
                                    <div class="pujas_model">
                                        <div class="col-md-3 tipoPuja">
                                            <p data-type="I" @if ($puja->pujrep_asigl1 != 'I')class="hidden" @endif><i class="fa fa-globe" aria-hidden="true"></i> </p>
                                            <p data-type="S" @if ($puja->pujrep_asigl1 != 'S')class="hidden" @endif><i class="fa fa-hand-paper-o" aria-hidden="true"></i> </p>
                                            <p data-type="T" @if ($puja->pujrep_asigl1 != 'T')class="hidden" @endif><i class="fa fa-phone" aria-hidden="true"></i> </p>
                                            <p data-type="E" @if ($puja->pujrep_asigl1 != 'E' && $puja->pujrep_asigl1 != 'P') class="hidden" @endif><i class="fa fa-desktop" aria-hidden="true"></i> </p>
                                            <p data-type="W" @if ($puja->pujrep_asigl1 != 'W')class="hidden" @endif><i class="fa fa-wikipedia-w" aria-hidden="true"></i> </p>
                                            <p data-type="O" @if ($puja->pujrep_asigl1 != 'O')class="hidden" @endif><i class="fa fa-desktop" aria-hidden="true"></i> </p>
                                            <p data-type="U" @if ($puja->pujrep_asigl1 != 'U')class="hidden" @endif><i class="fa fa-desktop" aria-hidden="true"></i> </p>

                                        </div>
                                        <div class="col-md-9 importePuja">
                                            <p>
                                            <?php if(!empty($data['js_item']['user']['is_gestor'])) { ?>
                                             <span class="licitadorPuja">({{ $puja->cod_licit }})</span>
                                            <?php } ?>
                                            <span>{{ $puja->formatted_imp_asigl1 }}</span> <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span></p>
                                        </div>
                                    </div>

                            <?php endforeach;?>

                            <div class="pujas_model hidden" id="type_bid_model">
                                <div class="col-lg-3 tipoPuja">
                                    <p data-type="I"><i class="fa fa-globe" aria-hidden="true"></i> </p>
                                    <p data-type="S" class="hidden"><i class="fa fa-hand-paper-o" aria-hidden="true"></i> </p>
                                    <p data-type="T" class="hidden"><i class="fa fa-phone" aria-hidden="true"></i> </p>
                                    <p data-type="E" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i> </p>
                                    <p data-type="P" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i></p>
                                    <p data-type="W" class="hidden"><i class="fa fa-wikipedia-w" aria-hidden="true"></i></p>
                                    <p data-type="O" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i></p>
                                    <p data-type="U" class="hidden"><i class="fa fa-desktop" aria-hidden="true"></i></p>

                                </div>
                                <div class="col-lg-9 importePuja">
                                    <p>
                                        <?php if(!empty($data['js_item']['user']['is_gestor'])) { ?>
                                            <span class="licitadorPuja"></span>
                                        <?php } ?>
                                        <span class="puj_imp"></span>
                                        <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span>
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
                    <h2>{{ trans($theme.'-app.sheet_tr.your_adjudications') }}</h2>
                    <div id="adjudicaciones_list">
                        @if (!empty($data['js_item']['user']) && !empty($data['js_item']['user']['adjudicaciones']))
                            <?php foreach ($data['js_item']['user']['adjudicaciones'] as $key => $val): ?>
                                <div class="adjudicaciones_model">
                                    <div class="col-lg-6 adj_ref">
                                        <p>{{ trans($theme.'-app.sheet_tr.lot') }}</i> <span>{{ $val->ref_asigl1 }}</span></p>
                                    </div>
                                    <div class="col-lg-6">
                                        <p><span class="adj_imp">{{ $val->imp_asigl1 }}</span> <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>



                        @endif
                        <div class="adjudicaciones_model hidden" id="type_adj_model">
                                <div class="col-lg-6 adj_ref">
                                    <p>{{ trans($theme.'-app.sheet_tr.lot') }}</i> <span></span></p>
                                </div>
                                <div class="col-lg-6">
                                    <p><span class="adj_imp"></span> <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span></p>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            @endif








    </div>


</div> <!-- row -->



<div class="row">
    <!-- Bloque 2 -->
    <div class="col-lg-12 col-xs-12">






    </div>
</div>




</div>

@stop
