<div class="col-xs-12">
    <fieldset>
        <legend>{{ trans('admin-app.title.files') }}</legend>
        <div class="row mt-2">

            <div class="col-xs-12 col-md-7">

                <div class="row">
                    <div class="col-xs-12 col-md-7">
                        <label>{{ trans('admin-app.fields.files') }}</label>
                        {!! $formulario->files['files'] !!}
                    </div>
                </div>

            </div>

            <div class="col-xs-12 col-md-5">

                <label>{{ trans('admin-app.fields.files_asigned') }}</label>
                <table class="table table-striped table-files table-condensed">
                    <tr>
                        <th>{{ trans('admin-app.fields.name') }}</th>
                        <th>{{ trans('admin-app.fields.actions') }}</th>
                    </tr>

                    @foreach ($files as $file)
                        <tr id="tr-{{ $loop->index }}">

                            @php
                                $emp = Config::get('app.emp');
                                $filePath = "/files/$emp/$fgAsigl0->num_hces1/$fgAsigl0->lin_hces1/files/$file";
                            @endphp
                            <td>
                                <a href="{{ $filePath }}" style="text-decoration: none;" target="_blank">
                                    <span class="mt-3">{{ $file }}</span>
                                </a>
                            </td>
                            <td>
                                <button class="btn btn-danger" type="button"
                                    onclick="deleteFile('{{ $fgAsigl0->num_hces1 }}', '{{ $fgAsigl0->lin_hces1 }}', '{{ $file }}', '{{ $loop->index }}')">
                                    <b>X</b>
                                </button>
                            </td>

                        </tr>
                    @endforeach

                </table>
            </div>



        </div>
    </fieldset>
</div>

<script>
    function deleteFile(num_hces1, lin_hces1, nameFile, position) {

        token = $("[name='_token']").val();
        bootbox.confirm(`¿Estás seguro que quieres borrar el archivo ${nameFile}?`, function(result) {
            if (!result) return;

            $.ajax({
                type: "DELETE",
                url: "{{ route('subastas.lotes.files.oldDestroy') }}",
                data: {
                    _token: token,
                    num_hces1: num_hces1,
                    lin_hces1: lin_hces1,
                    file: nameFile
                },
                success: function(response) {
                    $(`#tr-${position}`).remove();
                    showMessage("Archivo eliminado");
                },
                error: function(xhr, status, error) {
                    showMessage("Error al eliminar el archivo");
                }
            });

        });
    }
</script>
