@extends('layouts.default')

@section('title')
{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')
<section class="principal-bar no-principal">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="princiapl-bar-wrapper">
					<div class="principal-bar-title">
						<h3>{{ trans($theme.'-app.user_panel.mi_cuenta') }}</h3>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<style>
	@media (max-width: 600px) {
		.custom-wrapper-responsive .auc-data-custom p:first-child {
			height: 25px;
			padding-right: 10px;
			font-size: 12px;
		}
	}
</style>
<section class="account">
	<div class="container">
		<div class="row">
			<div class="col-xs-2 col-md-3">
				<?php
                    $tab="orders";
                    if(!empty($data['favorites'])){
                        $tab="favorites";
                    }

                    use App\libs\Currency;
                    $currency = new Currency();
                    $divisa = !empty(Session::get('user.currency'))? Session::get('user.currency') : 'EUR';
                    $currency->setDivisa($divisa);

                ?>
				@include('pages.panel.menu_micuenta')
			</div>
			<div class="col-xs-10 col-sm-9">
				<div role="tabpanel" class="user-datas-title">
					@if(!empty($data['favorites']))
					<p>{{ trans($theme.'-app.user_panel.favorites') }}</p>
					@else
					<p>{{ trans($theme.'-app.user_panel.orders') }}</p>
					@endif
					<small
						style="font-weight: 100;color: red;font-size: 12px;line-height: 0;">*{{ trans($theme.'-app.msg_neutral.noRT') }}</small>
					<div class="col_reg_form"></div>
				</div>
				<div class="panel-group" id="accordion">
					<div class="panel panel-default marg-resp">
						@php

						$finalized = [];
						$notFinalized = [];
						@endphp

						<div class="auctions-list-title"><strong>{{ trans($theme.'-app.subastas.next_auctions') }}</strong></div>
						@foreach($data['values'] as $key_sub => $all_inf)

						@php

						//ver si la subasta está cerrada
						$SubastaTR = new \App\Models\SubastaTiempoReal();
						$SubastaTR->cod =$all_inf['inf']->cod_sub;
						$SubastaTR->session_reference = $all_inf['inf']->reference;

						$status = $SubastaTR->getStatus();
						$subasta_finalizada = false;

						if(!empty($status) && $status[0]->estado == "ended" && $all_inf['inf']->tipo_sub != 'V'){
						$subasta_finalizada = true;
						array_unshift($finalized, $all_inf);
						}
						else{
						array_unshift($notFinalized, $all_inf);
						}
						@endphp


						@if ($all_inf['inf']->tipo_sub != 'V' && !$subasta_finalizada)
						@include('pages.panel.orders_auction')
						@endif

						@endforeach

						@if(count($finalized) > 0)
						<div class="auctions-list-title"><strong>{{ trans($theme.'-app.subastas.finished_auctions') }}</strong></div>
						@foreach ($finalized as $all_inf)
						@include('pages.panel.orders_auction')
						@endforeach

						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@stop
