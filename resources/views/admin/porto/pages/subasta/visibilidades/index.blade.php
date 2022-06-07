@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
	<section id="loader-page">
		<div class="lds-ripple">
			<div></div>
			<div></div>
		</div>
	</section>

	@include('admin::includes.header_content')

	@csrf

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-12">
			<h1>{{ trans("admin-app.title.visibility") }}</h1>
		</div>
	</div>

	<div class="row well">

		<div class="col-xs-12 text-right mb-1 pt-1 pb-1" style="background-color: #ffe7e7">
			{{-- configuracion de tabla --}}
			<a href="{{ route("visibilidad.create") }}" class="btn btn-primary btn-sm">{{ trans("admin-app.button.new") }}
				{{ trans("admin-app.title.visibility") }}</a>

			@include('admin::includes.config_table_v2', ['id' => 'visibility_table', 'params' => $tableParams, 'formulario' => $formulario])
		</div>


		<div class="col-xs-12 table-responsive">
			<table id="visibility_table" class="table table-striped table-condensed table-responsive" style="width:100%" data-order-name="order">
				<thead>
					<tr>
						@foreach ($tableParams as $field => $display)
							@include('admin::components.tables.sortable_column_header', ['field' => $field])
						@endforeach
						<th>
							<span>{{ trans("admin-app.fields.actions") }}</span>
						</th>
					</tr>
				</thead>
				<tbody>

					@forelse ($visibilitys as $visibility)

					<tr id="{{$visibility->cod_visibilidad}}">

						@foreach ($tableParams as $field => $display)
							<td class="{{$field}}" @if(!$tableParams[$field]) style="display: none" @endif>{{ $visibility->{$field} }}</td>
						@endforeach

						<td>
							<a title="{{ trans("admin-app.button.edit") }}"
								href="{{ route("visibilidad.edit", $visibility->cod_visibilidad) }}"
								class="btn btn-success btn-sm"><i class="fa fa-pencil-square-o"
									aria-hidden="true"></i>{{ trans("admin-app.button.edit") }}
							</a>
							<button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal"
								data-id="{{ $visibility->cod_visibilidad }}" data-name="{{ trans('admin-app.title.delete_resource', ['resource' => trans('admin-app.title.visibility'), 'id' => $visibility->cod_visibilidad]) }}">
								<i class="fa fa-trash"></i>
							</button>

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

	</div>

	<div class="col-xs-12 d-flex justify-content-center">
		{{ $visibilitys->links() }}
	</div>

	@include('admin::includes._delete_modal', ['routeToDelete' => route("visibilidad.destroy", [0]) ])

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

</section>
@stop
