@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

<?php
$all_adj = array();
$sub = new \App\Models\Subasta;
$all_adj=array();
foreach($data['adjudicaciones'] as $temp_adj){
    $all_adj[$temp_adj->cod_sub]['lotes'][]=$temp_adj;
}
foreach($all_adj as $key_inf => $value){
    $sub->cod = $key_inf;
    $all_adj[$key_inf]['inf'] = $sub->getInfSubasta();
}

use App\libs\Currency;
$currency = new Currency();
$divisas = $currency->getAllCurrencies();

$envioPorDefecto = collect($data['envio'])->where('codd_clid', 'W1')->first();

$codPais_clid = $envioPorDefecto->codpais_clid ?? $data['user']->codpais_cli ?? 'ES';

//dd($data['user']);
?>
<script>
	var info_lots = $.parseJSON('<?php echo str_replace("\u0022","\\\\\"",json_encode($data["js_item"],JSON_HEX_QUOT)); ?>');
    var currency = $.parseJSON('<?php  echo str_replace("\u0022","\\\\\"",json_encode($divisas,JSON_HEX_QUOT)); ?>');
</script>

@include('pages.panel.principal_bar')

<section class="payment">
	<div class="container">
		<div class="row">

			{{--<div class="col-xs-12">
				@include('pages.panel.menu')
            </div>--}}

			<div class="col-xs-12">
				<div class="user-datas-title flex align-items-center">
					<p style="margin: 0px">{{ trans(\Config::get('app.theme').'-app.user_panel.my_invoice') }}</p>
					<div class="col_reg_form"></div>
					<div class="btns-pay flex justify-content-space-bettween" style="flex: 1">
						<select id="actual_currency">
							@foreach($divisas as $divisa)
							@if($divisa->cod_div != 'EUR')
							<option value='{{ $divisa->cod_div }}'
								<?= ($divisa->cod_div == 'USD')? 'selected="selected"' : '' ?>>
								{{ $divisa->cod_div }}
							</option>
							@endif
							@endforeach
						</select>

						<a class="btn btn-color" type="btn" href="/es/user/panel/allotments">{{ trans("$theme-app.global.go_home") }}</a>

					</div>
				</div>
			</div>

			{{-- Fact address --}}
			<div class="col-xs-12 address-adj-wrapper mt-2">

				<div class="user-datas-title title-collapse d-flex justify-content-space-bettween flex-wrap" style="margin-bottom: 0; gap:10px;">
					<p>1.{{ trans(\Config::get('app.theme').'-app.user_panel.billing_address') }}</p>
					<small><i class="fa fa-info-circle" aria-hidden="true"></i> {!!
						trans(\Config::get('app.theme').'-app.user_panel.billing_address_info') !!}</small>

				</div>

				<div class="address-panel">
					<div class="panel panel-default" cod="new">
						<div class="panel-heading">
							<p class="panel-title">

								<a class="" cod="fact-address">
									<label class="camel-case w-100" for="">
										<span>
											{{$data['user']->nom_cli}} -
											{{$data['user']->dir_cli}}{{$data['user']->dir2_cli}} -
											{{$data['user']->cp_cli}}, {{$data['user']->pro_cli}} -
											{{ $data['countries'][$data['user']->codpais_cli] }}
										</span>

									</label>
								</a>

							</p>
						</div>
					</div>
				</div>

			</div>

			<form id="pagar_lotes_{{ head($all_adj)['inf']->cod_sub}}">
				{{-- 2. Direcciones de envio --}}
				<div class="col-xs-12 address-adj-wrapper mt-2">

					<div class="user-datas-title title-collapse" style="margin-bottom: 0">
						<p>2.{{ trans(\Config::get('app.theme').'-app.user_panel.title_envio') }}</p>
					</div>

					<div class="addresses-adj-wrapper" style="position: relative">
						@foreach ($data['envio'] as $key => $address)

						<label class="w-100 d-flex align-items-center address-name-wrapper mb-1"
							for="clidd_{{$address->codd_clid}}_{{ head($all_adj)['inf']->cod_sub}}">

							<input type="radio" id="clidd_{{$address->codd_clid}}_{{ head($all_adj)['inf']->cod_sub}}"
								name="clidd" value="{{$address->codd_clid}}" @if($address->codd_clid == 'W1') checked
							@endif>

							<span class="camel-case address-name ml-2">
								{{$address->nomd_clid}} - {{$address->dir_clid}}{{$address->dir2_clid}} -
								{{$address->cp_clid}}, {{$address->pro_clid}} -
								{{$data['countries'][strtoupper($address->codpais_clid)] }}
							</span>

						</label>

						@endforeach

						<a class="btn btn-color new-address-adj" type="btn"
							href="{{ route('panel.addresses', ['lang' => Config::get('app.locale'), 'cod_sub' => head($all_adj)['inf']->cod_sub]) }}">{{ trans(\Config::get('app.theme').'-app.user_panel.new_address') }}</a>
					</div>

				</div>


				{{-- 3. Forma de Envío --}}
				<div class="col-xs-12 payments-adj-wrapper mt-2">

					<div class="user-datas-title title-collapse" style="margin-bottom: 0">
						<p>3.{{ trans(\Config::get('app.theme').'-app.user_panel.shipping_form') }}</p>
					</div>

					<div class="payment-adj-wrapper" style="position: relative">

						@if($data['user']->envcorr_cli != 'N')
						<label class="w-100 d-flex align-items-center payment-name-wrapper mb-1" for="shipping_express">
							<input id="shipping_express" type="radio" name="shipping" value="express" checked="checked">
							<span
								class="payment-adj">{{ trans(\Config::get('app.theme').'-app.user_panel.shipping_express') }}</span><span
								class="gasto-envio-express-{{head($all_adj)['inf']->cod_sub}}_JS"></span> €

						</label>
						@endif

						<label class="w-100 d-flex align-items-center payment-name-wrapper mb-1"
							for="shipping_express_min">
							<input id="shipping_express_min" type="radio" name="shipping" value="min">
							<span
								class="payment-adj">{{ trans(\Config::get('app.theme').'-app.user_panel.shipping_express_min') }}
							</span><span class="gasto-envio-min-{{head($all_adj)['inf']->cod_sub}}_JS"></span> €

						</label>

						<label class="w-100 d-flex align-items-center payment-name-wrapper mb-1"
							for="shipping_express_recoger">
							<input id="shipping_express_recoger" type="radio" name="shipping" value="recoger" @if($data['user']->envcorr_cli == 'N') checked="checked" @endif>
							<span
								class="payment-adj">{{ trans("$theme-app.user_panel.store_pickup") }}
							</span><span class=""></span>

						</label>



					</div>

				</div>


				{{-- 4. Método de Pago --}}
				<div class="col-xs-12 payments-adj-wrapper mt-2">

					<div class="user-datas-title title-collapse" style="margin-bottom: 0">
						<p>4.{{ trans(\Config::get('app.theme').'-app.user_panel.payment_method') }}</p>
					</div>

					<div class="payment-adj-wrapper" style="position: relative">

						<label class="w-100 d-flex align-items-center payment-name-wrapper flex-wrap mb-1" for="paycreditcard">
							<input id="paycreditcard" type="radio" name="paymethod" value="creditcard"
								checked="checked">
							<span
								class="payment-adj" style="margin-right: 5px">{{ trans(\Config::get('app.theme').'-app.user_panel.pay_creditcard') }}</span>
							<div>
								<img src="/img/icons/visa.png" alt="Visa" class="mt-1">
								<img src="/img/icons/mastercard_1.png" alt="Mastercard" class="mt-1">
								<img src="/img/icons/maestro_1.png" alt="Maestro" class="mt-1">
								<img src="/img/icons/unionpay_1.png" alt="UnionPay" class="mt-1">
							</div>
						</label>

						<label class="w-100 d-flex align-items-center payment-name-wrapper flex-wrap mb-1" for="paybizum">
							<input id="paybizum" type="radio" name="paymethod" value="bizum">
							<span
								class="payment-adj" style="margin-right: 5px">{{ trans(\Config::get('app.theme').'-app.user_panel.pay_bizum') }}</span>
								<img src="/img/icons/bizum.png" alt="Bizum" class="mt-1">
						</label>

						{{-- Por el momento no
					@if(\Config::get("app.PayTransfer"))
					<label class="w-100 d-flex align-items-center payment-name-wrapper mb-1" for="paytransfer">
						<input id="paytransfer"  type="radio" name="paymethod" value="transfer">
						<span class="payment-adj">{{ trans(\Config::get('app.theme').'-app.user_panel.pay_transfer') }}</span>
						</label>
						@endif
						--}}

					</div>

				</div>

				<div class="col-xs-12">
					<div class="panel-group" id="accordion">
						<div class="panel panel-default panel-payment">
							<?php $i=0 ?>
							@foreach($all_adj as $key_sub => $all_inf)

							<?php
                            $total_remate = 0;
                            $total_base = 0;
							$total_iva = 0;
							$total_licencia_exportacion = 0;
                            ?>

							<a aria-expanded="true" data-toggle="collapse" href="#{{$all_inf['inf']->cod_sub}}">
								<div class="panel-heading">
									<h4 class="panel-title">
										{{$all_inf['inf']->name}}
									</h4>
									<i class="fas fa-sort-down"></i>
								</div>
							</a>

							<div id="{{$all_inf['inf']->cod_sub}}"
								class="panel-collapse collaps in">

								<!-- Cabeceras grises con titulos-->
								<div class="custom-head-wrapper flex hidden-xs hidden-sm">
									<div class="table-data-check flex hidden">

									</div>
									<div class="img-data-customs flex "></div>
									<div class="lot-data-custon">
										<p>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}</p>
									</div>
									<div class="name-data-custom">
										<p style="font-weight: 900">
											{{ trans(\Config::get('app.theme').'-app.lot.description') }}</p>
									</div>
									<div class="remat-data-custom">
										<p>{{ trans(\Config::get('app.theme').'-app.user_panel.price') }}</p>
									</div>
									<div class="auc-data-custom">
										<p>{{ trans(\Config::get('app.theme').'-app.user_panel.price_comision') }}</p>
									</div>
								</div>

								<!-- /Cabeceras grises con titulos-->

								@foreach($all_inf['lotes'] as $inf_lot)
								<?php
                                    $url_friendly = str_slug($inf_lot->titulo_hces1);
                                    $url_friendly = \Routing::translateSeo('lote').$inf_lot->cod_sub."-".str_slug($inf_lot->name).'-'.$inf_lot->id_auc_sessions."/".$inf_lot->ref_asigl0.'-'.$inf_lot->num_hces1.'-'.$url_friendly;
                                    $precio_remapte = $inf_lot->himp_csub;
                                    $precio_limpio = \Tools::moneyFormat($inf_lot->base_csub,false,2);
                                    $comision = $inf_lot->base_csub + $inf_lot->base_csub_iva;

                                    $precio_limpio_calculo =  number_format($inf_lot->himp_csub + $inf_lot->base_csub + $inf_lot->base_csub_iva, 2, '.', '');
                                    $calc_envio = number_format($inf_lot->himp_csub + $inf_lot->base_csub, 2, '.', '');


                                    //Calculo total
                                    $total_remate = $total_remate + $inf_lot->himp_csub;
                                    $total_base = $total_base + $inf_lot->base_csub;
									$total_iva = $total_iva + $inf_lot->base_csub_iva;
									$total_licencia_exportacion += $inf_lot->licencia_exportacion;
                                ?>

								{{-- Vista mobile --}}
								<div class="custom-wrapper-responsive  hidden-md hidden-lg {{$inf_lot->ref_asigl0}}-{{$inf_lot->cod_sub}}" style="padding: 10px">

									<div class="lot-data-custon">
										<p>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}
											{{$inf_lot->ref_asigl0}}
										</p>
									</div>

									<div class="lot-data-custon">
										<img style="margin: auto" class="img-responsive" src="/img/load/lote_medium/{{ $inf_lot->imagen }}">
									</div>

									<div class="name-data-custom" style="width: 100%">
										{!! $inf_lot->desc_hces1 !!}
									</div>

									<div class="flex justify-content-space-bettween mb-1 mt-1">

										<div class="auc-data-custom">
											<p>{{ trans(\Config::get('app.theme').'-app.user_panel.price') }}</p>
											<p>
												{{$precio_remapte}} {{ trans(\Config::get('app.theme').'-app.lot.eur') }}
											</p>
											<p><span value="{{$precio_remapte}}" class="js-divisa"></span></p>
										</div>

										<div class="auc-data-custom">
											<p>{{ trans(\Config::get('app.theme').'-app.user_panel.price_comision') }}</p>

											<p>
												{{ \Tools::moneyFormat($comision,false,2) }} {{ trans(\Config::get('app.theme').'-app.lot.eur') }}
											</p>
											<p><span value="{{$comision}}" class="js-divisa"></span></p>
										</div>

									</div>

								</div>
								{{-- Fin Vista mobile --}}


								<!-- Lotes en vista desktop-->
								<div class="custom-wrapper flex hidden-xs hidden-sm valign">
									<div class="table-data-check flex hidden">
										@if( empty(\Config::get( 'app.pasarela_web' )))
										<input type="checkbox" checked="" id="{{$i}}"
											class="filled-in add-carrito form-control"
											name="carrito[{{$inf_lot->sub_csub}}][{{$inf_lot->ref_csub}}][pagar]">
										<label for="{{$i}}"></label>
										@endif
									</div>

									<div class="img-dat img-data-custom flex valign" role="button" id="">
										<img class="img-responsive" src="/img/load/lote_medium/{{ $inf_lot->imagen }}">
									</div>
									<div class="lot-data-custon">
										<p>{{$inf_lot->ref_asigl1}}</p>
									</div>
									<div class="name-data-custom">
										<?= $inf_lot->desc_hces1?>
									</div>

									<div class="remat-data-custom">
										<p>
											<?= \Tools::moneyFormat($precio_remapte,false,2) ?>
											{{ trans(\Config::get('app.theme').'-app.lot.eur') }}
											&nbsp;|&nbsp;<span value="{{$precio_remapte}}" class="js-divisa"></span>
										</p>
									</div>
									<div class="auc-data-custom">
										<p>
											<?= \Tools::moneyFormat($comision,false,2) ?>
											{{ trans(\Config::get('app.theme').'-app.lot.eur') }}
											&nbsp;|&nbsp;<span value="{{$comision}}" class="js-divisa"></span>
										</p>
									</div>

									<input class="hide" type="hidden"
										name="carrito[{{$inf_lot->sub_csub}}][{{$inf_lot->ref_csub}}][envios]"
										value='1'>
									<input class="hide" type="hidden"
										name="carrito[{{$inf_lot->sub_csub}}][{{$inf_lot->ref_csub}}][exportacion]"
										value='{{ $codPais_clid }}'>

								</div>
								<!-- /Lotes en vista desktop-->
								<?php $i++ ?>
								@endforeach
								@if($data['user']->envcorr_cli != 'B')
								<div class="adj adj-panel-wrapper">

									<div class="adj-panel w-100">
										<div class="panel panel-default panel-payment">
											<div class="panel-heading">
												<p class="panel-title">

													<a class="" data-toggle="collapse" data-parent="#fact_accordion"
														href="#adj_fact">
														<label class="w-100 d-flex align-items-center" for="">
															<span class="titlecat" style="margin-left: auto">
																<span>{{ trans(\Config::get('app.theme').'-app.shopping_cart.total_pay') }}</span>
																<span
																	class="precio_final_{{$all_inf['inf']->cod_sub}}">0</span>
																<span>{{ trans(\Config::get('app.theme').'-app.lot.eur') }}
																	|</span>
																<span value=""
																	class="js-divisa precio_final_{{$all_inf['inf']->cod_sub}}"></span>
															</span>
															{{-- <i class="fa fa-2x fa-caret-right" aria-hidden="true"></i> --}}
														</label>
													</a>

												</p>
											</div>
											<div id="adj_fact" class="panel-collapse collapse in">
												<div class="panel-body col-xs-11 col-sm-10 col-md-7 col-lg-6" style="float: right" id="">
													<div class="info-pay-modal">

														<div class="price mb-1">
															<div class="row">
																<div class="col-xs-5">
																	<div class="row">
																		<div class="title col-xs-12">
																			{{ trans(\Config::get('app.theme').'-app.user_panel.price') }}
																		</div>
																	</div>
																</div>
																<div class="col-xs-7">

																	<div class="row">
																		<div
																			class="money col-xs-12 col-sm-7 text-right">
																			{{\Tools::moneyFormat($total_remate,false,2)}}
																			{{ trans(\Config::get('app.theme').'-app.lot.eur') }}
																			&nbsp;|&nbsp;&nbsp;
																		</div>
																		<div class="col-xs-12 col-sm-5 text-right">
																			<span value="{{$total_remate}}"
																				class="js-divisa"></span>
																		</div>
																	</div>

																</div>
															</div>
														</div>

														<div class="price mb-1">
															<div class="row">
																<div class="col-xs-5">
																	<div class="row">
																		<div class="title col-xs-12">
																			{{ trans(\Config::get('app.theme').'-app.user_panel.base') }}
																		</div>
																	</div>
																</div>
																<div class="col-xs-7">
																	<div class="row">
																		<div
																			class="money col-xs-12 col-sm-7 text-right">
																			{{\Tools::moneyFormat($total_base,false,2)}}
																			{{ trans(\Config::get('app.theme').'-app.lot.eur') }}
																			&nbsp;|&nbsp;&nbsp;
																		</div>
																		<div class="col-xs-12 col-sm-5 text-right">
																			<span value="{{$total_base}}"
																				class="js-divisa"></span>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="price mb-1">
															<div class="row">
																<div class="col-xs-5">
																	<div class="row">
																		<div class="title col-xs-12">
																			{{ trans(\Config::get('app.theme').'-app.user_panel.tax') }}
																		</div>
																	</div>
																</div>
																<div class="col-xs-7">
																	<div class="row">
																		<div
																			class="money col-xs-12 col-sm-7 text-right">
																			{{\Tools::moneyFormat($total_iva,false,2)}}
																			{{ trans(\Config::get('app.theme').'-app.lot.eur') }}
																			&nbsp;|&nbsp;&nbsp;
																		</div>
																		<div class="col-xs-12 col-sm-5 text-right">
																			<span value="{{$total_iva}}"
																				class="js-divisa"></span>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="price mb-1">
															<div class="row">
																<div class="col-xs-5">
																	<div class="row">
																		<div class="title col-xs-12">
																			{{ trans(\Config::get('app.theme').'-app.user_panel.export_license') }}
																		</div>
																	</div>
																</div>
																<div class="col-xs-7">
																	<div class="row">
																		<div
																			class="money col-xs-12 col-sm-7 text-right">
																			{{\Tools::moneyFormat($total_licencia_exportacion,false,2)}}
																			{{ trans(\Config::get('app.theme').'-app.lot.eur') }}
																			&nbsp;|&nbsp;&nbsp;
																		</div>
																		<div class="col-xs-12 col-sm-5 text-right">
																			<span
																				value="{{$total_licencia_exportacion}}"
																				class="js-divisa"></span>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="price mb-1">
															<div class="row">
																<div class="col-xs-5">
																	<div class="row">
																		<div class="title col-xs-12">
																			{{ trans(\Config::get('app.theme').'-app.user_panel.ship_tax') }}
																		</div>
																	</div>
																</div>
																<div class="col-xs-7">
																	<div class="row">
																		<div
																			class="money col-xs-12 col-sm-7 text-right">
																			<span
																				class='text-gasto-envio-{{$all_inf['inf']->cod_sub}}'></span>
																			{{ trans(\Config::get('app.theme').'-app.lot.eur') }}
																			&nbsp;|&nbsp;&nbsp;
																		</div>
																		<div class="col-xs-12 col-sm-5 text-right">
																			<span value=""
																				class="js-divisa text-gasto-envio-{{$all_inf['inf']->cod_sub}}"></span>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="price mb-1">
															<div class="row">
																<div class="col-xs-5">
																	<div class="row">
																		<div class="title col-xs-12">
																			<b>{{ trans(\Config::get('app.theme').'-app.user_panel.total') }}</b>
																		</div>
																	</div>
																</div>
																<div class="col-xs-7">
																	<div class="row">
																		<div
																			class="money col-xs-12 col-sm-7 text-right">
																			<b style="display: inline-block"
																				class='precio_final_{{$all_inf['inf']->cod_sub}}'></b><b
																				style="display: inline-block">
																				{{ trans(\Config::get('app.theme').'-app.lot.eur') }}</b>
																			&nbsp;|&nbsp;&nbsp;
																		</div>
																		<div class="col-xs-12 col-sm-5 text-right">
																			<span value=""
																				class="js-divisa precio_final_{{$all_inf['inf']->cod_sub}}"></span>
																		</div>
																	</div>
																</div>
															</div>
														</div>

													</div>
												</div>
											</div>
										</div>
									</div>

									@if($all_inf['inf']->compraweb_sub == 'S')
									<button type="button" cod_sub="{{$all_inf['inf']->cod_sub}}"
										class="btn btn-color btn-blue btn-step-reg submit_carrito"
										disabled>{{ trans(\Config::get('app.theme').'-app.user_panel.pay_now') }}</button>
									@endif
								</div>



							</div>
							@elseif($data['user']->envcorr_cli == 'B')
							<div class="adj">
								<p style="color: #283747;font-size: 18px;">
									{{ trans(\Config::get('app.theme').'-app.user_panel.contact_tauler') }}</p>
							</div>
							@endif
						</div>

						@endforeach
					</div>
				</div>
		</div>

		</form>

	</div>
	</div>
</section>

<script>
	$( document ).ready(function() {
         reload_carrito();


		$('.panel-collapse').on('show.bs.collapse', function (e) {
			$(`[href^='#${e.target.id}'] i.fa.fa-caret-right`).removeClass('fa-caret-right').addClass('fa-caret-down');
		});

		$('.panel-collapse').on('hide.bs.collapse', function (e) {
			$(`[href^='#${e.target.id}'] i.fa.fa-caret-down`).removeClass('fa-caret-down').addClass('fa-caret-right');
		});
    });

</script>


@stop
