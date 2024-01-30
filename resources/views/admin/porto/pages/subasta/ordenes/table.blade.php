@if (!empty($formularioImport))
	<a href="{{ $formularioImport }}" class="btn btn-success right"
		style="margin-right: 5px;">{{ trans('admin-app.button.upload_excel') }}</a>
@endif

<div class="col-xs-12 d-flex mb-1 pt-1 pb-1" style="background-color: #ffe7e7; gap:5px; flex-wrap: wrap">

	@if ($isRender)
		<div style="flex:1">
			<div class="btn-group" id="js-dropdownOrders">
				<button class="btn btn-default btn-sm" type="button">{{ trans("admin-app.button.selecteds") }}</button>
				<button
					data-objective="orders_ids"
					class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button"
					aria-haspopup="true" aria-expanded="false">
					<span class="caret"></span>
				</button>

				<ul aria-labelledby="js-dropdownOrders" class="dropdown-menu">

					<li>
						<button class="btn" data-id="mass_delete_button"
							data-objective="orders_ids"
							data-allselected="js-selectAllOrders"
							data-title="{{ trans("admin-app.questions.erase_mass_orders") }}"
							data-response="{{ trans("admin-app.success.erase_mass_orders") }}"
							data-url="{{ route('orders.destroy_selections') }}"
							data-urlwithfilters="{{ route('orders.destroy_with_filters') }}"
							onclick="removeOrdersSelecteds(this.dataset)">
							{{ trans("admin-app.button.destroy") }}
						</button>
					</li>
				</ul>
			</div>
		</div>

		<a class="btn btn-success btn-sm"
			href="{{ route('orders.export', ['idAuction' => $cod_sub]) }}">{{ trans('admin-app.button.download_excel') }}</a>

		<a href="/admin/order/excel/{{ $cod_sub }}"
			class="btn btn-success btn-sm">{{ trans('admin-app.button.upload_excel') }}</a>
	@endif

	@if ($cod_sub)
		<a href="{{ route('orders.create', ['idAuction' => $cod_sub, 'menu' => 'subastas']) }}"
			class="btn btn-primary btn-sm">{{ trans('admin-app.button.new_fem') }}
			{{ trans('admin-app.title.order') }}
		</a>
	@endif
	<div style="margin-left: auto">
		@include('admin::includes.config_table', ['id' => 'tableOrder', 'params' => ((array) $filter)])
	</div>

