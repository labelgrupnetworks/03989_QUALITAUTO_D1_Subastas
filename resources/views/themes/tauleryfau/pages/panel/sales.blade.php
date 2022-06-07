@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

<style>
	.container-503 {
		text-align: center;
		vertical-align: middle;
	}

	.container-503 .content {
		text-align: center;
		display: inline-block;
	}

	.container-503 .title {
		font-size: 72px;
		margin-bottom: 40px;
	}
</style>

@section('content')
@include('pages.panel.principal_bar')

<section class="account cesiones">
	<div class="container">
		<div class="row">
			<?php $tab="cesiones";?>

			<div class="col-xs-12">
				@include('pages.panel.menu')
			</div>

			<div class="col-xs-12">
				<div class="user-datas-title">
					<p>{{ trans(\Config::get('app.theme').'-app.user_panel.my_assignments') }}<span style="float: right">{{Session::get('user.cod')}} -
							{{ \Session::get('user.name') }}</span></p>

				</div>
			</div>

			<div class="col-xs-12 cesiones-bi">
				@include('pages.panel.sales.cesiones_bi')
			</div>

			@php
					use App\libs\Currency;
                    $currency = new Currency();
                    $divisa = !empty(Session::get('user.currency'))? Session::get('user.currency') : 'EUR';
                    $currency->setDivisa($divisa);
					$divisas = $currency->getAllCurrencies();

					$subastasActivasTr = [];
					$subastasActivasFinalizadas = [];

					$SubastaTR = new \App\Models\SubastaTiempoReal();

					foreach ($subastas as $cod_sub => $lotes) {

						$SubastaTR->cod = $cod_sub;
						$SubastaTR->session_reference = $lotes->first()->reference;
						$status  = $SubastaTR->getStatus();

						if (!empty($status) && $status[0]->estado == "ended" && in_array($lotes->first()->subc_sub, ['S', 'H']) && $lotes->first()->tipo_sub != 'V') {
							$subastasActivasFinalizadas[$cod_sub] = $subastas[$cod_sub];
						}
						elseif($lotes->first()->subc_sub == 'S'){
							$subastasActivasTr[$cod_sub] = $subastas[$cod_sub];
						}
					}
			@endphp


			<div class="col-xs-12 mt-2">

				<ul class="nav nav-tabs nav-justified">
					<li>
						<a data-toggle="tab" href="#finalizados">{{ trans(\Config::get('app.theme').'-app.user_panel.finished_lots') }}</a>
					</li>
					<li class="active">
						<a data-toggle="tab" href="#activos">{{ trans(\Config::get('app.theme').'-app.user_panel.active_lots') }}</a>
					</li>
					<li>
						<a {{--data-toggle="tab"--}} href="{{ \Routing::translateSeo('valoracion-articulos') }}">{{ trans(\Config::get('app.theme').'-app.user_panel.consign') }}</a>
					</li>
				</ul>

				<div class="tab-content">

					<div id="finalizados" class="tab-pane fade">


						<div class="panel-group" id="accordion">
							<div class="panel panel-default panel-payment" id="panel-payment-finish">
								@include('pages.panel.sales.invoice_assignor_cabecera', ['subastas' => $facturas])
							</div>
						</div>
					</div>
					<div id="activos" class="tab-pane fade in active">
						<div class="panel-group" id="accordion">
							<div class="panel panel-default panel-payment">
								@include('pages.panel.sales.auctions', ['subastas' => $subastasActivasTr, 'finalizada' => false])
								@include('pages.panel.sales.auctions', ['subastas' => $subastasActivasFinalizadas, 'finalizada' => true])
							</div>
						</div>
					</div>
					<div id="consignar" class="tab-pane fade">
						<div class="container-503">
							<div class="content">
								<div class="title">Coming soon.</div>
							</div>
						</div>
					</div>
				</div>

			</div>

		</div>
	</div>
</section>


@stop
