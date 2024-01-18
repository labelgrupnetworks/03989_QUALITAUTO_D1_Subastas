<div class="row well">

    <legend>{{ trans('admin-app.title.files') }}</legend>

    <div class="mb-3">
        <div class="btn-group" id="js-dropdownItems">
            <button class="btn btn-default btn-sm" type="button">{{ trans("admin-app.button.selecteds") }}</button>
            <button
				data-objective="files_ids"
				class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button"
                aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
            </button>

            <ul aria-labelledby="js-dropdownItems" class="dropdown-menu">

                <li>
                    <button class="btn" data-objective="files_ids"
                        data-title="¿Estás seguro de eliminar todos los archivos seleccionados"
                        data-response="Se han eliminado los archivos seleccionados"
                        data-url="{{ route('subastas.lotes.files.delete-selection') }}"
                        onclick="removeSelecteds(this.dataset, refreshFilesRows)">
                        {{ trans("admin-app.button.destroy") }}
                    </button>
                </li>

                <li>
                    <button class="btn" data-toggle="modal" data-target="#editMultpleFilesModal">
                        {{ trans("admin-app.button.modify") }}
                    </button>
                </li>

            </ul>
        </div>

        <a class="btn btn-primary btn-sm" id="addFile"
            data-url="{{ route('subastas.lotes.files.create', ['num_hces1' => $fgAsigl0->num_hces1, 'lin_hces1' => $fgAsigl0->lin_hces1]) }}">
            {{ trans("admin-app.button.new_file") }}
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-condensed table-files" id="session" style="width:100%">
            <thead>
                <tr>
                    <th>
                        <label>
                            <input name="js-selectAll" data-objective="files_ids" type="checkbox">
                        </label>
                    </th>
                    <th></th>
                    <th style="min-width: 120px">{{ trans('admin-app.fields.name_hces1_files') }}</th>
                    <th>{{ trans('admin-app.fields.permission_hces1_files') }}</th>
                    <th>{{ trans('admin-app.fields.date_update_hces1_files') }}</th>

                    <th>
                        <span>{{ trans('admin-app.fields.actions') }}</span>
                    </th>

                </tr>
            </thead>

            <tbody id="lotFilesRows">
                @include('admin::pages.subasta.lot_files._table_rows', ['files' => $files])
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addFileModal" role="dialog" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">{{ trans("admin-app.title.files_mass_update") }}</h5>
                <button class="close" data-dismiss="modal" type="button" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="modal-create-body"></div>

            <div class="modal-footer">
                <button class="btn btn-primary" id="modalSessionAccept" form="save_lot_file" type="submit">
                    {{ trans("admin-app.button.save") }}
                </button>
                <button class="btn btn-secondary" data-dismiss="modal" type="button">{{ trans("admin-app.button.close") }}</button>
            </div>

        </div>

    </div>
</div>

@include('admin::pages.subasta.lot_files._edit_selecteds')
