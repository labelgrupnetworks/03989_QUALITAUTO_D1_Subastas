@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
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

#Inicializa las variables para packengers
$urlToPackengersMaker = "";
$packengersMoneyValue = 0;

?>

{{-- es un sobrecargo en % que se cobrará a los que paguen por la web --}}
@if(Config::get("app.sobreCargoPagoWeb") && is_numeric(Config::get("app.sobreCargoPagoWeb")))
	<script>
		var extraCharge ={{Config::get("app.sobreCargoPagoWeb")}};
	</script>
@endif

<script src="{{ Tools::urlAssetsCache('js/payment.js')}}"></script>
<script>
    var info_lots = $.parseJSON('<?php echo str_replace("\u0022","\\\\\"",json_encode($data["js_item"],JSON_HEX_QUOT)); ?>');
</script>


<div class="color-letter">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                <h1 class="titlePage">{{ trans($theme.'-app.user_panel.mi_cuenta') }}</h1>
                </div>
            </div>
        </div>
    </div>

<div class="account-user color-letter  panel-user">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-3 col-lg-3 account-user-menu">
                <?php $tab="allotments";?>
                @include('pages.panel.menu_micuenta')
            </div>
            <div class="col-xs-12 col-md-9 col-lg-9 ">
                <div class="user-account-title-content">
                    <div class="user-account-menu-title">{{ trans($theme.'-app.user_panel.allotments') }}</div>
				</div>



                <div class="user-accounte-titles-link">
                    <ul class="ul-format d-flex justify-content-space-between flex-wrap" role="tablist">
                        <li role="pagar"class="active" >
                            <a data-toggle="collapse" href="#lot_pend" class="color-letter" href="{{ \Routing::slug('user/panel/allotments/outstanding') }}" >{{ trans($theme.'-app.user_panel.still_paid') }}</a></li>
                    </ul>
                </div>
                <div class="col-xs-12 no-padding in" aria-expanded="true" id="lot_pend">




                    <div class="panel-group" id="accordion">
                        <div class="panel panel-default">

                            @if($tipo_pago_global)
                            <form  id="pagar_lotes_global">
                            @endif

                            <?php $i=0 ?>
                            @foreach($all_adj as $key_sub => $all_inf)
                            <?php
                                $total_remate = 0;
                                $total_base = 0;
                                $total_iva = 0;
                                $precio_global=0;

								#Se reinician las variables para packengers
								$urlToPackengersMaker = "";
								$packengersMoneyValue = 0;
                            ?>
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <a class="d-flex justify-content-space-between" data-toggle="collapse" href="#{{$all_inf['inf']->cod_sub}}">
                                        <div>
                                            <span class="title-sub-list">{{$all_inf['inf']->name}}</span>
                                        @if($all_inf['inf']->compraweb_sub == 'S')
                                        <span class="total-price-sup"> / {{ trans($theme.'-app.user_panel.total_price') }} <span class="precio_final_{{$all_inf['inf']->cod_sub}}">0</span> {{ trans($theme.'-app.lot.eur') }} </span>
                                        @endif
                                        </div>
                                        <img width=10 src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCIgdmlld0JveD0iMCAwIDk2LjE1NCA5Ni4xNTQiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDk2LjE1NCA5Ni4xNTQ7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8cGF0aCBkPSJNMC41NjEsMjAuOTcxbDQ1Ljk1MSw1Ny42MDVjMC43NiwwLjk1MSwyLjM2NywwLjk1MSwzLjEyNywwbDQ1Ljk1Ni01Ny42MDljMC41NDctMC42ODksMC43MDktMS43MTYsMC40MTQtMi42MSAgIGMtMC4wNjEtMC4xODctMC4xMjktMC4zMy0wLjE4Ni0wLjQzN2MtMC4zNTEtMC42NS0xLjAyNS0xLjA1Ni0xLjc2NS0xLjA1NkgyLjA5M2MtMC43MzYsMC0xLjQxNCwwLjQwNS0xLjc2MiwxLjA1NiAgIGMtMC4wNTksMC4xMDktMC4xMjcsMC4yNTMtMC4xODQsMC40MjZDLTAuMTUsMTkuMjUxLDAuMDExLDIwLjI4LDAuNTYxLDIwLjk3MXoiIGZpbGw9IiMwMDAwMDAiLz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K" />
                                        </a>
                                    </div>
                                    <div id="{{$all_inf['inf']->cod_sub}}"  class="table-responsive panel-collapse collaps {{$all_inf['inf']->compraweb_sub == 'S' ? 'in': 'collapse'}}">
                                        @if(!$tipo_pago_global)
                                    <form id="pagar_lotes_{{$all_inf['inf']->cod_sub}}" >
                                        @endif
                                        <div class="user-account-heading hidden-xs d-flex align-items-center justify-content-space-between">
                                            <div class="col-xs-12 col-sm-10 col-lg-10 col-one user-account-item">
                                                    {{ trans($theme.'-app.user_panel.lot') }}
                                            </div>
                                            <div class="col-xs-12 col-sm-2 col-one user-account-fecha">
                                                    {{ trans($theme.'-app.user_panel.price') }}
                                            </div>

                                        </div>

                                        <div class="user-accout-items-content   ">

                                                @foreach($all_inf['lotes'] as $inf_lot)
                                                <?php
                                                $url_friendly = str_slug($inf_lot->titulo_hces1);
                                                $url_friendly = \Routing::translateSeo('lote').$inf_lot->cod_sub."-".str_slug($inf_lot->name).'-'.$inf_lot->id_auc_sessions."/".$inf_lot->ref_asigl0.'-'.$inf_lot->num_hces1.'-'.$url_friendly;
                                                $precio_remate = \Tools::moneyFormat($inf_lot->himp_csub);
                                                $precio_limpio = \Tools::moneyFormat($inf_lot->base_csub,false,2);
                                                $comision = \Tools::moneyFormat($inf_lot->base_csub ,false,2);

                                                $precio_limpio_calculo =  number_format($inf_lot->himp_csub + $inf_lot->base_csub + $inf_lot->base_csub_iva, 2, '.', '');
                                                $calc_envio = number_format($inf_lot->himp_csub + $inf_lot->base_csub, 2, '.', '');

												#Suma para calcular el precio total para URL de packengers
												#$packengersMoneyValue += $precio_limpio_calculo;

                                                //Calculo total
                                                $total_remate = $total_remate + $inf_lot->himp_csub;
                                                $total_base = $total_base + $inf_lot->base_csub;
                                               /* El iva se calcula la final con la suma total*/
                                                # $total_iva = $total_iva + $inf_lot->base_csub_iva;
                                            ?>
                                                    <div class="user-accout-item-wrapper  col-xs-12 no-padding">
                                                        <div class="d-flex">
                                                        <div class="col-xs-12 col-sm-10 col-lg-10 col-one user-account-item ">
                                                            <div class="col-xs-12 col-sm-1 no-padding">
																<?php //Oculto los check con una clase hidden ?>
                                                                     <div class="checkbox hidden" style="margin-top: 0px;">
                                                                        @if($all_inf['inf']->compraweb_sub == 'S')
                                                                            <input type="checkbox" checked="" id="add-carrito-{{$inf_lot->cod_sub}}-{{$inf_lot->ref_asigl0}}" class="filled-in add-carrito form-control" name="carrito[{{$inf_lot->sub_csub}}][{{$inf_lot->ref_csub}}][pagar]" >
                                                                        @endif
                                                                    </div>
                                                            </div>
                                                            <div class="col-xs-12 col-sm-2 no-padding ">
                                                                <img src="{{ \Tools::url_img("lote_small", $inf_lot->num_hces1, $inf_lot->lin_hces1) }}" class="img-responsive">
                                                            </div>
                                                            <div class="col-xs-12 col-sm-8 col-sm-offset-1 no-padding">
                                                                    @if(strtoupper($inf_lot->tipo_sub) == 'O' || strtoupper($inf_lot->tipo_sub) == 'P')
                                                                        <div class="user-account-item-auction text-right"><small>{{ trans($theme.'-app.user_panel.auctions_online') }}</small></div>
																	@endif

                                                                    <div class="user-account-item-lot"><span>{{ trans($theme.'-app.user_panel.lot') }}
																		@php
																			 //Modificamos ref_asigl0 de _ a . porque se ha hecho al reves en el controlador por un tema de javascript
																		 	$refLot  = str_replace('_','.',$inf_lot->ref_asigl0);

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
																		{{$refLot}}
																		</span></div>
                                                                    <div class="user-account-item-title">{!!$inf_lot->titulo_hces1?? $inf_lot->descweb_hces1!!}</div>

                                                                 {{--   <div class="user-account-item-text"><div>{{$inf_lot->cod_sub}}</div></div> --}}
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-2 col-lg-2 account-item-border">
                                                            <div class="user-account-item-date d-flex flex-direction-column align-items-center justify-content-center">
                                                                <div class="visible-xs">{{ trans($theme.'-app.user_panel.price') }}</div>
																<p><?= $precio_remate ?> {{ trans($theme.'-app.lot.eur') }}</p>
																@if ($comision !=0)
																	<small class="comision-title">{{ trans($theme.'-app.user_panel.price_comision') }}</small>
																	<div>+ <?=  $comision ?> {{ trans($theme.'-app.lot.eur') }}</div>
																@endif
                                                            </div>
                                                        </div>

														<input class="hide envios_{{$inf_lot->sub_csub}}_js" type="hidden" name="carrito[{{$inf_lot->sub_csub}}][{{$inf_lot->ref_csub}}][envios]" value='{{ Config::get('app.web_gastos_envio')? '5' : '1' }}'>
														<input class="hide seguro_lote_{{$inf_lot->sub_csub}}_js" type="hidden" name="carrito[{{$inf_lot->sub_csub}}][{{$inf_lot->ref_csub}}][seguro]" value='0'>

													</div>
                                                    </div>

													<?php #Crea la URL mediante la iteración del foreach con los lotes de dentro
														$urlToPackengersMaker .= $inf_lot->cod_sub."-".$inf_lot->ref_asigl0.",";
													?>

                                            @endforeach
                                        </div>
								@if(!$tipo_pago_global)
									<div class="adj color-letter  align-items-center justify-content-space-between">
										@if($all_inf['inf']->compraweb_sub == 'S' && (!empty(Config::get('app.merchantIdUP2')) || !empty(Config::get('app.paymentRedsys'))))

											<?PHP /* Calculo de gastos de envio */ ?>
											@if( !empty(Config::get('app.web_gastos_envio')))
												<div class="col-xs-12 col-sm-4 gastos_envio" >
													<strong> {{ trans($theme.'-app.user_panel.direccion-facturacion') }}</strong>
														<select id="clidd_{{$all_inf['inf']->cod_sub}}"  name="clidd_{{$all_inf['inf']->cod_sub}}" class="change_address_js "   data-sub="{{$all_inf['inf']->cod_sub}}" style="width: 90%;">
														@foreach($data["address"] as $key => $value)
															<option value="{{ $key}}">{{$value}} </option>
														@endforeach

													</select>
												</div>
												<div class="col-xs-12 col-sm-4 gastos_envio" >
													<strong> {{ trans($theme.'-app.user_panel.envio_agencia') }} </strong>
													<br>
													<?php #Debe estar checkeado almenos uno de los dos radio buttons ?>
													<div id="envioPosible_{{$all_inf['inf']->cod_sub}}_js">
														<input type="radio" checked="checked" class=" change_envio_js" data-sub="{{$all_inf['inf']->cod_sub}}" id="envio_agencia_{{$all_inf['inf']->cod_sub}}_js" name="envio_{{$all_inf['inf']->cod_sub}}"  value="1"> <label for="envio_agencia_{{$all_inf['inf']->cod_sub}}_js"> {{ trans($theme.'-app.user_panel.gastos_envio') }}:  <span id="coste-envio-{{$all_inf['inf']->cod_sub}}_js"> </span> {{ trans($theme.'-app.lot.eur') }}</label>

														@if (!empty(Config::get("app.porcentaje_seguro_envio")))
															<br>
															<input type="checkbox" style="top: 0px;height: 10px;margin-right: 0px;"  class="check_seguro_js" data-sub="{{$all_inf['inf']->cod_sub}}" id="seguro_{{$all_inf['inf']->cod_sub}}_js" name="seguro_{{$all_inf['inf']->cod_sub}}"  value="1"> <label for="seguro_{{$all_inf['inf']->cod_sub}}_js"> {{ trans($theme.'-app.user_panel.seguro_envio') }}:  <span id="coste-seguro-{{$all_inf['inf']->cod_sub}}_js"> </span> {{ trans($theme.'-app.lot.eur') }}</label>
															<input type="hidden" id="porcentaje-seguro-{{$all_inf['inf']->cod_sub}}_js" value="{{ \Config::get("app.porcentaje_seguro_envio")}}">
															<input type="hidden" id="iva_aplicable-{{$all_inf['inf']->cod_sub}}_js" value="{{ $data["ivaAplicable"]}}">

														@endif

													</div>
													<div id="envioNoDisponible_{{$all_inf['inf']->cod_sub}}_js" class="hidden">
														{{ trans($theme.'-app.user_panel.envio_no_disponible') }}
													</div>

													<br>

													<strong> {{ trans($theme.'-app.user_panel.recogida_producto') }} </strong>
													<br>
													<input type="radio" class=" change_envio_js" data-sub="{{$all_inf['inf']->cod_sub}}" id="recogida_almacen_{{$all_inf['inf']->cod_sub}}_js" name="envio_{{$all_inf['inf']->cod_sub}}" value="0"> <label  for="recogida_almacen">{{ trans($theme.'-app.user_panel.sala_almacen') }}</label>
													<script>
														//cargar precio de gastos de envio
															show_gastos_envio('{{$all_inf["inf"]->cod_sub}}');

															@if (!empty(Config::get("app.porcentaje_seguro_envio")))
																show_seguro_envio('{{$all_inf["inf"]->cod_sub}}');
															@endif

													</script>
												</div>
											@else
												<div class="col-xs-12 col-sm-8" ></div>

											@endif
											<div class="col-xs-12 col-sm-4 total-price">
												<br/>
												{{ trans($theme.'-app.user_panel.remate_comision') }} <br><span class="precio_final_remate_{{$all_inf['inf']->cod_sub}}">0</span> {{ trans($theme.'-app.lot.eur') }}
												<br/><br/>

												{{ trans($theme.'-app.user_panel.iva_comision') }} <br><span class="iva_final_{{$all_inf['inf']->cod_sub}}">0</span> {{ trans($theme.'-app.lot.eur') }}
												<br/><br/>

												{{ trans($theme.'-app.user_panel.total_price') }} <br><span class="precio_final_{{$all_inf['inf']->cod_sub}}">0</span> {{ trans($theme.'-app.lot.eur') }}
												<br/><br/>
												@if(\Config::get("app.PayBizum") || \Config::get("app.PayTransfer") || \Config::get("app.paymentPaypal"))
													<div class=" d-flex">
														<div style="flex:1"></div>
														<div class="mt-1 text-left">

															@if(\Config::get("app.paymentUP2")  || Config::get('app.paymentRedsys') )
																<input id="paycreditcard"  type="radio" name="paymethod" value="creditcard" checked="checked">
																<label for="paycreditcard"> <span class="fab fa-cc-visa" style="font-size: 20px;margin: 0px 3px;"></span> {{ trans($theme.'-app.user_panel.pay_creditcard') }}     </label>
																<br>
															@endif

															@if(\Config::get("app.PayBizum") )
																<input id="paybizum"    type="radio" name="paymethod" value="bizum" >
																<label for="paybizum" > <img src="/default/img/logos/bizum-blue.png" style="height: 20px;margin: 0px 6px;"> {{ trans($theme.'-app.user_panel.pay_bizum') }}   </label>
																<br>
															@endif


															@if(\Config::get("app.PayTransfer"))
																<input id="paytransfer"    type="radio" name="paymethod" value="transfer" >
																<label for="paytransfer"> {{ trans($theme.'-app.user_panel.pay_transfer') }} </label>
																<br>
															@endif

															@if(\Config::get("app.paymentPaypal"))
																<input id="paypaypal" type="radio" name="paymethod" value="paypal" >
																<label for="paypaypal"><i class="fa fa-paypal" aria-hidden="true"></i> {{ trans($theme.'-app.user_panel.pay_paypal') }} </label>
															@endif

														</div>
													</div>
												@else
												<input id="paytransfer" type="hidden" name="paymethod" value="@if(config('app.paymentPaypal', 0)){{'paypal'}}@else{{'creditcard'}}@endif">
												@endif
												<br/><br/>
												@if (\Config::get("app.urlToPackengers"))
													<?php #Se crea la URL para ser usable en el botón
														$urlToPackengers = rtrim($urlToPackengersMaker, ",");
														$urlToPackengers .= "?value=".\Tools::moneyFormat($packengersMoneyValue,false,2);
														$urlCompletePackengers = \Config::get('app.urlToPackengers').$urlToPackengers;
													?>
													<a class="packengers-button-adjudicaciones" href="{{$urlCompletePackengers}}" target="_blank">
														<i class="fa fa-truck" aria-hidden="true"></i>
														{{ trans("$theme-app.lot.packengers_adjudicaciones") }}
													</a>
												@endif
												<button style="margin-left: 15px;" type="button" class="secondary-button   submit_carrito "  cod_sub="{{$all_inf['inf']->cod_sub}}" class="btn btn-step-reg" disabled>{{ trans($theme.'-app.user_panel.pay') }}</button>


											</div>

										@endif
									</div>
                                    </form>
                                @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @if($tipo_pago_global)
                                <div class="adj color-letter d-flex align-items-center justify-content-space-between">


									<div class="total-price">{{ trans($theme.'-app.user_panel.remate_comision') }} <span class="precio_final_remate_global">0</span> {{ trans($theme.'-app.lot.eur') }} </div>
									<div class="total-price">{{ trans($theme.'-app.user_panel.iva_comision') }} <span class="iva_final_global">0</span> {{ trans($theme.'-app.lot.eur') }} </div>
                                    <div class="total-price">{{ trans($theme.'-app.user_panel.total_price') }} <span class="precio_final_global">0</span> {{ trans($theme.'-app.lot.eur') }} </div>
                                        @if(!empty(Config::get('app.merchantIdUP2')) || !empty(Config::get('app.paymentRedsys')))
                                            <button style="margin-left: 15px;" type="button" class="secondary-button   submit_carrito btn btn-step-reg2"  cod_sub="global" class="btn btn-step-reg" disabled>{{ trans($theme.'-app.user_panel.pay') }}</button>
                                        @endif
                                </div>
                            </form>
                        @endif
                        </div>
					</div>



					<?php #Lotes pendientes de que se realice la transferencia ?>
					@include("pages.panel.awardLots.pendingTransfer")







                    <div class="user-accounte-titles-link col-xs-12 no-padding">
                        <ul class="ul-format d-flex justify-content-space-between flex-wrap" role="tablist">
                            <li class="panel-collapse align-items-center d-flex justify-content-space-between w-100" role="pagadas"  data-toggle="collapse"  href="#lot_pag" >
                                <a class="color-letter" >
                                    <div>{{ trans($theme.'-app.user_panel.bills') }}</div>

                                </a>
                                <div class="toggle-open-close">
                                        <span class="toggle-open">{{ trans($theme.'-app.user_panel.open') }}</span>
                                        <span class="toggle-close" style="display: none">{{ trans($theme.'-app.user_panel.hide') }}</span>
                                    </div>

                            </li>
                        </ul>
                    </div>


                    <div class="col-xs-12 no-padding collapse" aria-expanded="false" id="lot_pag">

                        <div class="panel-group" id="accordion">
                            <div class="panel panel-default">

                                <?php $i=0 ?>
								@foreach($all_adj_pag as $key_sub => $all_inf)
								@if(!empty($all_inf['inf']))

									<?php

										$total_remate = 0;
										$total_base = 0;
										$total_iva = 0;
										$precio_global=0;
									?>
									<div class="panel-heading">
										<div class="panel-title">
											<a class="d-flex justify-content-space-between" data-toggle="collapse" href="#{{$all_inf['inf']->cod_sub}}_pag">
												<div>
													<span class="title-sub-list">{{$all_inf['inf']->name}}</span>

												</div>
												<img width=10 src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCIgdmlld0JveD0iMCAwIDk2LjE1NCA5Ni4xNTQiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDk2LjE1NCA5Ni4xNTQ7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8cGF0aCBkPSJNMC41NjEsMjAuOTcxbDQ1Ljk1MSw1Ny42MDVjMC43NiwwLjk1MSwyLjM2NywwLjk1MSwzLjEyNywwbDQ1Ljk1Ni01Ny42MDljMC41NDctMC42ODksMC43MDktMS43MTYsMC40MTQtMi42MSAgIGMtMC4wNjEtMC4xODctMC4xMjktMC4zMy0wLjE4Ni0wLjQzN2MtMC4zNTEtMC42NS0xLjAyNS0xLjA1Ni0xLjc2NS0xLjA1NkgyLjA5M2MtMC43MzYsMC0xLjQxNCwwLjQwNS0xLjc2MiwxLjA1NiAgIGMtMC4wNTksMC4xMDktMC4xMjcsMC4yNTMtMC4xODQsMC40MjZDLTAuMTUsMTkuMjUxLDAuMDExLDIwLjI4LDAuNTYxLDIwLjk3MXoiIGZpbGw9IiMwMDAwMDAiLz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K" />
												</a>
											</div>
											<div id="{{$all_inf['inf']->cod_sub}}_pag"  class="table-responsive panel-collapse collapse">

												<div class="user-account-heading hidden-xs d-flex align-items-center justify-content-space-between">
													<div class="col-xs-12 col-sm-10 col-lg-10 col-one user-account-item">
															{{ trans($theme.'-app.user_panel.lot') }}
													</div>
													<div class="col-xs-12 col-sm-2 col-lg-2  col-one user-account-fecha">
															{{ trans($theme.'-app.user_panel.price') }}
													</div>

												</div>

												<div class="user-accout-items-content">

													@foreach($all_inf['lotes'] as $inf_lot)
														<?php
														$url_friendly = str_slug($inf_lot->titulo_hces1);
														$url_friendly = \Routing::translateSeo('lote').$inf_lot->cod_sub."-".str_slug($inf_lot->name).'-'.$inf_lot->id_auc_sessions."/".$inf_lot->ref_asigl0.'-'.$inf_lot->num_hces1.'-'.$url_friendly;
														$precio_remate = \Tools::moneyFormat($inf_lot->himp_csub);
														$precio_limpio = \Tools::moneyFormat($inf_lot->base_csub,false,2);
														$comision = \Tools::moneyFormat($inf_lot->base_csub ,false,2);

														/* El iva se calcula la final con la suma total*/
                                                		#$precio_limpio_calculo =  number_format($inf_lot->himp_csub + $inf_lot->base_csub + $inf_lot->base_csub_iva, 2, '.', '');
														$calc_envio = number_format($inf_lot->himp_csub + $inf_lot->base_csub, 2, '.', '');


														//Calculo total
														$total_remate = $total_remate + $inf_lot->himp_csub;
														$total_base = $total_base + $inf_lot->base_csub;
														/* El iva se calcula la final con la suma total*/
                                                		#$total_iva = $total_iva + $inf_lot->base_csub_iva;
													?>
															<div class="user-accout-item-wrapper  col-xs-12 no-padding">
																<div class="d-flex">
																<div class="col-xs-12 col-sm-8 col-lg-10 col-one user-account-item ">
																	<div class="col-xs-12 col-sm-1 no-padding">
																			<div class="checkbox" style="margin-top: 0px;">

																			</div>
																	</div>
																	<div class="col-xs-12 col-sm-2 no-padding ">
																		<img src="{{ \Tools::url_img("lote_small", $inf_lot->num_hces1, $inf_lot->lin_hces1) }}" class="img-responsive">
																	</div>
																	<div class="col-xs-12 col-sm-8 col-sm-offset-1 no-padding">

																		<div class="user-account-item-lot"><span>{{ trans($theme.'-app.user_panel.lot') }} {{$inf_lot->ref_asigl0}}</span></div>
																		<div class="user-account-item-title">{!!$inf_lot->titulo_hces1?? $inf_lot->descweb_hces1  !!}</div>
																		{{--	<div class="user-account-item-text"><div>{{$inf_lot->cod_sub}}</div></div> --}}
																	</div>
																</div>
																<div class="col-xs-12 col-sm-2 col-lg-2 account-item-border">
																	<div class="user-account-item-date d-flex flex-direction-column align-items-center justify-content-center">
																		<div class="visible-xs">{{ trans($theme.'-app.user_panel.price') }}</div>
																		<p><?= $precio_remate ?> {{ trans($theme.'-app.lot.eur') }}</p>
																		@if ($comision !=0)
																			<small class="comision-title">{{ trans($theme.'-app.user_panel.price_comision') }}</small>
																			<div>+ <?=  $comision ?> {{ trans($theme.'-app.lot.eur') }}</div>
																		@endif
																	</div>
																</div>

																	<input class="hide" type="hidden" name="carrito[{{$inf_lot->sub_csub}}][{{$inf_lot->ref_csub}}][envios]" value='1'>
															</div>
															</div>

													@endforeach
												</div>

											</div>
										</div>
										@endif
                                    @endforeach
                                </div>

                            </div>
                        </div>

















                </div>
            </div>
        </div>
    </div>



<script>

    $( document ).ready(function() {
		 reload_carrito();
    });

</script>


@stop
