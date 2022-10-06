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
                <?php $tab="orders";?>
                @include('pages.panel.menu_micuenta')
            </div>
            <div class="col-xs-12 col-md-9 col-lg-9 ">

				<div class="user-account-title-content mb-3">
                    <div class="user-account-menu-title">{{ trans(\Config::get('app.theme').'-app.user_panel.orders') }}</div>
				</div>


				<table class="table">

					@foreach($data['values'] as $key_sub => $all_inf)

					<tr>
						<td colspan="12" data-toggle="collapse" class="accordion-toggle title-sub-list accordion-{{$all_inf['inf']->cod_sub}}" data-target="#{{$all_inf['inf']->cod_sub}}">
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
											<th class="col-xs-4">{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}</th>
											<th class="col-xs-1">{{ trans(\Config::get('app.theme').'-app.user_panel.date') }}</th>
											<th class="col-xs-2">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</th>
											<th class="col-xs-2">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</th>
											<th class="col-xs-2">{{ trans(\Config::get('app.theme').'-app.sheet_tr.your_actual_bid') }}</th>
										</tr>
									</thead>

									<tbody>

										@foreach($all_inf['lotes'] as $inf_lot)
                                            @php
                                            $url_friendly = str_slug($inf_lot->titulo_hces1);
                                            $url_friendly = \Routing::translateSeo('lote').$inf_lot->cod_sub."-".str_slug($inf_lot->session_name).'-'.$inf_lot->id_auc_sessions."/".$inf_lot->ref_asigl0.'-'.$inf_lot->num_hces1.'-'.$url_friendly;
											@endphp

											<tr>
												<td>
													<a onclick="javascript:document.location='{{$url_friendly}}';"><img src="{{ \Tools::url_img("lote_small", $inf_lot->num_hces1, $inf_lot->lin_hces1) }}" class="img-responsive"></a>
												</td>
												<td>
													<p class="td-desciption">{!!$inf_lot->desc_hces1!!}</p></td>
												<td>{{$inf_lot->date}}</td>
												<td>{{$inf_lot->formatted_impsalhces_asigl0 }} €</td>
												<td>{{$inf_lot->implic_hces1 }} €</td>

												<td>
													<span
													@if ( ($inf_lot->cod_licit == $inf_lot->licit_winner_bid && ($all_inf["inf"]->tipo_sub=='O' || $all_inf["inf"]->subabierta_sub=='P')) || ($inf_lot->cod_licit == $inf_lot->licit_winner_order && ($all_inf["inf"]->tipo_sub=='W' && $all_inf["inf"]->subabierta_sub!='P')) )
														class="mine"
                                                	@else
                                                    	class="other"
													@endif
												>{{$inf_lot->formatted_imp }} €</span></td>
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

