<fieldset>
    <legend>{{ trans('admin-app.title.files') }}</legend>

    @if (!empty($formulario->files))
        <div>
            <label>{{ trans('admin-app.fields.files') }}</label>
            {!! $formulario->files['files'] !!}
        </div>
    @endif

    <div class="mt-3">
        <label>{{ trans('admin-app.fields.files_asigned') }}</label>
        <table class="table table-striped table-files table-condensed">
            <tr>
                <th>{{ trans('admin-app.fields.name') }}</th>
                <th class="text-right">{{ trans('admin-app.fields.actions') }}</th>
            </tr>

            @foreach ($files as $file)
                <tr id="tr-{{ $loop->index }}">

                    @php
                        $filePath =
                            '/files/' .
                            Config::get('app.emp') .
                            "/$fgAsigl0->num_hces1/$fgAsigl0->lin_hces1/files/$file";
                    @endphp
                    <td style="vertical-align: middle;">
                        <a href="{{ $filePath }}" style="text-decoration: none;" target="_blank">
                            <span class="mt-3">{{ $file }}</span>
                        </a>
                    </td>
                    <td class="text-right">
                        <a class="btn btn-sm btn-danger"
                            onclick="javascript:deleteFile('{{ $fgAsigl0->num_hces1 }}', '{{ $fgAsigl0->lin_hces1 }}', '{{ $file }}', '{{ $loop->index }}')">
                            <b>X</b>
                        </a>
                    </td>

                </tr>
            @endforeach

        </table>
    </div>
</fieldset>

<script>
    function deleteFile(num_hces1, lin_hces1, nameFile, position) {

        token = $("[name='_token']").val();
        bootbox.confirm(`¿Estás seguro que quieres borrar el archivo ${nameFile}?`, function(result) {

            if (result) {

                data = {
                    _token: token,
                    num_hces1: num_hces1,
                    lin_hces1: lin_hces1,
                    file: nameFile
                }

                $.post("/admin/lote/deletefile", data, function(response) {

                    $(`#tr-${position}`).remove();
                    showMessage("Archivo eliminado");

                });

            }

        });
    }
</script>
