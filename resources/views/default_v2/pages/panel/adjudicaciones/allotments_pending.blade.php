@php
$sub = new \App\Models\Subasta();
$all_adj = array();

foreach($data['adjudicaciones'] as $temp_adj){
    $all_adj[$temp_adj->cod_sub]['lotes'][] = $temp_adj;
}

foreach($all_adj as $key_inf => $value){
    $sub->cod = $key_inf;
    $all_adj[$key_inf]['inf'] = $sub->getInfSubasta();
}
@endphp
<script src="{{ URL::asset('js/payment.js')}}"></script>
<script>
   const info_lots = @json($data["js_item"]);
</script>

{{-- pendientes --}}
<div class="accordion mb-3">
	<h2 class="accordion-item accordion-header" id="accordion-pendings-heading">
		<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#allotemnts-pending-collapse" aria-expanded="true" aria-controls="allotemnts-pending-collapse">
			{{ trans("$theme-app.user_panel.still_paid") }}
		</button>
	</h2>
	<div id="allotemnts-pending-collapse" class="accordion-collapse collapse show" aria-labelledby="#accordion-pendings-heading">
		<div class="accordion-body p-0">

			{{-- subastas --}}
			<div class="accordion">
				@foreach($all_adj as $key_sub => $all_inf)
					@php
						#Se reinician las variables para packengers
						$urlToPackengersMaker = "";
						$packengersMoneyValue = 0;
					@endphp

					<h2 class="accordion-item accordion-header" id="{{$all_inf['inf']->cod_sub}}-heading">
						<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#{{$all_inf['inf']->cod_sub}}" aria-expanded="true" aria-controls="{{$all_inf['inf']->cod_sub}}">
							{{$all_inf['inf']->name}}

							@if($all_inf['inf']->compraweb_sub == 'S')
								<span class="total-price-sup">
									{{ trans("$theme-app.user_panel.total_price") }}
									<span class="precio_final_{{$all_inf['inf']->cod_sub}}">0</span> {{ trans("$theme-app.lot.eur") }}
								</span>
							@endif
						</button>
					</h2>
					<div id="{{$all_inf['inf']->cod_sub}}" class="accordion-collapse collapse show" aria-labelledby="#{{$all_inf['inf']->cod_sub}}-heading">

						<div class="table-to-columns">
							<table class="table table-sm align-middle">
								<thead class="table-light">
									<tr>
										<th></th>
										<th>{{ trans("$theme-app.user_panel.lot") }}</th>
										<th style="max-width: 300px">{{ trans("$theme-app.user_panel.description") }}</th>
										<th>{{ trans("$theme-app.lot.lot-price") }}</th>
										<th>{{ trans("$theme-app.user_panel.price") }}</th>
										<th>{{ trans("$theme-app.user_panel.price_comision") }}</th>
										<th>{{ trans("$theme-app.user_panel.price_clean") }}</th>
										<th></th>
									</tr>
								</thead>

								<tbody>
									@foreach($all_inf['lotes'] as $inf_lot)
										@php
											$url_friendly = str_slug($inf_lot->titulo_hces1);
											$url_friendly = Routing::translateSeo('lote').$inf_lot->cod_sub."-".str_slug($inf_lot->name).'-'.$inf_lot->id_auc_sessions."/".$inf_lot->ref_asigl0.'-'.$inf_lot->num_hces1.'-'.$url_friendly;
											$precio_remate = Tools::moneyFormat($inf_lot->himp_csub);
											$comision = Tools::moneyFormat($inf_lot->base_csub + $inf_lot->base_csub_iva, trans("$theme-app.lot.eur"), 2);

											$total_price = $inf_lot->himp_csub + $inf_lot->base_csub + $inf_lot->base_csub_iva;
											$precio_limpio_calculo = Tools::moneyFormat($total_price, trans("$theme-app.lot.eur"), 2);

											#Suma para calcular el precio total para URL de packengers
											$packengersMoneyValue += $total_price;

											//Modificamos ref_asigl0 de _ a . porque se ha hecho al reves en el controlador por un tema de javascript
											$refLot = str_replace('_','.',$inf_lot->ref_asigl0);

											#si  tiene el . decimal hay que ver si se debe separar
											if(strpos($refLot, '.') !== false){
												$refLot = str_replace(array(".1",".2",".3", ".4", ".5"), array("-A", "-B", "-C", "-D", "-E"), $refLot);
												#si hay que recortar
											}elseif( \config::get("app.substrRef")){
												#cogemos solo los últimos x numeros, ya que se usaran hasta 9, los  primeros para diferenciar un lote cuando se ha vuelto a subir a subasta
												#le sumamos 0 para convertirlo en numero y así eliminamos los 0 a la izquierda
												$refLot = substr($refLot,-\config::get("app.substrRef")) +0;
											}

											#Crea la URL mediante la iteración del foreach con los lotes de dentro
											$urlToPackengersMaker .= $inf_lot->cod_sub."-".$inf_lot->ref_asigl0.",";
										@endphp

										<tr>
											@if($all_inf['inf']->compraweb_sub == 'S')
											<div class="hidden">
												<input type="checkbox" checked="" id="add-carrito-{{$inf_lot->cod_sub}}-{{$inf_lot->ref_asigl0}}" class="filled-in add-carrito form-control" name="carrito[{{$inf_lot->sub_csub}}][{{$inf_lot->ref_csub}}][pagar]" >
											</div>
											@endif
											<input class="hide envios_{{$inf_lot->sub_csub}}_js" type="hidden" name="carrito[{{$inf_lot->sub_csub}}][{{$inf_lot->ref_csub}}][envios]" value='{{ Config::get('app.web_gastos_envio')? '5' : '1' }}'>
											<input class="hide seguro_lote_{{$inf_lot->sub_csub}}_js" type="hidden" name="carrito[{{$inf_lot->sub_csub}}][{{$inf_lot->ref_csub}}][seguro]" value='0'>
											<td class="td-img">
												<a href="{{$url_friendly}}">
													<img src="{{ \Tools::url_img("lote_small", $inf_lot->num_hces1, $inf_lot->lin_hces1) }}" class="img-responsive">
												</a>
											</td>
											<td data-title="{{ trans("$theme-app.user_panel.lot") }}">
												{{$refLot}}
											</td>
											<td data-title="{{ trans("$theme-app.user_panel.description") }}" class="td-title">
												<span class="max-line-2">{!! $inf_lot->descweb_hces1 !!}</span>
											</td>
											<td data-title="Precio de salida">
												{{ $inf_lot->impsalhces_asigl0 ?? 0 }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
											</td>
											<td data-title="{{ trans("$theme-app.user_panel.price") }}">
												{{$precio_remate}}
											</td>
											<td data-title="{{ trans("$theme-app.user_panel.price_comision") }}">
												{{ $comision }}
											</td>
											<td data-title="{{ trans("$theme-app.user_panel.price_clean") }}">
												{{ $precio_limpio_calculo }}
											</td>
											<td>
												<div class="btn-group">
													<button type="button" class="btn btn-sm d-flex align-items-center p-2 rounded-circle" data-bs-toggle="dropdown" aria-expanded="false">
														<svg class="bi" width="16" height="16" fill="currentColor">
															<use xlink:href="/bootstrap-icons.svg#three-dots-vertical"/>
														</svg>
													</button>

													<ul class="dropdown-menu">
														<li><a class="dropdown-item" href="{{ $url_friendly }}" target="_blank">Ver lote</a></li>
													</ul>

												</div>
											</td>
										</tr>
									@endforeach


								</tbody>

							</table>
						</div>

						@php
							$isCompraWeb = $all_inf['inf']->compraweb_sub === 'S';
							$hasPaymentMethod = $isCompraWeb && (config('app.merchantIdUP2', false) || config('app.paymentRedsys', false));
						@endphp

						@if($hasPaymentMethod)

							{{--gastos de envío /@todo por probar --}}
							@if(config('app.web_gastos_envio', false) && !empty($data["address"]))
								<div class="row">
									<div class="col-sm-5 gastos_envio">
										<strong> {{ trans("$theme-app.user_panel.direccion-facturacion") }}</strong>
										<select id="clidd_{{$all_inf['inf']->cod_sub}}"  name="clidd_{{$all_inf['inf']->cod_sub}}" class="change_address_js "   data-sub="{{$all_inf['inf']->cod_sub}}" style="width: 90%;">
											@foreach($data["address"] as $key => $value)
												<option value="{{ $key}}">{{$value}} </option>
											@endforeach
										</select>
									</div>
									<div class="col-sm-4 gastos_envio" >
										<p><strong> {{ trans("$theme-app.user_panel.envio_agencia") }} </strong></p>
										<?php #Debe estar checkeado almenos uno de los dos radio buttons ?>
										<div id="envioPosible_{{$all_inf['inf']->cod_sub}}_js">
											<input type="radio" checked="checked" class=" change_envio_js" data-sub="{{$all_inf['inf']->cod_sub}}" id="envio_agencia_{{$all_inf['inf']->cod_sub}}_js" name="envio_{{$all_inf['inf']->cod_sub}}"  value="1"> <label for="envio_agencia_{{$all_inf['inf']->cod_sub}}_js"> {{ trans(\Config::get('app.theme').'-app.user_panel.gastos_envio') }}:  <span id="coste-envio-{{$all_inf['inf']->cod_sub}}_js"> </span> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</label>

											@if (!empty(Config::get("app.porcentaje_seguro_envio")))
												<br>
												<input type="checkbox" style="top: 0px;height: 10px;margin-right: 0px;"  class="check_seguro_js" data-sub="{{$all_inf['inf']->cod_sub}}" id="seguro_{{$all_inf['inf']->cod_sub}}_js" name="seguro_{{$all_inf['inf']->cod_sub}}"  value="1"> <label for="seguro_{{$all_inf['inf']->cod_sub}}_js"> {{ trans(\Config::get('app.theme').'-app.user_panel.seguro_envio') }}:  <span id="coste-seguro-{{$all_inf['inf']->cod_sub}}_js"> </span> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</label>
												<input type="hidden" id="porcentaje-seguro-{{$all_inf['inf']->cod_sub}}_js" value="{{ \Config::get("app.porcentaje_seguro_envio")}}">
												<input type="hidden" id="iva_aplicable-{{$all_inf['inf']->cod_sub}}_js" value="{{ $data["ivaAplicable"]}}">

											@endif

										</div>
										<div id="envioNoDisponible_{{$all_inf['inf']->cod_sub}}_js" class="hidden">
											{{ trans(\Config::get('app.theme').'-app.user_panel.envio_no_disponible') }}
										</div>

										<strong> {{ trans(\Config::get('app.theme').'-app.user_panel.recogida_producto') }} </strong>

										<input type="radio" class=" change_envio_js" data-sub="{{$all_inf['inf']->cod_sub}}" id="recogida_almacen_{{$all_inf['inf']->cod_sub}}_js" name="envio_{{$all_inf['inf']->cod_sub}}" value="0"> <label  for="recogida_almacen">{{ trans(\Config::get('app.theme').'-app.user_panel.sala_almacen') }}</label>

										<script>
											const hasShowSeguro = "{{config('app.porcentaje_seguro_envio', false)}}";
											const codSub = '{{ $all_inf["inf"]->cod_sub }}';
											//cargar precio de gastos de envio
											show_gastos_envio(codSub);

											if(Boolean(hasShowSeguro)){
												show_seguro_envio(codSub);
											}
										</script>
									</div>
								</div>
							@endif

							<div class="total-price checkout">
								<div>
									<h4>{{ trans(\Config::get('app.theme').'-app.user_panel.total_price') }}</h4>
									<h4><span class="precio_final_{{$all_inf['inf']->cod_sub}}">0</span> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</h4>
								</div>

								@if(\Config::get("app.PayBizum") || \Config::get("app.PayTransfer") || \Config::get("app.paymentPaypal"))

									<p>Métodos de pago</p>
									<div class="btn-group gap-1" role="group" aria-label="Pay method">

										@if(Config::get("app.paymentUP2")  || Config::get('app.paymentRedsys') )
											<input type="radio" class="btn-check" name="paymethod" id="paycreditcard" value="creditcard" autocomplete="off" checked>
											<label class="btn btn-outline-lb-secondary" for="paycreditcard">
												{{ trans(\Config::get('app.theme').'-app.user_panel.pay_creditcard') }}
											</label>
										@endif

										@if(Config::get("app.PayBizum", false))
										<input type="radio" class="btn-check" name="paymethod" id="paybizum" value="bizum" autocomplete="off">
										<label class="btn btn-outline-lb-secondary" for="paybizum">
											<img src="/default/img/logos/bizum-blue.png" style="height: 20px;margin: 0px 6px;"> {{ trans(\Config::get('app.theme').'-app.user_panel.pay_bizum') }}
										</label>
										@endif


										@if(Config::get("app.PayTransfer", false))
											<input type="radio" class="btn-check" name="paymethod" id="paytransfer" value="transfer" autocomplete="off">
											<label class="btn btn-outline-lb-secondary" for="paytransfer">
												{{ trans(\Config::get('app.theme').'-app.user_panel.pay_transfer') }}
											</label>
										@endif

										@if(Config::get("app.paymentPaypal", false))
											<input type="radio" class="btn-check" name="paymethod" id="paypaypal" value="paypal" autocomplete="off">
											<label class="btn btn-outline-lb-secondary" for="paypaypal">
												<i class="fa fa-paypal" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.user_panel.pay_paypal') }}
											</label>
										@endif
									</div>

								@else
									<input id="paytransfer" type="hidden" name="paymethod" value="{{ config('app.paymentPaypal', 0) ? 'paypal' : 'creditcard' }}">
								@endif

								@if (\Config::get("app.urlToPackengers"))
									@php
										#Se crea la URL para ser usable en el botón
										$urlToPackengers = rtrim($urlToPackengersMaker, ",");
										$urlToPackengers .= "?value=".\Tools::moneyFormat($packengersMoneyValue,false,2);
										$urlCompletePackengers = \Config::get('app.urlToPackengers').$urlToPackengers;
									@endphp

									<a class="packengers-button-adjudicaciones btn btn-outline-lb-secondary"
										href="{{ $urlCompletePackengers }}" target="_blank">
										<svg class="bi" width="16" height="16" fill="currentColor">
											<use xlink:href="/bootstrap-icons.svg#truck"></use>
										</svg>
										{{ trans("$theme-app.lot.packengers_adjudicaciones") }}
									</a>

								@endif

								<button type="button" class="submit_carrito btn btn-lb-primary" cod_sub="{{$all_inf['inf']->cod_sub}}" class="btn btn-step-reg" disabled>
									{{ trans(\Config::get('app.theme').'-app.user_panel.pay') }}
								</button>

							</div>

						@endif

					</div>
				@endforeach
			</div>

		</div>
	</div>
</div>
