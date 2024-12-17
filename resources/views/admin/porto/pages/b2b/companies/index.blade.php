@extends('admin::layouts.logged')
@section('content')

    <section class="content-body" role="main">
        @include('admin::includes.header_content')
        @csrf

        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-12">
                <h1>Empresas</h1>
            </div>
        </div>

        <div class="row well">

            <div class="col-xs-12 text-right mb-1 pt-1 pb-1" style="background-color: #ffe7e7">

                <a class="btn btn-sm btn-primary" href="{{ route('admin.b2b.companies.create') }}">
                    {{ trans('admin-app.button.new') }} Empresa
                </a>

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
                        @forelse ($users as $user)
                            <tr id="{{ $user->cod_cli }}">
                                <td>{{ $user->cod_cli }}</td>
                                <td>{{ $user->nom_cli }}</td>
                                <td>{{ $user->rsoc_cli }}</td>
                                <td>{{ mb_strtolower($user->email_cli) }}</td>
                                <td>{{ $user->cif_cli }}</td>
                                <td>{{ $user->tel1_cli }}</td>
                                <td>{{ $user->tipoBajaTmpTypes }}</td>

                                <td>
                                    <a class="btn btn-success btn-sm"
                                        href="{{ route('admin.b2b.companies.edit', ['idCli' => $user->cod_cli]) }}"
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


    <script>
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
