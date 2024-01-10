@php
	use App\Models\V5\FxCli;
	use App\Models\V5\FxTcli;
    $formularioFiles = [
        'source' => FormLib::Select('tipo_select', 0, '', FxTcli::pluck('des_tcli', 'cod_tcli'), '', '', false),
        'temporaryblock' => FormLib::Select('bloq_temporal_select', 0, '', (new FxCli)->getTipoBajaTmpTypes(), '', '', false),
		'enviocatalogo' => FormLib::Select('envio_catalogo_select', 0, 'S', ['S' => 'Si', 'N' => 'No'], '', '', false)
    ];
@endphp

<div class="modal fade" id="editMultpleClientsModal" role="dialog" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">{{ trans("admin-app.title.client_mass_update") }}</h5>
                <button class="close" data-dismiss="modal" type="button" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="modal-create-body">
                <form id="edit_multple_clients" name="edit_multple_clients"
                    action="{{ route('clientes.update_selections') }}" method="POST">
                    <div class="row">
                        @include('admin::pages.usuario.cliente_v2._form_selecteds', ['formulario' => $formularioFiles])
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" form="edit_multple_clients" type="submit">
                    {{ trans("admin-app.button.save") }}
                </button>
                <button class="btn btn-secondary" data-dismiss="modal" type="button">{{ trans("admin-app.button.close") }}</button>
            </div>

        </div>

    </div>
</div>
