<div id="reload_inf_lot" class="col-xs-12 info-ficha-buy-info no-padding">

	<p class="ficha-size-lot">
		@if(!empty($lote_actual->nobj_hces1))

		{{ trans("$theme-app.lot_list.pices_at_auction") }}
		{{ $lote_actual->nobj_hces1 }} {{ trans("$theme-app.lot.pieces") }}
		@else
		{{ trans("$theme-app.lot_list.meters_at_auction") }}
		{{ str_replace('.', ',', $lote_actual->ancho_hces1) }} m<sup>2</sup></p>
		@endif
	</p>



	<!-- ------------------------ PRECIO SALIDA ------------------------ -->
    <div class="col-xs-12 no-padding info-ficha-buy-info-price d-flex">

		@if ($lote_actual->ocultarps_asigl0 != 'S')
		<div class="pre">
			<p class="pre-title">{{ trans(\Config::get('app.theme') . '-app.lot.lot-price') }}</p>
			<p class="pre-price lotprice">
				{{ $lote_actual->formatted_impsalhces_asigl0 }}
				{{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}
				<span class="lower-case">{{ trans("$theme-app.lot.lot-name") }}</span>
				@if (\Config::get('app.exchange'))
					| <span id="startPriceExchange_JS" class="exchange"> </span>
				@endif
			</p>
		</div>
		@endif

		@if (!empty($lote_actual->nobj_hces1))
			<div class="pre">
				<p class="pre-price">
					<span>{{ \Tools::moneyFormat($lote_actual->impsalhces_asigl0 / $lote_actual->nobj_hces1, false, 2) }}</span>
					<span>{{ trans(\Config::get('app.theme') . '-app.subastas.euros') }} {!! trans("$theme-app.lot.piece") !!}</span>
				</p>
			</div>
        @elseif (!empty($lote_actual->ancho_hces1))
            <div class="pre">
                <p class="pre-price">
                    <span>{{ \Tools::moneyFormat($lote_actual->impsalhces_asigl0 / $lote_actual->ancho_hces1, false, 2) }}</span>
                    <span>{{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}{!! trans("$theme-app.lot.m2") !!}</span>
                </p>
            </div>
        @endif

    </div>

	<!-- ------------------------ PUJA ACTUAL ------------------------ -->
    <div id="text_actual_max_bid"
        class="col-xs-12 no-padding info-ficha-buy-info-price d-flex @if (!count($lote_actual->pujas)) hidden @endif">

        <div class="pre pre-actual_max_bid">
            <p class="pre-title">{{ trans(\Config::get('app.theme') . '-app.lot.puja_actual') }}</p>
            <p class="pre-price lotprice">
                <strong>
                    {{-- aparecera en rojo(clase other) si no eres el ganador y en verde si loeres (clase mine) , si no estas logeado no se modifica el color --}}
                    @if (Session::has('user'))
                        @php($class = !empty($lote_actual->max_puja) && $lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit'] ? 'mine' : 'other')
                    @endif

                    <span id="actual_max_bid" class="{{ $class ?? '' }}">{{ $lote_actual->formatted_actual_bid }}
                        {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}
					</span>
                    <span class="status_bid {{ $class ?? '' }} lower-case"> {{ trans("$theme-app.lot.lot-name") }}</span>
                    @if (\Config::get('app.exchange'))
                        | <span id="actualBidExchange_JS" class="exchange"> </span>
                    @endif

                </strong>
            </p>
        </div>

		@if (!empty($lote_actual->nobj_hces1) && !empty($lote_actual->actual_bid))
		<div class="pre">
			<p class="pre_min">
				<strong>
					<span id="acutalPricePerpiece">
						{{ \Tools::moneyFormat($lote_actual->actual_bid / $lote_actual->nobj_hces1, false, 2) }}
					</span>
					<span>
						{{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}
					</span>
					{!! trans("$theme-app.lot.piece") !!}
				</strong>
			</p>
		</div>

        @elseif (!empty($lote_actual->ancho_hces1) && !empty($lote_actual->actual_bid))
            <div class="pre">
				<p class="pre_min">
					<strong>
						<span id="acutalPriceMeter">
							{{ \Tools::moneyFormat($lote_actual->actual_bid / $lote_actual->ancho_hces1, false, 2) }}
						</span>
						<span>
							{{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}
						</span>
						{!! trans("$theme-app.lot.m2") !!}
					</strong>
				</p>
            </div>
        @endif

    </div>

	<!-- ------------------------ SIGUIENTE PUJA ------------------------ -->
    <div class="col-xs-12 no-padding info-ficha-buy-info-price d-flex">

        <div class="pre">
            <p class="pre-title">{{ trans(\Config::get('app.theme') . '-app.lot.next_min_bid') }}</p>
            <p class="pre-price lotprice">
                <strong>
                    <span class="siguiente_puja"> </span>
                    {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}
					<span class="lower-case"> {{ trans("$theme-app.lot.lot-name") }} </span>
                    @if (\Config::get('app.exchange'))
                        | <span id="nextBidExchange_JS" class="exchange"> </span>
                    @endif
                </strong>
            </p>
        </div>

		@if (!empty($lote_actual->nobj_hces1))
		<div class="pre">
			<p class="pre-price">
				<strong><span class="siguiente_puja_perpiece"> </span>
					{{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}{!! trans("$theme-app.lot.piece") !!}
					@if (\Config::get('app.exchange'))
						| <span id="nextBidExchange_JS" class="exchange"> </span>
					@endif
				</strong>
			</p>
		</div>
        @elseif (!empty($lote_actual->ancho_hces1))
            <div class="pre">
                <p class="pre-price">
                    <strong><span class="siguiente_puja_permeter"> </span>
                        {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}{!! trans("$theme-app.lot.m2") !!}
                        @if (\Config::get('app.exchange'))
                            | <span id="nextBidExchange_JS" class="exchange"> </span>
                        @endif
                    </strong>
                </p>
            </div>
        @endif

    </div>


    @if ($start_session || $subasta_abierta_P)

		@if($deposito)
			<div class="insert-bid-input col-xs-12 d-flex justify-content-center flex-column pt-1 pb-1">

				@if (Session::has('user') && Session::get('user.admin'))
					<div class="d-block w-100">
						<input id="ges_cod_licit" name="ges_cod_licit" class="form-control" type="text" value=""
							type="text" style="border: 1px solid red;" placeholder="Código de licitador">
						@if ($subasta_abierta_P)
							<input type="hidden" id="tipo_puja_gestor" value="abiertaP">
						@endif
					</div>
				@endif


				<div class="input-group d-block group-pujar-custom ">
					<div>
						<div class="insert-bid insert-max-bid mb-1">
							<p class="mt-1 mb-0">{{ trans(\Config::get('app.theme') . '-app.lot.insert_max_puja') }}
							</p>
							<div
								class="info_single_title hist_new <?= !empty($data['js_item']['user']['ordenMaxima']) ? '' : 'hidden' ?> ">
								{{ trans(\Config::get('app.theme') . '-app.lot.max_puja') }}
								<strong>

									<span id="tuorden">
										@if (!empty($data['js_item']['user']['ordenMaxima']))
											@if (!empty($lote_actual->max_puja) && $lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit'])
												{{ $lote_actual->formatted_actual_bid }}
											@else
												{{ $data['js_item']['user']['ordenMaxima'] }}
											@endif
										@endif
									</span>
									{{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}
									@if (\Config::get('app.exchange'))
										| <span id="yourOrderExchange_JS" class="exchange"> </span>
									@endif
								</strong>
							</div>
						</div>
					</div>
					<div class="d-flex mb-2">


							<input id="bid_amount" placeholder="{{ $data['precio_salida'] }}"
								class="form-control control-number" type="text" value="{{ $data['precio_salida'] }}">
							<div class="input-group-btn">
								<button type="button" data-from="modal"
									class="lot-action_pujar_on_line ficha-btn-bid ficha-btn-bid-height button-principal <?= Session::has('user') ? 'add_favs' : '' ?>"
									type="button" ref="{{ $lote_actual->ref_asigl0 }}"
									ref="{{ $lote_actual->ref_asigl0 }}"
									codsub="{{ $lote_actual->cod_sub }}">{{ trans(\Config::get('app.theme') . '-app.lot.pujar') }}</button>
							</div>

					</div>

				</div>
			</div>
		@else

		<div class="insert-bid-input col-xs-12 d-flex justify-content-center flex-column pt-1 pb-2">
			<p class="mt-1 mb-1">
				{!! trans(\Config::get('app.theme') . '-app.lot.text_pay_deposit',["imp_deposito" =>($lote_actual->impsalhces_asigl0 * Config::get("app.depositPct") /100 )." €" ]) !!}
			</p>
			<p class="mt-1 mb-1 ">
				<form id="depositoForm" class="text-center">
					<button id="submitDeposito_JS" type="button" 	class="ficha-btn-deposit "
						>{{ trans(\Config::get('app.theme') . '-app.lot.pay_deposit') }}
					</button>
					<input type="hidden" name="codSub" value="{{$lote_actual->cod_sub }}">
					<input type="hidden" name="ref" value="{{$lote_actual->ref_asigl0 }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />

				</form>
			</p>
		</div>

		@endif
    @endif


    <?php //solo se debe recargar la fecha en las subatsas tipo Online, ne las abiertas tipo P no se debe ejecutar
    ?>


    @if ($subasta_online)
        <script>
            $(document).ready(function() {

                $("#actual_max_bid").bind('DOMNodeInserted', function(event) {
                    if (event.type == 'DOMNodeInserted') {

                        $.ajax({
                            type: "GET",
                            url: "/lot/getfechafin",
                            data: {
                                cod: cod_sub,
                                ref: ref
                            },
                            success: function(data) {

                                if (data.status == 'success') {
                                    $(".timer").data('ini', new Date().getTime());
                                    $(".timer").data('countdownficha', data.countdown);
                                    //var close_date = new Date(data.close_at * 1000);
                                    // $("#cierre_lote").html(close_date.toLocaleDateString('es-ES') + " " + close_date.toLocaleTimeString('es-ES'));
                                    $("#cierre_lote").html(format_date_large(new Date(data
                                        .close_at * 1000), ''));
                                }


                            }
                        });
                    }
                });
            });
        </script>
    @endif
</div>
