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



?>

<script>
<?php if(!empty($data['js_item']['user']['is_gestor'])){ ?>
var licitadores = {
    <?php foreach ($data['licitadores'] as $key => $value) : ?> '<?php echo $key; ?>': '<?php echo str_replace("'", "",$value);?>',
    <?php endforeach; ?>
};
<?php } ?>

<?php
//Sonidos
if(\config::get("app.always_alert_bid")){
    echo "var alert_bid = true;";
}

//Notificación de sobrepujas, en true no se muestran
if(\config::get("app.higher_bid_tr")){
    echo "var higher_bid_notshow = true";
}
?>

<?php

    /* Diferentes tipos e monedas */
        use App\libs\Currency;
        $currency = new Currency();
        $divisas = $currency->getAllCurrencies()
    ?>

var currency = $.parseJSON('<?php  echo str_replace("\u0022","\\\\\"",json_encode($divisas,JSON_HEX_QUOT)); ?>');




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

        $("#actual_currency").change(function(){
            changeCurrency(auction_info.lote_actual.actual_bid,$(this).val(),"impsalexchange-actual");
            changeCurrency( auction_info.lote_actual.importe_escalado_siguiente,$(this).val(),"impsalexchange-next");
        })
         changeCurrency({{ $data['subasta_info']->lote_actual->actual_bid }},$("#actual_currency").val(),"impsalexchange-actual");
         changeCurrency({{ $data['subasta_info']->lote_actual->importe_escalado_siguiente }},$("#actual_currency").val(),"impsalexchange-next");

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
body,
html {
    margin: 0;
    padding: 0;
}

.fondo1 {
    /*border: 1px solid black;*/
    height: 451px;
}

.img-lot {
    /*max-width: 500px*/
    ;
    max-height: 395px;
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
                    <img style="display: block;max-width: 300px; margin: 0 auto"
                        src="/themes/{{\Config::get('app.theme')}}/assets/img/logo.png"
                        alt="{{(\Config::get( 'app.name' ))}}">
                </div>
                <div data-countdown="{{strtotime($tiempo) - getdate()[0] }}"
                    data-format="%D {{trans(\Config::get('app.theme')."-app.msg_neutral.days")}} <br> %H:%M:%S  {{trans(\Config::get('app.theme')."-app.msg_neutral.hours")}}"
                    data-txtend="{{trans(\Config::get('app.theme')."-app.msg_neutral.auction_coming_soon")}}"
                    class="tiempo wait-time text-center"></div>
            </div>
            @if(!empty($data['js_item']['user']['is_gestor']))
            <div class="botonclock">
                <button class="btn btn-primary btn-lg start" data-to="iniciar_subasta"
                    start='1'>{{ trans(\Config::get('app.theme').'-app.sheet_tr.start_auction') }}</button>
            </div>
            @endif
             @if(Session::has('user') && $data['js_item']['user']['is_gestor'])
                @include('pages.ficha_tiempo_real_admin')
            @else
                @include('pages.ficha_tiempo_real_user')
            @endif




        </div>
    </div>

</div>


@stop
