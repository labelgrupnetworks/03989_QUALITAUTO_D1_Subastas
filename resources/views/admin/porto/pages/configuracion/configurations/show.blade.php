@extends('admin::layouts.logged')
@section('content')

<style>
	.floating-save-button {
		position: fixed;
		bottom: 50px;
		right: 50px;
		border-radius: 999px;
		background-color: #007bff;
		color: white;
		border: none;
		padding: 12px 16px;
		cursor: pointer;
	}

</style>

    <section class="content-body" role="main">
        @csrf

        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-9">
                <h1 class="m-0">Configuración de {{ $section }}</h1>
            </div>
        </div>

        <div class="row well">

            <div class="col-xs-12">
                <form action="" method="POST">
                    @csrf
                    <table class="table table-striped table-bordered table-responsive" id="" style="width:100%">
                        <thead>
                            <tr>
                                <th>Configuración</th>
                                <th>Descripción</th>
								<th>Valor por defecto</th>
                                <th>Valor</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($configurations as $configuration)
                                <tr>
                                    <td>{{ $configuration->key }}</td>
                                    <td>{{ $configuration->meta['description'] ?? '' }}</td>
									<td>
										{{ var_export($defaultValues[$configuration->key] ?? null, false) }}
									</td>
                                    <td>
                                        @if (!empty($configuration->meta))
                                            @if ($configuration->meta['type'] === 'boolean')
                                                <select class="form-control form-select"
                                                    name="{{ $configuration->id_web_config }}">
                                                    <option value="1" {{ $configuration->value ? 'selected' : '' }}>Sí
                                                    </option>
                                                    <option value="0" {{ !$configuration->value ? 'selected' : '' }}>No
                                                    </option>
                                                </select>
                                            @elseif($configuration->meta['type'] === 'select_multiple')
                                                @foreach ($configuration->meta['values'] as $keyValue => $value)
                                                    <div class="form-check">
                                                        <input class="form-check-input"
                                                            id="{{ $configuration->key }}_{{ $keyValue }}"
                                                            name="{{ $configuration->id_web_config }}" type="checkbox"
                                                            value="{{ $keyValue }}" @checked(in_array($keyValue, array_map('trim', explode(',', $configuration->value))))>
                                                        <label class="form-check-label"
                                                            for="{{ $configuration->key }}_{{ $keyValue }}">
                                                            {{ $value }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @elseif($configuration->meta['type'] === 'string')
                                                <input class="form-control" name="{{ $configuration->id_web_config }}"
                                                    type="text" value="{{ $configuration->value }}">
                                            @elseif($configuration->meta['type'] === 'integer')
                                                <input class="form-control" name="{{ $configuration->id_web_config }}"
                                                    type="number" value="{{ $configuration->value }}">
                                            @else
                                                {{ $configuration->value }}
                                            @endif
                                        @else
                                            {{ $configuration->value }}
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>

				{{-- botón flotante para poder guardar, estilo redondo y con un icono de disket en su interior --}}
				<button class="btn floating-save-button" onclick="saveConfigurations()">
					<i class="fa fa-save"></i> Guardar
				</button>
            </div>
        </div>

        <script>
            //almacenar en variabla solo los inputs que hayan sido modificados
            let modifiedInputs = {};
            document.querySelector('form').addEventListener('change', function(event) {
                if (event.target.name) {
					if( event.target.type === 'checkbox') {
						modifiedInputs[event.target.name] = Array.from(document.querySelectorAll(`input[name="${event.target.name}"]:checked`)).map(checkbox => checkbox.value).join(',');

					} else {
						modifiedInputs[event.target.name] = event.target.value;
					}
                }
            });

            window.onbeforeunload = function() {
                if (Object.keys(modifiedInputs).length > 0) {
                    return '¿Estás seguro de que deseas salir? Se perderán los cambios no guardados.';
                }
            };

			document.addEventListener('keydown', function(event) {
				if (event.ctrlKey && event.key === 's') {
					event.preventDefault();
					saveConfigurations();
				}
			});

            function saveConfigurations() {

				if( Object.keys(modifiedInputs).length === 0) {
					notify('Ojo', 'No hay cambios para guardar.', 'warning');
					return;
				}

				fetch('{{ route('admin.configurations.update', $section) }}', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'X-CSRF-TOKEN': '{{ csrf_token() }}'
					},
					body: JSON.stringify({
						configurations: modifiedInputs
					})
				})
				.then(response => {
					if (response.ok) {
						saved('Configuraciones guardadas correctamente.');
						modifiedInputs = {};
						location.reload();
					} else {
						error('Error al guardar configuraciones.');
					}
				});
            }
        </script>

    @stop
