@php
	$salePrice = $lote_actual->actual_bid;
	$pvp = Tools::moneyFormat($salePrice + $salePrice * ($lote_actual->comlhces_asigl0 / 100) * 1.21, trans("$theme-app.subastas.euros"), 2);
@endphp

<div class="info_single ficha_V col-xs-12">
    <div class="info_single_title col-xs-12">
        <div class="sub-o">

            {{-- entraran tanto lotes de subastas V como cerrados de otra con posibilidad de compra --}}
            <p>
                @if ($lote_actual->tipo_sub == 'V')
                    {{ trans($theme . '-app.subastas.lot_subasta_venta') }}
                @elseif($lote_actual->tipo_sub == 'W')
                    {{ trans($theme . '-app.subastas.lot_subasta_presencial') }}
                @elseif($lote_actual->tipo_sub == 'O')
                    {{ trans($theme . '-app.subastas.lot_subasta_online') }}
                @endif
            </p>

            @if ($lote_actual->cerrado_asigl0 == 'N')
                <span class="clock"><i class="fa fa-clock-o"></i><span
                        data-countdown="{{ strtotime($lote_actual->end_session) - getdate()[0] }}"
                        data-format="<?= \Tools::down_timer($lote_actual->end_session) ?>"
                        data-closed="{{ $lote_actual->cerrado_asigl0 }}" class="timer"></span>
            @endif
        </div>
        <div class="date_top_side_small">

            @if ($lote_actual->cerrado_asigl0 == 'N')
                <span class="cierre_lote"></span>
            @endif
            {{-- no ponemos CET   <span id="cet_o"> {{ trans($theme.'-app.lot.cet') }}</span> --}}

        </div>
    </div>

    <div class="col-xs-12 col-sm-6">
        <p class="pre">{{ trans($theme . '-app.subastas.price_sale') }}</p>

		<div class="pre">
			{{$pvp}}
			{{-- {{ $lote_actual->formatted_actual_bid }} {{ trans($theme . '-app.subastas.euros') }} --}}
		</div>

        <div class="info_single_content info_single_button">
            @if ($lote_actual->retirado_asigl0 == 'N' && empty($lote_actual->himp_csub) && ($lote_actual->subc_sub == 'S' || $lote_actual->subc_sub == 'A'))
				@if (\Session::has('user'))
					@php
						$userController = new \App\Http\Controllers\UserController();
						$ccAndCIFverif = $userController->getCreditCardAndCIFImages(\Session::get('user.cod'));
					@endphp
					@if ($userController->getCreditCardAndCIFImages(\Session::get('user.cod')))
						<button data-from="modal" class="lot-action_comprar_lot btn btn-lg btn-custom" type="button"
							ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">
							<i class="fa fa-shopping-cart" aria-hidden="true"></i>
							{{ trans($theme . '-app.subastas.buy_lot') }}
						</button>
					@endif
				@else
					<button type="button" data-from="modal"
						class="lot-action_pujar_on_line btn btn-lg btn-custom <?= Session::has('user') ? 'add_favs' : '' ?>"
						type="button" ref="{{ $lote_actual->ref_asigl0 }}" ref="{{ $lote_actual->ref_asigl0 }}"
						codsub="{{ $lote_actual->cod_sub }}">
							<i class="fa fa-shopping-cart" aria-hidden="true"></i>
							{{ trans($theme . '-app.subastas.buy_lot') }}
						</button>
				@endif
            @endif
        </div>

    </div>
    <div class="col-xs-12 col-sm-6">

        <p class="cat">{{ trans($theme . '-app.lot.categories') }}</p>
        <?php
        $category = new \App\Models\Category();
        $tipo_sec = $category->getSecciones($data['js_item']['lote_actual']->sec_hces1);
        ?>
        <p>
            @foreach ($tipo_sec as $sec)
                {{ $sec->des_tsec }}
            @endforeach
        </p>
        <p class="shared">{{ trans($theme . '-app.lot.share_lot') }}</p>
        @include('includes.ficha.share')

    </div>
	@if (\Session::has('user'))
		@if (!$ccAndCIFverif)
			<div class="col-xs-12">
				<p class="color-red">{!! trans("$theme-app.lot.info_no_cc_and_cif") !!}</p>
			</div>
		@endif
	@endif
    <div class="col-xs-12">
		<p style="margin-top: 0.5rem; margin-bottom: 0">{{ trans("$theme-app.lot.amount_with_commission") }}</p>
        <div class="col-xs-12">
        	<?php
        		$lotFotURL = $lote_actual->cod_sub . '-' . $lote_actual->ref_asigl0;
        		$urlCompletePackengers = \Config::get('app.urlToPackengers') . $lotFotURL;
        	?>
			@if (\Config::get('app.urlToPackengers'))
				<div class="packengers-container-button-ficha">
					<a class="packengers-button-ficha" href="{{ $urlCompletePackengers }}" target="_blank">
						<i class="fa fa-truck" aria-hidden="true"></i>
						{{ trans("$theme-app.lot.packengers-ficha") }}
					</a>
				</div>
			@endif
		</div>
    </div>

    <div class="col-xs-12">
        <div class="checkbox">
            <label for="recibir-newletter">
                <?= trans($theme . '-app.subastas.read_conditions') ?>
            </label>
        </div>
    </div>


</div>

<script>
    $(document).ready(function() {
        //calculamos la fecha de cierre
        //   $("#cierre_lote").html(format_date_large(new Date("{{ $lote_actual->end_session }}".replace(/-/g, "/"))),"");
        $(".cierre_lote").html(format_date_large(new Date("{{ $lote_actual->end_session }}".replace(/-/g, "/")), ''));

    });
</script>
