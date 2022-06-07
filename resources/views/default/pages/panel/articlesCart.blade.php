@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')




<script src="{{ Tools::urlAssetsCache('js/default/articles.js') }}"></script>

<link href="{{ Tools::urlAssetsCache('/css/default/articles.css') }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/articles.css') }}" rel="stylesheet" type="text/css">

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
                <?php $tab="shopping_cart";?>
                @include('pages.panel.menu_micuenta')
            </div>
            <div class="col-xs-12 col-md-9 col-lg-9 ">

			@if(!Session::has('user'))
				<h2 class="text-center">{!! trans(\Config::get('app.theme').'-app.shopping_cart.mustLoginShippingCart') !!} </h2>

			@elseif(count($articles)==0)
				<h2 class="text-center">{{ trans(\Config::get('app.theme').'-app.shopping_cart.noLots') }} </h2>

			@else
                <div class="user-account-title-content">
					<div class="user-account-menu-title">{{ trans(\Config::get('app.theme').'-app.shopping_cart.myCart') }}</div>

                </div>

				<div id="7500" class="table-responsive panel-collapse collaps in ">
					<form id="articleCartForm" autocomplete="off">
						{{-- código de token --}}
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

						@foreach($articles as $article)
							@php $imp = round($article->pvp_art + ($article->pvp_art * $iva), 2); @endphp

							<div class="user-accout-items-content   ">
								<div class="user-accout-item-wrapper  col-xs-12 no-padding">
									<div class="d-flex">
										<div class="col-xs-12 col-sm-6  col-one user-account-item ">
											<div class="col-xs-12 col-sm-2 no-padding img-articleCart">
												@if( !empty($article->image))
													<img src="{{$article->image }}" >
												@endif
											</div>
											<div class="col-xs-12 col-sm-9 col-sm-offset-1 no-padding">

												<div class="visible-xs">{{ trans(\Config::get('app.theme').'-app.user_panel.units') }}</div>
												<div class="user-account-item-title">
													<a href="{{ Route("article", ["idArticle" => $article->id_art0, "friendly" =>\Str::slug($article->model_art0) ])}}">
													{{$article->model_art0  }}
													@if (!empty($tallasColores[$article->id_art]))
														@foreach($tallasColores[$article->id_art] as $nameTallaColor => $tallaColor)
															<br>{{$nameTallaColor}}: {{$tallaColor}}
														@endforeach
													@endif
													</a>
													<br/>
													<label class="labelGrabasdo">{{ trans(\Config::get('app.theme').'-app.articles.engraveTitle') }} </label>
													<a href="javascript:;" data-toggle="modal" data-target="#modalAjax" class="info-ficha-lot pt-1 c_bordered" data-ref="/es/pagina/personaliza-anillo?modal=1" data-title="{{ trans(\Config::get('app.theme').'-app.articles.engraveTitle') }}">   <i class="fas fa-info-circle"></i></a>
													{{--  loteauto_art es temporal hasta que tengamos el real para hacer esto --}}
													@if($article->personalizado_art0 == 'S')
														<input type="text" name="grabados[{{$article->id_art}}][0]" value="" placeholder="{{ trans(\Config::get('app.theme').'-app.articles.engravePlaceHolder') }}" class="inputGrabado">
														<div id="grabados_JS" >
															@for($i=1;$i<$units[$article->id_art];$i++ )
																<input type="text" name="grabados[{{$article->id_art}}][{{$i}}]" value="" placeholder="{{ trans(\Config::get('app.theme').'-app.articles.engravePlaceHolder') }}" class="inputGrabado">
															@endfor
														</div>
													@endif

												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-1  account-item-border">
											<div class="user-account-item-date d-flex flex-direction-column align-items-center justify-content-center">

												<p>
												@php
													#evitamso el stock negativo
													if($article->stock<0){
														$article->stock = 0;
													}

													#bajamos el numero de unidades a lo que permite el stock
													$units[$article->id_art] = min($units[$article->id_art], $article->stock );
												@endphp
												<input class="units_JS" data-idart="{{$article->id_art}}" type="number" name="units_{{$article->id_art}}"	value="{{ $units[$article->id_art]}}" min="{{ min($article->stock, 1)}}" max="{{ min($article->stock, 10)}}">
												</p>

											</div>
										</div>
										<div class="col-xs-12 col-sm-2  account-item-border">
											<div class="user-account-item-date d-flex flex-direction-column align-items-center justify-content-center">
												<div class="visible-xs">{{ trans(\Config::get('app.theme').'-app.user_panel.unit_price') }}</div>
												<p>{{ \Tools::moneyFormat($imp,'', 2) }} </p>
												<input type="hidden" name="pvp_{{$article->id_art}}"	value="{{ $imp}}" >
											</div>
										</div>

										<div class="col-xs-12 col-sm-2  account-item-border">
												<div class="user-account-item-price  d-flex align-items-center justify-content-center">
													<div class="visible-xs">{{ trans(\Config::get('app.theme').'-app.user_panel.price_clean') }}</div>
													<div>
														<strong>
															<span class="toPayArticle_{{$article->id_art}}_JS">	{{ \Tools::moneyFormat($imp * $units[$article->id_art],trans(\Config::get('app.theme').'-app.subastas.euros'), 2) }}
														</strong>
													</div>
												</div>
										</div>
										<div class="col-xs-12 col-sm-1  account-item-border">
											<div class="user-account-item-price  d-flex align-items-center justify-content-center">
												<div class="deleteArticle_JS cursor" data-idart="{{$article->id_art}}"  style="color:red">{{ trans(\Config::get('app.theme').'-app.user_panel.delete') }}</div>

											</div>
									</div>

									</div>
								</div>
							</div>

						@endforeach
						<?php

