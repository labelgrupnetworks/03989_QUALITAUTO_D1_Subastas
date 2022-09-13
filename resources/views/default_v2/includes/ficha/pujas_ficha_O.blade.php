<div id="reload_inf_lot" class="ficha-pujas ficha-pujas-o">

	{{-- Precio salida --}}
	<p class="price salida-price">
		<span>{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</span>
		<span>
			{{$lote_actual->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}

			@if(config("app.exchange"))
			| <span id="startPriceExchange_JS" class="exchange"> </span>
			@endif
		</span>
	</p>

	{{-- Estimación --}}
	@if(!empty($lote_actual->imptash_asigl0))
        <p class="price estimacion-price">
            <span>{{ trans(\Config::get('app.theme').'-app.lot.estimate') }}</span>
            <span>{{ \Tools::moneyFormat($lote_actual->imptash_asigl0)}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
				@if(\Config::get("app.exchange"))
				| <span id="estimateExchange_JS" class="exchange"> </span>
				@endif
			</span>
		</p>
	@endif

	{{-- Puja actual --}}
	<h4 id="text_actual_max_bid" @class([
		'price bid-price',
		'hidden' => count($lote_actual->pujas) == 0
	])>
		<span>{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</span>
		<span id="actual_max_bid" @class([
			'mine' => Session::has('user') && $lote_actual->max_puja && $lote_actual->max_puja?->cod_licit == $data['js_item']['user']['cod_licit'],
			'other' => Session::has('user') && $lote_actual->max_puja &&  $lote_actual->max_puja?->cod_licit != $data['js_item']['user']['cod_licit'],
		])>
			{{ $lote_actual->formatted_actual_bid }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}

			@if(config("app.exchange"))
				| <span id="actualBidExchange_JS" class="exchange"> </span>
			@endif
		</span>
	</h4>

	{{-- Sin pujas --}}
	<h5 id="text_actual_no_bid" @class(['hidden' => count($lote_actual->pujas) > 0])>
		{{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }}
	</h5>

	{{-- Reserva alcanzada  --}}
	<p @class(['price_minim_reached', 'hidden' => empty($lote_actual->impres_asigl0)])>
		<span>{{ trans(\Config::get('app.theme').'-app.subastas.price_minim') }}</span>
		<span class="precio_minimo_alcanzado hidden">{{ trans(\Config::get('app.theme').'-app.subastas.reached') }}</span>
		<span class="precio_minimo_no_alcanzado hidden">{{ trans(\Config::get('app.theme').'-app.subastas.no_reached') }}</span>
	</p>

	{{-- Siguiente puja --}}
	<p class="price next-price">
		<span>{{ $hay_pujas ? trans(\Config::get('app.theme').'-app.lot.next_min_bid') : trans(\Config::get('app.theme').'-app.lot.min_puja') }}</span>
		<span>
			<span class="siguiente_puja"></span>
			<span>&nbsp; {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>

			@if(\Config::get("app.exchange"))
				| <span id="nextBidExchange_JS" class="exchange"> </span>
			@endif

		</span>
	</p>

	{{-- inputs pujar --}}
	@if($start_session || $subasta_abierta_P)

        <div class="insert-bid-input mt-3">

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
				<div class="require-wallet">{!! trans(\Config::get('app.theme').'-app.lot.require_wallet') !!}</div>
			@else

				<p>Puja rapida</p>
				<div class="escalados-container d-flex justify-content-between gap-1">
					@foreach ($lote_actual->siguientes_escalados as $escalado)
					<button type="button" data-from="modal" data-escalado-position="{{$loop->index}}" value="{{$escalado}}"
						@class([
							'btn btn-lb-primary w-100 lot-action_pujar_on_line js-lot-action_pujar_escalado',
							'add_favs' => Session::has('user')
						])>

						<span value="{{$escalado}}" id="button-escalado">{{ \Tools::moneyFormat($escalado) }}</span>
						{{trans(\Config::get('app.theme').'-app.subastas.euros')}}
					</button>
					@endforeach
				</div>


				<p class="mt-2">{{ trans(\Config::get('app.theme').'-app.lot.insert_max_puja') }}</p>
				<div class="input-group">
					<input id="bid_amount" placeholder="{{ $data['precio_salida'] }}" class="form-control control-number" type="text" value="{{ $data['precio_salida'] }}" aria-describedby="button-bid">
					<span class="input-group-text currency-input">{{trans(\Config::get('app.theme').'-app.subastas.euros')}}</span>
					<button type="button" id="button-bid" data-from="modal"
						@class([
							'lot-action_pujar_on_line btn btn-lb-primary',
							'add_favs' => Session::has('user')
						])
						ref="{{ $lote_actual->ref_asigl0 }}" codsub="{{ $lote_actual->cod_sub }}">
						{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}
					</button>
				</div>
			@endif

		</div>
	@endif

	{{-- mi orden máxima --}}
    <p @class(['hist_new', 'hidden' => empty($data['js_item']['user']['ordenMaxima'])])>
		{{trans(\Config::get('app.theme').'-app.lot.max_puja')}}
		<strong>
			<span id="tuorden">
				@if (!empty($data['js_item']['user']['ordenMaxima']))
					@if (!empty($lote_actual->max_puja) &&   $lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit'])
						{{ $lote_actual->formatted_actual_bid }}
					@else
						{{ $data['js_item']['user']['ordenMaxima']}}
					@endif
				@endif
			</span>
			{{trans(\Config::get('app.theme').'-app.subastas.euros')}}
			@if(\Config::get("app.exchange"))
				|	<span  id="yourOrderExchange_JS" class="exchange"> </span>
			@endif
		</strong>
    </p>

	{{-- Packengers --}}
	@if (config('app.urlToPackengers'))
	@php
	$lotFotURL = $lote_actual->cod_sub . '-' . $lote_actual->ref_asigl0;
	$urlCompletePackengers = \Config::get('app.urlToPackengers') . $lotFotURL;
	@endphp

	<div class="mt-3">
		<a class="d-block btn btn-outline-lb-secondary" href="{{ $urlCompletePackengers }}" target="_blank">
			<svg class="bi" width="16" height="16" fill="currentColor">
				<use xlink:href="/bootstrap-icons.svg#truck"></use>
			</svg>
			{{ trans("$theme-app.lot.packengers_ficha") }}
		</a>
	</div>
	@endif

</div>

<?php //solo se debe recargar la fecha en las subatsas tipo Online, ne las abiertas tipo P no se debe ejecutar ?>
@if($subasta_online)
    <script>
        $(document).ready(function() {

            $("#actual_max_bid").bind('DOMNodeInserted', function(event) {
                if (event.type == 'DOMNodeInserted') {

                   $.ajax({
                        type: "GET",
                        url:  "/lot/getfechafin",
                        data: { cod: cod_sub, ref: ref},
                        success: function( data ) {
                            if (data.status == 'success'){
                               $(".timer").data('ini', new Date().getTime());
                               $(".timer").data('countdownficha',data.countdown);
                               $("#cierre_lote").html(format_date_large(new Date(data.close_at * 1000),''));
                            }
                        }
                    });
                }
            });
        });
    </script>
@endif


