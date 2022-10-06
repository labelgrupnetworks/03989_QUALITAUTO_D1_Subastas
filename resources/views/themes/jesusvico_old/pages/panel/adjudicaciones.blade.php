@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

<?php
$all_adj = array();
$sub = new \App\Models\Subasta;
$all_adj=array();
$tipo_pago_global = false; //cuando el tipo de pago es glogal o por subastas
foreach($data['adjudicaciones'] as $temp_adj){
    $all_adj[$temp_adj->cod_sub]['lotes'][]=$temp_adj;
}
foreach($all_adj as $key_inf => $value){
    $sub->cod = $key_inf;
    $all_adj[$key_inf]['inf'] = $sub->getInfSubasta();
}

$all_adj_pag = array();

$all_adj_pag=array();
foreach($data['adjudicaciones_pag'] as $temp_adj){
    $all_adj_pag[$temp_adj->cod_sub]['lotes'][]=$temp_adj;
}
foreach($all_adj_pag as $key_inf => $value){
    $sub->cod = $key_inf;
    $all_adj_pag[$key_inf]['inf'] = $sub->getInfSubasta();
}

?>
<script src="{{ URL::asset('js/payment.js')}}"></script>
<script>
    var info_lots = $.parseJSON('<?php echo str_replace("\u0022","\\\\\"",json_encode($data["js_item"],JSON_HEX_QUOT)); ?>');
</script>


<div class="color-letter">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                	<h1 class="titlePage">
						{{ trans("$theme-app.user_panel.mi_cuenta") }}
					</h1>
                </div>
            </div>
        </div>
    </div>