?>

							<div class="adj color-letter  align-items-center justify-content-space-between">
								<br>
								{!! trans(\Config::get('app.theme').'-app.shopping_cart.comment')  !!}
								<br>
								<textarea name="comments" rows="5" style="width: 100%;"> </textarea>
								@if( !empty(Config::get('app.web_gastos_envio')))
								<?PHP /* Calculo de gastos de envio
									<div class="col-xs-12 col-sm-5 gastos_envio" >
										<strong> {{ trans(\Config::get('app.theme').'-app.user_panel.direccion-facturacion') }}</strong>
											<select id="clidd_carrito"  name="clidd_carrito" class="change_address_carrito_js "   data-sub="carrito" style="width: 90%;">
											@foreach($address as $key => $value)
												<option value="{{ $key}}">{{$value}} </option>
											@endforeach

										</select>
										<br>

									</div>
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
									 */ ?>
								@else
									<div class="col-xs-12 col-sm-8 "  > </div>

								@endif


								<div class="col-xs-12 col-sm-4 total-price" >

									{{ trans(\Config::get('app.theme').'-app.shopping_cart.total_articles') }} <br> <span class="totalArticulos_JS " > </span> {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}


									<br><br>{{ trans(\Config::get('app.theme').'-app.shopping_cart.esimationTime') }}
									@if( !empty(Config::get('app.web_gastos_envio')))
										<br>	<br><br>

										{{ trans(\Config::get('app.theme').'-app.shopping_cart.total_pay') }} <br><span class="totalPagar_JS"></span> {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
									@endif


									@if(\Config::get("app.PayBizum") || \Config::get("app.PayTransfer"))
										<br><br>
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

									<button style="margin-left: 15px;" type="button" class="secondary-button   submitArticleCart_JS "  cod_sub="carrito" class="btn btn-step-reg" >{{ trans(\Config::get('app.theme').'-app.user_panel.pay') }}</button>
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
	var articles = [@foreach(array_keys($units) as $id_art) "{{$id_art}}", @endforeach];
	var lang = "{{ \Config::get("app.locale")}}";
    $( document ).ready(function() {
		//No eliminar
		calcShippingCosts();
		$(".units_JS").on("change",function () {
			idart = $(this).data("idart");
			changeUnitsArticle( idart, $(this).val());
			units = $(this).val();
			pvp = $("input[name=pvp_" + idart + "]").val();
			if (!isNaN(units) && !isNaN(pvp)){
				totalArticle =  parseFloat(units) * parseFloat(pvp) ;
				totalArticle =new Intl.NumberFormat("de", {minimumFractionDigits: 2}).format(parseFloat(totalArticle));
				$(".toPayArticle_" + idart + "_JS").html(totalArticle);
			}

			if(!isNaN(units)){

				$("#grabados_JS").html("");
				for(x=1;x<units;x++){
					grabado = $('input[name="grabados[' + idart +'][0]"]').clone();
					grabado.attr("name",'grabados[' + idart +'][' + x +']');
					$("#grabados_JS").append(grabado);
				}
			}

			calcCostToPay();
		});
    });

</script>


@stop
