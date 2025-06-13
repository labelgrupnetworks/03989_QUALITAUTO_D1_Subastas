@php
	use App\Services\Auction\AuctionService;

    $cerrado = $lote_actual->cerrado_asigl0 == 'S';
    $cerrado_N = $lote_actual->cerrado_asigl0 == 'N';
    $hay_pujas = count($lote_actual->pujas) > 0;
    $devuelto = $lote_actual->cerrado_asigl0 == 'D';
    $remate = $lote_actual->remate_asigl0 == 'S';
    $compra = $lote_actual->compra_asigl0 == 'S';
    $subasta_online = $lote_actual->tipo_sub == 'P' || $lote_actual->tipo_sub == 'O';
    $subasta_venta = $lote_actual->tipo_sub == 'V';
    $subasta_web = $lote_actual->tipo_sub == 'W';
    $subasta_abierta_O = $lote_actual->subabierta_sub == 'O';
    $subasta_abierta_P = $lote_actual->subabierta_sub == 'P';
    $retirado = $lote_actual->retirado_asigl0 != 'N';
    $sub_historica = $lote_actual->subc_sub == 'H';
    $sub_cerrada = $lote_actual->subc_sub != 'A' && $lote_actual->subc_sub != 'S';
    $remate = $lote_actual->remate_asigl0 == 'S';
    $awarded = \Config::get('app.awarded');

    // D = factura devuelta, R = factura pedniente de devolver
    $fact_devuelta = $lote_actual->fac_hces1 == 'D' || $lote_actual->fac_hces1 == 'R';
    $fact_N = $lote_actual->fac_hces1 == 'N';
    $start_session = strtotime('now') > strtotime($lote_actual->start_session);
    $end_session = strtotime('now') > strtotime($lote_actual->end_session);
    $inicio_pujas = strtotime('now') > strtotime($lote_actual->fini_asigl0);
    $start_orders = strtotime('now') > strtotime($lote_actual->orders_start);
    $end_orders = strtotime('now') > strtotime($lote_actual->orders_end);

	$auctionService = new AuctionService();
	$isLastHistoryAuction = $sub_historica && $auctionService->isLastHistoryAuction($lote_actual->cod_sub);

@endphp

@if ($subasta_galeria)
    @include('includes.ficha.fichaGaleria')
@else
    @include('includes.ficha.fichaNormal')
@endif

@include('includes.ficha.modals_ficha')
