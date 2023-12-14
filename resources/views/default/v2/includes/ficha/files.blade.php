@php
    use App\Models\V5\FgHces1Files;

	$files = [];
    $path = '/files/' . Config::get('app.emp') . "/$lote_actual->num_hces1/$lote_actual->lin_hces1/files/";

    if (Config::get('app.use_table_files', false)) {
        $files = FgHces1Files::getAllFilesByLotCanViewUser($userSession, $lote_actual->num_hces1, $lote_actual->lin_hces1, $deposito);
    } elseif (is_dir(getcwd() . $path)) {
        $files = array_diff(scandir(getcwd() . $path), ['.', '..']);
    }
@endphp

<h5>{{ trans("$theme-app.lot.documents") }}</h5>

<div class="ficha-files-list list-group list-group-flush">

    @foreach ($files as $file)

        @if (is_object($file))
            <a class="list-group-item list-group-item-action btn-icon" href="{{ $file->download_path }}"
                alt="{{ $file->name_hces1_files }}" download>
				@include('components.boostrap_icon', ['icon' => 'file-earmark-pdf', 'size' => '18', 'color' => 'red'])

                {{ $file->name_hces1_files }}
            </a>
        @else
            <a class="list-group-item list-group-item-action" href="{{ $path . $file }}" target="_blank">
                {{ $file }}
            </a>
        @endif
    @endforeach
</div>
