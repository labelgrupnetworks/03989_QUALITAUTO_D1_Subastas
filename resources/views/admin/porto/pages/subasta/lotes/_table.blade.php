<div class="col-xs-12 text-right mb-1 pt-1 pb-1" style="background-color: #ffe7e7">
	@if (\Config::get("app.exportExcelExhibition"))
	<a class="btn btn-success btn-sm" href="{{ route("$parent_name.$resource_name.printExcel", ['codSub' => $cod_sub ]) }}" target="_blank">Excel Obras</a>
	@endif
	@if(\Config::get("app.exportPdfExhibition"))
		<a class="btn btn-success btn-sm" href="{{ route("$parent_name.$resource_name.printPdf", ['codSub' => $cod_sub ]) }}" target="_blank">Pdf Obras</a>
	@endif
	<a class="btn btn-success btn-sm" href="{{ route("$parent_name.$resource_name.order_edit", ['cod_sub' => $cod_sub]) }}">Ordenar</a>

	<a class="btn btn-success btn-sm" href="/themes_admin/porto/assets/files/plantillaejemplo.xlsx" download="plantilla.xlsx">Descargar plantilla Excel</a>

	<a href="/admin/lote/file/{{$cod_sub}}" class="btn btn-success btn-sm">Subir Excel</a>

	@if(\Config::get("app.uploadLotFile"))
		@foreach(explode(",",\Config::get("app.uploadLotFile") ) as $typeUploadFile)

			<a href="/admin/lote/file/{{$cod_sub}}?type={{trim($typeUploadFile)}}" class="btn btn-success btn-sm">Subir {{$typeUploadFile}}</a>
		@endforeach
	@endif


	<a href="{{ route("$parent_name.$resource_name.create", ['cod_sub' => $cod_sub ,'menu' => 'subastas']) }}"
		class="btn btn-primary btn-sm">{{ trans("admin-app.button.new") }}
		{{ trans("admin-app.title.lot") }}</a>

	@include('admin::includes.config_table', ['id' => $resource_name, 'params' => $tableParams])

</div>

<div class="col-xs-12">
	<table id="{{$resource_name}}" class="table table-striped table-condensed table-responsive" style="width:100%" data-order-name="order">
		<thead>
			<tr>

				<th class="col-xs-1" style="width: 5%">{{ trans("admin-app.title.img") }}</th>
				@foreach ($tableParams as $param => $display)

					<th class="{{$param}}"  style="cursor: pointer; @if(!$display) display: none; @endif" @if(!in_array($param, ['max_puja', 'max_orden', 'descweb_hces1'])) data-order="{{$param}}" @endif>

						{{ trans_choice("admin-app.fields.$param", $tipo_sub ?? '') }}

						@if(request()->order == $param)
							<span style="margin-left: 5px; float: right;">
								@if(request()->order_dir == 'asc')
									<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
								@else
										<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
								@endif
							</span>
						@endif

					</th>

				@endforeach
				<th>
					<span>{{ trans("admin-app.fields.actions") }}</span>
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
						<td class="{{$param}}" @if(!$display) style="display: none" @endif> {!! $formulario->$param ?? '' !!}</td>
					@endforeach

					<td class="d-flex">
						<input type="submit" class="btn btn-info"
							value="{{ trans("admin-app.button.search") }}">
							<a
							@if($render)
								href="{{route("$parent_name.show", ['cod_sub' => $cod_sub, 'menu' => 'subastas'])}}"
							@else
								href="{{route("$parent_name.$resource_name.index", ['cod_sub' => $cod_sub,'menu' => 'subastas'])}}"
							@endif
							class="btn btn-warning">{{ trans("admin-app.button.restart") }}
						</a>
					</td>
				</form>
			</tr>


			@forelse ($lotes as $lote)

			<tr id="fila{{$lote->ref_asigl0}}" style="max-height: 60px; overflow: hidden;">
				<td><img src="{{ \Tools::url_img('lote_medium', $lote->numhces_asigl0, $lote->linhces_asigl0) }}" width="100%"></td>

				@foreach ($tableParams as $param => $display)

						<td class="{{$param}}" @if(!$display) style="display: none" @endif>

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
					<a title="{{ trans("admin-app.button.edit") }}"
						href="{{ route("$parent_name.$resource_name.edit", [$lote->sub_asigl0, $lote->ref_asigl0, 'render' => $render] ) }}"
						class="btn btn-success btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
						Editar
					</a>
					@if(($pujas->where('ref_asigl1', $lote->ref_asigl0)->max('imp_asigl1') ?? 0) == 0 && ($ordenes->where('ref_orlic', $lote->ref_asigl0)->max('himp_orlic') ?? 0) == 0)
					<button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal"
								data-id="{{ $lote->ref_asigl0 }}" data-name="{{ trans('admin-app.title.delete_resource', ['resource' => trans('admin-app.title.lot'), 'id' => $lote->ref_asigl0]) }}">
								<i class="fa fa-trash"></i>
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
	{{ $lotes->appends(array_except(Request::query(), ['lotesPage', 'tab']) + ['tab' => 'lotes'])->links() }}
</div>

@include('admin::includes._delete_modal', ['routeToDelete' => route("$parent_name.$resource_name.destroy", [$cod_sub, 0]) ])

<script>
	$('#deleteModal').on('show.bs.modal', function (event) {

		var button = $(event.relatedTarget);
		var id = button.data('id');
		var name = button.data('name');

		//obtenemos el id del data action del form
		var action = $('#formDelete').attr('data-action').slice(0, -1) + id;
		$('#formDelete').attr('action', action);

		var modal = $(this);
		modal.find('.modal-title').text(name);
	});
</script>
