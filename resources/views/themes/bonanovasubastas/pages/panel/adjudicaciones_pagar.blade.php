@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

<?php
$all_adj = array();
$sub = new \App\Models\Subasta;
$all_adj=array();
foreach($data['adjudicaciones'] as $temp_adj){
    $all_adj[$temp_adj->cod_sub]['lotes'][]=$temp_adj;
}
foreach($all_adj as $key_inf => $value){
    $sub->cod = $key_inf;
    $all_adj[$key_inf]['inf'] = $sub->getInfSubasta();
}
$urlToPackengersMaker = "";
$packengersMoneyValue = 0;
?>
<script>
	var info_lots = $.parseJSON('<?php echo str_replace("\u0022","\\\\\"",json_encode($data["js_item"],JSON_HEX_QUOT)); ?>');
</script>

<section class="principal-bar no-principal">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="princiapl-bar-wrapper">
					<div class="principal-bar-title">
						<h3>{{ trans(\Config::get('app.theme').'-app.user_panel.mi_cuenta') }}</h3>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="account payment">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<?php $tab="allotments";?> @include('pages.panel.menu_micuenta')
			</div>
			<div class="col-xs-12" style="margin-top: 5px;">
				<div class="tabs">
					<ul class="nav nav-tabs nav-justified" role="tablist">
						<li role="pagar" class="active"><a
								href="{{ \Routing::slug('user/panel/allotments/outstanding') }}">{{ trans(\Config::get('app.theme').'-app.user_panel.still_paid') }}</a>
						</li>
						<li role="pagadas"><a
								href="{{ \Routing::slug('user/panel/allotments/paid') }}">{{ trans(\Config::get('app.theme').'-app.user_panel.bills') }}</a>
						</li>
					</ul>
				</div>
				<div class="panel-group" id="accordion">
					<div class="panel panel-default">
						<?php $i=0 ?>
						@foreach($all_adj as $key_sub => $all_inf)
						<?php
                            $total_remate = 0;
                            $total_base = 0;
                            $total_iva = 0;
                            ?>
						<div class="panel-heading">
							<a aria-expanded="true" data-toggle="collapse" href="#{{$all_inf['inf']->cod_sub}}">

								<h4 class="panel-title">
									{{$all_inf['inf']->name}}
								</h4>

							</a>
						</div>



						<div id="{{$all_inf['inf']->cod_sub}}" class="table-responsive panel-collapse collaps in"
							style="padding: 10px;">

							<!-- Cabeceras grices con titulos-->


							<form id="pagar_lotes_{{$all_inf['inf']->cod_sub}}">



								<table class="table table-hover">
									<thead>
										<tr>
											<th class="hide">#</th>
											<th></th>
											<th style="font-weight: 100; color: grey;">
												{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}</th>
											<th style="font-weight: 100; color: grey;">
												{{ trans(\Config::get('app.theme').'-app.user_panel.name') }}</th>
											<th style="font-weight: 100; color: grey;">
												{{ trans(\Config::get('app.theme').'-app.user_panel.price') }}</th>
											<th style="font-weight: 100; color: grey;">
												{{ trans(\Config::get('app.theme').'-app.user_panel.price_clean') }}
											</th>




										</tr>
									</thead>



									<!-- /Cabeceras grices con titulos-->
									<?php $countBid=1; ?>

									@foreach($all_inf['lotes'] as $inf_lot)
									<?php
                                    $url_friendly = str_slug($inf_lot->titulo_hces1);
                                    $url_friendly = \Routing::translateSeo('lote').$inf_lot->cod_sub."-".str_slug($inf_lot->name).'-'.$inf_lot->id_auc_sessions."/".$inf_lot->ref_asigl0.'-'.$inf_lot->num_hces1.'-'.$url_friendly;
                                    $precio_remapte = \Tools::moneyFormat($inf_lot->himp_csub);
                                    $precio_limpio = \Tools::moneyFormat($inf_lot->base_csub,false,2);
                                    $comision = \Tools::moneyFormat($inf_lot->base_csub + $inf_lot->base_csub_iva,false,2);

                                   $precio_limpio_calculo =  number_format($inf_lot->himp_csub + $inf_lot->base_csub + $inf_lot->base_csub_iva, 2, '.', '');
                                    $calc_envio = number_format($inf_lot->himp_csub + $inf_lot->base_csub, 2, '.', '');

									//Suma para calcular el precio total para URL de packengers
									$packengersMoneyValue += $precio_limpio_calculo;

                                    //Calculo total
                                    $total_remate = $total_remate + $inf_lot->himp_csub;
                                    $total_base = $total_base + $inf_lot->base_csub;
                                    $total_iva = $total_iva + $inf_lot->base_csub_iva;
                                ?>

									<tbody>
										<tr>
											<th class="hide">@if( empty(\Config::get( 'app.pasarela_web' )))
												<input type="checkbox" checked="" id="{{$i}}"
													class="filled-in add-carrito form-control"
													name="carrito[{{$inf_lot->sub_csub}}][{{$inf_lot->ref_csub}}][pagar]">
												<label for="{{$i}}"></label>
												@endif
											</th>
											<th>
												<div class="img-dat img-data-customs flex valign" role="button" id="">
													<img class="img-responsive"
														src="{{ \Tools::url_img("lote_medium", $inf_lot->num_hces1, $inf_lot->lin_hces1) }}"
														style="width: 50px;">
												</div>
											</th>
											<th>
												<div class="lot-data-custon">
													<p>{{$inf_lot->ref_asigl1}}</p>
												</div>
											</th>
											<th>
												<div class="name-data-custom">
													<?= $inf_lot->desc_hces1?>
												</div>

											</th>
											<th>
												<div class="remat-data-custom">
													<p><?= $precio_remapte ?>
														{{ trans(\Config::get('app.theme').'-app.lot.eur') }}</p>
												</div>
											</th>
											<th>
												<div class="auc-data-custom">
													<p><?= \Tools::moneyFormat($precio_limpio_calculo,false,2);?>
														{{ trans(\Config::get('app.theme').'-app.lot.eur') }}</p>

												</div>
												<input class="hide" type="hidden"
													name="carrito[{{$inf_lot->sub_csub}}][{{$inf_lot->ref_csub}}][envios]"
													value='1'>
											</th>
										</tr>

										<?php $urlToPackengersMaker .= $inf_lot->cod_sub."-".$inf_lot->ref_asigl0.","; ?>

										<!-- Lotes en vista desktop-->

										@endforeach

										<?php
											$urlToPackengers = rtrim($urlToPackengersMaker, ",");
											$urlToPackengers .= "?value=".\Tools::moneyFormat($packengersMoneyValue,false,2);
											$urlCompletePackengers = \Config::get('app.packengers').$urlToPackengers;
										?>


									</tbody>
								</table>
								@if($data['user']->envcorr_cli != 'B')
								<div class="adj">
									<span
										style="font-size: 23px;">{{ trans(\Config::get('app.theme').'-app.user_panel.total_price') }}
									</span>
									<span class="titlecat" style="font-size: 24px;"><span
											class="precio_final_{{$all_inf['inf']->cod_sub}}">0</span>
										{{ trans(\Config::get('app.theme').'-app.lot.eur') }}</span>
									@if($all_inf['inf']->compraweb_sub == 'S')
									<button style="margin-left: 15px;" type="button"
										class="submit_carrito btn btn-step-reg2" cod_sub="{{$all_inf['inf']->cod_sub}}"
										class="btn btn-step-reg"
										disabled>{{ trans(\Config::get('app.theme').'-app.user_panel.pay') }}</button>
									@endif

									<a class="packengers-button-adjudicaciones" href="{{$urlCompletePackengers}}" target="_blank"><i class="fa fa-truck" aria-hidden="true"></i> Gestionar Envio</a>

								</div>
								@elseif($data['user']->envcorr_cli == 'B')
								<div class="adj">
									<p style="color: #283747;font-size: 18px;">
										{{ trans(\Config::get('app.theme').'-app.user_panel.contact') }}</p>
								</div>
								@endif
							</form>




						</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
</section>

<script>
	$( document ).ready(function() {
         reload_carrito();
    });

</script>


@stop
