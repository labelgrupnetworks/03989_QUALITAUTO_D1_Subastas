@extends('layouts.default')

@section('content')
<div class="container user-panel-page orders-page">

	<div class="row">
		<div class="col-lg-3">
			@include('pages.panel.menu_micuenta')
		</div>

		<div class="col-lg-9">
			<h1>{{ trans(\Config::get('app.theme').'-app.user_panel.orders') }}</h1>


			<div class="accordion">
				@foreach($data['values'] as $all_inf)
				<div>
				  <h2 class="accordion-item accordion-header" id="{{ $all_inf['inf']->cod_sub }}-heading">
					<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $all_inf['inf']->cod_sub }}-collapse" aria-expanded="true" aria-controls="{{ $all_inf['inf']->cod_sub }}-collapse">
						{{$all_inf['inf']->name}}
					</button>
				  </h2>
				  <div id="{{ $all_inf['inf']->cod_sub }}-collapse" class="accordion-collapse collapse show" aria-labelledby="#{{$all_inf['inf']->cod_sub}}-heading">
					<div class="accordion-body p-0">
						<div class="table-to-columns">
							<table class="table table-sm align-middle">
								<thead class="table-light">
									<tr>
										<th></th>
										<th>{{ trans("$theme-app.user_panel.lot") }}</th>
										<th style="max-width: 300px">Title</th>
										<th>Precio de salida</th>
										<th>{{ trans("$theme-app.user_panel.actual_bid") }}</th>
										<th>{{ trans("$theme-app.user_panel.mi_puja") }}</th>
										<th>{{ trans("$theme-app.user_panel.bid_date") }}</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									@foreach($all_inf['lotes'] as $inf_lot)
										@php
											$url_friendly = str_slug($inf_lot->titulo_hces1);
											$url_friendly = \Routing::translateSeo('lote').$inf_lot->cod_sub."-".str_slug($inf_lot->session_name).'-'.$inf_lot->id_auc_sessions."/".$inf_lot->ref_asigl0.'-'.$inf_lot->num_hces1.'-'.$url_friendly;

											$refLot = $inf_lot->ref_asigl0;
											#si  tiene el . decimal hay que ver si se debe separar
											if(strpos($refLot,'.')!==false){
												$refLot =str_replace(array(".1",".2",".3", ".4", ".5"), array("-A", "-B", "-C", "-D", "-E"),  $refLot);
											#si hay que recortar
											}elseif( \config::get("app.substrRef")){
												#cogemos solo los últimos x numeros, ya que se usaran hasta 9, los  primeros para diferenciar un lote cuando se ha vuelto a subir a subasta
												#le sumamos 0 para convertirlo en numero y así eliminamos los 0 a la izquierda
												$refLot = substr($refLot,-\config::get("app.substrRef"))+0;
											}
										@endphp
										<tr>
											<td class="td-img">
												<a href="{{$url_friendly}}">
												<img src="{{ \Tools::url_img("lote_small", $inf_lot->num_hces1, $inf_lot->lin_hces1) }}" class="img-fluid">
												</a>
											</td>
											<td data-title="{{ trans("$theme-app.user_panel.lot") }}">
												{{$refLot}}
											</td>
											<td data-title="Title" class="td-title">
												<span class="max-line-2">{!! $inf_lot->descweb_hces1 !!} Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugiat provident eum repellendus aliquid unde ex exercitationem itaque voluptates error odit veniam earum quod, voluptas vel beatae laborum minus ea maxime.</span>
											</td>
											<td data-title="Precio de salida">
												{{$inf_lot->formatted_impsalhces_asigl0 }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
											</td>
											<td data-title="{{ trans("$theme-app.user_panel.actual_bid") }}">
												@if(empty($inf_lot->implic_hces1) || $all_inf["inf"]->tipo_sub == 'W' && $all_inf["inf"]->subabierta_sub == 'N')
													-
												@else
													{{ \Tools::moneyFormat($inf_lot->implic_hces1, trans(\Config::get('app.theme').'-app.subastas.euros')) }}
												@endif
											</td>
											<td data-title="{{ trans("$theme-app.user_panel.mi_puja") }}"
												@class([
													'mine' => ($inf_lot->cod_licit == $inf_lot->licit_winner_bid && ($all_inf["inf"]->tipo_sub=='O' || $all_inf["inf"]->subabierta_sub=='P')) || ($inf_lot->cod_licit == $inf_lot->licit_winner_order && ($all_inf["inf"]->tipo_sub=='W' && $all_inf["inf"]->subabierta_sub == 'O')),
													'other' => !($inf_lot->cod_licit == $inf_lot->licit_winner_bid && ($all_inf["inf"]->tipo_sub=='O' || $all_inf["inf"]->subabierta_sub=='P')) || ($inf_lot->cod_licit == $inf_lot->licit_winner_order && ($all_inf["inf"]->tipo_sub=='W' && $all_inf["inf"]->subabierta_sub == 'O'))
												])
											>
											{{$inf_lot->formatted_imp }} {{trans(\Config::get('app.theme').'-app.subastas.euros')}}
											</td>
											<td data-title="{{ trans("$theme-app.user_panel.bid_date") }}">
												{{$inf_lot->date}}
											</td>
											<td>
												<div class="btn-group">
													<button type="button" class="btn btn-sm d-flex align-items-center p-2 rounded-circle" data-bs-toggle="dropdown" aria-expanded="false">
														<svg class="bi" width="16" height="16" fill="currentColor">
															<use xlink:href="/bootstrap-icons.svg#three-dots-vertical"/>
														</svg>
													</button>

													<ul class="dropdown-menu">
														<li><a class="dropdown-item" href="{{ $url_friendly }}" target="_blank">{{ trans("$theme-app.user_panel.see_lot") }}</a></li>
														{{-- <li><hr class="dropdown-divider"></li> --}}
														@if(config("app.DeleteOrders") && (empty($inf_lot->implic_hces1) || $inf_lot->imp > $inf_lot->implic_hces1))
															<li>
																<a class="dropdown-item delete_order_panel" href="#" ref="{{$inf_lot->ref_asigl0}}" sub="{{$inf_lot->cod_sub}}">
																	{{ trans("$theme-app.user_panel.delete_orden") }}
																</a>
															</li>
														@endif
													  </ul>

												</div>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				  </div>
				</div>
				@endforeach
			</div>

		</div>
	</div>

</div>

{{-- @if($inf_lot->tipop_orlic == 'T')
	{{trans(\Config::get('app.theme').'-app.lot.puja_telefonica')}}<br/>
@endif --}}



@stop


