@extends('layouts.tiempo_real')

@section('title')
{{ trans('web.head.title_app') }}
@stop

@section('content')
<link rel="stylesheet" href="{{ Tools::urlAssetsCache('/css/tiempo_real/tiempo_real_proyector.css') }}" />
<link rel="stylesheet" href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/css/tiempo_real/tiempo_real_proyector.css') }}" />
@include('includes.tr.tiempo_real_proyector.header')

<?php
# Fecha hasta
$horah = $data['subasta_info']->lote_actual->end_session;
$hastah = substr($data['subasta_info']->lote_actual->end_session, 0, 10);
$hastah = str_replace('-', '/', $hastah);
$fecha_finh = $hastah . $horah;
?>

<script>



    $(function () {

<?php
if($data['subasta_info']->status == 'stopped' || $data['subasta_info']->status == 'reload') {
    $tiempo = $data['subasta_info']->reanudacion;
} elseif ($data['subasta_info']->status != 'in_progress') {
    $tiempo = $data['subasta_info']->lote_actual->start_session;
} else {
    $tiempo = $data['subasta_info']->lote_actual->start_session;
    ?>
            $('#clock, button.start').hide();
            $(".logo").show();
            $(".subasta h3").show();

            $('.started').removeClass('hidden');

            // si aun no esta iniciada se verá la imagen en grande
            $('.colimagen').addClass('col-lg-6');
            $('.colimagen').removeClass('col-lg-12');
<?php } ?>

        $(document).ready(function () {
            $(".tiempo").data('ini', new Date().getTime());
            countdown_timer($(".tiempo"));
        });

<?php
# Subasta finalizada
if ($data['subasta_info']->status == 'ended') {
    ?>
            $('.tiempo').countdown('stop');
            $('.tiempo').html(messages.neutral.auction_end);
            $('button.start').hide();
<?php } ?>

    });
</script>

<div id="ficha"  class="ficha_tiempo_real">
    @include('includes.tr.tiempo_real_user.clock')
    @include('includes.tr.tiempo_real_proyector.content')
    @include('includes.tr.tiempo_real_proyector.info')
</div>


@stop
