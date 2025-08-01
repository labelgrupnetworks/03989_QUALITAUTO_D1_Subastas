<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th style="width: 20%">Id</th>
            <th style="width: 60%">Operador</th>
            <th style="width: 20%">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($operadores as $operador)
            <tr data-id="{{ $operador->cod_operadores }}">
                <td style="vertical-align: middle">{{ $operador->cod_operadores }}</td>
                <td class="editable-container" style="vertical-align: middle">
                    <span class="editable"
                        data-id="{{ $operador->cod_operadores }}">{{ $operador->nom_operadores }}</span>
                    <input class="form-control hidden" data-id="{{ $operador->cod_operadores }}"
                        data-route="{{ route('subastas.operadores.update', $operador->cod_operadores) }}" type="text"
                        value="{{ $operador->nom_operadores }}">
                </td>
                <td>
                    <button class="btn btn-link btn-sm text-danger"
                        data-route="{{ route('subastas.operadores.destroy', $operador->cod_operadores) }}"
                        type="button" onclick="removeOperador(this,{{ $operador->cod_operadores }})">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="text-right">
    <button class="btn btn-primary" type="button" onclick="$('#modalCrearOperador').modal('show')">
        <i class="fa fa-plus"></i>
    </button>
</div>

<script>
    function removeOperador(button, id) {
        if (confirm('¿Estás seguro de que quieres eliminar este operador?')) {
            $.ajax({
                url: $(button).data('route'),
                type: 'DELETE',
                data: {
                    _token: $('[name="_token"]').val()
                },
                success: function(response) {
                    saved(response.message || 'Operador eliminado correctamente');
                    $('tr[data-id="' + id + '"]').remove();
                },
                error: function(xhr) {
                    error('Error al eliminar el operador: ' + xhr.responseText);
                }
            });
        }
    }

    $(document).ready(function() {
        // Hacer editable el nombre del operador
        $('.editable-container .editable').on('click', function() {
            var $this = $(this);
            var id = $this.data('id');
            var value = $this.text();
            $this.addClass('hidden');
            $this.siblings('input').removeClass('hidden').val(value).focus();
        });

        $('.editable-container input[type="text"]').on('keyup', function(e) {
            if (e.key === 'Enter') {
                $(this).blur();
            }
        });

        $('.editable-container input[type="text"]').on('blur', function(e) {
            if (e.type === 'blur') {

                var $input = $(this);
                var id = $input.data('id');
                var newValue = $input.val();

                if (newValue === $input.siblings('.editable').text()) {
                    $input.addClass('hidden');
                    $input.siblings('.editable').removeClass('hidden');
                    return;
                }

                var $span = $('.editable[data-id="' + id + '"]');

                $.ajax({
                    url: $input.data('route'),
                    type: 'PUT',
                    data: {
                        nom_operadores: newValue,
                        _token: $('[name="_token"]').val()
                    },
                    success: function(response) {
                        saved(response.message || 'Operador actualizado correctamente');
                    },
                    error: function(xhr) {
                        error('Error al guardar el operador: ' + xhr.responseText);
                    }
                });

                $span.text(newValue).removeClass('hidden');
                $input.addClass('hidden');
            }
        });
    });
</script>
