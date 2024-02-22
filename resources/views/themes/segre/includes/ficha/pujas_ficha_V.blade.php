<?php

$name="";
$phone="";
$email="";
if(!empty($data['usuario'])){
	$name=$data['usuario']->nom_cliweb;
	$phone=$data['usuario']->tel1_cli;
	$email=$data['usuario']->email_cliweb;

}
$importe = $lote_actual->impsalhces_asigl0;

if(!empty($lote_actual->impres_asigl0) && $lote_actual->impres_asigl0 >  $lote_actual->impsalhces_asigl0 ){
	$importe =  $lote_actual->impres_asigl0;

}

#indicamos el importe + comision + iva
#no quieren poner la comisión ya que no es la misma siempre yel iva depende del uaurio
/*
$comision =$importe * $lote_actual->comlhces_asigl0/100;
$ivaComision =  $comision * 0.21;
$importe = $importe + $comision +  $ivaComision;
*/
$importeExchange =\Tools::moneyFormat( $importe);
$importe =  \Tools::moneyFormat($importe,false,2);
?>
<script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>
<div class="col-xs-12 no-padding ">


    <div class="info_single ficha_V col-xs-12 no-padding mb-2">

        <div class="col-xs-12 no-padding ficha-info-items-buy">
			<div class="col-xs-6 ">
            	<div class="pre">
                    <p class="pre-title-principal">{{ trans($theme.'-app.subastas.price_sale') }}</p>
                    <p class="pre-price">{{$importe}} {{ trans($theme.'-app.subastas.euros') }}
						@if(\Config::get("app.exchange"))
						|   <span id="directSaleExchange_JS" class="exchange"> </span>
							<input id="startPriceDirectSale" type="hidden" value="{{$importeExchange}}">
						@endif

					</p>
					<p>+  comisión (17%) e IVA</p>
                </div>
			</div>
			<div class="col-xs-6 ">
                <div class="info_single_content info_single_button ficha-button-buy">
                    @if (!$retirado && empty($lote_actual->himp_csub) && !$sub_cerrada)
						{{-- Si el lote es NFT y el usuario está logeado pero no tiene wallet --}}
						@if ($lote_actual->es_nft_asigl0 == "S" &&  !empty($data["usuario"])  && empty($data["usuario"]->wallet_cli) )
							<div class="require-wallet">{!! trans($theme.'-app.lot.require_wallet') !!}</div>
						@else
                        	<button data-from="modal" class="button-principal lot-action_comprar_lot" type="button" ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">{{ trans($theme.'-app.subastas.buy_lot') }}</button>
						@endif
					@endif
                </div>
			</div>


        </div>
		<div class="col-xs-12">
			<p class="pre-title-principal adj-text">	{{ trans(\Config::get('app.theme').'-app.galery.request_information') }} </p>
					<form name="infoLotForm" id="infoLotForm" method="post" action="javascript:sendInfoLot()">
						<input type="hidden" name="auction" value="{{ $lote_actual->cod_sub}} - {{ $lote_actual->des_sub}}">
						<input type="hidden" name="lot" value="   {{$lote_actual->descweb_hces1 }} ">

						<div class="form-group">
							<div class="input-effect col-xs-12">
								<label>{{trans(\Config::get('app.theme').'-app.login_register.contact') }} *</label>
								<input type="text" class="form-control  " name="nombre" id="texto__1__nombre" value="{{$name}}" onblur="comprueba_campo(this)" data-placement="right" placeholder="" autocomplete="off" data-content="">

							</div>

							<div class="input-effect col-xs-12">
								<label>{{trans(\Config::get('app.theme').'-app.foot.newsletter_text_input') }} *</label>
								<input type="text" class="form-control  " name="email" id="email__1__email" value="{{$email}}" onblur="comprueba_campo(this)" data-placement="right" placeholder="" autocomplete="off" data-content="">

							</div>

							<div class="input-effect col-xs-12">
								<label>{{trans(\Config::get('app.theme').'-app.user_panel.phone') }} *</label>
								<input type="text" class="form-control  " name="telefono" id="texto__1__telefono" value="{{$phone}}" onblur="comprueba_campo(this)" data-placement="right" placeholder="" autocomplete="off" data-content="">

							</div>

							<div class="input-effect col-xs-12">
								<label>{{trans(\Config::get('app.theme').'-app.global.coment') }} *</label>
								<textarea  class="form-control  " name="comentario"  id="textogrande__1__comentario"   rows="10">  </textarea>

							</div>
							@php
								/*

							<div class="check_term col-xs-12 mt-2">
								<div class="row">
									<div class="col-xs-2 col-md-1">
										<input type="checkbox" class="newsletter" name="condiciones" value="on"
											id="bool__1__condiciones" autocomplete="off">
									</div>
									<div class="col-xs-10 col-md-11">
										<label
											for="accept_new"><?= trans(\Config::get('app.theme') . '-app.emails.privacy_conditions') ?></label>
									</div>
								</div>
							</div>
							*/
							@endphp
							<div class="col-xs-12">
								<p>Los campos con * son obligatorios </p>
							</div>
							<div class="col-xs-12 mt">
								<div class="row">
									<div class="g-recaptcha col-xs-6"
										data-sitekey="{{\Config::get('app.codRecaptchaEmailPublico')}}"
										data-callback="onSubmit">
									</div>
								</div>
							</div>

							<div class="col-xs-12 mt-3 mb-3">
								<div class="row">
									<div class="col-xs-6">
										<a onclick="javascript:submit_form(document.getElementById('infoLotForm'),0);" class="button-principal submitButton">Enviar</a>
									</div>
								</div>
							</div>
						</div>
					</form>
			</div>

    </div>
</div>