<div class="account-user color-letter  panel-user">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-3 col-lg-3 account-user-menu">
                <?php
					$tab = $data['onlyDirectSales'] ? 'directsale' : 'allotments';
				?>
                @include('pages.panel.menu_micuenta')
            </div>
            <div class="col-xs-12 col-md-9 col-lg-9 {{-- table-responsive --}}">
                <div class="user-account-title-content mb-3">
                    <div class="user-account-menu-title">
						@if($data['onlyDirectSales'])
						{{ trans("$theme-app.foot.direct_sale") }}
						@else
						{{ trans(\Config::get('app.theme').'-app.user_panel.allotments') }}
						@endif
					</div>
				</div>


				{{-- Pendientes de pago --}}
				@if(!empty($all_adj))
				<table class="table">

					{{-- Formulario el pago de todos los lotes --}}
					@if($tipo_pago_global)
                        <form  id="pagar_lotes_global">
					@endif

					@foreach($all_adj as $key_sub => $all_inf)
                        @php
                        	$total_remate = 0;
                            $total_base = 0;
                            $total_iva = 0;
                            $precio_global=0;
						@endphp

						<tr>
							<td colspan="12" data-toggle="collapse"
								class="accordion-toggle title-sub-list accordion-{{$all_inf['inf']->cod_sub}}"
								data-target="#{{$all_inf['inf']->cod_sub}}">
								<div class="d-flex align-items-center">
									<span class="w-100">{{$all_inf['inf']->name}}

										{{-- muestra el precio total junto al nombre de la subasta --}}
                                        @if($all_inf['inf']->compraweb_sub == 'S')
                                        <span class="total-price-sup"> / {{ trans(\Config::get('app.theme').'-app.user_panel.total_price') }} <span class="precio_final_{{$all_inf['inf']->cod_sub}}">0</span> {{ trans(\Config::get('app.theme').'-app.lot.eur') }} </span>
                                        @endif
									</span>
									<i style="float: right; font-size: 14px;" class="fas fa-plus"></i>
								</div>
							</td>
						</tr>

						<tr>
							<td colspan="12" class="hiddenRow">
								<div class="accordian-body collapse" id="{{$all_inf['inf']->cod_sub}}">
									<table class="table table-condensed table-to-card" id="{{$all_inf['inf']->cod_sub}}_table">

										<thead style="background-color: #f8f9fa">
											<tr>
												<th data-card-title class="col-xs-1"></th>
												<th class="col-xs-5">{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}</th>
												<th class="col-xs-2">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</th>
												<th class="col-xs-2">{{ trans(\Config::get('app.theme').'-app.user_panel.price_comision') }}</th>
												<th class="col-xs-2">{{ trans(\Config::get('app.theme').'-app.user_panel.price_clean') }}</th>
											</tr>
										</thead>

										<tbody>

											{{-- Form para pago por lotes individuales --}}
											@if(!$tipo_pago_global)
												<form id="pagar_lotes_{{$all_inf['inf']->cod_sub}}" >
											@endif

											@foreach($all_inf['lotes'] as $inf_lot)

												@php
													$url_friendly = str_slug($inf_lot->titulo_hces1);
                                                	$url_friendly = \Routing::translateSeo('lote').$inf_lot->cod_sub."-".str_slug($inf_lot->name).'-'.$inf_lot->id_auc_sessions."/".$inf_lot->ref_asigl0.'-'.$inf_lot->num_hces1.'-'.$url_friendly;
                                                	$precio_remapte = \Tools::moneyFormat($inf_lot->himp_csub);
                                                	$precio_limpio = \Tools::moneyFormat($inf_lot->base_csub,false,2);
                                                	$comision = \Tools::moneyFormat($inf_lot->base_csub + $inf_lot->base_csub_iva,false,2);

                                               		$precio_limpio_calculo =  number_format($inf_lot->himp_csub + $inf_lot->base_csub + $inf_lot->base_csub_iva, 2, '.', '');
                                                	$calc_envio = number_format($inf_lot->himp_csub + $inf_lot->base_csub, 2, '.', '');

                                                	//Calculo total
                                                	$total_remate = $total_remate + $inf_lot->himp_csub;
                                                	$total_base = $total_base + $inf_lot->base_csub;
                                                	$total_iva = $total_iva + $inf_lot->base_csub_iva;
												@endphp


												<tr>
														@if ($all_inf['inf']->compraweb_sub == 'S')
														<div class="checkbox hidden" style="margin-top: 0px;">
															@if($all_inf['inf']->compraweb_sub == 'S')
																<input type="checkbox" checked="" id="add-carrito-{{$inf_lot->cod_sub}}-{{$inf_lot->ref_asigl0}}" class="filled-in add-carrito form-control" name="carrito[{{$inf_lot->sub_csub}}][{{$inf_lot->ref_csub}}][pagar]" >
															@endif
														</div>
														@endif

													<td>
														<a onclick="javascript:document.location='{{$url_friendly}}';"><img
																src="{{ \Tools::url_img("lote_small", $inf_lot->num_hces1, $inf_lot->lin_hces1) }}"
																class="img-responsive"></a>
													</td>


													<td>
														<span>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}
															{{$inf_lot->ref_asigl0}}</span>
														<p class="max-line-2 m-0">
															{!!$inf_lot->desc_hces1!!}</p>
													</td>

													<td>{{ $precio_remapte }} {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</td>

													<td>
														{{ $comision }} {{ trans(\Config::get('app.theme').'-app.lot.eur') }}
													</td>

													<td>
														{{ \Tools::moneyFormat($precio_limpio_calculo,false,2) }} {{ trans(\Config::get('app.theme').'-app.lot.eur') }}
													</td>

													<input class="hide" type="hidden" name="carrito[{{$inf_lot->sub_csub}}][{{$inf_lot->ref_csub}}][envios]" value='1'>
												</tr>



											@endforeach

										</tbody>
									</table>

									{{-- Form para pago por lotes individuales --}}
									@if(!$tipo_pago_global)
										<div class="adj color-letter d-flex align-items-center justify-content-space-between mb-3 mt-2">
												{{-- Muestra linea de precio total y botón de pagar (aparece encima de los lotes de la subasta ??) --}}
												@if($all_inf['inf']->compraweb_sub == 'S' && !empty(Config::get('app.merchantIdUP2')))
													<div class="total-price">{{ trans(\Config::get('app.theme').'-app.user_panel.total_price') }} <span class="precio_final_{{$all_inf['inf']->cod_sub}}">0</span> {{ trans(\Config::get('app.theme').'-app.lot.eur') }} </div>
													<button style="margin-left: 15px;" type="button" class="secondary-button   submit_carrito btn btn-step-reg2"  cod_sub="{{$all_inf['inf']->cod_sub}}" class="btn btn-step-reg" disabled>{{ trans(\Config::get('app.theme').'-app.user_panel.pay') }}</button>
												@endif
											</div>
										</form>
                                    @endif

								</div>
							</td>
						</tr>

						{{-- con separacion entre subastas --}}
						{{--<tr class="separator" style="height: 30px"></tr>--}}

					@endforeach

					@if($tipo_pago_global)
						</form>
					@endif
				</table>
				@endif

				{{-- Pagadas --}}
				@if(!empty($all_adj_pag))
				<div class="user-account-title-content mt-3 mb-3">
                    <div class="user-account-menu-title">{{ trans(\Config::get('app.theme').'-app.user_panel.bills') }}</div>
				</div>

				<table class="table">

					@foreach($all_adj_pag as $key_sub => $all_inf)
                        @php
                        	$total_remate = 0;
                            $total_base = 0;
                            $total_iva = 0;
                            $precio_global=0;
						@endphp

						<tr>
							<td colspan="12" data-toggle="collapse"
								class="accordion-toggle title-sub-list accordion-{{$all_inf['inf']->cod_sub}}_pag"
								data-target="#{{$all_inf['inf']->cod_sub}}_pag">
								<div class="d-flex align-items-center">
									<span class="w-100">{{$all_inf['inf']->name}}</span>
									<i style="float: right; font-size: 14px;" class="fas fa-plus"></i>
								</div>
							</td>
						</tr>

						<tr>
							<td colspan="12" class="hiddenRow">
								<div class="accordian-body collapse" id="{{$all_inf['inf']->cod_sub}}_pag">
									<table class="table table-condensed table-to-card" id="{{$all_inf['inf']->cod_sub}}_table">

										<thead style="background-color: #f8f9fa">
											<tr>
												<th data-card-title class="col-xs-1"></th>
												<th class="col-xs-5">{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}</th>
												<th class="col-xs-2">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</th>
												<th class="col-xs-2">{{ trans(\Config::get('app.theme').'-app.user_panel.price_comision') }}</th>
												<th class="col-xs-2">{{ trans(\Config::get('app.theme').'-app.user_panel.paid_out') }}</th>
											</tr>
										</thead>

										<tbody>

											@foreach($all_inf['lotes'] as $inf_lot)

												@php
													$url_friendly = str_slug($inf_lot->titulo_hces1);
                                                	$url_friendly = \Routing::translateSeo('lote').$inf_lot->cod_sub."-".str_slug($inf_lot->name).'-'.$inf_lot->id_auc_sessions."/".$inf_lot->ref_asigl0.'-'.$inf_lot->num_hces1.'-'.$url_friendly;
                                                	$precio_remapte = \Tools::moneyFormat($inf_lot->himp_csub);
                                                	$precio_limpio = \Tools::moneyFormat($inf_lot->base_csub,false,2);
                                                	$comision = \Tools::moneyFormat($inf_lot->base_csub + $inf_lot->base_csub_iva,false,2);

                                               		$precio_limpio_calculo =  number_format($inf_lot->himp_csub + $inf_lot->base_csub + $inf_lot->base_csub_iva, 2, '.', '');
                                                	$calc_envio = number_format($inf_lot->himp_csub + $inf_lot->base_csub, 2, '.', '');

                                                	//Calculo total
                                                	$total_remate = $total_remate + $inf_lot->himp_csub;
                                                	$total_base = $total_base + $inf_lot->base_csub;
                                                	$total_iva = $total_iva + $inf_lot->base_csub_iva;
												@endphp


												<tr>
														@if ($all_inf['inf']->compraweb_sub == 'S')
														<div class="checkbox hidden" style="margin-top: 0px;">
															@if($all_inf['inf']->compraweb_sub == 'S')
																<input type="checkbox" checked="" id="add-carrito-{{$inf_lot->cod_sub}}-{{$inf_lot->ref_asigl0}}" class="filled-in add-carrito form-control" name="carrito[{{$inf_lot->sub_csub}}][{{$inf_lot->ref_csub}}][pagar]" >
															@endif
														</div>
														@endif

													<td>
														<a onclick="javascript:document.location='{{$url_friendly}}';">
															<img src="{{ \Tools::url_img("lote_small", $inf_lot->num_hces1, $inf_lot->lin_hces1) }}" class="img-responsive">
														</a>
													</td>


													<td>
														<span>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}
															{{$inf_lot->ref_asigl0}}</span>
														<p class="max-line-2 m-0">
															{!!$inf_lot->desc_hces1!!}</p>
													</td>

													<td>{{ $precio_remapte }} {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</td>

													<td>
														{{ $comision }} {{ trans(\Config::get('app.theme').'-app.lot.eur') }}
													</td>

													<td>
														{{ \Tools::moneyFormat($precio_limpio_calculo,false,2) }} {{ trans(\Config::get('app.theme').'-app.lot.eur') }}
													</td>
												</tr>

											@endforeach

										</tbody>
									</table>

								</div>
							</td>
						</tr>

						{{-- con separacion entre subastas --}}
						{{--<tr class="separator" style="height: 30px"></tr>--}}

					@endforeach

				</table>
				@endif

            </div>
        </div>
    </div>
</div>

<script>
    $( document ).ready(function() {
         reload_carrito();
	});
	$('.table').on('hide.bs.collapse', function (e) {
		$(`.accordion-${e.target.id} i`).removeClass('fa-minus').addClass('fa-plus');
	})
	$('.table').on('show.bs.collapse', function (e) {
		$(`.accordion-${e.target.id} i`).removeClass('fa-plus').addClass('fa-minus');
	})
</script>
@stop

@push('scripts')
	<script src="{{ URL::asset('js/tableToCards.js') }}"></script>
@endpush
