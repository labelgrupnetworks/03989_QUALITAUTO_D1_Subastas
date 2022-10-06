@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<script>
    var pendientes =  @json($data["pending"])
</script>
<!-- titulo -->
<div class="color-letter">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                <h1 class="titlePage">{{ trans(\Config::get('app.theme').'-app.user_panel.mi_cuenta') }}</h1>
                </div>
            </div>
        </div>
    </div>




<div class="account-user color-letter  panel-user pendientes_pago">
        <div class="container">
            <div class="row">

				<!-- Menu -->
                <div class="col-xs-12 col-md-3 col-lg-3 account-user-menu">
					@php
					$tab="bills";
					@endphp
                    @include('pages.panel.menu_micuenta')
                </div>

                <div class="col-xs-12 col-md-9 col-lg-9 ">

					<!-- Facturas -->
                    <div class="user-account-title-content mb-3">
                        <div class="user-account-menu-title">{{ trans(\Config::get('app.theme').'-app.user_panel.pending_bills') }}</div>
					</div>

					{{-- Pendientes de pago --}}
					<table class="table">
					<form  id="pagar_fact">
						<input name="_token" type="hidden" value="{{ csrf_token() }}" />

							<tr>
								<td colspan="12" data-toggle="collapse" class="accordion-toggle title-sub-list accordion-pendings" data-target="#pendings">
									<div class="d-flex align-items-center">
										<span class="w-100">{{ trans(\Config::get('app.theme').'-app.user_panel.still_paid') }}</span>
										<i style="float: right; font-size: 14px;" class="fas fa-plus"></i>
									</div>
								</td>
							</tr>

							<tr>
								<td colspan="12" class="hiddenRow">
									<div class="accordian-body collapse" id="pendings">

										<table class="table table-condensed" id="pendings_table">

											<thead style="background-color: #f8f9fa">
												<tr>
													<th class="col-xs-1"></th>
													<th class="col-xs-2">{{ trans(\Config::get('app.theme').'-app.user_panel.short_pdf') }}</th>
													<th class="col-xs-5">{{ trans(\Config::get('app.theme').'-app.user_panel.pending_bills') }}</th>
													<th class="col-xs-2 hidden-xs hidden-sm">{{ trans(\Config::get('app.theme').'-app.user_panel.total_fact') }}</th>
													<th class="col-xs-2">{{ trans(\Config::get('app.theme').'-app.user_panel.total_price_fact') }}</th>
												</tr>
											</thead>



										<tbody>
											@foreach($data['pending'] as $key_bill => $pendiente)

											@include('pages.panel.bills.bills',
												['anum' => $pendiente->anum_pcob,
												'num' => $pendiente->num_pcob,
												'efec' => $pendiente->efec_pcob,
												'fec' => $pendiente->fec_pcob,
												'imp' => $pendiente->imp_pcob,
												'bill' => $pendiente,
												'info_factura' => $data['inf_factura'],
												'tipo_tv' => $data['tipo_tv']
												])
											@endforeach
										</tbody>

										</table>

										<div class="col-xs-12">
											<div class="facturacion_info_data">
												<div class="d-flex">
													<div style="flex:1"></div>
													<div>
														<div class="importe_total adj" style="">
															<span>{{ trans(\Config::get('app.theme').'-app.user_panel.total_pay_fact') }} </span>
															<span id="total_bills">00</span><span> €</span>
														</div>
														@if(\Config::get("app.PayBizum") || \Config::get("app.PayTransfer"))

															<div class="mt-1">
																<input id="paycreditcard"  type="radio" name="paymethod" value="creditcard" checked="checked">
																<label for="paycreditcard"> {{ trans(\Config::get('app.theme').'-app.user_panel.pay_creditcard') }}  <?php /* <span class="fab fa-cc-visa" style="font-size: 20px;"></span>*/ ?>  </span></label>
																<br>
																@if(\Config::get("app.PayBizum") )
																	<input id="paybizum" type="radio" name="paymethod" value="bizum">
																	<label for="paybizum" >  {{ trans(\Config::get('app.theme').'-app.user_panel.pay_bizum') }}  <?php /*<img src="/default/img/logos/bizum-blue.png" style="height: 20px;">*/ ?> </label>
																@endif

																<br>
																@if(\Config::get("app.PayTransfer"))
																	<input id="paytransfer" type="radio" name="paymethod" value="transfer">
																	<label for="paytransfer">{{ trans(\Config::get('app.theme').'-app.user_panel.pay_transfer') }}</label>
																@endif

															</div>

														@endif
														<div class="mt-1 text-right">
															<button id="btoLoader"  class="btn btn-custom hidden" type="button"><div class="loader"></div></button>
															<button id="submit_fact" style="" type="button" class="secondary-button " >{{ trans(\Config::get('app.theme').'-app.user_panel.pay') }}</button>
														</div>
													</div>
												</div>
											</div>
										</div>

									</div>
								</td>
							</tr>

					</form>
					</table>

					{{-- Pagadas --}}
					<table class="table">
						<tr>
							<td colspan="12" data-toggle="collapse" class="accordion-toggle title-sub-list accordion-bills"
								data-target="#bills">
								<div class="d-flex align-items-center">
									<span class="w-100">{{ trans(\Config::get('app.theme').'-app.user_panel.bills') }}</span>
									<i style="float: right; font-size: 14px;" class="fas fa-plus"></i>
								</div>
							</td>
						</tr>

						<tr>
							<td colspan="12" class="hiddenRow">
								<div class="accordian-body collapse" id="bills">

									<table class="table table-condensed" id="bills_table">

										<thead style="background-color: #f8f9fa">
											<tr>
												<th class="col-xs-1"></th>
												<th class="col-xs-2">{{ trans(\Config::get('app.theme').'-app.user_panel.short_pdf') }}</th>
												<th class="col-xs-5">{{ trans(\Config::get('app.theme').'-app.user_panel.pending_bills') }}
												</th>
												<th class="col-xs-2 hidden-xs hidden-sm">{{ trans(\Config::get('app.theme').'-app.user_panel.total_fact') }}
												</th>
												<th class="col-xs-2">
													{{ trans(\Config::get('app.theme').'-app.user_panel.paid_out') }}</th>
											</tr>
										</thead>

										<tbody>
											@foreach($data['bills'] as $key_bill => $bill)

											@include('pages.panel.bills.bills',
											['anum' => $bill->afra_cobro1,
											'num' => $bill->nfra_cobro1,
											'fec' => $bill->fec_cobro1,
											'imp' => $bill->imp_cobro1,
											'bill' => $bill,
											'info_factura' => $data['inf_factura_pag'],
											'tipo_tv' => $data['tipo_tv_pag']
											])

											@endforeach
										</tbody>

									</table>
								</div>
							</td>
						</tr>
					</table>

                </div>
            </div>
        </div>
</div>

<script>
    $( document ).ready(function() {
        reload_facturas();
	});
	$('.table').on('hide.bs.collapse', function (e) {
		$(`.accordion-${e.target.id} i`).removeClass('fa-minus').addClass('fa-plus');
	});
	$('.table').on('show.bs.collapse', function (e) {
		$(`.accordion-${e.target.id} i`).removeClass('fa-plus').addClass('fa-minus');
	});
</script>
@stop
