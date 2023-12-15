@forelse ($files as $file)
    <tr id="fila_{{ $file->id_hces1_files }}" data-id="{{ $file->id_hces1_files }}">
        <td>
            <label>
                <input type="checkbox" name="files_ids" value="{{ $file->id_hces1_files }}">
            </label>
        </td>
        <td>

            <button class="btn btn-link sortable-button"><i class="fa fa-reorder"></i></button>
        </td>
        <td>
            <a href="{{ route('subastas.lotes.files.show', ['fgHces1File' => $file->id_hces1_files]) }}" target="_blank">
                @if ($file->is_active_hces1_files == 'S')
                    {{ $file->name_hces1_files }}
                @else
                    <del>{{ $file->name_hces1_files }}</del>
                @endif
            </a>
        </td>
        <td>{{ $file->permission_value }}</td>
        <td>{{ $file->updated_at }}</td>

        <td>
            <div class="table-actions">

                <button class="btn btn-sm @if ($file->is_active_hces1_files == 'S') btn-info @else btn-warning @endif"
                    data-action="{{ route('subastas.lotes.files.status', ['fgHces1File' => $file]) }}"
                    title="Activar/Desactivar" onclick="changeStatusFile(this)">
                    <i class="fa @if ($file->is_active_hces1_files == 'S') fa-eye @else fa-eye-slash @endif"></i>
                </button>

                <button class="btn btn-success btn-sm"
                    data-action="{{ route('subastas.lotes.files.edit', ['fgHces1File' => $file]) }}"
                    title="{{ trans('admin-app.button.edit') }}" onclick="editFile(this)">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    {{-- {{ trans('admin-app.button.edit') }} --}}
                </button>

                <button class="btn btn-danger btn-sm"
                    data-action="{{ route('subastas.lotes.files.destroy', ['fgHces1File' => $file->id_hces1_files]) }}"
                    title="{{ trans('admin-app.button.delete') }}" onclick="removeFile(this)">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                    {{--  {{ trans('admin-app.button.delete') }} --}}
                </button>
            </div>

        </td>
    </tr>

@empty

    <tr>
        <td colspan="6">
            <h3 class="text-center">{{ trans('admin-app.title.without_results') }}</h3>
        </td>
    </tr>
@endforelse
