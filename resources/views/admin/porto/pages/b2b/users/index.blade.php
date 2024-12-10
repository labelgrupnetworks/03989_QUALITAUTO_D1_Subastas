@extends('admin::layouts.logged')
@section('content')

    <section class="content-body" role="main">
        @include('admin::includes.header_content')
        @csrf

        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-12">
                <h1>{{ trans_choice('admin-app.title.client', 2) }}</h1>
            </div>
        </div>

        <div class="row well">

            <div class="col-xs-12 text-right mb-1 pt-1 pb-1" style="background-color: #ffe7e7">

                <a class="btn btn-sm btn-primary" href="{{ route('admin.b2b.users.create') }}">
                    {{ trans('admin-app.button.new') }} {{ trans('admin-app.fields.cli_creditosub') }}
                </a>

                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#importExcelModal" type="button">
                    Importar Excel
                </button>

				{{-- Notificar --}}
				<button class="btn btn-sm btn-primary" onclick="notifyClients()">
					Notificar
				</button>

                <button class="btn btn-sm btn-danger" onclick="removeAllClients()">
                    Borrar Clientes
                </button>


            </div>

            <div class="col-xs-12 table-responsive">
                <table class="table table-striped table-condensed table-responsive" id="clientes" data-order-name="order"
                    style="width:100%">
                    <thead>
                        <tr>
                            @foreach ($tableParams as $param => $display)
                                <th class="{{ $param }}" data-order="{{ $param }}"
                                    style="cursor: pointer; @if (!$display) display: none; @endif">

                                    {{ trans("admin-app.fields.$param") }}

                                    @if (request()->order == $param)
                                        <span style="margin-left: 5px; float: right;">
                                            @if (request()->order_dir == 'asc')
                                                <i class="fa fa-arrow-up" aria-hidden="true" style="color:green"></i>
                                            @else
                                                <i class="fa fa-arrow-down" aria-hidden="true" style="color:red"></i>
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
                        {{-- <tr id="filters">
                            <form class="form-group" action="">
                                <input name="order" type="hidden" value="{{ request('order', 'cod_cli') }}">
                                <input name="order_dir" type="hidden" value="{{ request('order_dir', 'desc') }}">

								<td></td>
                                @foreach ($tableParams as $param => $display)
                                    <td class="{{ $param }}"
                                        @if (!$display) style="display: none" @endif>
                                        {!! $formulario->$param ?? '' !!}</td>
                                @endforeach

                                <td class="d-flex">
                                    <input class="btn btn-info w-100" type="submit"
                                        value="{{ trans('admin-app.button.search') }}"><a class="btn btn-warning w-100"
                                        href="{{ route('admin.b2b.users') }}">{{ trans('admin-app.button.restart') }}</a>
                                </td>
                            </form>
                        </tr> --}}

                        @forelse ($users as $user)
                            <tr id="{{ $user->invited->cod2_cliweb }}">
                                <td>{{ $user->invited_nom_subinvites }}</td>
                                <td>{{ mb_strtolower($user->invited->email_cliweb) }}</td>
                                <td>{{ $user->invited_cif_subinvites }}</td>
                                <td>{{ $user->invited_tel_subinvites }}</td>

                                <td>
                                    <a class="btn btn-success btn-sm" href=""
                                        title="{{ trans('admin-app.button.edit') }}">
                                        <i class="fa fa-pencil-square-o"
                                            aria-hidden="true"></i>{{ trans('admin-app.button.edit') }}
                                    </a>

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
                {{ $users->appends(array_except(Request::query(), ['page']))->links() }}
            </div>
        </div>

    </section>

    <div class="modal fade" id="importExcelModal" role="dialog" aria-labelledby="importExcelLabel" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal" type="button" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="importExcelLabel">Importar Archivo Excel</h4>
                </div>
                <form id="importExcelForm" action="{{ route('admin.b2b.users.import') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="excelFile">Seleccionar Archivo Excel</label>
                            <input class="form-control" id="excelFile" name="file" type="file" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal" type="button">Cerrar</button>
                        <button class="btn btn-primary" type="submit">Importar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        function removeAllClients() {
            bootbox.confirm("¿Estas seguro de que quieres eliminar todos los clientes?", function(result) {
                if (!result) return;

                $.ajax({
                    url: "{{ route('admin.b2b.users.delete-all') }}",
                    type: 'DELETE',
                    data: {
                        _token: $('input[name="_token"]').val()
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            bootbox.alert("Ha ocurrido un error al eliminar los clientes");
                        }
                    }
                });
            });
        }

		function notifyClients() {
			bootbox.confirm("¿Estas seguro de que quieres notificar a todos los clientes?", function(result) {
				if (!result) return;

				$.ajax({
					url: "{{ route('admin.b2b.users.notify') }}",
					type: 'POST',
					data: {
						_token: $('input[name="_token"]').val()
					},
					success: function(response) {
						if (response.success) {
							bootbox.alert("Se ha notificado a los clientes correctamente");
						} else {
							bootbox.alert("Ha ocurrido un error al notificar a los clientes");
						}
					}
				});
			});
		}
    </script>
@stop
