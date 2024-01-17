@extends('admin::layouts.logged')
@section('content')

@php
	$emp = \Config::get('app.emp');
	$linkToPrint = \Config::get('app.printExhibitionLabels');
@endphp

<section role="main" class="content-body">
	@include('admin::includes.header_content')
	@csrf

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-12">
			<h1>{{ trans("admin-app.title.auctions") }}</h1>
		</div>
	</div>

	<div class="row well">

		<div class="col-xs-12 text-right mb-1 pt-1 pb-1" style="background-color: #ffe7e7">
			{{-- <a href="{{ route("$resource_name.lotes.order_destacadas_edit") }}"
				class="btn btn-sm btn-warning">{{ trans("admin-app.button.sort") }}
				{{ trans("admin-app.title.lot_auction_destacadas") }}</a> --}}

			<div class="btn-group" id="js-dropdownItems">
				<button class="btn btn-default btn-sm" type="button">{{ trans("admin-app.button.selecteds") }}</button>
				<button
					data-objective="auc_ids"
					class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button"
					aria-haspopup="true" aria-expanded="false">
					<span class="caret"></span>
				</button>

				<ul aria-labelledby="js-dropdownItems" class="dropdown-menu">

					<li>
						<button class="btn" data-toggle="modal" data-target="#editMultpleAuctionsModal">
							{{ trans("admin-app.button.modify") }}
						</button>
					</li>

				</ul>
			</div>

			<a href="{{ route("$resource_name.create") }}"
				class="btn btn-sm btn-primary">{{ trans("admin-app.button.new_fem") }}
				{{ trans("admin-app.title.auction") }}</a>

			@include('admin::includes.config_table', ['id' => $resource_name, 'params' => ((array) $formulario)])
		</div>

		<div class="col-xs-12">
			<table id="{{ $resource_name }}" class="table table-striped table-condensed table-responsive" style="width:100%" data-order-name="order">
				<thead>

					<tr>
						<th>
							<label>
								<input name="js-selectAll" data-objective="auc_ids" type="checkbox" value="true">
								<input id="urlAllSelected" name="url-allSelected"  type="hidden" value="{{ route('subastas.update_with_filters') }}">
							</label>
						</th>
						<th class="cod_sub" style="cursor: pointer" data-order="cod_sub">
							{{ trans("admin-app.fields.cod_sub") }}
							@if(request()->order == 'cod_sub')
								<span style="margin-left: 5px; float: right;">
									@if(request()->order_dir == 'asc')
										<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
									@else
										<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
									@endif
								</span>
							@endif
						</th>
						<th class="des_sub" style="cursor: pointer" data-order="des_sub">
							{{ trans("admin-app.fields.des_sub") }}
							@if(request()->order == 'des_sub')
								<span style="margin-left: 5px; float: right;">
									@if(request()->order_dir == 'asc')
										<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
									@else
										<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
									@endif
								</span>
							@endif
						</th>
						@if (config("app.ArtistInExibition", false))
						<th class="artist_name" style="cursor: pointer" data-order="valorcol_sub">
							{{ trans("admin-app.fields.artist_name") }}
							@if(request()->order == 'valorcol_sub')
								<span style="margin-left: 5px; float: right;">
									@if(request()->order_dir == 'asc')
										<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
									@else
										<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
									@endif
								</span>
							@endif
						</th>
						@endif
						<th class="subc_sub" style="cursor: pointer" data-order="subc_sub">
							{{ trans("admin-app.fields.subc_sub") }}
							@if(request()->order == 'subc_sub')
								<span style="margin-left: 5px; float: right;">
									@if(request()->order_dir == 'asc')
										<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
									@else
										<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
									@endif
								</span>
							@endif
						</th>
						<th class="tipo_sub" style="cursor: pointer" data-order="tipo_sub">
							{{ trans("admin-app.fields.tipo_sub") }}
							@if(request()->order == 'tipo_sub')
								<span style="margin-left: 5px; float: right;">
									@if(request()->order_dir == 'asc')
										<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
									@else
										<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
									@endif
								</span>
							@endif
						</th>
						<th class="dfec_sub" style="cursor: pointer" data-order="dfec_sub">
							{{ trans("admin-app.fields.dfec_sub") }}
							@if(request()->order == 'dfec_sub')
								<span style="margin-left: 5px; float: right;">
									@if(request()->order_dir == 'asc')
										<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
									@else
										<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
									@endif
								</span>
							@endif
						</th>
						<th class="hfec_sub" style="cursor: pointer" data-order="hfec_sub">
							{{ trans("admin-app.fields.hfec_sub") }}
							@if(request()->order == 'hfec_sub')
								<span style="margin-left: 5px; float: right;">
									@if(request()->order_dir == 'asc')
										<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
									@else
										<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
									@endif
								</span>
							@endif
						</th>
						<th>
							<span>{{ trans("admin-app.fields.actions") }}</span>
						</th>
					</tr>
				</thead>

				<tbody>

					<tr id="filters">
						<form class="form-group" action="">
							<td></td>
							<input type="hidden" name="order" value="{{ request('order', 'dfec_sub') }}">
							<input type="hidden" name="order_dir" value="{{ request('order_dir', 'desc') }}">
							<td class="cod_sub">{!! $formulario->cod_sub !!}</td>
							<td class="des_sub">{!! $formulario->des_sub !!}</td>
							@if (config("app.ArtistInExibition", false))
							<td class="artist_name">{!! $formulario->artist_name !!}</td>
							@endif
							<td class="subc_sub">{!! $formulario->subc_sub !!}</td>
							<td class="tipo_sub">{!! $formulario->tipo_sub !!}</td>
							<td class="dfec_sub">{!! $formulario->dfec_sub !!}</td>
							<td class="hfec_sub">{!! $formulario->hfec_sub !!}</td>
							<td class="d-flex" style="width: 300px">
								<input type="submit" class="btn btn-info w-100"
									value="{{ trans("admin-app.button.search") }}"><a
									href="{{route("$resource_name.index")}}"
									class="btn btn-warning w-100">{{ trans("admin-app.button.restart") }}</a>
							</td>
						</form>
					</tr>
					@php
						if (\Config::get("app.subalia_cli")) {
							$auchouse = App\Models\V5\SubAuchouse::where("emp_auchouse", \Config::get("app.APP_SUBALIA_EMP","001"))->where("cli_auchouse",\Config::get("app.subalia_cli"))->first();
						}
					@endphp

					@forelse ($fgSubs as $fgSub)

					<tr id="fila{{$fgSub->cod_sub}}">
						<td>
							<label>
								<input type="checkbox" name="auc_ids" value="{{$fgSub->cod_sub}}">
							</label>
						</td>
						<td class="cod_sub">{{$fgSub->cod_sub}}</td>
						<td class="des_sub">{{$fgSub->des_sub}}</td>
						@if (config("app.ArtistInExibition", false))
							<td class="artist_name">{!! $artists[$fgSub->valorcol_sub] ?? null !!}</td>
						@endif
						<td class="subc_sub">{{$fgSub->subc_sub_type}}</td>
						<td class="tipo_sub">{{$fgSub->tipo_sub_type}}</td>
						<td class="dfec_sub">{{$fgSub->desde_fecha_hora}}</td>
						<td class="hfec_sub">{{$fgSub->hasta_fecha_hora}}</td>
						<td class="d-inline-flex justify-content-flex-end" style="gap: 2px;">
							<a title="{{ trans("admin-app.button.edit") }}"
								href="{{ route("$resource_name.edit", $fgSub->cod_sub) }}"
								class="btn btn-success btn-sm"><i class="fa fa-pencil-square-o"
									aria-hidden="true"></i>{{ trans("admin-app.button.edit") }} {{ trans('admin-app.title.auction') }}</a>
							<a title="{{ trans("admin-app.button.show") }}"
								href="{{ route("$resource_name.show", $fgSub->cod_sub) }}"
								class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> {{ trans("admin-app.button.show_content") }}</a>
								@if (\Config::get('app.printExhibitionLabels')) {{-- Botón de descarga PDF de ansorena --}}
									<a href="{{\Config::get('app.printExhibitionLabels')}}?empresa={{$emp}}&subasta={{$fgSub->cod_sub}}"
									class="btn btn-info btn-sm" target="blank_"><i class="fa fa-print" aria-hidden="true"></i></a>
								@endif



							{{-- si esta en subalia --}}
							@if(\Config::get("app.subalia_cli"))

								@php
									$hash = hash_hmac("sha256", \Config::get("app.emp")." ".$fgSub->cod_sub, "f5a7433f517028601d98d9f392d0A87b2df43h76jhty");
								@endphp

								<button data-url="{{ \Config::get("app.subalia_URL")}}/forceImportAuction?client={{$auchouse->cod_auchouse}}&cod_sub={{$fgSub->cod_sub}}&hash={{$hash}}" class="show_subalia_JS btn btn-warning btn-sm">
									 {{ trans("admin-app.title.show_in_subalia") }}
								</button>

								<button data-url="{{ \Config::get("app.subalia_URL")}}/hideAuctionErp/{{$auchouse->cod_auchouse}}-{{\Config::get("app.emp")}}-{{$fgSub->cod_sub}}/{{$hash}}" class="hide_subalia_JS btn btn-info btn-sm">
									{{ trans("admin-app.title.hide_in_subalia") }}
								</button>

							@endif
						</td>
					</tr>

					@empty

					<tr>
						<td colspan="6">
							<h3 class="text-center">{{ trans("admin-app.title.without_results") }}</h3>
						</td>
					</tr>

					@endforelse
				</tbody>
			</table>

		</div>
		<div class="col-xs-12 d-flex justify-content-center">
			{{ $fgSubs->appends(Request::query())->links() }}
		</div>
	</div>

</section>

@include('admin::pages.subasta.subastas._edit_selecteds')

@stop
