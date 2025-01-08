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

                <div class="btn-group left" id="js-dropdownItems">
                    <button class="btn btn-default btn-sm" type="button">{{ trans('admin-app.button.selecteds') }}</button>
                    <button class="btn btn-default btn-sm dropdown-toggle" data-objective="cli_ids" data-toggle="dropdown"
                        type="button" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                    </button>

                    <ul class="dropdown-menu" aria-labelledby="js-dropdownItems">
                        <li>
                            <button class="btn" data-objective="user_invited_ids" data-allselected="js-selectAll"
                                data-title="¿Estas seguro de que quieres notificar a los clientes selecciondos?"
                                data-response="Se ha notificado a los clientes correctamente"
                                data-url="{{ route('admin.b2b.users.notify-selection') }}"
                                onclick="notifyClientSelecteds(this.dataset)">
                                Notificar
                            </button>
                        </li>

                        <li>
                            <button class="btn" data-objective="user_invited_ids" data-allselected="js-selectAll"
                                data-title="{{ trans('admin-app.questions.erase_mass_cli') }}"
                                data-response="{{ trans('admin-app.success.erase_mass_cli') }}"
                                data-url="{{ route('admin.b2b.users.destroy-selection') }}"
                                onclick="removeClientSelecteds(this.dataset)">
                                {{ trans('admin-app.button.destroy') }}
                            </button>
                        </li>



                    </ul>
                </div>

                <a class="btn btn-sm btn-primary" href="{{ route('admin.b2b.users.create') }}">
                    {{ trans('admin-app.button.new') }} {{ trans('admin-app.fields.cli_creditosub') }}
                </a>

                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#importExcelModal" type="button">
                    Importar Excel
                </button>

                {{-- Notificar --}}
                <button class="btn btn-sm btn-primary" onclick="notifyAllClients()">
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
                            <th>
                                <label>
                                    <input id="selectAllClients" name="js-selectAll" data-objective="user_invited_ids"
                                        type="checkbox" value="true">
                                </label>
                            </th>
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

                        @forelse ($users as $user)
                            <tr id="{{ $user->invited->cod_cliweb }}">
                                <td>
                                    <label>
                                        <input name="user_invited_ids" type="checkbox"
                                            value="{{ $user->invited->cod_cliweb }}">
                                    </label>
                                </td>
                                <td>{{ $user->invited_nom_subinvites }}</td>
                                <td>{{ mb_strtolower($user->invited->email_cliweb) }}</td>
                                <td>{{ $user->invited_cif_subinvites }}</td>
                                <td>{{ $user->invited_tel_subinvites }}</td>
                                <td>
                                    @if ($user->notification_sent_subinvites)
                                        <i class="fa fa-2x fa-check-circle text-success" aria-hidden="true"></i>
                                    @endif
                                </td>

                                <td>
                                    <a class="btn btn-success btn-sm" href="{{ route('admin.b2b.users.edit', $user->invited->cod_cliweb) }}"
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

				<div class="modal-body">
					<div class="form-group">
						<a href="/themes/b2b/assets/files/plantilla_clientes.xlsx" class="btn btn-sm btn-success" download>
							<i class="fa fa-download"></i>
							Descargar Plantilla Excel
						</a>
					</div>
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
        $(document).ready(function() {

            $('[name="js-selectAll"').on('click', function() {
                const isChecked = this.checked;
                const objectiveInputs = this.dataset.objective;
                isChecked ? selectAllTable(objectiveInputs) : unselectAllTable(objectiveInputs);
            });

            $('input[name="user_invited_ids"]').on('change', function() {

                const selectAllElement = document.getElementById('selectAllClients');
                const isChecked = selectAllElement.checked;

                if (isChecked) {
                    selectAllElement.checked = false;
                }
            });
        });

        function selectAllTable(inputName) {
            const inputs = Array.from(document.getElementsByName(inputName));
            inputs.forEach((element) => element.checked = true);
        }

        function unselectAllTable(inputName) {
            const inputs = Array.from(document.getElementsByName(inputName));
            inputs.forEach((element) => element.checked = false);
        }

        function removeAllClients() {
            bootbox.confirm("¿Estas seguro de que quieres eliminar todos los clientes?", function(result) {
                if (!result) return;

                $.ajax({
                    url: "{{ route('admin.b2b.users.destroy-all') }}",
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

        function notifyAllClients(force = false) {
            bootbox.confirm("¿Estas seguro de que quieres notificar a todos los clientes?", function(result) {
                if (!result) return;

                $.ajax({
                    url: "{{ route('admin.b2b.users.notify') }}",
                    type: 'POST',
                    data: {
                        _token: $('input[name="_token"]').val(),
                        force: force ? 1 : 0
                    },
                    success: function(response) {
                        if (response.success) {
                            bootbox.alert("Se ha notificado a los clientes correctamente");
                            location.reload();
                        } else {
                            bootbox.alert("Ha ocurrido un error al notificar a los clientes");
                            location.reload();
                        }
                    }
                });
            });
        }

        function removeClientSelecteds({
            objective,
            allselected,
            url,
            title,
            response
        }) {

            const valueAllSelected = getValueFromInput(allselected);
            if (valueAllSelected) {
                return removeAllClients();
            }

            const ids = selectedCheckItemsByName(objective);
            bootbox.confirm(title, function(result) {
                if (!result) return;

                $.ajax({
                    url,
                    type: "delete",
                    data: {
                        _token: $('input[name="_token"]').val(),
                        ids
                    },
                    success: function(result) {
                        saved(result.message);
                        location.reload(true);
                    },
                    error: function(result) {
                        error(result.responseJSON.message);
                    }
                });

            });
        }

        function notifyClientSelecteds({
            objective,
            allselected,
            url,
            title,
            response
        }) {
            const valueAllSelected = getValueFromInput(allselected);
            if (valueAllSelected) {
                return notifyAllClients(true);
            }

            const ids = selectedCheckItemsByName(objective);
            bootbox.confirm(title, function(result) {
                if (!result) return;

                $.ajax({
                    url,
                    type: "post",
                    data: {
                        _token: $('input[name="_token"]').val(),
                        ids
                    },
                    success: function(result) {
                        bootbox.alert(response);
                        location.reload(true);

                    },
                    error: function(result) {
                        error(result.responseJSON.message);
                    }
                });

            });
        };

        function selectedCheckItemsByName(name) {
            return Array.from(document.getElementsByName(name))
                .filter((element) => element.checked)
                .map((element) => element.value);
        }

        function getValueFromInput(inputName) {
            const input = document.querySelector(`input[name="${inputName}"]`);
            if (input.type == 'checkbox' && input.checked) {
                return input.value;
            }
            return false;
        }
    </script>
@stop
