@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

<div class="color-letter">
	<div class="container titlePage-container">
		<div class="row">
			<div class="col-xs-12 text-center">
				<h1 class="titlePage">{{ trans(\Config::get('app.theme').'-app.user_panel.mi_cuenta') }}</h1>
			</div>
		</div>
	</div>
</div>

<div class="account-user   panel-user">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-3 col-lg-3 account-user-menu">
				<?php $tab="contraoferta";?>
				@include('pages.panel.menu_micuenta')
			</div>
			<div class="col-xs-12 col-md-9 col-lg-9 ">
			@if(!\Session::has("user"))
			<center ><h2>	Debes estar logeado para poder aceptar la oferta</h2></center>
			@elseif(Session::get('user.cod')!= $lot->prop_hces1)
			<center><h2>	Debes ser el propietario para aceptar la oferta</h2></center>
			@else
				<div class="col-xs-12 col-md-2  no-padding ">
				</div>
				<div class="col-xs-12 col-md-10 no-padding ">
					<div >
						<div class="user-account-menu-title">
							Aceptar contraoferta
						</div>
					</div>
					<div class="panel-group" id="accordion">
						<div class="panel panel-default">
							<br><br>
							@php
								$comision = 1 + config('app.carlandiaCommission');
								$contraoferta = $asigAux->imp_asigl1 / $comision;
								$precioMinimo = $lot->imptas_asigl0 / $comision;
							@endphp
							<p><b>Datos del vehículo:</b></p>
								<ul class="ml-2">
									<li><b>Oferta Nº:</b> {{$lot->ref_asigl0}}</li>
									<li><b>Vehículo:</b> {{$lot->descweb_hces1 }}</li>
									<li><b>Importe de la Contraoferta recibida:</b> {{\Tools::moneyFormat($contraoferta,"€",2)}} (IVA incluido)</li>
									<li><b>Precio Mínimo:</b> {{\Tools::moneyFormat($precioMinimo)}} € (IVA incluido)</li>
								</ul>
						</div>
					</div>
				</div>
				<center>


					<br>
					Por favor confirma que deseas aceptar la contraoferta
					<br><br>
					<form action="/contraoferta-aceptada"  method="post" >
						<button type="submit" class="button-principal" >Confirmar  </button>
						<input type="hidden" name="sku" value="{{request("sku")}}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}" />

					</form>
					<br><br>
				</center>
			@endif
			</div>

		</div>
	</div>
</div>


@stop
