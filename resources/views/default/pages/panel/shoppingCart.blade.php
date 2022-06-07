@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

<?php

?>


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

			@if(!Session::has('user'))
				<h2 class="text-center">{{ trans(\Config::get('app.theme').'-app.shopping_cart.mustLoginShippingCart') }} </h2>

			@elseif(count($lots)==0)
				<h2 class="text-center">{{ trans(\Config::get('app.theme').'-app.shopping_cart.noLots') }} </h2>

			@else
                <div class="user-account-title-content">
					<div class="user-account-menu-title">{{ trans(\Config::get('app.theme').'-app.shopping_cart.myCart') }}</div>
					<div >{{ trans(\Config::get('app.theme').'-app.shopping_cart.text_reserve') }}</div>
                </div>

				<div id="7500" class="table-responsive panel-collapse collaps in ">
					<form id="pagar_lotes_carrito" autocomplete="off">
						@csrf
						<div class="user-account-heading hidden-xs d-flex align-items-center justify-content-space-between">
							<div class="col-xs-12 col-sm-6  col-one user-account-item">
								{{ trans(\Config::get('app.theme').'-app.shopping_cart.article') }}
							</div>
							<div class="col-xs-12 col-sm-1 col-one user-account-fecha" style="text-align:center">
								{{ trans(\Config::get('app.theme').'-app.user_panel.units') }}
							</div>
							<div class="col-xs-12 col-sm-2 col-one user-account-fecha" style="text-align:center">
								{{ trans(\Config::get('app.theme').'-app.user_panel.unit_price') }}
							</div>
							<div class="col-xs-12 col-sm-2  col-one user-account-max-bid" style="text-align:center">
								{{ trans(\Config::get('app.theme').'-app.user_panel.price_clean') }}
							</div>
							<div class="col-xs-12 col-sm-1  col-one user-account-max-bid" style="text-align:center">

							</div>
						</div>

						@foreach($lots as $lot)
							@include("includes.shoppingcart.lot")


						@endforeach
						<?php

							$pagar =  $totalLotes;
							if($gastosEnvio >1){
								$pagar +=  $gastosEnvio;
							}
							#ponemos todos lso imputs ocultos con sus valores
						?>
							<input type="hidden" id="totalLotes_JS" value="{{$totalLotes}}">
							<input type="hidden" id="gastosEnvio_JS" value="{{$gastosEnvio}}">
							<input type="hidden" id="seguro_JS" value="{{$totalSeguro}}">


							<div class="adj color-letter  align-items-center justify-content-space-between">
								<?PHP /* Calculo de gastos de envio */ ?>
								@if( !empty(Config::get('app.web_gastos_envio')) || !empty(Config::get('app.direccion_envio')))
									<div class="col-xs-12 col-sm-5 gastos_envio" >
										<strong> {{ trans(\Config::get('app.theme').'-app.user_panel.direccion-facturacion') }}</strong>
											<select id="clidd_carrito"  name="clidd_carrito" class="change_address_carrito_js "   data-sub="carrito" style="width: 90%;">
											@foreach($address as $key => $value)
												<option value="{{ $key}}">{{$value}} </option>
											@endforeach

										</select>
										<br>
										<br>
										{!! trans(\Config::get('app.theme').'-app.shopping_cart.comment')  !!}
										<br>
										<textarea name="comments" rows="5" style="width: 100%;"> </textarea>
									</div>
								@else
									<div class="col-xs-12 col-sm-5 "  ></div>
								@endif


								@if( !empty(Config::get('app.web_gastos_envio')) )
									<div class="col-xs-12 col-sm-3 gastos_envio" >
										<strong> {{ trans(\Config::get('app.theme').'-app.user_panel.envio_agencia') }} </strong>
										<br>
										<?php #Debe estar checkeado almenos uno de los dos radio buttons ?>
										<div id="envioPosible_carrito_js" <?= ($gastosEnvio== "-1")? 'class="hidden"' :''   ?>>
											<input type="radio" <?= ($gastosEnvio== "-1")? '' :'checked="checked"'   ?>  class=" change_envio_carrito_js" data-sub="carrito" id="envio_agencia_carrito_js" name="envio_carrito"  value="1">
											<label for="envio_agencia_carrito_js"> {{ trans(\Config::get('app.theme').'-app.user_panel.gastos_envio') }}:  <span id="coste-envio-carrito_js"> {{ \Tools::moneyFormat($gastosEnvio,false,2)}} </span> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</label>

											@if (!empty(Config::get("app.porcentaje_seguro_envio")))
												<br>
												<input type="checkbox" style="top: 0px;height: 10px;margin-right: 0px;"  class="check_seguro_js" data-sub="carrito" id="seguro_carrito_js" name="seguro_carrito"  value="1">
												<label for="seguro_carrito_js"> {{ trans(\Config::get('app.theme').'-app.user_panel.seguro_envio') }}:  <span id="coste-seguro-carrito_js"> {{ \Tools::moneyFormat($totalSeguro,false,2)}} </span> {{ trans(\Config::get('app.theme').'-app.lot.eur') }}</label>

											@endif

										</div>
										<div id="envioNoDisponible_carrito_js" <?= ($gastosEnvio== "-1")? '' :'class="hidden"'   ?>>
											{{ trans(\Config::get('app.theme').'-app.user_panel.envio_no_disponible') }}
										</div>

										<br>

										<strong> {{ trans(\Config::get('app.theme').'-app.user_panel.recogida_producto') }} </strong>
										<br>
										<input type="radio" class=" change_envio_carrito_js" <?= ($gastosEnvio== "-1")? 'checked="checked"' :''   ?> data-sub="carrito" id="recogida_almacen_carrito_js" name="envio_carrito" value="0"> <label  for="recogida_almacen">{{ trans(\Config::get('app.theme').'-app.user_panel.sala_almacen') }}</label>

									</div>
								@else
									<div class="col-xs-12 col-sm-3 "  >

									</div>

								@endif


								<div class="col-xs-12 col-sm-4 total-price" >
									{{-- En este caso se envia la información pero no se calculan gastos ya que será informativo--}}
									@if( !empty(Config::get('app.SeguroCarrito')) )
										<input type="checkbox" style="top: 0px;height: 10px;margin-right: 0px;"   data-sub="carrito" id="seguro_carrito_info" name="seguro_carrito_info"  value="1">
										<label for="seguro_carrito_info"> {{ trans(\Config::get('app.theme').'-app.user_panel.seguro_envio') }}  </label>
										<br><br>
								@endif
									{{ trans(\Config::get('app.theme').'-app.shopping_cart.total_articles') }} <br>{{ \Tools::moneyFormat($totalLotes, trans(\Config::get('app.theme').'-app.subastas.euros'),2)}}

									<br>	<br><br>

									{{ trans(\Config::get('app.theme').'-app.shopping_cart.total_pay') }} <br><span class="precio_final_carrito">{{ \Tools::moneyFormat($pagar, trans(\Config::get('app.theme').'-app.subastas.euros'), 2)}}</span>

									<br><br>

									@if(\Config::get("app.PayBizum") || \Config::get("app.PayTransfer"))
										<div class=" d-flex">
											<div style="flex:1"></div>
											<div class="mt-1 text-left">
												<input id="paycreditcard"  type="radio" name="paymethod" value="creditcard" checked="checked">
												<label for="paycreditcard"> <span class="fab fa-cc-visa" style="font-size: 20px;margin: 0px 3px;"></span> {{ trans(\Config::get('app.theme').'-app.user_panel.pay_creditcard') }}     </label>
												<br>
												@if(\Config::get("app.PayBizum") )
													<input id="paybizum"    type="radio" name="paymethod" value="bizum">
													<label for="paybizum" > <img src="/default/img/logos/bizum-blue.png" style="height: 20px;margin: 0px 6px;"> {{ trans(\Config::get('app.theme').'-app.user_panel.pay_bizum') }}   </label>
												@endif

												<br>
												@if(\Config::get("app.PayTransfer"))
													<input id="paytransfer"    type="radio" name="paymethod" value="transfer">
													<label for="paytransfer"> {{ trans(\Config::get('app.theme').'-app.user_panel.pay_transfer') }} </label>
												@endif






											</div>
										</div>
									@else
									<input id="paytransfer"    type="hidden" name="paymethod" value="creditcard">
									@endif
									<br><br>

								</div>
								<div class="col-xs-12" >
									@if(\Config::get("app.checkPayCart"))

									<label style="float:right" for = "acceptCheck"> {!! trans(\Config::get('app.theme').'-app.shopping_cart.check') !!} </label>
									<input style="float:right" type="checkbox" id="acceptCheck"  name="acceptCheck" value=1>
									<br><br>
									<p class= "text-justify mt-1">
										{!! trans(\Config::get('app.theme').'-app.shopping_cart.text_condition') !!}
									</p>
									@else
										<?php //en principio solo duran tiene el check el resto que lo use se marcara siempre y quedará oculto ?>
										<input class="hidden"  type="checkbox" id="acceptCheck"  name="acceptCheck" value=1 checked="checked">
									@endif
									<button style="float:right" type="button" class="secondary-button   submitShoppingCart_JS "  cod_sub="carrito" class="btn btn-step-reg" >{{ trans(\Config::get('app.theme').'-app.user_panel.pay') }}</button>
									<br><br>
								</div>

							</div>

					</form>


					@endif
				</div>

		</div>
	</div>
</div>



<script>

    $( document ).ready(function() {
		//No eliminar
		calcShippingCosts();

    });

</script>


@stop
