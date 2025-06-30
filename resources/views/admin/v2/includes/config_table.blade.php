{{-- Botón para abrir el modal --}}
<a class="btn btn-secondary btn-sm" aria-hidden="true"
   data-bs-toggle="modal" data-bs-target="#modal_config_{{$id}}">
    <i style="cursor: pointer" class="fa fa-cog"></i> {{ trans("admin-app.button.config_table") }}
</a>

{{-- Modal --}}
<div class="modal fade text-start" id="modal_config_{{$id}}" tabindex="-1" aria-labelledby="modalTitle_{{$id}}" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle_{{$id}}">{{ trans("admin-app.title.table_columns") }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ trans('admin-app.button.close') }}"></button>
            </div>

            <div class="modal-body">
                <form id="table_config_{{$id}}">
                    <div class="mb-3">
                        @foreach ($params as $param => $check)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   id="check_{{$param}}"
                                   name="check_{{$param}}" @if($check) checked @endif>
                            <label class="form-check-label" for="check_{{$param}}">
                                {{ trans_choice("admin-app.fields.$param", '') }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ trans("admin-app.button.close") }}
                </button>
                <button type="submit" form="table_config_{{$id}}" class="btn btn-success">
                    {{ trans("admin-app.button.accept") }}
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function(){
        tableConfig.addTable('{{$id}}', @json(array_keys($params)));
    });
</script>
