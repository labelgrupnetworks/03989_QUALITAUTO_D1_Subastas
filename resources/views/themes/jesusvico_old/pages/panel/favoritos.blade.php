@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

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
				<?php $tab="favorites";?>
				@include('pages.panel.menu_micuenta')
			</div>
			<div class="col-xs-12 col-md-9 col-lg-9 ">
				<div class="user-account-title-content mb-3">
					<div class="user-account-menu-title">
						{{ trans(\Config::get('app.theme').'-app.user_panel.favorites') }}</div>
				</div>


				<table class="table">

					@foreach($data['favoritos'] as $key_sub => $all_inf)

					<tr>
						<td colspan="12" data-toggle="collapse"
							class="accordion-toggle title-sub-list accordion-{{$all_inf['inf']->cod_sub}}"
							data-target="#{{$all_inf['inf']->cod_sub}}">
							<div class="d-flex align-items-center">
								<span class="w-100">{{$all_inf['inf']->name}}</span>
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
											<th class="col-xs-5">
												{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}</th>
											<th class="col-xs-2">
												{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</th>
											<th class="col-xs-2">
												{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}
											</th>
											<th data-card-action-links class="col-xs-2">{{ trans(\Config::get('app.theme').'-app.user_panel.delete') }}</th>
										</tr>
									</thead>

									<tbody>

										@foreach($all_inf['lotes'] as $inf_lot)
										@php
										$url_friendly = str_slug($inf_lot->titulo_hces1);
										$url_friendly =
										\Routing::translateSeo('lote').$inf_lot->cod_sub."-".str_slug($inf_lot->name).'-'.$inf_lot->id_auc_sessions."/".$inf_lot->ref_asigl0.'-'.$inf_lot->num_hces1.'-'.$url_friendly;
										@endphp

										<tr>
											<td>
												<a onclick="javascript:document.location='{{$url_friendly}}';"><img
														src="{{ \Tools::url_img("lote_small", $inf_lot->num_hces1, $inf_lot->lin_hces1) }}"
														class="img-responsive"></a>
											</td>
											<td>
												<span>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}
													{{$inf_lot->ref_asigl0}}</span>
												<p class="td-desciption">
													{!!$inf_lot->desc_hces1!!}</p>
											</td>
											<td>{{$inf_lot->formatted_impsalhces_asigl0 }} €</td>
											<td>
												<span
											@if (!empty($inf_lot->pujas[0]) && !empty($data["codigos_licitador"][$inf_lot->cod_sub]) && $data["codigos_licitador"][$inf_lot->cod_sub] == $inf_lot->pujas[0]->cod_licit)
												class="mine"
											@else
												class="other"
											@endif
												>{{ empty($inf_lot->pujas[0]->formatted_imp_asigl1)? '-': $inf_lot->pujas[0]->formatted_imp_asigl1.' €' }}
											</td>

											<td class="delete-fav-table">
												<a title="{{trans(\Config::get('app.theme').'-app.lot.del_from_fav')}}"
													href="javascript:action_fav_lote('remove','{{ $inf_lot->ref_asigl0 }}','{{$inf_lot->cod_sub }}',' <?= $data['codigos_licitador'][$inf_lot->cod_sub] ?>')">
													{{trans(\Config::get('app.theme').'-app.lot.del_from_fav')}}
												</a>
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


			</div>
		</div>
	</div>
</div>

<script>
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
