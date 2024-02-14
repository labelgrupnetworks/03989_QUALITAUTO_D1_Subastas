{{-- Tambien los usa la subasta inversa --}}
<div class="insert-bid-input">

	@if (Session::has('user') &&  Session::get('user.admin'))
	<div class="mb-3">
		<input id="ges_cod_licit" name="ges_cod_licit" class="form-control" type="text" value="" type="text" style="border: 1px solid red;" placeholder="Código de licitador">
		@if ($subasta_abierta_P)
			<input type="hidden" id="tipo_puja_gestor" value="abiertaP" >
		@endif
	</div>
	@endif

	{{-- Si el lote es NFT y el usuario está logeado pero no tiene wallet --}}
	@if ($lote_actual->es_nft_asigl0 == "S" &&  !empty($data["usuario"])  && empty($data["usuario"]->wallet_cli) )
		<div class="require-wallet">{!! trans($theme.'-app.lot.require_wallet') !!}</div>
	@else

		<p>{{ trans("$theme-app.lot.quick_bid") }}</p>
		<div class="escalados-container d-flex justify-content-between gap-1">
			@foreach ($lote_actual->siguientes_escalados as $escalado)
			<button type="button" data-from="modal" data-escalado-position="{{$loop->index}}" value="{{$escalado}}"
				@class([
					'btn btn-lb-primary w-100 lot-action_pujar_on_line js-lot-action_pujar_escalado',
					'add_favs' => Session::has('user')
				])>

				<span value="{{$escalado}}" id="button-escalado">{{ \Tools::moneyFormat($escalado) }}</span>
				{{trans($theme.'-app.subastas.euros')}}
			</button>
			@endforeach
		</div>


		<p class="mt-4">{{ trans($theme.'-app.lot.insert_max_puja') }}</p>
		<div class="input-group">
			<input id="bid_amount" placeholder="{{ $data['precio_salida'] }}" class="form-control control-number" type="text" value="{{ $data['precio_salida'] }}" aria-describedby="button-bid">
			<span class="input-group-text currency-input">{{trans($theme.'-app.subastas.euros')}}</span>
			<button type="button" id="button-bid" data-from="modal"
				@class([
					'lot-action_pujar_on_line btn btn-lb-primary',
					'add_favs' => Session::has('user')
				])
				ref="{{ $lote_actual->ref_asigl0 }}" codsub="{{ $lote_actual->cod_sub }}">
				{{ trans($theme.'-app.lot.pujar') }}
			</button>
		</div>
	@endif

</div>

