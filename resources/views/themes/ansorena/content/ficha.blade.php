@php

$cerrado = $lote_actual->cerrado_asigl0 == 'S'? true : false;
$cerrado_N = $lote_actual->cerrado_asigl0 == 'N'? true : false;
$hay_pujas = count($lote_actual->pujas) >0? true : false;
$devuelto= $lote_actual->cerrado_asigl0 == 'D'? true : false;
$remate = $lote_actual->remate_asigl0 =='S'? true : false;
$compra = $lote_actual->compra_asigl0 == 'S'? true : false;
$subasta_online = ($lote_actual->tipo_sub == 'P' || $lote_actual->tipo_sub == 'O')? true : false;
$subasta_venta = $lote_actual->tipo_sub == 'V' ? true : false;
$subasta_web = $lote_actual->tipo_sub == 'W' ? true : false;
$subasta_abierta_O = $lote_actual->subabierta_sub == 'O'? true : false;
$subasta_abierta_P = $lote_actual->subabierta_sub == 'P'? true : false;
$retirado = $lote_actual->retirado_asigl0 !='N'? true : false;
$sub_historica = $lote_actual->subc_sub == 'H'? true : false;
$sub_cerrada = ($lote_actual->subc_sub != 'A'  && $lote_actual->subc_sub != 'S')? true : false;
$remate = $lote_actual->remate_asigl0 =='S'? true : false;
$awarded = \Config::get('app.awarded');
// D = factura devuelta, R = factura pedniente de devolver
$fact_devuelta = ($lote_actual->fac_hces1 == 'D' || $lote_actual->fac_hces1 == 'R') ? true : false;
$fact_N = $lote_actual->fac_hces1=='N' ? true : false;
$start_session = strtotime("now") > strtotime($lote_actual->start_session);
$end_session = strtotime("now")  > strtotime($lote_actual->end_session);
$inicio_pujas = strtotime("now") > strtotime($lote_actual->fini_asigl0);

    $start_orders = strtotime('now') > strtotime($lote_actual->orders_start);
    $end_orders = strtotime('now') > strtotime($lote_actual->orders_end);

@endphp

@if ($subasta_galeria)
    @include('includes.ficha.fichaGaleria')
@else
    @include('includes.ficha.fichaNormal')
@endif

@include('includes.ficha.modals_ficha')
