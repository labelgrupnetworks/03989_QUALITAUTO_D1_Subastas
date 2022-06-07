@extends('layouts.tiempo_real')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

@include('includes.tr.tiempo_real_user.header')

@if(\Config::get("app.exchange"))
	<script src="{{ URL::asset('js/default/divisas.js') }}"></script>

@endif


<?php
# Fecha hasta
$horah       = $data['subasta_info']->lote_actual->end_session;
$hastah      = substr($data['subasta_info']->lote_actual->end_session,0,10);
$hastah      = str_replace('-', '/', $hastah);
$fecha_finh  = $hastah.$horah;
$ministeryLicit = config('app.ministeryLicit', false);
?>

<script>

var ministeryLicit = @json($ministeryLicit);


<?php
if(\Config::get("app.exchange")){
/* Diferentes tipos e monedas */
		$currency = new App\libs\Currency();
		$divisas = $currency->getAllCurrencies($data['js_item']['subasta']['currency']->name);

}

?>
@if(\Config::get("app.exchange"))

	var currency = $.parseJSON('<?php  echo str_replace("\u0022","\\\\\"",json_encode($divisas,JSON_HEX_QUOT)); ?>');
@endif

<?php if(!empty($data['js_item']['user']['is_gestor'])){ ?>
    var licitadores = {
    <?php foreach ($data['licitadores'] as $key => $value) : ?>
            '<?php echo $key; ?>': '<?php echo str_replace("'", "",$value);?>',
    <?php endforeach; ?>
    };
<?php } ?>



$(function() {

<?php if($data['subasta_info']->status == 'stopped' || $data['subasta_info']->status == 'reload') {
        $tiempo =  $data['subasta_info']->reanudacion;
       ?>
          $('.tiempo_real')[0].style.position = "fixed";
        <?php
      } elseif($data['subasta_info']->status != 'in_progress') {
         $tiempo =  $data['subasta_info']->lote_actual->start_session;
		 ?>
          $('.tiempo_real')[0].style.position = "fixed";
        <?php
  }  else {
        $tiempo =  $data['subasta_info']->lote_actual->start_session;

  ?>
        $('#clock, button.start').hide();
        $(".logo").show();
        $(".subasta h3").show();

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

<div id="ficha" class="ficha_tr">

        @include('includes.tr.tiempo_real_user.clock')

        @include('includes.tr.tiempo_real_user.product')
        @include('includes.tr.tiempo_real_user.info')
        @include('includes.tr.tiempo_real_user.info_auction')
        @include('includes.tr.tiempo_real_user.streaming')

</div>

@stop
