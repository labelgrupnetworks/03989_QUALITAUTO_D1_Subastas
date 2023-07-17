@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@section('content')
	<div class="color-letter">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 text-center">
					<h1 class="titlePage">{{ trans(\Config::get('app.theme') . '-app.user_panel.mi_cuenta') }}</h1>
				</div>
			</div>
		</div>
	</div>




	<div class="account-user color-letter  panel-user">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-md-3 col-lg-3 account-user-menu">
					<?php $tab = 'orders'; ?>
					@include('pages.panel.menu_micuenta')
				</div>
				<div class="col-xs-12 col-md-9 col-lg-9 ">
					<div class="user-account-title-content">
						<div class="user-account-menu-title">{{ trans(\Config::get('app.theme') . '-app.user_panel.orders') }}</div>
					</div>
					<div class="col-xs-12 no-padding ">
						<div class="panel-group" id="accordion">
							<div class="panel panel-default">
								<?php $count_collapse = 0; ?>
								@foreach ($data['values'] as $key_sub => $all_inf)
									<?php $count_collapse = $count_collapse + 1; ?>
									<div class="panel-heading">
										<div class="panel-title">
											<a class="panel-collapse d-block" id="open_collapse" aria-expanded="true" data-toggle="collapse"
												href="#{{ $all_inf['inf']->cod_sub }}">
												<span class="title-sub-list">{{ $all_inf['inf']->name }}</span>
												<span class="f-right">
													<span class="label-open"
														@if ($count_collapse == 1) style="display: none" @endif>{{ trans(\Config::get('app.theme') . '-app.user_panel.open') }}</span>
													<span class="label-close"
														@if ($count_collapse != 1) style="display: none" @endif>{{ trans(\Config::get('app.theme') . '-app.user_panel.hide') }}</span>
													<img width=10
														src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCIgdmlld0JveD0iMCAwIDk2LjE1NCA5Ni4xNTQiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDk2LjE1NCA5Ni4xNTQ7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8cGF0aCBkPSJNMC41NjEsMjAuOTcxbDQ1Ljk1MSw1Ny42MDVjMC43NiwwLjk1MSwyLjM2NywwLjk1MSwzLjEyNywwbDQ1Ljk1Ni01Ny42MDljMC41NDctMC42ODksMC43MDktMS43MTYsMC40MTQtMi42MSAgIGMtMC4wNjEtMC4xODctMC4xMjktMC4zMy0wLjE4Ni0wLjQzN2MtMC4zNTEtMC42NS0xLjAyNS0xLjA1Ni0xLjc2NS0xLjA1NkgyLjA5M2MtMC43MzYsMC0xLjQxNCwwLjQwNS0xLjc2MiwxLjA1NiAgIGMtMC4wNTksMC4xMDktMC4xMjcsMC4yNTMtMC4xODQsMC40MjZDLTAuMTUsMTkuMjUxLDAuMDExLDIwLjI4LDAuNTYxLDIwLjk3MXoiIGZpbGw9IiMwMDAwMDAiLz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K" />
												</span>
											</a>
										</div>

										<div id="{{ $all_inf['inf']->cod_sub }}" aria-expanded="true"
											class="panel-collapse @if ($count_collapse != 1) collapse @else in @endif <?= count($data['values']) == '1' ? 'in' : ' ' ?>">

											<div class="user-account-heading hidden-xs d-flex align-items-center justify-content-space-between">
												<div class="col-xs-12 col-sm-6  col-one user-account-item">
													{{ trans(\Config::get('app.theme') . '-app.user_panel.lot') }}
												</div>

												<div class="col-xs-12 col-sm-2 col-one user-account-fecha text-center">
													{{ trans(\Config::get('app.theme') . '-app.user_panel.actual_bid') }}
												</div>
												<div class="col-xs-12 col-sm-2  col-one user-account-max-bid text-center">
													{{ trans(\Config::get('app.theme') . '-app.user_panel.mi_puja') }}
												</div>
												<div class="col-xs-12 col-sm-2 col-one user-account-fecha text-center">
													{{ trans(\Config::get('app.theme') . '-app.user_panel.bid_date') }}
												</div>
											</div>
											<div class="user-accout-items-content">
												@foreach ($all_inf['lotes'] as $inf_lot)
													<?php
													$url_friendly = str_slug($inf_lot->titulo_hces1);
													$url_friendly = \Routing::translateSeo('lote') . $inf_lot->cod_sub . '-' . str_slug($inf_lot->session_name) . '-' . $inf_lot->id_auc_sessions . '/' . $inf_lot->ref_asigl0 . '-' . $inf_lot->num_hces1 . '-' . $url_friendly;
													?>
													<div class="user-accout-item-wrapper  col-xs-12 no-padding">
														<div class="d-flex d-block-sm">
															<div class="col-xs-12 col-sm-6  col-one user-account-item ">
																<a href="{{ $url_friendly }}">
																	<div class="col-xs-12 col-sm-3  ">
																		<img src="{{ \Tools::url_img('lote_small', $inf_lot->num_hces1, $inf_lot->lin_hces1) }}"
																			class="img-responsive margin-auto-sm">
																	</div>
																	<div class="col-xs-12 col-sm-9 no-padding">


																		<div class="user-account-item-lot"><span>{{ trans(\Config::get('app.theme') . '-app.user_panel.lot') }}
																				@php
																					$refLot = $inf_lot->ref_asigl0;
																					#si  tiene el . decimal hay que ver si se debe separar
																					if (strpos($refLot, '.') !== false) {
																					    $refLot = str_replace(['.1', '.2', '.3', '.4', '.5'], ['-A', '-B', '-C', '-D', '-E'], $refLot);

																					    #si hay que recortar
																					} elseif (\config::get('app.substrRef')) {
																					    #cogemos solo los últimos x numeros, ya que se usaran hasta 9, los  primeros para diferenciar un lote cuando se ha vuelto a subir a subasta
																					    #le sumamos 0 para convertirlo en numero y así eliminamos los 0 a la izquierda
																					    $refLot = substr($refLot, -\config::get('app.substrRef')) + 0;
																					}
																				@endphp
																				{{ $refLot }}
																			</span></div>

																		<div class="user-account-item-title">

																			{!! $inf_lot->descweb_hces1 !!}
																			<p> {{ trans(\Config::get('app.theme') . '-app.lot.lot-price') }}:
																				{{ $inf_lot->formatted_impsalhces_asigl0 }}
																				{{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}</p>
																		</div>

																	</div>
																	<div class="clearfix"></div>
																</a>
															</div>

															<div class="col-xs-12 col-sm-2  account-item-border ">
																<div class="user-account-item-date d-flex align-items-center justify-content-center d-block-sm">
																	<div class="visible-xs">{{ trans(\Config::get('app.theme') . '-app.user_panel.actual_bid') }}</div>
																	@if (empty($inf_lot->implic_hces1) || ($all_inf['inf']->tipo_sub == 'W' && $all_inf['inf']->subabierta_sub == 'N'))
																		-
																	@else
																		{{ \Tools::moneyFormat($inf_lot->implic_hces1, trans(\Config::get('app.theme') . '-app.subastas.euros')) }}
																	@endif
																</div>
															</div>
															<div class="col-xs-12 col-sm-2  account-item-border ">
																<div class="user-account-item-price  d-flex align-items-center justify-content-center d-block-sm">
																	<div class="visible-xs">
																		{{ trans(\Config::get('app.theme') . '-app.user_panel.mi_puja') }}

																	</div>
																	<div style="text-align: center;"
																		@if (
																			($inf_lot->cod_licit == $inf_lot->licit_winner_bid &&
																				($all_inf['inf']->tipo_sub == 'O' || $all_inf['inf']->subabierta_sub == 'P')) ||
																				($inf_lot->cod_licit == $inf_lot->licit_winner_order &&
																					($all_inf['inf']->tipo_sub == 'W' && $all_inf['inf']->subabierta_sub == 'O'))) class="mine"
																				@elseif($all_inf['inf']->tipo_sub == 'W' && $all_inf['inf']->subabierta_sub != 'O')
																					class=""
																				@else
																					class="other" @endif>


																		{{ $inf_lot->formatted_imp }} {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}
																		<br />
																		@if ($inf_lot->tipop_orlic == 'T')
																			{{ trans(\Config::get('app.theme') . '-app.lot.puja_telefonica') }}<br />
																		@endif
																		@if (\Config::get('app.DeleteOrders') && (empty($inf_lot->implic_hces1) || $inf_lot->imp > $inf_lot->implic_hces1))
																			<input class="btn btn-danger delete_order_panel" type="button" ref="{{ $inf_lot->ref_asigl0 }}"
																				sub="{{ $inf_lot->cod_sub }}"
																				value="{{ trans(\Config::get('app.theme') . '-app.user_panel.delete_orden') }}">
																		@endif


																	</div>


																</div>
															</div>
															<div class="col-xs-12 col-sm-2  account-item-border">
																<div class="user-account-item-date d-flex align-items-center d-block-sm mb-sm-2">
																	<div class="visible-xs">{{ trans(\Config::get('app.theme') . '-app.user_panel.bid_date') }}</div>
																	{{ $inf_lot->date }}
																</div>
															</div>

														</div>
													</div>
												@endforeach
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


@stop
