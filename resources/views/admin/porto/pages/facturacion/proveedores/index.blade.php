@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-12">
			<h1>{{ trans_choice("admin-app.title.provider", 2) }}</h1>
		</div>
	</div>

	<div class="row well">
			@include('admin::pages.facturacion.proveedores._table', compact('providers'))
	</div>

	@include('admin::includes._delete_modal', ['routeToDelete' => route('providers.destroy', 0)])

</section>

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

@stop
