@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 titlePage">
			<h1 {{-- class="titlePage" --}}>{{ trans(\Config::get('app.theme').'-app.user_panel.mi_cuenta') }}</h1>
			<p class="mini-underline"></p>
		</div>
	</div>
</div>
<div class="container panel">
	<div class="row">
		<div class="col-xs-12 col-sm-12">
            <?php $tab="favorites";?>
            @include('pages.panel.menu_micuenta')
                    <div class="panel-group" id="accordion">
                        <div class="panel panel-default">
                            @foreach($data['favoritos'] as $key_sub => $all_inf)
								@php
									$totalSalida = 0;
									$totalPrecio = 0;
									$totalMiPuja = 0;
								@endphp

                                    <div class="panel-heading">

                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" href="#{{$all_inf['inf']->cod_sub}}">
                                         {{$all_inf['inf']->name}}
                                          </a>
                                        </h4>

                                    </div>

                              <div id="{{$all_inf['inf']->cod_sub}}" class="panel-collapse collapse <?= count($data['favoritos']) == '1'? 'in':' ';?>">
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-custom">
                                            <thead>
                                                <tr>
                                                    <tr>
                                                        <th> </th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}</th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.auction') }}</th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.name') }}</th>
														@if(!empty($all_inf) &&  $all_inf['inf']->tipo_sub != 'V' )
															<th class="text-right">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</th>
															<th class="text-right">{{ trans(\Config::get('app.theme').'-app.user_panel.actual_price') }}</th>
                                                            <th class="text-right">{{ trans(\Config::get('app.theme').'-app.user_panel.mi_puja') }}</th>
                                                        @else
                                                            <th class="text-right">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</th>
                                                        @endif
                                                        <th> </th>
                                                    </tr>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach(collect($all_inf['lotes'])->sortBy('ref_asigl0') as $inf_lot)
												<?php
                                                    $url_friendly = str_slug($inf_lot->titulo_hces1);
                                                    $url_friendly = \Routing::translateSeo('lote').$inf_lot->cod_sub."-".str_slug($inf_lot->name).'-'.$inf_lot->id_auc_sessions."/".$inf_lot->ref_asigl0.'-'.$inf_lot->num_hces1.'-'.$url_friendly;
                                                ?>

                                                <tr class='{{$inf_lot->ref_asigl0}}-{{$inf_lot->cod_sub}}'>
                                                    <td><a href="{{$url_friendly}}"><img src="{{ \Tools::url_img("lote_small", $inf_lot->num_hces1, $inf_lot->lin_hces1) }}" height="42"></td></a>
                                                    <td>&nbsp;&nbsp;{{$inf_lot->ref_asigl0}}</td>
                                                    @if(strtoupper($inf_lot->tipo_sub) == 'O' || strtoupper($inf_lot->tipo_sub) == 'P')
                                                        <td>&nbsp;&nbsp;{{ trans(\Config::get('app.theme').'-app.user_panel.auctions_online') }}</td>
                                                    @else
                                                        <td>&nbsp;&nbsp;{{$inf_lot->cod_sub}}</td>
                                                    @endif

													<td style="padding-left:10px;"><a href="{{$url_friendly}}" style="text-decoration: none;color: #333;">{{$inf_lot->titulo_hces1}}</a></td>

													@if($inf_lot->tipo_sub != 'V' )

													<td style='padding-right:10px;text-align:right'>{{ $inf_lot->formatted_impsalhces_asigl0 }} €</td>


													<td style='padding-right:10px;text-align:right'>
														@php
															//añadimos las ordenes como valor actual
															$subs = new \App\Models\Subasta();
															$maxOrder = $subs->price_open_auction($inf_lot->impsalhces_asigl0, $inf_lot->ordenes);
															$maxBid = $inf_lot->implic_hces1;
															$maxBidOrOrder = max($maxOrder, $maxBid);

															$totalPrecio += $maxBidOrOrder;
															$totalSalida += $inf_lot->impsalhces_asigl0;
														@endphp
														{{ \Tools::moneyFormat($maxBidOrOrder) }} €
													</td>
													@endif

                                                    <td style='padding-right:10px;text-align:right'>

														@if($inf_lot->tipo_sub != 'V' )
															{{-- Buscar también la orden maxima y establecer la mas alta. --}}
															@php
																$thisMaxBid = collect($inf_lot->pujas)->where('cod_licit', $data["codigos_licitador"][$inf_lot->cod_sub])->max('imp_asigl1');
																$thisMaxOrder = collect($inf_lot->ordenes)->where('cod_licit', $data["codigos_licitador"][$inf_lot->cod_sub])->max('himp_orlic');
																$thisMax = \Tools::moneyFormat(max($thisMaxBid, $thisMaxOrder));
																$totalMiPuja += max($thisMaxBid, $thisMaxOrder);
															@endphp

															{{ $thisMax ? $thisMax . ' €' : '-' }}
                                                        @else
                                                            <?= $inf_lot->formatted_impsalhces_asigl0 ?>€
                                                        @endif

                                                    </td>

                                                    <td style="text-align:center"><a title="{{trans(\Config::get('app.theme').'-app.lot.del_from_fav')}}" class="btn btn-del" href="javascript:action_fav_lote('remove','{{ $inf_lot->ref_asigl0 }}','{{$inf_lot->cod_sub }}',' <?= $data['codigos_licitador'][$inf_lot->cod_sub] ?>')">{{trans(\Config::get('app.theme').'-app.lot.del_from_fav')}}</a></td>

                                                </tr>
                                            @endforeach
                                           </tbody>
										   <tfoot>
											   <tr>
												   <td colspan="2"><b>{{ trans(\Config::get('app.theme').'-app.user_panel.total_pay') }}</b></td>
												   <td></td>
												   <td></td>
												   <td class="text-right">{{ \Tools::moneyFormat($totalSalida) . ' €' }}</td>
												   <td class="text-right">{{ \Tools::moneyFormat($totalPrecio) . ' €' }}</td>
												   <td class="text-right">{{ $totalMiPuja ? \Tools::moneyFormat($totalMiPuja) . ' €' : '-' }}</td>
												   <td></td>
											   </tr>
										   </tfoot>
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
@stop

