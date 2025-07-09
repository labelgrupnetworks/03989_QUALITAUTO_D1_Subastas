@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@section('content')

    <main class="container user-panel-page represented-page">

        <div class="row">

            <div class="col-lg-3">
                @include('pages.panel.menu_micuenta')
            </div>

            <div class="col-lg-9">

                <h1>{{ trans('web.user_panel.represented_title') }}</h1>
                <div class="my-3">
                    <button class="btn btn-lb-primary pull-right" data-bs-toggle="modal" data-bs-target="#representedModal"
                        type="button">
                        + {{ trans("$theme-app.user_panel.add_represented") }}
                    </button>
                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ trans("$theme-app.user_panel.represented_alias") }}</th>
                            <th>{{ trans("$theme-app.user_panel.represented_name") }}</th>
                            <th>{{ trans("$theme-app.user_panel.represented_cif") }}</th>
                            <th>{{ trans("$theme-app.user_panel.actions") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($representedCollection as $represented)
                            <tr>
                                <td>
                                    {{ $represented->alias_representados }}</td>
                                <td>{{ $represented->nom_representados }}</td>
                                <td>{{ $represented->cif_representados }}</td>
                                <td>
                                    <button class="btn btn-lb-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#representedModalEdit" data-id="{{ $represented->id }}"
                                        data-alias="{{ $represented->alias_representados }}"
                                        data-nom="{{ $represented->nom_representados }}"
                                        data-cif="{{ $represented->cif_representados }}" type="button">
                                        {{ trans("$theme-app.global.update") }}
                                    </button>

                                    <button class="btn btn-lb-primary btn-sm"
                                        onclick="toggleStatus({{ $represented->id }})">
                                        @if ($represented->activo_representados)
                                            {{ trans("$theme-app.global.desactivate") }}
                                        @else
                                            {{ trans("$theme-app.global.activate") }}
                                        @endif
                                    </button>
                                    <button class="btn btn-lb-secondary btn-sm"
                                        onclick="deleteRepresented({{ $represented->id }})">
                                        {{ trans("$theme-app.global.delete") }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </main>

    <div class="modal" id="representedModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="representedModalLabel">{{ trans("$theme-app.user_panel.add_represented") }}
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="repreForm"
                        action="{{ route('panel.represented.create', ['lang' => Config::get('app.locale')]) }}"
                        method="post">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">{{ trans("$theme-app.user_panel.represented_alias") }}:</label>
                            <input class="form-control" name="alias_repre" type="text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ trans("$theme-app.user_panel.represented_name") }}:</label>
                            <input class="form-control" name="nom_repre" type="text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ trans("$theme-app.user_panel.represented_cif") }}:</label>
                            <input class="form-control" name="cif_repre" type="text">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-bs-dismiss="modal" type="button">
                        {{ trans("$theme-app.global.cancel") }}</button>
                    <button class="btn btn-lb-primary" form="repreForm"
                        type="submit">{{ trans("$theme-app.global.add") }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="representedModalEdit" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="representedModalEditLabel">
                        {{ trans("$theme-app.user_panel.edit_represented") }}</h5>
                    <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="repreEditForm"
                        action="{{ route('panel.represented.update', ['lang' => Config::get('app.locale')]) }}"
                        method="post">
                        @csrf
                        <input name="id_repre" type="hidden" value="">
                        <div class="mb-3">
                            <label class="form-label">{{ trans("$theme-app.user_panel.represented_alias") }}:</label>
                            <input class="form-control" name="alias_repre" type="text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ trans("$theme-app.user_panel.represented_name") }}:</label>
                            <input class="form-control" name="nom_repre" type="text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ trans("$theme-app.user_panel.represented_cif") }}:</label>
                            <input class="form-control" name="cif_repre" type="text">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal" type="button">
                        {{ trans("$theme-app.global.cancel") }}
                    </button>
                    <button class="btn btn-lb-primary" form="repreEditForm" type="submit">
                        {{ trans("$theme-app.global.update") }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#representedModalEdit').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);

            const modal = $(this)

            modal.find('[name="id_repre"]').val(button.data('id'));
            modal.find('[name="alias_repre"]').val(button.data('alias'));
            modal.find('[name="nom_repre"]').val(button.data('nom'));
            modal.find('[name="cif_repre"]').val(button.data('cif'));
        });

        function toggleStatus(id) {

            askConfirmModal(messages.neutral.represented_confirm_state, 'Confirmar', function() {

                $.ajax({
                    contentType: 'application/json',
                    url: "{{ route('panel.represented.toggle-status', ['lang' => Config::get('app.locale')]) }}",
                    type: 'POST',
                    data: JSON.stringify({
                        _token: '{{ csrf_token() }}',
                        id_repre: id
                    }),
                    success: function(data) {
                        location.reload();
                    },
                    error: function(data) {
                        $("#insert_msgweb").html(messages.error.user_panel_inf_actualizada);
                        $.magnificPopup.open({
                            items: {
                                src: '#modalMensajeWeb'
                            },
                            type: 'inline'
                        }, 0);
                    }
                });

            });
        }

        function deleteRepresented(id) {

            askConfirmModal(messages.neutral.represented_confim_delete, 'Confirmar', function() {

                $.ajax({
                    contentType: 'application/json',
                    url: "{{ route('panel.represented.delete', ['lang' => Config::get('app.locale')]) }}",
                    type: 'POST',
                    data: JSON.stringify({
                        _token: '{{ csrf_token() }}',
                        id_repre: id
                    }),
                    success: function(data) {
                        location.reload();
                    },
                    error: function(data) {
                        $("#insert_msgweb").html(messages.error.user_panel_inf_actualizada);
                        $.magnificPopup.open({
                            items: {
                                src: '#modalMensajeWeb'
                            },
                            type: 'inline'
                        }, 0);
                    }
                });

            });

        }
    </script>


@stop
