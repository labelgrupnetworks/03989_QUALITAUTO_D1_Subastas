<div class="row well mt-3">
    <div class="col-xs-12 table-responsive">

        <legend>{{ trans('admin-app.title.files') }}</legend>

        <a class="btn btn-primary btn-sm pull-right" id="addFile"
            data-url="{{ route('subastas.lotes.files.create', ['num_hces1' => $fgAsigl0->num_hces1, 'lin_hces1' => $fgAsigl0->lin_hces1]) }}">
            + Nuevo archivo
        </a>

        <table class="table table-striped table-condensed table-files" id="session" style="width:100%">
            <thead>
                <tr>
                    <th></th>
                    <th>{{ trans('admin-app.fields.id_hces1_files') }}</th>
                    <th>{{ trans('admin-app.fields.name_hces1_files') }}</th>
                    <th>{{ trans('admin-app.fields.is_active_hces1_files') }}</th>
                    <th>{{ trans('admin-app.fields.permission_hces1_files') }}</th>

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


<div class="modal fade" id="addFileModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Archivo</h5>
                <button class="close" data-dismiss="modal" type="button" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="modal-create-body"></div>

            <div class="modal-footer">
                <button class="btn btn-primary" id="modalSessionAccept" form="save_lot_file" type="submit">
                    Guardar
                </button>
                <button class="btn btn-secondary" data-dismiss="modal" type="button">Cerrar</button>
            </div>

        </div>

    </div>
</div>
