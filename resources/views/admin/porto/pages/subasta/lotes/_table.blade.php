<div class="col-xs-12 d-flex mb-1 pt-1 pb-1" style="background-color: #ffe7e7; gap:5px; flex-wrap: wrap">

	<div style="flex:1">
		<div class="btn-group" id="js-dropdownItems">
			<button class="btn btn-default btn-sm" type="button">{{ trans("admin-app.button.selecteds") }}</button>
			<button
				data-objective="lot_ids"
				class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button"
				aria-haspopup="true" aria-expanded="false">
				<span class="caret"></span>
			</button>

			<ul aria-labelledby="js-dropdownItems" class="dropdown-menu">

				<li>
					<button class="btn" data-id="mass_delete_button"
						data-objective="lot_ids"
						data-allselected="js-selectAll"
						data-title="{{ trans("admin-app.questions.erase_mass_lot") }}"
						data-response="{{ trans("admin-app.success.erase_mass_lot") }}"
						data-url="{{ route('subastas.lotes.destroy_selections') }}"
						data-urlwithfilters="{{ route('subastas.lotes.destroy_with_filters') }}"
						onclick="removeLotsSelecteds(this.dataset)">
						{{ trans("admin-app.button.erase") }}
					</button>
				</li>

				<li>
					<button class="btn" data-toggle="modal" data-target="#editMultpleLotsModal">
						{{ trans("admin-app.button.modify") }}
					</button>
				</li>

				@if (Config::get('app.stockIni') > 0)
					<li><a class="js-actionSelectedLots btn"
							data-title="¿Estás seguro de poner el stock a 0 en todos las Obras seleccionadas?"
							data-respuesta="Se ha puesto el stock a  0 en las obras seleccionados"
							href="{{ route('subastas.lotes.stockRemove_selection', ['cod_sub' => $cod_sub]) }}">{{ trans("admin-app.button.put_stock_to_zero") }}</a></li>
					<li><a class="js-actionSelectedLots btn"
							data-title="¿Estás seguro de poner en Fondo de Galeria todas las obras seleccionadas?"
							data-respuesta="Se ha puesto en Fondo de Galeria las obras seleccionados"
							href="{{ route('subastas.lotes.setToSellSelection', ['cod_sub' => $cod_sub]) }}">{{ trans("admin-app.button.gallery_background") }}</a>
					</li>
				@endif

				@if (Config::get('app.lot_api_integrations', false))
					<li class="dropdown-submenu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
							aria-expanded="false">{{ trans("admin-app.button.export") }}</a>
						<ul class="dropdown-menu">
							<li>
								<a class="js-actionSelectedLots btn"
									href="{{ route('subastas.lotes.multiple_export', ['cod_sub' => $cod_sub, 'service' => 'bbd']) }}"
									data-title="¿Estás seguro de exportar todas las obras seleccionadas?"
									data-respuesta="Se han exportado las obras seleccionados">
									{{ trans("admin-app.button.auctions_diary") }}
								</a>
							</li>
						</ul>
					</li>
				@endif

			</ul>
		</div>
	</div>

	@if (\Config::get('app.exportExcelExhibition'))
		<a class="btn btn-success btn-sm" href="{{ route("$parent_name.$resource_name.printExcel", ['codSub' => $cod_sub]) }}"
			target="_blank">Excel
			Obras</a>
	@endif
	@if (\Config::get('app.exportPdfExhibition'))
		<a class="btn btn-success btn-sm" href="{{ route("$parent_name.$resource_name.printPdf", ['codSub' => $cod_sub]) }}"
			target="_blank">Pdf Obras</a>
	@endif
	<a class="btn btn-success btn-sm"
		href="{{ route("$parent_name.$resource_name.order_edit", ['cod_sub' => $cod_sub]) }}">Ordenar</a>

	<a class="btn btn-success btn-sm" href="/themes_admin/porto/assets/files/plantillaejemplo.xlsx"
		download="plantilla.xlsx">Descargar plantilla Excel</a>

	<a href="/admin/lote/file/{{ $cod_sub }}" class="btn btn-success btn-sm">Subir Excel</a>

	@if (\Config::get('app.uploadLotFile'))
		@foreach (explode(',', \Config::get('app.uploadLotFile')) as $typeUploadFile)
			<a href="/admin/lote/file/{{ $cod_sub }}?type={{ trim($typeUploadFile) }}" class="btn btn-success btn-sm">Subir
				{{ $typeUploadFile }}</a>
		@endforeach
	@endif


	<a href="{{ route("$parent_name.$resource_name.create", ['cod_sub' => $cod_sub, 'menu' => 'subastas']) }}"
		class="btn btn-primary btn-sm">{{ trans('admin-app.button.new') }}
		{{ trans('admin-app.title.lot') }}</a>

	@include('admin::includes.config_table', ['id' => $resource_name, 'params' => $tableParams])

