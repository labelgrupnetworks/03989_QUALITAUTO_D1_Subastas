<div class="col-xs-12 mb-1">

	<div class="info_single_title info-type-auction-title">

		<div class="d-flex justify-content-space-between flex-wrap">
			@if(!$subasta_venta)

			<div class="date_top_side_small date-color-black">
				<span>{{ trans("$theme-app.lot.ending_date") }}</span>
				@if($subasta_venta)
				<span id="cierre_lote">{{ Tools::getDateFormat($timeCountdown, 'Y-m-d H:i:s', 'd/m/Y H:i') }}
					h</span>
				@else
				<span id="cierre_lote">{{ Tools::getDateFormat($timeCountdown, 'Y/m/d H:i:s', 'd/m/Y H:i') }}
					h</span>
				@endif
			</div>

				@if($cerrado_N && !empty($timeCountdown) && strtotime($timeCountdown) > getdate()[0])
				<div class=" ficha-info-clock">
					<span class="clock">
						<i class="fas fa-clock"></i>
						<span data-{{$nameCountdown}}="{{ strtotime($timeCountdown) - getdate()[0] }}"
							data-format="<?= \Tools::down_timer($timeCountdown); ?>" class="timer">
						</span>
					</span>
				</div>

				@elseif($cerrado)
				<div class="info-type-auction">{{ trans(\Config::get('app.theme').'-app.subastas.finalized') }}</div>
				@endif

			@endif

		</div>
	</div>

	<div class="d-flex gap-5 info_single_title info-type-auction-title flex-wrap">

		@if($subasta_online)
		<i class="fa fa-info-circle mr-1 js-modal"
			aria-hidden="true" data-title="{{ trans("$theme-app.subastas.lot_subasta_online") }}" data-content="{{ trans("$theme-app.subastas.lot_subasta_online_info") }}">
		</i>
		<div class="info-type-auction online">
			{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_online') }}
		</div>
		@elseif($subasta_web)
		<div class="info-type-auction web">
			{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_presencial') }}
		</div>
		@elseif($subasta_venta)

		<div class="info-type-auction venta">
			{{ trans("$theme-app.lot.counteroffer_btn") }}
		</div>
		@endif

		@if($subasta_venta)
		<a class="secondary-button similar-button clicVehiculosSimilares_JS" href="{{$urlSimilar}}">
			{{ trans("$theme-app.lot.similar_lots") }}
		</a>
		@endif

		@if(Session::has('user') && !$retirado)
		<a class="secondary-button d-flex align-items-center uppercase-text clicAnadirFavoritos_JS <?= $lote_actual->favorito? 'hidden':'' ?>" id="add_fav"
			href="javascript:action_fav_modal('add')">
			{{ trans(\Config::get('app.theme').'-app.lot.add_to_fav') }}
		</a>
		<a class="secondary-button d-flex align-items-center uppercase-text clicEliminarFavoritos_JS <?= $lote_actual->favorito? '':'hidden' ?>" id="del_fav"
			href="javascript:action_fav_modal('remove')">
			{{ trans(\Config::get('app.theme').'-app.lot.del_from_fav') }}
		</a>
		@endif

		@if($subasta_online)
		<a class="secondary-button similar-button" href="{{$urlSimilar}}">
			{{ trans("$theme-app.lot.similar_lots") }}
		</a>
		@endif

		<div class="title-ficha-wrapper">
			<p class="m-0 titleficha">{{ trans("$theme-app.lot.lot-name") }} {{ $lote_actual->ref_asigl0 }} </p>
		</div>
	</div>



</div>