</div>
<table id="tableOrder" class="table table-striped table-condensed table-responsive" style="width:100%"
	data-order-name="order_orders">
	<thead>

		<tr>
			@if ($isRender)
				<th>
					<label>
						<input name="js-selectAllOrders" data-objective="orders_ids" type="checkbox" value="true">
						<input name="auc_id" type="hidden" value="{{ $cod_sub }}">
					</label>
				</th>
			@endif
			<th class="sub_orlic" style="cursor: pointer" data-order="sub_orlic">
				{{ trans('admin-app.title.auction') }}
				@if (request()->order_orders == 'sub_orlic')
					<span style="margin-left: 5px; float: right;">
						@if (request()->order_orders_dir == 'asc')
							<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
						@else
							<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
						@endif
					</span>
				@endif
			</th>
			<th class="ref_asigl0" style="cursor: pointer" data-order="ref_asigl0">
				{{ trans('admin-app.fields.reflot') }}
				@if (request()->order_orders == 'ref_asigl0')
					<span style="margin-left: 5px; float: right;">
						@if (request()->order_orders_dir == 'asc')
							<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
						@else
							<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
						@endif
					</span>
				@endif
			</th>
			<th class="tipop_orlic" style="cursor: pointer" data-order="tipop_orlic">
				{{ trans('admin-app.placeholder.type') }} {{ trans('admin-app.fields.order') }}
				@if (request()->order_orders == 'tipop_orlic')
					<span style="margin-left: 5px; float: right;">
						@if (request()->order_orders_dir == 'asc')
							<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
						@else
							<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
						@endif
					</span>
				@endif
			</th>
			<th class="descweb_hces1">
				{{ trans('admin-app.fields.lot.desc_hces1') }}

			</th>
			<th class="nom_cli" style="cursor: pointer" data-order="nom_cli">
				{{ trans('admin-app.fields.licit_csub') }}
				@if (request()->order_orders == 'nom_cli')
					<span style="margin-left: 5px; float: right;">
						@if (request()->order_orders_dir == 'asc')
							<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
						@else
							<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
						@endif
					</span>
				@endif
			</th>
			<th class="fec_orlic" style="cursor: pointer" data-order="fec_orlic">
				{{ trans('admin-app.fields.lot.fec_asigl1') }}
				@if (request()->order_orders == 'fec_orlic')
					<span style="margin-left: 5px; float: right;">
						@if (request()->order_orders_dir == 'asc')
							<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
						@else
							<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
						@endif
					</span>
				@endif
			</th>
			<th class="himp_orlic" style="cursor: pointer" data-order="himp_orlic">
				{{ trans('admin-app.fields.himp_csub') }}
				@if (request()->order_orders == 'himp_orlic')
					<span style="margin-left: 5px; float: right;">
						@if (request()->order_orders_dir == 'asc')
							<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
						@else
							<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
						@endif
					</span>
				@endif
			</th>
			<th class="tel1_orlic" style="cursor: pointer" data-order="tel1_orlic">
				{{ trans('admin-app.fields.tel1_cli') }}
				@if (request()->order_orders == 'tel1_orlic')
					<span style="margin-left: 5px; float: right;">
						@if (request()->order_orders_dir == 'asc')
							<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
						@else
							<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
						@endif
					</span>
				@endif
			</th>
			<th class="idorigen_hces1" style="cursor: pointer" data-order="idorigen_hces1">
				{{ trans('admin-app.fields.idorigen_hces1') }}
				@if (request()->order_orders == 'idorigen_hces1')
					<span style="margin-left: 5px; float: right;">
						@if (request()->order_orders_dir == 'asc')
							<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
						@else
							<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
						@endif
					</span>
				@endif
			</th>
			<th class="cod2_cli" style="cursor: pointer" data-order="cod2_cli">
				{{ trans('admin-app.fields.cod2_cli') }}
				@if (request()->order_orders == 'cod2_cli')
					<span style="margin-left: 5px; float: right;">
						@if (request()->order_orders_dir == 'asc')
							<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
						@else
							<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
						@endif
					</span>
				@endif
			</th>
			<th class="cod_licit" style="cursor: pointer" data-order="cod_licit">
				{{ trans('admin-app.fields.cod_licit') }}
				@if (request()->order_orders == 'cod_licit')
					<span style="margin-left: 5px; float: right;">
						@if (request()->order_orders_dir == 'asc')
							<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
						@else
							<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
						@endif
					</span>
				@endif
			</th>
			<th>
				{{ trans('admin-app.fields.actions') }}
			</th>
		</tr>

	</thead>
	<tbody>
		<tr id="filters">
			<form class="form-group" action="">
				<input type="hidden" name="order_orders_orders" value="{{ request('order_orders_orders', 'cod_cli') }}">
				<input type="hidden" name="order_orders_dir" value="{{ request('order_orders_dir', 'desc') }}">
				@if ($isRender)
					<td></td>
				@endif
				@foreach ($filter as $index => $item)
					<td class="{{ $index }}">{!! $item !!}</td>
				@endforeach
				<td class="d-flex">
					<input type="submit" class="btn btn-info w-100" value="{{ trans('admin-app.button.search') }}">
					<a
						@if ($isRender) href="{{ route(request()->route()->getName(),['subasta' => $cod_sub, 'menu' => 'subastas']) }}"
							@else
								href="{{ route('orders.index', ['menu' => 'subastas']) }}" @endif
						class="btn btn-warning w-100">{{ trans('admin-app.button.restart') }}</a>
				</td>
			</form>
		</tr>
		@foreach ($orders as $order)
			<tr>
				@if ($isRender)
					<td>
						<label>
							<input type="checkbox" name="orders_ids" value="{{ $order->sub_orlic . '_' . $order->ref_asigl0 . '_' . $order->licit_orlic }}">
						</label>
					</td>
				@endif
				<td class="sub_orlic">{{ $order->sub_orlic }}</td>
				<td class="ref_asigl0">{{ $order->ref_asigl0 }}</td>
				<td class="tipop_orlic">{{ $order->tipo_order_orders_type }}</td>
				<td class="descweb_hces1"> {!! $order->descweb_hces1 !!}</td>
				<td class="nom_cli">{{ $order->nom_cli }}</td>

				<td class="fec_orlic">{{ \Tools::euroDate($order->fec_orlic) }}</td>
				<td class="himp_orlic">
					{{ \Tools::moneyFormat($order->himp_orlic, trans(\Config::get('app.theme') . '-app.subastas.euros'), 2) }} </td>
				<td class="tel1_orlic">{{ $order->tel1_orlic }}</td>
				<td class="idorigen_hces1">{{ $order->idorigen_hces1 }}</td>
				<td class="cod2_cli">{{ $order->cod2_cli }}</td>
				<td class="cod_licit">{{ $order->cod_licit }}</td>
				<td style="">
					<a title="{{ trans('admin-app.button.edit') }}"
						href="{{ route('orders.edit', ['idAuction' => $order->sub_orlic, 'ref' => $order->ref_asigl0, 'licit' => $order->licit_orlic]) }}"
						class="btn btn-success btn-sm"><i class="fa fa-pencil-square-o"
							aria-hidden="true"></i>{{ trans('admin-app.button.edit') }}
					</a>

					<a
						href="javascript:deleteOrder('{{ $order->sub_orlic }}', '{{ $order->ref_asigl0 }}', '{{ $order->licit_orlic }}');"
						class="btn btn-danger btn-sm">{{ trans('admin-app.button.delete') }}</a>


					@if (Config::get('app.WebServiceClient') && strtoupper(session('user.usrw')) == 'SUBASTAS@LABELGRUP.COM')
						<br />
						<a class="js-send_webservice_order btn btn-send-webservice btn-sm" data-sub="{{ $order->sub_orlic }}"
							data-codcli="{{ $order->cod_cli }}" data-ref="{{ $order->ref_asigl0 }}"
							data-imp="{{ $order->himp_orlic }}">
							{{ trans('admin-app.button.send_webservice', ['empresa' => \Config::get('app.theme')]) }} </a>
					@endif
				</td>

			</tr>
		@endforeach

	</tbody>
</table>
{{ $orders->links() }}
