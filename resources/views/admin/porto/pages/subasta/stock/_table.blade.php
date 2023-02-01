<div class="col-xs-12 d-flex mb-1 pt-1 pb-1" style="background-color: #ffe7e7; gap:5px; flex-wrap: wrap">
	<div style="flex:1"> </div>


		<a class="btn btn-success btn-sm"
			href="{{ route("$parent_name.$resource_name.printExcel") }}" target="_blank">Excel
			Stock</a>




	@include('admin::includes.config_table', ['id' => $resource_name, 'params' => $tableParams])

</div>

<div class="col-xs-12">
	<table id="{{ $resource_name }}" class="table table-striped table-condensed table-responsive" style="width:100%"
		data-order-name="order">
		<thead>
			<tr>
				<th class="col-xs-1" style="width: 5%">{{ trans('admin-app.title.img') }}</th>
				@foreach ($tableParams as $param => $display)
					<th class="{{ $param }}" style="cursor: pointer; @if (!$display) display: none; @endif"
						@if (!in_array($param, [ 'descweb_hces1'])) data-order="{{ $param }}" @endif>

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

					@foreach ($tableParams as $param => $display)
						<td class="{{ $param }}" @if (!$display) style="display: none" @endif>
							{!! $formulario->$param ?? '' !!}</td>
					@endforeach

					<td class="d-flex">
						<input type="submit" class="btn btn-info"
							value="{{ trans("admin-app.button.search") }}">
							<a
							@if($render)
								href="{{route("$parent_name.show", [ 'menu' => 'subastas'])}}"
							@else
								href="{{route("$parent_name.$resource_name.index", ['menu' => 'subastas'])}}"
							@endif
							class="btn btn-warning">{{ trans("admin-app.button.restart") }}
						</a>
					</td>
				</form>
			</tr>


			@forelse ($lotes as $lote)

			@php
				if (\Config::get('app.moveLot')) {
					$actualLot = App\Models\V5\FgAsigl0::select("sub_asigl0", "ref_asigl0", "sub_hces1", "ref_hces1" /* "numhces_asigl0", "linhces_asigl0", "num_hces1", "lin_hces1" */)->leftJoinFghces1Asigl0()
					->where('sub_asigl0', $lote->sub_asigl0)->where('ref_asigl0', $lote->ref_asigl0)->first();
				}
			@endphp


				<tr id="fila{{ $lote->ref_asigl0 }}" style="max-height: 60px; overflow: hidden;">

					<td><img src="{{ \Tools::url_img('lote_medium', $lote->numhces_asigl0, $lote->linhces_asigl0) }}" width="100%"></td>

					@foreach ($tableParams as $param => $display)
						<td class="{{ $param }}" @if (!$display) style="display: none" @endif>

							@switch($param)

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
						@if ((\Config::get('app.moveLot') && ($actualLot->sub_asigl0 != $actualLot->sub_hces1 || $actualLot->ref_asigl0 != $actualLot->ref_hces1)))
							<p>MOVIDO</p>
						@else
							<a title="{{ trans('admin-app.button.edit') }}"
								href="{{ route("subastas.lotes.edit", [$lote->sub_asigl0, $lote->ref_asigl0, 'render' => $render,'return'=>'stockList']) }}"
								class="btn btn-success btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
								Editar
							</a>
							@if (\Config::get('app.moveLot'))
								<button id="clone-lot" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#moveLotModal"
									data-id="{{ $lote->ref_asigl0 }}" data-auction="{{ $lote->sub_asigl0 }}"
									data-name="{{ trans('admin-app.title.duplicate_resource', ['resource' => trans('admin-app.title.lot'), 'id' => $lote->ref_asigl0]) }}">
									<i class="fa fa-files-o"></i>
								</button>
							@endif
							{{-- quito todo lo referente a borrar lote, lo marco con BORRAR_LOTE
							@if (($pujas->where('ref_asigl1', $lote->ref_asigl0)->max('imp_asigl1') ?? 0) == 0 && ($ordenes->where('ref_orlic', $lote->ref_asigl0)->max('himp_orlic') ?? 0) == 0)
								<button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal"
									data-id="{{ $lote->ref_asigl0 }}"
									data-name="{{ trans('admin-app.title.delete_resource', ['resource' => trans('admin-app.title.lot'), 'id' => $lote->ref_asigl0]) }}">
									<i class="fa fa-trash"></i>
								</button>
							@endif
							--}}
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
		@include('admin::includes._move_lot_modal', ['routeToClone' => route("subastas.lotes.cloneLot", [$lote->ref_asigl0])])
	@endif
	{{-- quito todo lo referente a borrar lote, lo marco con BORRAR_LOTE
	@include('admin::includes._delete_modal', ['routeToDelete' => route("$parent_name.$resource_name.destroy", [$cod_sub, 0]),])

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


	</script>
--}}
