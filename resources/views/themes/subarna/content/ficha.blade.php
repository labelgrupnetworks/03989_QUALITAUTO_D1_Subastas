@php
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

    $start_orders = strtotime('now') > strtotime($lote_actual->orders_start);
    $end_orders = strtotime('now') > strtotime($lote_actual->orders_end);

    $auctionName = match ($lote_actual->tipo_sub) {
        'P' => trans("$theme-app.foot.online_auction"),
        'V' => trans("$theme-app.subastas.lot_subasta_venta"),
        'W' => trans("$theme-app.subastas.lot_subasta_presencial"),
        'O' => trans("$theme-app.subastas.lot_subasta_online"),
        default => trans("$theme-app.subastas.lot_subasta_presencial"),
    };
    $dateFormat = Tools::getDateFormatDayMonthLocale($lote_actual->start_session);

@endphp


<div class="ficha-content container-fluid">
    <div class="ficha-grid" data-tipe-sub="{{ $lote_actual->tipo_sub }}">

        {{-- image --}}
        <section class="ficha-image">
            @include('includes.ficha.ficha_image')
        </section>

        {{-- minatures --}}
        <section class="ficha-miniatures">
            @include('includes.ficha.ficha_miniatures')
        </section>

        {{-- title --}}
        <section class="ficha-title d-flex">
			<div class="">
				<p class="ficha_auction-type">
					{{ $auctionName . ' - ' . $dateFormat }}
				</p>
				<h1>
					{!! strip_tags($lote_actual->descweb_hces1) !!}
				</h1>
			</div>

			@if (Session::has('user') && $lote_actual->retirado_asigl0 == 'N')
				<div class="date_top_side_small">
					<button id="add_fav" @class(['btn', 'hidden' => $lote_actual->favorito]) onclick="action_fav_modal('add')">
						<i class="fa fa-heart-o" aria-hidden="true"></i>
					</button>
					<button id="del_fav" @class(['btn', 'hidden' => !$lote_actual->favorito]) onclick="action_fav_modal('remove')">
						<i class="fa fa-heart" aria-hidden="true"></i>
					</button>
				</div>
			@endif
        </section>

        {{-- block pujas --}}
        <section class="ficha-pujas">
            @include('includes.ficha.ficha_pujas')
        </section>

        {{-- description --}}
        <section class="ficha-desciption">
            <h2>{{ trans("$theme-app.lot.description") }}</h2>
            <p>
                {!! $lote_actual->desc_hces1 !!}
            </p>
        </section>
    </div>

    {{-- recommended lots --}}
    @php
        $replace = [
            'emp' => Config::get('app.emp'),
            'sec_hces1' => $lote_actual->sec_hces1,
            'id_hces1' => $lote_actual->id_hces1,
            'lang' => Config::get('app.language_complete')['' . Config::get('app.locale') . ''],
        ];
    @endphp
    <section class="lotes_destacados">
        <h2>{{ trans("$theme-app.lot.recommended_lots") }}</h2>
        <div class='loader hidden'></div>
        <div class="owl-theme owl-carousel" id="lotes_recomendados"></div>

        <script>
            const replace = @json($replace);
            $(document).ready(function() {

                ajax_carousel("lotes_recomendados", replace);

            });
        </script>
    </section>
</div>

@include('includes.ficha.modals_ficha')
