@forelse ($files as $file)
    <tr data-id="{{ $file->id_hces1_files }}" id="fila_{{ $file->id_hces1_files }}">
		<td>
			<button class="btn btn-link js-soratble-button"><i class="fa fa-reorder"></i></button>
		</td>
        <td>{{ $file->id_hces1_files }}</td>
        <td>
			<a href="{{ route('subastas.lotes.files.show', ['fgHces1File' => $file->id_hces1_files]) }}" target="_blank">
				{{ $file->name_hces1_files }}
			</a>
		</td>
        <td>{{ trans("admin-app.general." . mb_strtolower($file->is_active_hces1_files)) }}</td>
        <td>{{ $file->permission_value }}</td>

        <td>
            <button class="btn btn-success btn-sm" data-action="{{ route('subastas.lotes.files.edit', ['fgHces1File' => $file]) }}" title="{{ trans('admin-app.button.edit') }}"
			 onclick="editFile(this)">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                {{ trans('admin-app.button.edit') }}
            </button>

            <button class="btn btn-danger btn-sm" onclick="removeFile(this)" data-action="{{ route('subastas.lotes.files.destroy', ['fgHces1File' => $file->id_hces1_files]) }}"
                title="{{ trans('admin-app.button.delete') }}">
                <i class="fa fa-trash" aria-hidden="true"></i>
                {{ trans('admin-app.button.delete') }}
            </button>

        </td>
    </tr>

@empty

    <tr>
        <td colspan="6">
            <h3 class="text-center">{{ trans('admin-app.title.without_results') }}</h3>
        </td>
    </tr>
@endforelse
