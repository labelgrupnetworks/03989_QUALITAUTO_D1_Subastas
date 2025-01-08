@extends('admin::layouts.logged')
@section('content')

    <section class="content-body" role="main">
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
                <h1>{{ trans('admin-app.title.visibility') }}</h1>
            </div>
        </div>

        <div class="row well">

            <div class="col-xs-12 text-right mb-1 pt-1 pb-1" style="background-color: #ffe7e7">

                <div class="left">
                    <label for="">Por defecto:</label>
                    <div class="btn-group ml-2" role="group" aria-label="...">
                        <button type="button" data-action="show" @class([
                            'btn btn-xs',
                            'btn-primary' => $auctionVisibility?->eliminado_visibilidad !== 'S',
                            'btn-default' => $auctionVisibility?->eliminado_visibilidad === 'S',
                        ])>Mostrar a todos</button>
                        <button type="button" data-action="hide" @class([
                            'btn btn-xs',
                            'btn-primary' => $auctionVisibility?->eliminado_visibilidad === 'S',
							'btn-default' => $auctionVisibility?->eliminado_visibilidad !== 'S',
                        ])>Ocultar a todos</button>
                    </div>
                </div>

                {{-- configuracion de tabla --}}
                <a class="btn btn-primary btn-sm" href="{{ route('admin.b2b.visibility.create') }}">
                    {{ trans('admin-app.button.new') }}
                    {{ trans('admin-app.title.visibility') }}
                </a>

                @include('admin::includes.config_table_v2', [
                    'id' => 'b2b_visibility_table',
                    'params' => $tableParams,
                    'formulario' => $formulario,
                ])
            </div>


            <div class="col-xs-12 table-responsive">
                <table class="table table-striped table-condensed table-align-middle" id="b2b_visibility_table"
                    data-order-name="order" style="width:100%">
                    <thead>
                        <tr>
                            @foreach ($tableParams as $field => $display)
                                @include('admin::components.tables.sortable_column_header', [
                                    'field' => $field,
                                ])
                            @endforeach
                            <th>
                                <span>{{ trans('admin-app.fields.actions') }}</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($visibilities as $visibility)
                            <tr id="{{ $visibility->cod_visibilidad }}">

                                <td class="sub_visibilidad" @if (!$tableParams['sub_visibilidad']) style="display: none" @endif>
                                    {{ $visibility->sub_visibilidad }}
                                </td>

                                <td class="cli_visibilidad" @if (!$tableParams['cli_visibilidad']) style="display: none" @endif>
                                    {{ $visibility->cli_visibilidad }}
                                </td>
                                <td class="clientName" @if (!$tableParams['clientName']) style="display: none" @endif>
                                    {{ $visibility?->client?->invitation->invited_nom_subinvites }}
                                </td>
                                <td class="email_cli" @if (!$tableParams['email_cli']) style="display: none" @endif>
                                    {{ mb_strtolower($visibility?->client?->email_cli) }}
                                </td>
                                <td class="ref_visibilidad" @if (!$tableParams['ref_visibilidad']) style="display: none" @endif>
                                    {{ $visibility->ref_visibilidad }}
                                </td>

                                <td>
                                    <a class="btn btn-success btn-sm"
                                        href="{{ route('admin.b2b.visibility.edit', $visibility->cod_visibilidad) }}"
                                        title="{{ trans('admin-app.button.edit') }}"><i class="fa fa-pencil-square-o"
                                            aria-hidden="true"></i>{{ trans('admin-app.button.edit') }}
                                    </a>
                                    <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal"
                                        data-id="{{ $visibility->cod_visibilidad }}"
                                        data-name="{{ trans('admin-app.title.delete_resource', ['resource' => trans('admin-app.title.visibility'), 'id' => $visibility->cod_visibilidad]) }}">
                                        <i class="fa fa-trash"></i>
                                    </button>

                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="{{ $numberOfColumns }}">
                                    <h3 class="text-center">{{ trans('admin-app.title.without_results') }}</h3>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>

        </div>

        <div class="col-xs-12 d-flex justify-content-center">
            {{ $visibilities->links() }}
        </div>

        @include('admin::includes._delete_modal', [
            'routeToDelete' => route('admin.b2b.visibility.destroy', [0]),
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

			$('[data-action]').on('click', function() {
				var action = $(this).data('action');
				var url = '{{ route('admin.b2b.visibility.showOrHideEveryone') }}';
				var data = {
					'action': action,
				};

				$.ajax({
					url: url,
					type: 'POST',
					data: data,
					success: function(response) {
						location.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});

			});


        </script>

    </section>
@stop
