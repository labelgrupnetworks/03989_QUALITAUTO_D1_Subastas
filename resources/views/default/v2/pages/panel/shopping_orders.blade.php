@extends('layouts.default')

@section('title')
{{ trans('web.head.title_app') }}
@stop

@section('content')

<div class="color-letter">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 text-center">
				<h1 class="titlePage">{{ trans('web.user_panel.mi_cuenta') }}</h1>
			</div>
		</div>
	</div>
</div>

<div class="account-user color-letter  panel-user">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-3 col-lg-3 account-user-menu">
				<?php $tab="shopping_orders";?>
				@include('pages.panel.menu_micuenta')
			</div>
			<div class="col-xs-12 col-md-9 col-lg-9 ">

				<div class="user-account-title-content">
					<div class="user-account-menu-title">
						{{ trans("web.shopping_cart.shopping_orders") }}
					</div>
				</div>


				<div class="mt-1 shopping-orders-panel">

					@foreach ($shoppingOrders as $idShoppingOrder => $shoppingOrder)

					{{-- Pedidos --}}
					<div class="mb-1 panel-title p-1" data-toggle="collapse" data-target="#order_{{ $loop->index }}" aria-expanded="false">
						<div class="row">
							<div class="col-xs-12 col-sm-3">{{ trans("web.shopping_cart.shopping_order_number") }} {{ $idShoppingOrder }}</div>
							<div class="col-xs-12 col-sm-5">{{ trans("web.user_panel.date") }}: {{ date_format(date_create_from_format('Y-m-d H:i:s', $shoppingOrder['info']['fecaccpto_pedc0']),'d/m/Y') }}</div>
							<div class="col-xs-10 col-sm-3">{{ trans("web.user_panel.total_pay") }}: {{ \Tools::moneyFormat($shoppingOrder['info']['total_pedc0'], trans('web.lot.eur'), 2)}} </div>
							<div class="col-xs-2 col-sm-1 text-right"><i class="fa fa-caret-right" aria-hidden="true"></i></div>
						</div>
					</div>

					{{-- Lineas de pedido --}}
					<div id="order_{{ $loop->index }}" class="collapse shopping-orders-lines" aria-expanded="false">

						{{-- Cabecera tabla en Pc --}}
						<div class="row user-account-heading hidden-xs mt-0 mb-1 text-center">
							<div class="col-xs-2 text-left">{{ trans("web.shopping_cart.img") }}</div>
							<div class="col-xs-3 text-left">{{ trans("web.shopping_cart.article") }}</div>
							<div class="col-xs-1">{{ trans("web.shopping_cart.quantity") }}</div>
							<div class="col-xs-2">{{ trans("web.articles.price") }}</div>
							<div class="col-xs-2">{{ trans("web.user_panel.iva") }}</div>
							<div class="col-xs-2">{{ trans("web.user_panel.total_pay") }}</div>
						</div>

						{{-- Articulos --}}
						@foreach ($shoppingOrder['articles'] as $article)
						<div id="{{ $article->id_art }}" class="text-center d-flex align-items-stretch flex-wrap mb-1 user-accout-item-wrapper">

							<div class="col-xs-12 visible-xs">
								<p class="max-line-2">{{ $article->model_art0 }}</p>
							</div>

							<div class="col-xs-12 col-sm-2 d-flex align-items-center justify-content-center">
								<img class="img-responsive" src="{{ $article->image }}" alt="{{ $article->model_art0 }}">
							</div>

							<div class="col-xs-12 col-sm-3 account-item-border d-flex orders-line">
								<div class="text-left">

									<p class="max-line-2 hidden-xs mb-1">{{ $article->model_art0 }}</p>

									@foreach ($article->tallasColores as $tallasColores)
										<p>
											<span class="line-attribute">{{ $tallasColores->name_variante }}: </span>
											<span class="value-attribute">{{ $tallasColores->valor_valvariante }}</span>
										</p>
									@endforeach
								</div>
							</div>

							<div class="col-xs-12 col-sm-1 order-xs account-item-border d-flex justify-content-center orders-line">
								<p>
									<span class="visible-xs line-attribute">{{ trans("web.shopping_cart.quantity") }}:</span>
									<span class="value-attribute">{{ \Tools::moneyFormat($article->cant_pedc1, false, 0) }}</span>
								</p>
							</div>

							<div class="col-xs-12 col-sm-2 account-item-border d-flex justify-content-center orders-line">
								<p>
									<span class="visible-xs line-attribute">{{ trans("web.user_panel.iva") }}:</span>
									<span class="value-attribute">{{ \Tools::moneyFormat($article->imp_pedc1, trans('web.lot.eur'), 2) }}</span>
								</p>
							</div>

							<div class="col-xs-12 col-sm-2 account-item-border d-flex justify-content-center orders-line">
								<p>
									<span class="visible-xs line-attribute">{{ trans("web.articles.price") }}:</span>
									<span class="value-attribute">{{ \Tools::moneyFormat($article->impiva_pedc1, trans('web.lot.eur'), 2) }}</span>
								</p>
							</div>

							<div class="col-xs-12 col-sm-2 account-item-border d-flex justify-content-center orders-line">
								<p>
									<span class="visible-xs line-attribute">{{ trans("web.user_panel.total_pay") }}:</span>
									<span class="value-attribute">{{ \Tools::moneyFormat(($article->imp_pedc1 + $article->impiva_pedc1), trans('web.lot.eur'), 2) }}</span>
								</p>
							</div>

						</div>
						@endforeach

						{{-- Desglose --}}
						<div class="row mb-2">
							<div class="col-xs-12">
								<hr class="m-1">
							</div>
							<div class="col-xs-12 col-md-3 col-md-offset-9 orders-totals">
								<p>{{ trans("web.shopping_cart.base_price") }}: <span>{{ \Tools::moneyFormat(($shoppingOrder['info']['base_pedc0']), trans('web.lot.eur'), 2) }}</span></p>
								<p>{{ trans("web.user_panel.iva") }}: <span>{{ \Tools::moneyFormat(($shoppingOrder['info']['impiva_pedc0']), trans('web.lot.eur'), 2) }}</span></p>
								<p>{{ trans("web.user_panel.total_price") }} <span>{{ \Tools::moneyFormat(($shoppingOrder['info']['total_pedc0']), trans('web.lot.eur'), 2) }}</span></p>
							</div>
						</div>



					</div>

					@endforeach

				</div>
			</div>
		</div>
	</div>
</div>

<script>
$('.collapse').on('show.bs.collapse', function (e) {
  $(e.target.previousElementSibling.querySelector('i')).removeClass('fa-caret-right').addClass('fa-caret-down');
})
$('.collapse').on('hide.bs.collapse', function (e) {
  $(e.target.previousElementSibling.querySelector('i')).removeClass('fa-caret-down').addClass('fa-caret-right');
})
</script>
@stop
