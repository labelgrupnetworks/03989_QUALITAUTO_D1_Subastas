@extends('layouts.default')

@section('content')
<div class="container user-panel-page favorites-page">
	<div class="row">
		<div class="col-lg-3">
			@include('pages.panel.menu_micuenta')
		</div>

		<div class="col-lg-9">
			<h1>{{ trans(\Config::get('app.theme').'-app.user_panel.favorites') }}</h1>

			<div class="accordion">
				@foreach($data['favoritos'] as $all_inf)
				<div class="">
					<h2 class="accordion-item accordion-header" id="{{ $all_inf['inf']->cod_sub }}-heading">
						<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $all_inf['inf']->cod_sub }}-collapse" aria-expanded="true" aria-controls="{{ $all_inf['inf']->cod_sub }}-collapse">
							{{$all_inf['inf']->name}}
						</button>
					  </h2>

					  <div id="{{ $all_inf['inf']->cod_sub }}-collapse" class="accordion-collapse collapse show" aria-labelledby="#{{$all_inf['inf']->cod_sub}}-heading">
						<div class="accordion-body p-0">
							<div class="table-responsive table-to-columns">

								<table class="table table-sm align-middle">
									<thead class="table-light">
										<tr>
											<th></th>
											<th>{{ trans("$theme-app.user_panel.lot") }}</th>
											<th style="max-width: 300px">Title</th>
											<th>Precio de salida</th>
											<th>{{ trans("$theme-app.user_panel.actual_bid") }}</th>
											<th>{{ trans("$theme-app.user_panel.mi_puja") }}</th>
											<th></th>
										</tr>
									</thead>

									<tbody>
										@foreach($all_inf['lotes'] as $inf_lot)
											@php
												$url_friendly = str_slug($inf_lot->titulo_hces1);
                                            	$url_friendly = \Routing::translateSeo('lote').$inf_lot->cod_sub."-".str_slug($inf_lot->name).'-'.$inf_lot->id_auc_sessions."/".$inf_lot->ref_asigl0.'-'.$inf_lot->num_hces1.'-'.$url_friendly;

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
													<span class="max-line-2">{!! $inf_lot->descweb_hces1 !!}</span>
												</td>
												<td data-title="Precio de salida">
													{{$inf_lot->formatted_impsalhces_asigl0 }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
												</td>
												<td data-title="{{ trans("$theme-app.user_panel.actual_bid") }}">
													{{ \Tools::moneyFormat($inf_lot->actual_bid ?? 0, trans(\Config::get('app.theme').'-app.subastas.euros')) }}
												</td>
												@php
													$pujas = collect($inf_lot->pujas);
													$myMaxBid = $pujas->where('cod_licit', $data["codigos_licitador"][$inf_lot->cod_sub])->first();
												@endphp
												<td data-title="{{ trans("$theme-app.user_panel.mi_puja") }}" @class(['mine' => $myMaxBid?->rn == 1, 'other' => $myMaxBid?->rn != 1])>
													{{ \Tools::moneyFormat($myMaxBid?->imp_asigl1, trans(\Config::get('app.theme').'-app.subastas.euros')) }}
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
															<li>
																<a class="dropdown-item"
																	href="javascript:action_fav_lote('remove','{{ $inf_lot->ref_asigl0 }}','{{$inf_lot->cod_sub }}',' <?= $data['codigos_licitador'][$inf_lot->cod_sub] ?>')">
																	{{trans(\Config::get('app.theme').'-app.lot.del_from_fav')}}
																</a>
															</li>

															{{-- <li><hr class="dropdown-divider"></li> --}}
														</ul>

													</div>
												</td>


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
@stop
