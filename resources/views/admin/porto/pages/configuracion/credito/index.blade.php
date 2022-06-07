@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">

	@include('admin::includes.header_content')
	@csrf

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-9">
			<h1>{{ trans("admin-app.title.credits") }}</h1>
		</div>
		<div class="col-xs-3">
			<a href="{{ route('credito.create') }}" class="btn btn-primary right">{{ trans("admin-app.button.new") }}
				{{ trans("admin-app.title.credit") }}</a>
				<a id="export-credit" style="margin: 0px 10px" class="btn btn-primary right">{{ trans("admin-app.button.export") }}</a>
		</div>
	</div>

	<div class="row well">

		<div class="col-xs-12">
			<table id="" class="table table-striped table-condensed table-responsive" style="width:100%">
				<thead>

					<tr>
						<th>{{ trans("admin-app.fields.cli_creditosub") }}</th>
						<th>{{ trans("admin-app.fields.sub_creditosub") }}</th>
						<th>{{ trans("admin-app.fields.actual_creditosub") }}</th>
						<th>{{ trans("admin-app.fields.nuevo_creditosub") }}</th>
						<th>{{ trans("admin-app.fields.ries_cli") }}</th>
						<th>{{ trans("admin-app.fields.riesmax_cli") }}</th>
						<th>{{ trans("admin-app.fields.fecha_creditosub") }}</th>

						<th>{{ trans("admin-app.fields.actions") }}</th>
					</tr>
				</thead>

				<tbody>

					<tr id="filters">
						<form id="form-search" class="form-group" action="">
							<td>{!! $formulario->cli_creditosub !!}</td>
							<td>{!! $formulario->sub_creditosub !!}</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td>{!! $formulario->fecha_creditosub !!}</td>
							<td><input type="submit" class="btn btn-info w-100"
									value="{{ trans("admin-app.button.search") }}"><a href="{{route('credito.index')}}"
									class="btn btn-warning w-100">{{ trans("admin-app.button.restart") }}</a></td>
						</form>
					</tr>

					@forelse ($fgCreditoSubs as $credito)

					<tr id="fila{{$credito->id_creditosub}}">
						<td>{{$credito->cli_creditosub}} - {{$credito->rsoc_cli}}</td>
						<td>{{$credito->sub_creditosub}}</td>
						<td>{{$credito->actual_creditosub}}</td>
						<td>{{$credito->nuevo_creditosub}}</td>
						<td>{{$credito->ries_cli}}</td>
						<td><button data-id="{{$credito->cli_creditosub}}" data-name="{{$credito->rsoc_cli}}"
								data-riesmax="{{$credito->riesmax_cli}}" data-toggle="modal" data-target="#changeModal">
								<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
							</button>
							<span>{{$credito->riesmax_cli}}</span>
						</td>
						<td>{{ Tools::getDateFormat($credito->fecha_creditosub, 'Y-m-d H:i:s', 'd/m/Y H:i:s') }}</td>

						<td>
							{{--<a href="{{ route('credito.edit', $credito->id_creditosub) }}"
							class="btn btn-primary btn-sm">{{ trans("admin-app.button.edit") }}</a>--}}
							<button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal"
								data-id="{{ $credito->id_creditosub }}" data-name="{{$credito->cli_creditosub}} - {{$credito->rsoc_cli}}">
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
		<div class="col-xs-12 d-flex justify-content-center">
			{{ $fgCreditoSubs->links() }}
		</div>
	</div>

	<div class="modal fade" id="changeModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">

				<div class="modal-header">
					<h5 class="modal-title" id=" modalLabel"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id="formUpdate" action="{{ route('credito.update', 0) }}"
					data-action="{{ route('credito.update', 0) }}" method="POST">
					<div class="modal-body">
						<p>¿Cual es el credito máximo que quiere establecer?</p>
						<input class="form-control" type="number" name="riesmax_cli">
					</div>

					<div class="modal-footer">

						@method('PUT')
						@csrf
						<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans("admin-app.button.close") }}</button>
						<button type="submit" class="btn btn-success">{{ trans("admin-app.button.update") }}</button>
				</form>
			</div>

		</div>
	</div>
	</div>

	<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">

				<div class="modal-header">
					<h5 class="modal-title" id=" modalLabel"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					<p>{{ trans("admin-app.title.sure_delete") }}</p>
				</div>

				<div class="modal-footer">

					<form id="formDelete" action="{{ route('credito.destroy', 0) }}"
						data-action="{{ route('credito.destroy', 0) }}" method="POST">
						@method('DELETE')
						@csrf
						<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans("admin-app.button.close") }}</button>
						<button type="submit" class="btn btn-danger">{{ trans("admin-app.button.delete") }}</button>
					</form>
				</div>

			</div>
		</div>
	</div>


</section>

<script>
	window.onload = function(){

		$('#changeModal').on('show.bs.modal', function (event) {

			var button = $(event.relatedTarget); // Button that triggered the modal
			var id = button.data('id'); // Extract info from data-* attributes
			var riesmax = button.data('riesmax');
			var name = button.data('name');

			//obtenemos el id del data action del form
			var action = $('#formUpdate').attr('data-action').slice(0, -1) + id;

			//Le asignamos el nuevo id
			$('#formUpdate').attr('action', action);

			var modal = $(this);
			modal.find('.modal-title').text('{{ trans("admin-app.title.modify_credit") }}' + id + ' - ' + name);
			modal.find('.modal-body input').val(riesmax);
		});

		$('#deleteModal').on('show.bs.modal', function (event) {

			var button = $(event.relatedTarget);
			var id = button.data('id');
			var name = button.data('name');

			//obtenemos el id del data action del form
			var action = $('#formDelete').attr('data-action').slice(0, -1) + id;
			$('#formDelete').attr('action', action);

			var modal = $(this);
			modal.find('.modal-title').text('{{ trans("admin-app.title.delete_credit") }}' + name);
		});

		$('#export-credit').on('click', function(event){

			event.preventDefault();
			location.href = "{{ route('credito.export') }}?" + $('#form-search').serialize();
			//$('<form></form>').attr('action', "{{ route('credito.export') }}?" + $('#form-search').serialize()).appendTo('body').submit().remove();

		})


}

</script>

@stop
