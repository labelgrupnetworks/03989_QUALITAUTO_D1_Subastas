@extends('admin::layouts.logged')
@section('content')


<section role="main" class="content-body">
	@include('admin::includes.header_content')

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-12">
			<h1>{{ trans("admin-app.title.clients_newsletter") }}</h1>
		</div>
	</div>

	<div class="row well">

		<div class="col-xs-12 text-right mb-1 pt-1 pb-1" style="background-color: #ffe7e7">
			<a class="btn btn-success btn-sm"
				href="{{ route('user_newsletter.export') }}">{{ trans('admin-app.button.export') }}</a>

			@include('admin::includes.config_table', ['id' => 'clients_newsletter', 'params' => ((array) $filters)])
		</div>

		<div class="col-xs-12 table-responsive">
			<table id="clients_newsletter" class="table table-striped table-condensed" style="width:100%"
				data-order-name="order">

				<thead>
					<tr>

						@foreach (array_keys((array) $filters) as $param)

						<th class="{{$param}}" style="cursor: pointer;" data-order="{{$param != 'is_client_web' ? $param : 'cod_cliweb'}}">
							{{ trans("admin-app.fields.$param") }}
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
							<input type="hidden" name="order" value="{{ request('order', 'fecalta_cliweb') }}">
							<input type="hidden" name="order_dir" value="{{ request('order_dir', 'desc') }}">

							@foreach ($filters as $param => $form)
							<td class="{{$param}}"> {!! $form ?? '' !!}</td>
							@endforeach

							<td class="d-flex">
								<input type="submit" class="btn btn-info"
									value="{{ trans("admin-app.button.search") }}">
								<a href="{{ route('user_newsletter.index') }}"
									class="btn btn-warning">{{ trans("admin-app.button.restart") }}</a>
							</td>
						</form>
					</tr>

					@forelse ($clients as $client)

					<tr id="fila{{$client->cod_cli}}">
						<td class="cod_cliweb">{{ $client->cod_cliweb }}</td>
						<td class="cod2_cliweb">{{ $client->cod2_cliweb }}</td>
						<td class="email_cliweb">{{ $client->email_cliweb }}</td>
						<td class="nom_cliweb">{{ $client->nom_cliweb }}</td>
						<td class="fecalta_cliweb">
							{{ \Tools::getDateFormat($client->fecalta_cliweb, 'Y-m-d H:i:s', 'd/m/Y') }}</td>
						<td class="is_client_web">
							{{ !empty($client->cod_cliweb) ? trans('admin-app.general.yes') : trans('admin-app.general.not') }}
						</td>

						<td>
							<button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal"
                    			data-id="{{ $client->email_cliweb }}">{{ trans("admin-app.button.delete_subscription") }}</button>
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
			{{ $clients->links() }}
		</div>

	</div>

</section>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p>{{ trans("admin-app.questions.delete_newsletter") }}</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans("admin-app.button.close") }}</button>

				<form id="formDelete" action="{{ route('user_newsletter.destroy', 0) }}"
                    data-action="{{ route('user_newsletter.destroy', 0) }}" method="POST" style="display: inline-block">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger">{{ trans("admin-app.button.delete") }}</button>
                </form>

            </div>

        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function(){

        $('#deleteModal').on('show.bs.modal', function (event) {

            var button = $(event.relatedTarget);
            var id = button.data('id');

            //obtenemos el id del data action del form
            var action = $('#formDelete').attr('data-action').slice(0, -1) + id;

            //Le asignamos el nuevo id
            $('#formDelete').attr('action', action);

            var modal = $(this);
            modal.find('.modal-title').text('Vas a eliminar la subscripcioón de ' + id);
        });

    });
</script>

@stop
