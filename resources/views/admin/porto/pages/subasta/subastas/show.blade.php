@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
	@include('admin::includes.header_content')

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-9">
			@if(config('app.admin_show_auction_code', true))
			<h1>{{ trans("admin-app.title.auction") }} {{ $fgSub->cod_sub }}</h1>
			@endif
			<h3>{{ $fgSub->des_sub }}</h3>
		</div>
		<div class="col-xs-3">
			<a href="{{ route("$resource_name.index") }}"
				class="btn btn-primary right">{{ trans("admin-app.button.return") }}</a>
		</div>
	</div>

	<div class="row well p-0">
		<div class="col-xs-12">

			<div class="row">
				<div class="col-xs-12">
					<ul class="nav nav-tabs" id="show-auction-tab" role="tablist">
						@if(!in_array('noLots',Config::get('app.config_menu_admin')))
						<li class="nav-item active">
							<a class="nav-link" id="lotes-tab" data-toggle="tab" href="#lotes" role="tab"
								aria-controls="lotes" aria-selected="false">{{ trans("admin-app.title.lots") }}</a>
						</li>
						@endif
						@if(!in_array('noBids',Config::get('app.config_menu_admin')))
						<li class="nav-item">
							<a class="nav-link" id="pujas-tab" data-toggle="tab" href="#pujas" role="tab" aria-controls="pujas"
								aria-selected="false">{{ trans('admin-app.title.bids') }}</a>
						</li>
						@endif
						@if(!in_array('noOrders', Config::get('app.config_menu_admin')) && !empty($ordersTable))
							<li class="nav-item">
								<a class="nav-link" id="ordenes-tab" data-toggle="tab" href="#ordenes" role="tab" aria-controls="ordenes"
									aria-selected="false">{{ trans('admin-app.title.orders') }}</a>
							</li>
						@endif
						@if(!in_array('noAwards', Config::get('app.config_menu_admin')))
						<li class="nav-item">
							<a class="nav-link" id="adjudicaciones-tab" data-toggle="tab" href="#adjudicaciones" role="tab" aria-controls="adjudicaciones"
								aria-selected="false">{{ trans('admin-app.title.awards') }}</a>
						</li>
						@endif
						{{-- @if(!in_array('noWinners', Config::get('app.config_menu_admin')))
						<li class="nav-item">
							<a class="nav-link" id="ganadores-tab" data-toggle="tab" href="#ganadores" role="tab" aria-controls="ganadores"
								aria-selected="false">{{ trans('admin-app.title.winners') }}</a>
						</li>
						@endif --}}

						@if(in_array('lotsNotAwards', Config::get('app.config_menu_admin')))
						<li class="nav-item">
							<a class="nav-link" id="notAwards-tab" data-toggle="tab" href="#notAwards" role="tab" aria-controls="notAwards"
								aria-selected="false">{{ trans('admin-app.title.not_awards') }}</a>
						</li>
						@endif
					</ul>
				</div>
			</div>

				<div class="row">

					<div class="col-xs-12">

						<div class="tab-content" id="myTabContent">

							<div class="tab-pane fade active in" id="lotes" role="tabpanel" aria-labelledby="lotes-tab">
								<div class="row">
									{!! $viewFgAsigl0 !!}
								</div>
							</div>

							<div class="tab-pane fade" id="pujas" role="tabpanel" aria-labelledby="pujas-tab">
								<div class="row">
									{{-- @include('admin::pages.subasta.subastas._nav_pujas', ['pujas' => $pujas, 'id' => $fgSub->cod_sub]) --}}
									{!! $pujasTable !!}
								</div>
							</div>

							<div class="tab-pane fade" id="ordenes" role="tabpanel" aria-labelledby="ordenes-tab">
								<div class="row">
									@if(!empty($ordersTable))
										{!! $ordersTable !!}
									@endif
								</div>
							</div>

							<div class="tab-pane fade" id="adjudicaciones" role="tabpanel" aria-labelledby="adjudicaciones-tab">
								<div class="row">
									{!! $awardsTable !!}
								</div>
							</div>

							<div class="tab-pane fade" id="notAwards" role="tabpanel" aria-labelledby="notAwards-tab">
								<div class="row">
									{!! $notAwardsTable !!}
								</div>
							</div>

							{{-- <div class="tab-pane fade" id="ganadores" role="tabpanel" aria-labelledby="ganadores-tab">
								<div class="row">
									@include('admin::pages.subasta.subastas._nav_ganadores', compact('ganadores', 'licitadores'))
									{!! $winnersTable !!}
								</div>
							</div> --}}

						</div>



					</div>

				</div>

		</div>
	</div>

	{!! \FormLib::modalToList('list_modal')!!}

</section>

@stop
