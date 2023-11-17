<div>
    <div class="form-group mb-3">
        <label for="clientFile">Añadir archivos</label>
        <input type="file" class="form-control" name="client_files[]" id="clientFile" multiple>
        <small>{{ trans('admin-app.general.max_file_size', ['size' => min(ini_get('upload_max_filesize'), ini_get('post_max_size'))]) }}</small>

        <input id="store_file_route" type="hidden"
            value="{{ route('clientes.files.store', [$cliente->codcli]) }}">
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-condensed table-files" style="width:100%">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Tamaño</th>
                    <th>Fecha mod.</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="bodyTableFile">

                @foreach ($files as $file)
                    <tr>
                        <td>
                            <a href="{{ $file->link }}" target="_blank">
                                {{ $file->name }}
                            </a>
                        </td>
                        <td>{{ $file->size_kb }}</td>
                        <td>{{ $file->last_modified_human }}</td>
                        <td>
                            <button type="button" class="btn btn-xs btn-danger"
                                onclick="deleteFile('{{ $file->unlink }}')">Eliminar</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
