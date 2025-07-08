@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@section('content')

    <div class="color-letter">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h1 class="titlePage">{{ trans(\Config::get('app.theme') . '-app.user_panel.mi_cuenta') }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="account-user color-letter  panel-user">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-3 col-lg-3 account-user-menu">
                    <?php $tab = 'represented'; ?>
                    @include('pages.panel.menu_micuenta')
                </div>
                <div class="col-xs-12 col-md-9 col-lg-9 ">
                    <div class="user-account-title-content">
                        <div class="user-account-menu-title">
                            {{ trans("$theme-app.user_panel.represented_title") }}
                        </div>
                    </div>
                    <div class="row mt-3 mb-1">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-lb-primary pull-right" data-toggle="modal"
                                data-target="#representedModal">
                                + {{ trans("$theme-app.user_panel.add_represented") }}
                            </button>
                            </a>
                        </div>
                    </div>
                    <div class="col-xs-12 no-padding">
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
                                            <button type="button" class="btn btn-lb-primary btn-sm" data-toggle="modal"
                                                data-target="#representedModalEdit" data-id="{{ $represented->id }}"
                                                data-alias="{{ $represented->alias_representados }}"
                                                data-nom="{{ $represented->nom_representados }}"
                                                data-cif="{{ $represented->cif_representados }}">
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
                                            <button class="btn btn-danger btn-sm"
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
            </div>
        </div>
    </div>

    <div class="modal fade" id="representedModal" tabindex="-1" role="dialog" aria-labelledby="representedModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="representedModalLabel">{{ trans("$theme-app.user_panel.add_represented") }}
                    </h4>
                </div>
                <div class="modal-body">
                    <form id="repreForm"
                        action="{{ route('panel.represented.create', ['lang' => Config::get('app.locale')]) }}"
                        method="post">
                        @csrf
                        <div class="form-group">
                            <label class="control-label">{{ trans("$theme-app.user_panel.represented_alias") }}:</label>
                            <input type="text" class="form-control" name="alias_repre">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{ trans("$theme-app.user_panel.represented_name") }}:</label>
                            <input type="text" class="form-control" name="nom_repre">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{ trans("$theme-app.user_panel.represented_cif") }}:</label>
                            <input type="text" class="form-control" name="cif_repre">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        {{ trans("$theme-app.global.cancel") }}
                    </button>
                    <button type="submit" class="btn btn-primary" form="repreForm">
                        {{ trans("$theme-app.global.add") }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="representedModalEdit" tabindex="-1" role="dialog"
        aria-labelledby="representedModalEditLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="representedModalEditLabel">
                        {{ trans("$theme-app.user_panel.edit_represented") }}</h4>
                </div>
                <div class="modal-body">
                    <form id="repreEditForm"
                        action="{{ route('panel.represented.update', ['lang' => Config::get('app.locale')]) }}"
                        method="post">
                        @csrf
                        <input type="hidden" name="id_repre" value="">
                        <div class="form-group">
                            <label class="control-label">{{ trans("$theme-app.user_panel.represented_alias") }}:</label>
                            <input type="text" class="form-control" name="alias_repre">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{ trans("$theme-app.user_panel.represented_name") }}:</label>
                            <input type="text" class="form-control" name="nom_repre">
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{ trans("$theme-app.user_panel.represented_cif") }}:</label>
                            <input type="text" class="form-control" name="cif_repre">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        {{ trans("$theme-app.global.cancel") }}
                    </button>
                    <button type="submit" class="btn btn-primary" form="repreEditForm">
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
