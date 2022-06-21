<?php
	# al carrousel no deberían llegar lotes cerrados, devueltos, retirados ni Ocultos
//	dd($bann);
    $url = \Tools::url_lot($bann->sub_asigl0,$bann->id_auc_sessions,$bann->name,$bann->ref_asigl0,$bann->num_hces1,$bann->webfriend_hces1,$bann->titulo_hces1);
    $titulo = "$bann->titulo_hces1";
    $hay_pujas = !empty($bann->max_puja)? true : false;
    $subasta_online = ($bann->tipo_sub == 'P' || $bann->tipo_sub == 'O')? true : false;
    $subasta_abierta_P = (!empty($bann->subabierta_sub) && $bann->subabierta_sub == 'P') ? true : false;
    $description = !empty($bann->desc_hces1) ? true : false;
    $cerrado = $bann->cerrado_asigl0 == 'S'? true : false;
	$closeAt = !empty($bann->close_at) ? $bann->close_at : null;
?>

<div style="position: relative; padding: 0 10px;">

	<div class="item_home">

		<a class="lote-destacado-link secondary-color-text" @if(!$cerrado) href="{{ $url }}" @endif>
			<div class="border_item_img">
				<div class="item_img">

					<div data-loader="loaderDetacados" class='text-input__loading--line'></div>
					<img class="lazy" data-src="{{Tools::url_img('lote_medium',$bann->num_hces1,$bann->lin_hces1)}}">
				</div>
			</div>


			<div class="title_item text-left mt-3">
				<span class="seo_h4 text-left">{{ trans("$theme-app.lot.lot-name") }} {{ $bann->ref_asigl0 }} - {!! $bann->titulo_hces1 ?? $bann->descweb_hces1 ?? '' !!}</span>
			</div>
		</a>

		@if($description)
		<div class="description-carrousel text-center desc_lot">{!! str_replace('Referencia', '<br><br><br>', $bann->desc_hces1) !!}</div>
		@endif


		<div class="data-price">

			<div class="row">
				<div class="salida col-xs-12 text-center mt-2" style="height: 60px;">


					@if($bann->impsalhces_asigl0 > 0 && $bann->tipo_sub != 'V' && !$cerrado)
					<p class="salida-title mb-0">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
					<div class="salida-title mt-1 letter-price-salida">
						{{\Tools::moneyFormat( $bann->imptas_asigl0, false, 2) }}
						{{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</div>
					@endif

				</div>

			</div>

			<a class="lote-destacado-link secondary-color-text" @if(!$cerrado) href="{{ $url }} @endif">
				<p class="mt-2 d-flex align-items justify-content-center btn-pujar-itemhome">

					<span class="button-principal carousel-pujar @if($cerrado) btn-red @endif">
						@if($subasta_online && strtotime($bann->start_session) > time())
						{{ trans(\Config::get('app.theme').'-app.subastas.proximamente') }}
						@elseif($subasta_online && !$cerrado)
						{{ trans(\Config::get('app.theme').'-app.lot.place_bid') }}
						@elseif(!$cerrado)
						{{ trans(\Config::get('app.theme').'-app.subastas.enter_lot') }}
						@else
						{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}
						@endif
					</span>
				</p>
			</a>

			<p>
				@if($subasta_online && !$cerrado)
					<span data-countdown="{{strtotime($closeAt) - getdate()[0] }}" data-format="<?= \Tools::down_timer($closeAt); ?>" class="timer"></span>
				@endif
			</p>

		</div>

	</div>


</div>
