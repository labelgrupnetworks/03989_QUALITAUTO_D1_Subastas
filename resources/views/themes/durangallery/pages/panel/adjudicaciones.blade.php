@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
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

?>
<script src="{{ URL::asset('js/payment.js')}}"></script>
<script>
    var info_lots = $.parseJSON('<?php echo str_replace("\u0022","\\\\\"",json_encode($data["js_item"],JSON_HEX_QUOT)); ?>');
</script>


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
                <?php $tab="allotments";?>
                @include('pages.panel.menu_micuenta')
            </div>
            <div class="col-xs-12 col-md-9 col-lg-9 ">
                <div class="user-account-title-content">
                    <div class="user-account-menu-title">{{ trans(\Config::get('app.theme').'-app.user_panel.allotments') }}</div>
				</div>




                <div class="col-xs-12 no-padding in" aria-expanded="true" id="lot_pend">

                    <div class="col-xs-12 no-padding " id="lot_pag">

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

										<div class="table-responsive ">

												<div class="user-account-heading hidden-xs d-flex align-items-center justify-content-space-between">
													<div class="col-xs-12 col-sm-7 col-lg-8 col-one user-account-item">
															{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}
													</div>
													<div class="col-xs-12 col-sm-2 col-one user-account-fecha">
															{{ trans(\Config::get('app.theme').'-app.user_panel.price') }}
													</div>
													<div class="col-xs-12 col-sm-3 col-lg-2 col-one user-account-max-bid">
															{{ trans(\Config::get('app.theme').'-app.user_panel.price_clean') }}
													</div>
												</div>

												<div class="user-accout-items-content">

													@foreach($all_inf['lotes'] as $inf_lot)
														<?php
														$url_friendly = str_slug($inf_lot->titulo_hces1);
														$url_friendly = \Routing::translateSeo('lote').$inf_lot->cod_sub."-".str_slug($inf_lot->name).'-'.$inf_lot->id_auc_sessions."/".$inf_lot->ref_asigl0.'-'.$inf_lot->num_hces1.'-'.$url_friendly;
														$precio_remate = \Tools::moneyFormat($inf_lot->himp_csub);
														$precio_limpio = \Tools::moneyFormat($inf_lot->base_csub,false,2);
														$comision = \Tools::moneyFormat($inf_lot->base_csub + $inf_lot->base_csub_iva,false,2);

													$precio_limpio_calculo =  number_format($inf_lot->himp_csub + $inf_lot->base_csub + $inf_lot->base_csub_iva, 2, '.', '');
														$calc_envio = number_format($inf_lot->himp_csub + $inf_lot->base_csub, 2, '.', '');


														//Calculo total
														$total_remate = $total_remate + $inf_lot->himp_csub;
														$total_base = $total_base + $inf_lot->base_csub;
														$total_iva = $total_iva + $inf_lot->base_csub_iva;
													?>
															<div class="user-accout-item-wrapper  col-xs-12 no-padding">
																<div class="d-flex">
																<div class="col-xs-12 col-sm-7 col-lg-8 col-one user-account-item ">
																	<div class="col-xs-12 col-sm-1 no-padding">
																			<div class="checkbox" style="margin-top: 0px;">

																			</div>
																	</div>
																	<div class="col-xs-12 col-sm-2 no-padding ">
																		<img src="{{ \Tools::url_img("lote_small", $inf_lot->num_hces1, $inf_lot->lin_hces1) }}" class="img-responsive">
																	</div>
																	<div class="col-xs-12 col-sm-8 col-sm-offset-1 no-padding">

																		<div class="user-account-item-lot"><span>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }} {{$inf_lot->ref_asigl0}}</span></div>
																		<div class="user-account-item-title">{{$inf_lot->titulo_hces1?? $inf_lot->descweb_hces1  }}</div>
																		{{--	<div class="user-account-item-text"><div>{{$inf_lot->cod_sub}}</div></div> --}}
																	</div>
																</div>
																<div class="col-xs-12 col-sm-2 col-lg-2 account-item-border">
																	<div class="user-account-item-date d-flex flex-direction-column align-items-center justify-content-center">
																		<div class="visible-xs">{{ trans(\Config::get('app.theme').'-app.user_panel.date') }}</div>
																		<p><?= $precio_remate ?> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</p>
																		@if ($comision !=0)
																			<small class="comision-title">{{ trans(\Config::get('app.theme').'-app.user_panel.price_comision') }}</small>
																			<div>+ <?=  $comision ?> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</div>
																		@endif
																	</div>
																</div>
																<div class="col-xs-12 col-sm-3 col-lg-2 account-item-border">
																		<div class="user-account-item-price  d-flex align-items-center justify-content-center">

																				<div class="visible-xs">{{ trans(\Config::get('app.theme').'-app.user_panel.mi_puja') }}</div>
																		<div><strong><?= \Tools::moneyFormat($precio_limpio_calculo,false,2); ?> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</strong></div>
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
