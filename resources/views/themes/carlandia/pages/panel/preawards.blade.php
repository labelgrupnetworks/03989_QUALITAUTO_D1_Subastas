@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')

@php
	$tab = '';
@endphp


<div class="color-letter">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 text-center">
				<h1 class="titlePage">{{ trans(\Config::get('app.theme').'-app.user_panel.mi_cuenta') }}</h1>
			</div>
		</div>
	</div>
</div>

<div class="account-user color-letter  panel-user">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-3 col-lg-3 account-user-menu">
				@include('pages.panel.menu_micuenta')
			</div>
			<div class="col-xs-12 col-md-9 col-lg-9 ">
				<div class="user-account-title-content">
					<div class="user-account-menu-title">{{ trans("$theme-app.user_panel.pre_awards") }}</div>
					<p>Vehículos que te has adjudicado (comprado) en venta directa o subasta con pago de señal pendiente.</p>
				</div>

				<div class="col-xs-12 no-padding ">
					<div class="panel-group" id="accordion">
						<div class="panel panel-default">

							<div class="panel-heading">

								<div
									class="user-account-heading hidden-xs d-flex align-items-center justify-content-space-between">
									<div class="col-xs-12 col-sm-6 col-one user-account-item">
										{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}
									</div>

									<div class="col-xs-12 col-sm-2 col-one user-account-fecha text-center">
										{{ trans("$theme-app.user_panel.sale_price") }}
									</div>

									<div class="col-xs-12 col-sm-2  col-one user-account-max-bid text-center">
										{{ trans("$theme-app.user_panel.purchase_amount") }}
									</div>

									<div class="col-xs-12 col-sm-2 col-one user-account-fecha text-center">
										{{ trans(\Config::get('app.theme').'-app.user_panel.bid_date') }}
									</div>
								</div>

								<div class="user-accout-items-content">
									@foreach($data['values'] as $inf_lot)

									@php
										$url_friendly = str_slug($inf_lot->titulo_hces1);
                                        $url_friendly = \Routing::translateSeo('lote').$inf_lot->sub_asigl0."-".str_slug($inf_lot->name).'-'.$inf_lot->id_auc_sessions."/".$inf_lot->ref_asigl0.'-'.$inf_lot->num_hces1.'-'.$url_friendly;
                                    @endphp
									<div class="user-accout-item-wrapper col-xs-12 no-padding">
										<div class="d-flex">
											<div class="col-xs-12 col-sm-6  col-one user-account-item ">
												<a href="{{$url_friendly}}">
													<div class="col-xs-12 col-sm-3">
														<img src="{{ \Tools::url_img("lote_small", $inf_lot->num_hces1, $inf_lot->lin_hces1) }}" class="img-responsive">
													</div>
													<div class="col-xs-12 col-sm-9 no-padding account-item-description">

														<div class="user-account-item-lot"><span>{{
																trans(\Config::get('app.theme').'-app.user_panel.offer')}}
																{{$inf_lot->ref_asigl0}}</span></div>

														<div class="user-account-item-title">
															{!! $inf_lot->descweb_hces1 !!}
														</div>

													</div>
													{{-- <div class="clearfix"></div> --}}
												</a>
											</div>

											<div class="col-xs-12 col-sm-2  account-item-border ">
												<div
													class="user-account-item-date d-flex align-items-center justify-content-center">

													<div class="visible-xs">{{ trans("$theme-app.user_panel.sale_price") }}
													</div>
													{{ \Tools::moneyFormat($inf_lot->impsalhces_asigl0, trans("$theme-app.subastas.euros")) }}

												</div>
											</div>

											<div class="col-xs-12 col-sm-2  account-item-border ">
												<div
													class="user-account-item-date d-flex align-items-center justify-content-center">

													<div class="visible-xs">{{
														trans("$theme-app.user_panel.purchase_amount") }}</div>
													{{ \Tools::moneyFormat($inf_lot->imp_asigl1, trans("$theme-app.subastas.euros")) }}

												</div>
											</div>

											<div class="col-xs-12 col-sm-2  account-item-border ">
												<div class="user-account-item-date d-flex align-items-center">
													<div class="visible-xs">{{
														trans(\Config::get('app.theme').'-app.user_panel.bid_date') }}
													</div>

													{{ \Tools::getDateFormat($inf_lot->fec_asigl1, 'Y-m-d H:i:s', 'd/m/Y H:i:s') }}
												</div>
											</div>

										</div>
									</div>

									@endforeach
								</div>
							</div>

						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>


@stop
