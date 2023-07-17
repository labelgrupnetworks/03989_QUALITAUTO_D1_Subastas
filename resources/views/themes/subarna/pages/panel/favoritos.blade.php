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
					<?php $tab = 'favorites'; ?>
					@include('pages.panel.menu_micuenta')
				</div>
				<div class="col-xs-12 col-md-9 col-lg-9 ">
					<div class="user-account-title-content">
						<div class="user-account-menu-title">
							{{ trans(\Config::get('app.theme') . '-app.user_panel.favorites') }}</div>
					</div>
					<div class="col-xs-12 no-padding ">
						<div class="panel-group" id="accordion">
							<div class="panel panel-default">

								@foreach ($data['favoritos'] as $key_sub => $all_inf)
									<div class="panel-heading">
										<div class="panel-title">
											<a class="d-block" data-toggle="collapse" href="#{{ $all_inf['inf']->cod_sub }}">
												<span class="title-sub-list">{{ $all_inf['inf']->name }}</span>
												<img width=10 class="f-right"
													src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCIgdmlld0JveD0iMCAwIDk2LjE1NCA5Ni4xNTQiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDk2LjE1NCA5Ni4xNTQ7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8cGF0aCBkPSJNMC41NjEsMjAuOTcxbDQ1Ljk1MSw1Ny42MDVjMC43NiwwLjk1MSwyLjM2NywwLjk1MSwzLjEyNywwbDQ1Ljk1Ni01Ny42MDljMC41NDctMC42ODksMC43MDktMS43MTYsMC40MTQtMi42MSAgIGMtMC4wNjEtMC4xODctMC4xMjktMC4zMy0wLjE4Ni0wLjQzN2MtMC4zNTEtMC42NS0xLjAyNS0xLjA1Ni0xLjc2NS0xLjA1NkgyLjA5M2MtMC43MzYsMC0xLjQxNCwwLjQwNS0xLjc2MiwxLjA1NiAgIGMtMC4wNTksMC4xMDktMC4xMjcsMC4yNTMtMC4xODQsMC40MjZDLTAuMTUsMTkuMjUxLDAuMDExLDIwLjI4LDAuNTYxLDIwLjk3MXoiIGZpbGw9IiMwMDAwMDAiLz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K" />
											</a>
										</div>
										<div id="{{ $all_inf['inf']->cod_sub }}"
											class="panel-collapse collapse <?= count($data['favoritos']) == '1' ? 'in' : ' ' ?>">

											<div class="user-account-heading hidden-xs d-flex align-items-center justify-content-space-between">
												<div class="col-xs-12 col-sm-7 col-lg-8 col-one user-account-item">
													{{ trans(\Config::get('app.theme') . '-app.user_panel.lot') }}
												</div>
												<div class="col-xs-12 col-sm-2 col-one user-account-fecha">
													{{ trans(\Config::get('app.theme') . '-app.lot.puja_actual') }}
												</div>
												<div class="col-xs-12 col-sm-3 col-lg-2 col-one user-account-max-bid">

												</div>
											</div>
											<div class="user-accout-items-content">
												@foreach ($all_inf['lotes'] as $inf_lot)
													<?php
													$url_friendly = str_slug($inf_lot->titulo_hces1);
													$url_friendly = \Routing::translateSeo('lote') . $inf_lot->cod_sub . '-' . str_slug($inf_lot->name) . '-' . $inf_lot->id_auc_sessions . '/' . $inf_lot->ref_asigl0 . '-' . $inf_lot->num_hces1 . '-' . $url_friendly;
													?>
													<div class="user-accout-item-wrapper  col-xs-12 no-padding">
														<div class="d-flex d-block-sm">
															<div class="col-xs-12 col-sm-7 col-lg-8 col-one user-account-item ">
																<a href='{{ $url_friendly }}'>
																	<div class="col-xs-12 col-sm-3 no-padding ">
																		<img src="{{ \Tools::url_img('lote_small', $inf_lot->num_hces1, $inf_lot->lin_hces1) }}"
																			class="img-responsive margin-auto-sm">
																	</div>
																	<div class="col-xs-12 col-sm-9 no-padding">
																		@if (strtoupper($inf_lot->tipo_sub) == 'O' || strtoupper($inf_lot->tipo_sub) == 'P')
																			<div class="user-account-item-auction text-right">
																				<small>{{ trans(\Config::get('app.theme') . '-app.user_panel.auctions_online') }}</small>
																			</div>
																		@endif
																		<div class="user-account-item-title">
																			{{ $inf_lot->titulo_hces1 }}</div>

																		<div class="user-account-item-lot">
																			<span>{{ trans(\Config::get('app.theme') . '-app.user_panel.lot') }}

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


																			</span>
																		</div>
																		<div class="user-account-item-text">
																			<div>{{ $inf_lot->cod_sub }}</div>
																		</div>
																	</div>
																</a>
															</div>
															<div class="col-xs-12 col-sm-2 col-lg-2 account-item-border">
																<div class="user-account-item-price d-flex align-items-center d-block-sm">
																	<div class="visible-xs">
																		{{ trans(\Config::get('app.theme') . '-app.lot.puja_actual') }}
																	</div>
																	<div
																		@if (
																			!empty($inf_lot->pujas[0]) &&
																				!empty($data['codigos_licitador'][$inf_lot->cod_sub]) &&
																				$data['codigos_licitador'][$inf_lot->cod_sub] == $inf_lot->pujas[0]->cod_licit) class="mine"
															@else
															class="other" @endif>
																		@php
																			// Con esta condición muestra o oculta el precio
																			$conditionForSeePrice = empty($inf_lot->pujas[0]->formatted_imp_asigl1) || ($all_inf['inf']->tipo_sub == 'W' && $all_inf['inf']->subabierta_sub == 'N');
																		@endphp
																		<?= $conditionForSeePrice ? '-' : $inf_lot->pujas[0]->formatted_imp_asigl1 . ' ' . trans(\Config::get('app.theme') . '-app.subastas.euros') ?>
																	</div>
																</div>
															</div>
															<div class="col-xs-12 col-sm-3 col-lg-2 account-item-border">
																<div class="user-account-item-price  d-flex align-items-center d-block-sm mb-sm-2">
																	<div><a title="{{ trans(\Config::get('app.theme') . '-app.lot.del_from_fav') }}" class="d-block"
																			href="javascript:action_fav_lote('remove','{{ $inf_lot->ref_asigl0 }}','{{ $inf_lot->cod_sub }}',' <?= $data['codigos_licitador'][$inf_lot->cod_sub] ?>')">{{ trans(\Config::get('app.theme') . '-app.lot.del_from_fav') }}</a>
																	</div>
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


	<section class="panel-user hide">
		<div class="container panel">
			<div class="row">
				<div class="col-xs-12 col-sm-12">
					<?php $tab = 'favorites'; ?>
					@include('pages.panel.menu_micuenta')
					<div class="panel-group" id="accordion">
						<div class="panel panel-default">
							@foreach ($data['favoritos'] as $key_sub => $all_inf)
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" href="#{{ $all_inf['inf']->cod_sub }}">{{ $all_inf['inf']->name }}</a>
									</h4>
								</div>
								<div id="{{ $all_inf['inf']->cod_sub }}"
									class="panel-collapse collapse <?= count($data['favoritos']) == '1' ? 'in' : ' ' ?>">
									<div class="panel-body">
										<div class="table-responsive">
											<table class="table table-striped table-custom">
												<thead>
													<tr>
													<tr>
														<th> </th>
														<th>{{ trans(\Config::get('app.theme') . '-app.user_panel.lot') }}</th>
														<th>{{ trans(\Config::get('app.theme') . '-app.user_panel.auction') }}
														</th>
														<th>{{ trans(\Config::get('app.theme') . '-app.user_panel.name') }}</th>
														<th>{{ trans(\Config::get('app.theme') . '-app.user_panel.date') }}</th>
														<th>{{ trans(\Config::get('app.theme') . '-app.user_panel.mi_puja') }}
														</th>
													</tr>
													</tr>
												</thead>
												<tbody>
													@foreach ($all_inf['lotes'] as $inf_lot)
														<?php
														$url_friendly = str_slug($inf_lot->titulo_hces1);
														$url_friendly = \Routing::translateSeo('lote') . $inf_lot->cod_sub . '-' . str_slug($inf_lot->name) . '-' . $inf_lot->id_auc_sessions . '/' . $inf_lot->ref_asigl0 . '-' . $inf_lot->num_hces1 . '-' . $url_friendly;
														?>

														<tr class='{{ $inf_lot->ref_asigl0 }}-{{ $inf_lot->cod_sub }}'>
															<td><img src="{{ \Tools::url_img('lote_small', $inf_lot->num_hces1, $inf_lot->lin_hces1) }}"
																	height="42" width="42"></td>
															<td>{{ $inf_lot->ref_asigl0 }}</td>
															@if (strtoupper($inf_lot->tipo_sub) == 'O' || strtoupper($inf_lot->tipo_sub) == 'P')
																<td>{{ trans(\Config::get('app.theme') . '-app.user_panel.auctions_online') }}
																</td>
															@else
																<td>{{ $inf_lot->cod_sub }}</td>
															@endif
															<td>{{ $inf_lot->titulo_hces1 }}</td>
															<td>
																<?= empty($inf_lot->pujas->formatted_imp_asigl1) ? '-' : $inf_lot->pujas->formatted_imp_asigl1 . ' ' . trans(\Config::get('app.theme') . '-app.subastas.euros') ?>
															</td>
															<td><a title="{{ trans(\Config::get('app.theme') . '-app.lot.del_from_fav') }}" class="btn btn-del"
																	href="javascript:action_fav_lote('remove','{{ $inf_lot->ref_asigl0 }}','{{ $inf_lot->cod_sub }}',' <?= $data['codigos_licitador'][$inf_lot->cod_sub] ?>')">{{ trans(\Config::get('app.theme') . '-app.lot.del_from_fav') }}</a>
															</td>
														</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@stop
