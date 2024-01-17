@php
	use App\Models\V5\FgHces1Files;
    $formularioFiles = [
        'is_active_hces1_files' => FormLib::Select('is_active_hces1_files', true, 'S', ['S' => 'Si', 'N' => 'No'], '', '', false),
        'permission_hces1_files' => FormLib::Select('permission_hces1_files', true, FgHces1Files::PERMISSION_EMPTY, FgHces1Files::getPermissions(), '', '', false),
    ];
@endphp

<div class="modal fade" id="editMultpleFilesModal" role="dialog" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">{{ trans("admin-app.title.files_mass_update") }}</h5>
                <button class="close" data-dismiss="modal" type="button" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="modal-create-body">
                <form id="edit_multple_files" name="edit_multple_files"
                    action="{{ route('subastas.lotes.files.update-selection') }}" method="POST">
                    <div class="row">
                        @include('admin::pages.subasta.lot_files._form', ['formulario' => $formularioFiles])
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" form="edit_multple_files" type="submit">
                    {{ trans("admin-app.button.save") }}
                </button>
                <button class="btn btn-secondary" data-dismiss="modal" type="button">{{ trans("admin-app.button.close") }}</button>
            </div>

        </div>

    </div>
</div>
