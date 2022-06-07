<div class="info_single ficha_V col-xs-12">
	<div class="info_single_title col-xs-12">
		<div class="sub-o">

			<?php // entraran tanto lotes de subastas V como cerrados de otra con posibilidad de compra  ?>

			<p>
				@if ($lote_actual->tipo_sub == 'V')
				{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_venta') }}
				@elseif($lote_actual->tipo_sub == 'W')
				{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_presencial') }}
				@elseif($lote_actual->tipo_sub == 'O')
				{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_online') }}
				@endif
			</p>


		</div>
		<div class="date_top_side_small">


			<?php /* no ponemos CET   <span id="cet_o"> {{ trans(\Config::get('app.theme').'-app.lot.cet') }}</span> */ ?>
		</div>
	</div>

	<div class="col-xs-12 col-sm-6">
		<p class="pre">{{ trans(\Config::get('app.theme').'-app.lot.total_pagar') }}</p>
		{{-- Precio con comision + iva de la comision --}}
		<div class="pre">
			@if($lote_actual->sub_hces1 == 'VDJ')
			{{ \Tools::moneyFormat($lote_actual->impsalhces_asigl0, " €", 2) }}
			@else
			{{\Tools::moneyFormat($lote_actual->impsalhces_asigl0 + ($lote_actual->impsalhces_asigl0 * ($lote_actual->comlhces_asigl0/100) * 1.21)," €",2  )}}
			@endif

		</div>
	</div>
	<div class="col-xs-12 col-sm-6">
		@if(Session::has('user') && $lote_actual->retirado_asigl0 =='N')
		<a class="btn {{ $lote_actual->favorito ? 'hidden' : '' }}" id="add_fav"
			href="javascript:action_fav_modal('add')">
			<i class="fa fa-heart-o" aria-hidden="true"></i>
		</a>
		<a class="btn {{ $lote_actual->favorito ? '' : 'hidden' }}" id="del_fav"
			href="javascript:action_fav_modal('remove')">
			<i class="fa fa-heart" aria-hidden="true"></i>
		</a>
		@endif
	</div>

	<div class="col-xs-12">
		<div class="info_single_content info_single_button">
			@if ($lote_actual->retirado_asigl0 == 'N' && empty($lote_actual->himp_csub) && ($lote_actual->subc_sub ==
			'S' || $lote_actual->subc_sub == 'A'))
			<button data-from="modal" class="lot-action_comprar_lot btn btn-lg btn-custom" type="button"
				ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}"
				codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}"><i class="fa fa-shopping-cart"
					aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</button>
			@endif
		</div>
	</div>

	<div class="col-xs-12 mb-1" style=" margin-top:10px ; font-size: 12px;">
		@if($lote_actual->sub_hces1 == 'VDJ')
			{!! trans(\Config::get('app.theme').'-app.lot.price_commission_vdj') !!}
		@else
			<?= trans(\Config::get('app.theme').'-app.lot.price_commission',array("precio_salida" =>\Tools::moneyFormat($lote_actual->impsalhces_asigl0), "comision" =>\Tools::moneyFormat($lote_actual->impsalhces_asigl0 * ($lote_actual->comlhces_asigl0/100)),  "iva_comision" =>\Tools::moneyFormat($lote_actual->impsalhces_asigl0 * ($lote_actual->comlhces_asigl0/100) *0.21,"",2)  )) ?>
		@endif

	</div>

</div>

<script>
	$(document).ready(function() {
        //calculamos la fecha de cierre
     //   $("#cierre_lote").html(format_date_large(new Date("{{$lote_actual->end_session}}".replace(/-/g, "/"))),"");
        $(".cierre_lote").html(format_date_large(new Date("{{$lote_actual->end_session}}".replace(/-/g, "/")),''));

    });
</script>
