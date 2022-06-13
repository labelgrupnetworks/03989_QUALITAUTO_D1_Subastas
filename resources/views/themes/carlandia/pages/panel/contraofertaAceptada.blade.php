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

				<div class="col-xs-12 col-md-2  no-padding ">
				</div>
				<div class="col-xs-12 col-md-10 no-padding ">
					@if($estado == "aceptada")
						<div >
							<div class="user-account-menu-title">
								Contraoferta Aceptada.
							</div>
						</div>

						<br>
						El usuario recibirá un email desde el que podrá realizar el pago de la señal.
						<br><br><br>
					@elseif($estado == "no_existe")
						<br>	La contraoferta ya fue aceptada anteriormente.
					@else
						<br>	Ha ocurrido un error y no se ha podido aceptar la contraoferta.
					@endif
				</div>


			</div>

		</div>
	</div>
</div>


@stop
