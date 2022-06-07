<div class="col-xs-12 info_single">
	<div class="info_single_title col-xs-12">
		<div class="sub-o sub-w">


			@if ($lote_actual->tipo_sub == 'P')
			<p class="sub-title">{{ trans(\Config::get('app.theme').'-app.foot.online_auction') }}</p>
			@else
			<p class="sub-title">{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_presencial') }}</p>
			@endif

			@if($cerrado_N && $subasta_online && !empty($lote_actual->close_at) && strtotime($lote_actual->close_at) > getdate()[0])
				<span class="clock text-right">

					<span
						data-countdownficha="{{ strtotime($lote_actual->close_at) - getdate()[0] }}"
						data-format="<?= \Tools::down_timer($lote_actual->close_at); ?>"
						class="timer">
					</span>
				</span>
			@elseif(!$subasta_venta)
			<span class="clock text-right">
				<span
					data-countdown="{{ strtotime($lote_actual->start_session) - getdate()[0] }}"
					data-format="<?= \Tools::down_timer($lote_actual->start_session); ?>"
					data-closed="{{ $lote_actual->cerrado_asigl0 }}" class="timer"></span>
			</span>
			@endif



		</div>
		<div class="date_top_side_small">
			@if (!$subasta_venta)
				<span style="font-weight: 500">{{ trans(\Config::get('app.theme').'-app.lot.closing_date') }} </span>
				<span class="cierre_lote"></span>
			@endif

			<?php
			//dd($data['subasta_info']->lote_actual);
			 /* no ponemos CET   <span id="cet_o"> {{ trans(\Config::get('app.theme').'-app.lot.cet') }}</span> */ ?>

			@if(Session::has('user') &&  $lote_actual->retirado_asigl0 =='N')
			<a  class="btn {{ $lote_actual->favorito ? 'hidden' : '' }}" id="add_fav" href="javascript:action_fav_modal('add')">
				<i class="fa fa-heart-o" aria-hidden="true"></i>
			</a>
			<a class="btn {{ $lote_actual->favorito ? '' : 'hidden' }}" id="del_fav" href="javascript:action_fav_modal('remove')">
				<i class="fa fa-heart" aria-hidden="true"></i>
			</a>
			@endif

		</div>
	</div>
	<div class="col-xs-10 col-sm-6 exit-price p-0">
		@if( \Config::get('app.estimacion'))
		<div>
		<span class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.estimate') }}</span>
		<span class="pre">
			<b>
			{{$lote_actual->formatted_imptas_asigl0}} - {{$lote_actual->formatted_imptash_asigl0}}
			{{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
			</b>
		</span>
		</div>
		@elseif( \Config::get('app.impsalhces_asigl0'))
		<div>
			<span class="pre">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</span>
			<span class="pre">
				<b>{{$lote_actual->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</b>
			</span>
		</div>
		<div class="mt-1">
			<span class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.estimate') }}</span>
			<span class="pre">
			<b>
			{{--$lote_actual->formatted_imptas_asigl0 - --}} {{$lote_actual->formatted_imptash_asigl0}}
			{{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
			</b>
			</span>
		</div>

		@endif
	</div>
	<div class="col-xs-12 col-sm-6 actual-bid-container">
		<div id="text_actual_max_bid" class="{{count($lote_actual->pujas) >0 ? '' : 'hidden' }}">
			<div>{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</div>
			<b><span id="actual_max_bid" >{{ $lote_actual->formatted_actual_bid }} €</span></b>
		</div>
		<div id="text_actual_no_bid" class="{{count($lote_actual->pujas) > 0 ? 'hidden' : '' }}">
			<p>{{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }} </p>
		</div>

	</div>
	<div class="col-xs-12 separator-ficha"></div>
</div>

<script>

	var is_subasta_online = {{ !empty($subasta_online) ? "true" : "false" }};

	$(document).ready(function() {
        //calculamos la fecha de cierre
		if(is_subasta_online){
			$('.cierre_lote').text(new Date("{{ $lote_actual->close_at }}").toLocaleDateString('es-ES', {timeZoneName:"short", year: 'numeric', month: 'numeric', day: 'numeric', hour:'numeric', minute:'numeric'}));
		}
		else{
			$(".cierre_lote").html(format_date_large(new Date("{{ $lote_actual->start_session }}".replace(/-/g, "/")),'{{ trans(\Config::get('app.theme').'-app.lot.from') }}'));
		}
    });
</script>