</div>

<div class="col-xs-12">
	<table id="{{ $resource_name }}" class="table table-striped table-condensed table-responsive" style="width:100%"
		data-order-name="order">
		<thead>
			<tr>
				<th>
					<label>
						<input name="js-selectAll" data-objective="lot_ids" type="checkbox" value="true">
						<input id="urlAllSelected" name="url-allSelected"  type="hidden" value="{{ route('subastas.lotes.update_with_filters') }}">
						<input name="auc_id" type="hidden" value="{{ $cod_sub }}">
					</label>
				</th>

				<th class="col-xs-1" style="width: 5%">{{ trans('admin-app.title.img') }}</th>
				@foreach ($tableParams as $param => $display)
					<th class="{{ $param }}" style="cursor: pointer; @if (!$display) display: none; @endif"
						@if (!in_array($param, ['max_puja', 'max_orden', 'descweb_hces1'])) data-order="{{ $param }}" @endif>

						{{ trans_choice("admin-app.fields.$param", $tipo_sub ?? '') }}

						@if (request()->order == $param)
							<span style="margin-left: 5px; float: right;">
								@if (request()->order_dir == 'asc')
									<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
								@else
									<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
								@endif
							</span>
						@endif

					</th>
				@endforeach
				<th>
					<span>{{ trans('admin-app.fields.actions') }}</span>
				</th>

			</tr>
		</thead>

		<tbody>
			<tr id="filters">
				<form class="form-group" action="">
					<input type="hidden" name="order" value="{{ request('order', 'ref_asigl0') }}">
					<input type="hidden" name="order_dir" value="{{ request('order_dir', 'asc') }}">

					<td class=""></td>
					<td class=""></td>

					@foreach ($tableParams as $param => $display)
						<td class="{{ $param }}" @if (!$display) style="display: none" @endif>
							{!! $formulario->$param ?? '' !!}</td>
					@endforeach

					<td class="d-flex">
						<input type="submit" class="btn btn-info" value="{{ trans('admin-app.button.search') }}">
						<a
							@if ($render) href="{{ route("$parent_name.show", ['subasta' => $cod_sub, 'menu' => 'subastas']) }}"
							@else
								href="{{ route("$parent_name.$resource_name.index", ['subasta' => $cod_sub, 'menu' => 'subastas']) }}" @endif
							class="btn btn-warning">{{ trans('admin-app.button.restart') }}
						</a>
					</td>
				</form>
			</tr>


			@forelse ($lotes as $lote)

				@php
					if (\Config::get('app.moveLot')) {
					    $actualLot = App\Models\V5\FgAsigl0::select('sub_asigl0', 'ref_asigl0', 'sub_hces1', 'ref_hces1' /* "numhces_asigl0", "linhces_asigl0", "num_hces1", "lin_hces1" */)
					        ->leftJoinFghces1Asigl0()
					        ->where('sub_asigl0', $lote->sub_asigl0)
					        ->where('ref_asigl0', $lote->ref_asigl0)
					        ->first();
					}
				@endphp


				<tr id="fila{{ $lote->ref_asigl0 }}" style="max-height: 60px; overflow: hidden;">
					@php
						$withoutBids = ($pujas->where('ref_asigl1', $lote->ref_asigl0)->max('imp_asigl1') ?? 0) == 0;
						$withoutOrders = ($ordenes->where('ref_orlic', $lote->ref_asigl0)->max('himp_orlic') ?? 0) == 0;
						$withExternalApi = Config::get('app.lot_api_integrations', false);
					@endphp

					<td>
						@if (!$withExternalApi)
							<label>
								<input type="checkbox" name="lot_ids" value="{{ $lote->ref_asigl0 }}">
								<input type="hidden" name="has_orders_or_bids" data-lot_ref="{{ $lote->ref_asigl0 }}" value="{{ (!$withoutBids || !$withoutOrders) ? '1' : '0' }}">
							</label>
						@endif
					</td>

					<td><img src="{{ \Tools::url_img('lote_medium', $lote->numhces_asigl0, $lote->linhces_asigl0) }}" width="100%">
					</td>

					@foreach ($tableParams as $param => $display)
						<td class="{{ $param }}" @if (!$display) style="display: none" @endif>

							@switch($param)
								@case('max_puja')
									{!! Tools::moneyFormat($pujas->where('ref_asigl1', $lote->ref_asigl0)->max('imp_asigl1') ?? 0) !!}
								@break

								@case('max_orden')
									{!! Tools::moneyFormat($ordenes->where('ref_orlic', $lote->ref_asigl0)->max('himp_orlic') ?? 0) !!}
								@break

								@case('prop_hces1')
									{!! $propietarios[$lote->$param] ?? '' !!}
								@break

								@case('fini_asigl0')
									{!! \Tools::getDateFormat($lote->fini_asigl0, 'Y-m-d H:i:s', 'd/m/Y') !!}
								@break

								@case('ffin_asigl0')
									{!! \Tools::getDateFormat($lote->ffin_asigl0, 'Y-m-d H:i:s', 'd/m/Y') !!}
								@break

								@default
									{!! $lote->$param ?? '' !!}
							@endswitch
						</td>
					@endforeach

					<td>
						@if (
							\Config::get('app.moveLot') &&
								($actualLot->sub_asigl0 != $actualLot->sub_hces1 || $actualLot->ref_asigl0 != $actualLot->ref_hces1))
							<p>{{ mb_strtoupper(trans("admin-app.information.moved")) }}</p>
						@else
							<a title="{{ trans('admin-app.button.edit') }}"
								href="{{ route("$parent_name.$resource_name.edit", [$lote->sub_asigl0, $lote->ref_asigl0, 'render' => $render]) }}"
								class="btn btn-success btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
								{{ trans("admin-app.button.edit") }}
							</a>
							@if (\Config::get('app.moveLot'))
								<button id="clone-lot" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#moveLotModal"
									data-id="{{ $lote->ref_asigl0 }}" data-auction="{{ $lote->sub_asigl0 }}"
									data-name="{{ trans('admin-app.title.duplicate_resource', ['resource' => trans('admin-app.title.lot'), 'id' => $lote->ref_asigl0]) }}">
									<i class="fa fa-files-o"></i>
								</button>
							@endif
							@if (
								($pujas->where('ref_asigl1', $lote->ref_asigl0)->max('imp_asigl1') ?? 0) == 0 &&
									($ordenes->where('ref_orlic', $lote->ref_asigl0)->max('himp_orlic') ?? 0) == 0)
								<button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal"
									data-id="{{ $lote->ref_asigl0 }}"
									data-name="{{ trans('admin-app.title.delete_resource', ['resource' => trans('admin-app.title.lot'), 'id' => $lote->ref_asigl0]) }}">
									<i class="fa fa-trash"></i>
								</button>
							@endif
							@if ($lote->cerrado_asigl0 == 'S')
								@if (Config::get('app.WebServiceClient') && strtoupper(session('user.usrw')) == 'SUBASTAS@LABELGRUP.COM')
									<br />
									<a class="js-send_webservice_close_lot btn btn-send-webservice btn-sm" data-sub="{{ $lote->sub_asigl0 }}"
										data-ref="{{ $lote->ref_asigl0 }}">
										{{ trans('admin-app.button.send_close_lot_webservice', ['empresa' => \Config::get('app.theme')]) }} </a>
								@endif
							@endif
							@if (Config::get('app.lot_api_integrations', false))
								<div class="btn-group">
									<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown"
										aria-haspopup="true" aria-expanded="false">
										{{ trans("admin-app.button.export") }} <span class="caret"></span>
									</button>
									<ul class="dropdown-menu dropdown-menu-right">
										<li>
											<a class="export-lot" type="button" href="#"
												data-route="{{ route('subastas.lotes.export', ['cod_sub' => $lote->sub_asigl0, 'ref_asigl0' => $lote->ref_asigl0, 'service' => 'bbd']) }}">
												{{ trans("admin-app.button.auctions_diary") }}
											</a>
										</li>
									</ul>
								</div>
							@endif
						@endif
					</td>
				</tr>


				@empty

					<tr>
						<td colspan="6">
							<h3 class="text-center">{{ trans('admin-app.title.without_results') }}</h3>
						</td>
					</tr>

				@endforelse
			</tbody>
		</table>

	</div>
	<div class="col-xs-12 d-flex justify-content-center">
		{{ $lotes->appends(array_except(Request::query(), ['lotesPage', 'tab']) + ['tab' => 'lotes'])->links() }}
	</div>
	@if (\Config::get('app.moveLot') && !empty($lote))
		@include('admin::includes._move_lot_modal', [
			'routeToClone' => route('subastas.lotes.cloneLot', [$lote->ref_asigl0]),
		])
	@endif
	@include('admin::includes._delete_modal', [
		'routeToDelete' => route("$parent_name.$resource_name.destroy", [$cod_sub, 0]),
	])

	<script>
		$('#deleteModal').on('show.bs.modal', function(event) {

			var button = $(event.relatedTarget);
			var id = button.data('id');
			var name = button.data('name');

			//obtenemos el id del data action del form
			var action = $('#formDelete').attr('data-action').slice(0, -1) + id;
			$('#formDelete').attr('action', action);

			var modal = $(this);
			modal.find('.modal-title').text(name);
		});

		$('#moveLotModal').on('show.bs.modal', function(event) {

			var button = $(event.relatedTarget);
			var id = button.data('id');
			var name = button.data('name');
			var auction = button.data('auction');

			//obtenemos el id del data action del form
			var action = $('#formDuplicateLot').attr('data-action').slice(0, -1) + id;
			$('#formDuplicateLot').attr('action', action);

			//en el formulario #formDuplicateLot el valor auctionSource se sustituye por el id de la subasta y el lotToDuplicate se sustituye por el id del lote
			$('#formDuplicateLot').find('#auctionSource').val(auction);
			$('#formDuplicateLot').find('#lotToDuplicate').val(id);

			var modal = $(this);
			modal.find('.modal-title').text(name);
		});

		$('.export-lot').on('click', function(event) {
			event.preventDefault();
			const route = $(this).data('route');

			const dialegOptions = {
				title: 'Exportando lote',
				message: '<p><i class="fa fa-spin fa-spinner"></i> Exportando...</p>'
			}

			let dialog = bootbox.dialog(dialegOptions);

			dialog.init(function() {
				$.ajax({
					url: route,
					type: 'POST',
					dataType: 'json',
					success: function(response) {
						let message = `<p>${response.message}</p>`;
						if (response.data?.errors) {
							let errors = Object.values(response.data.errors);
							message += '<ul>';
							errors.forEach(error => {
								message += `<li>${error}</li>`;
							});
							message += '</ul>';
						}
						dialog.find('.bootbox-body').html(message);
					}
				});
			});

		});
	</script>

	@include('admin::pages.subasta.lotes._edit_selecteds')
