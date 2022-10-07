

<div  class="col-lg-12 col-md-12 info-ficha-buy-info no-padding">
	@if ($lote_actual->ocultarps_asigl0 != 'S')
		<div class=" col-xs-12 no-padding info-ficha-buy-info-price ">

				<div class="pre d-flex justify-content-space-between align-items-center">
					<p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
					<p class="pre-price">{{$lote_actual->formatted_impsalhces_asigl0}}  {{ trans(\Config::get('app.theme').'-app.subastas.euros') }} </p>

				</div>

		</div>
	@endif
</div>


<div class="info_single col-xs-12 ficha-puja no-padding">
	<div class="col-lg-12 no-padding">
		<div class="info_single_title mt-2 hist_new <?= !empty($data['js_item']['user']['ordenMaxima'])?'':'hidden'; ?> ">
		{{trans(\Config::get('app.theme').'-app.lot.max_puja')}}
			<strong><span id="tuorden">
				@if ( !empty($data['js_item']['user']['ordenMaxima']))
					{{ $data['js_item']['user']['ordenMaxima']}}
				@endif
				</span>

				{{trans(\Config::get('app.theme').'-app.subastas.euros')}}

			</strong>
			<?php
				#lo quito por que han pedido que esté en el panel de usuario
				//<input style="float: right;margin-top: -10px;" class="btn btn-danger delete_order" type="button" ref="{{$data['subasta_info']->lote_actual->ref_asigl0}}" sub="{{$data['subasta_info']->lote_actual->cod_sub}}" value="{{ trans(\Config::get('app.theme').'-app.user_panel.delete_orden') }}">
			?>
		</div>
	</div>
</div>


@if( $cerrado_N && $fact_N && $start_session  &&  !$end_session )


	<div class="col-xs-12 no-padding">
		<div class="ficha-live-btn-content subasta-presencial d-flex align-items-center justify-content-space-between mt-2">
			<a class="ficha-live-btn-link secondary-button col-xs-6" href='{{\Tools::url_real_time_auction($data['subasta_info']->lote_actual->cod_sub,$data['subasta_info']->lote_actual->name,$data['subasta_info']->lote_actual->id_auc_sessions)}}'>
				<div class="bid-online"></div>
				<div class="bid-online animationPulseRed"></div>
				<?=trans(\Config::get('app.theme').'-app.lot.bid_live')?>
			</a>
		</div>
	</div>


@else




<div class="ficha-info-item-for-pay col-xs-12 no-padding">
    <div class="info_single_content">
        <?php  //las subastas abiertas tipo P se veran como W cuando empiece la subasta, pero controlamos que no sep uedan hacer ordenes (!$subasta_abierta_P) ?>
        @if( $cerrado_N && $fact_N &&  $start_orders  &&   !$end_orders && !$subasta_abierta_P)
            <div ><p class="pt-1 pre-title  pb-1"><?=trans(\Config::get('app.theme').'-app.lot.deja_puja')?> </p></div>
            <div class="input-group group-pujar-custom d-flex justify-content-space-between">
				<div class="col-xs-7 no-padding">
                    <input id="bid_modal_pujar" placeholder="{{ $data['precio_salida'] }}" class="form-control control-number" value="{{ $data['precio_salida'] }}" type="text">
				</div>
				<div class="col-xs-5 no-padding text-right">
					<button id="pujar_ordenes_w" data-from="modal" type="button" class="ficha-btn-bid button-principal fondo_azul" ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}</button>
				</div>
			</div>

			@if( $lote_actual->ordentel_sub !=0 && $lote_actual->ordentel_sub <=  $lote_actual->impsalhces_asigl0)
				<div class="input-group-btn ">
					<button id="pujar_orden_telefonica_duran" data-from="modal" type="button" class="ficha-btn-telephone-bid  button-principal hidden" ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">{{ trans(\Config::get('app.theme').'-app.lot.puja_telefonica') }}</button>
					<a href="javascript:;" data-toggle="modal" data-target="#modalAjax" class="info-ficha-lot pt-1 c_bordered" data-ref="{{ Routing::translateSeo('pagina')."info-pujas-presencial"  }}?modal=1" data-title="{{ trans(\Config::get('app.theme').'-app.lot.title_info_pujas') }}"><i class="fas fa-info-circle"></i></a>
				</div>
			@endif

        @endif
    </div>
</div>

<script>
	$(document).ready(function(){
		$.ajax({
			type: "POST",
			url: "/verBotonOrdenTelefonica",
			data: {
				cod_sub: "{{$lote_actual->cod_sub}}",
				ref: "{{$lote_actual->ref_asigl0}}",
				_token: $("input[name=_token]").val()
			},

			success: function (data) {
				if (data.status == 'success') {
					$('#pujar_orden_telefonica_duran').removeClass('hidden');
				}
			}

		});
	});
</script>

@endif
