<fieldset>
    <legend>Documento Identidad</legend>

    <input type="hidden" name="route_dni"
        value="{{ route('admin.clientes.dni.store', ['cod_cli' => $cliente->codcli]) }}">

    @foreach (['dni1', 'dni2'] as $dni)
        @php
            $has = !empty($dnis[$dni]);
			$position = $dni === 'dni1' ? 'Frontal' : 'Trasera';
        @endphp

        <div class="dni mb-2">
            <div class="d-flex gap-5 mb-1">
                <span>{{ $position }}</span>

				<label class="btn btn-xs btn-success ml-auto" for="{{ $dni }}">
                    {{ $has ? 'Modificar' : 'Añadir' }}
                </label>

				{{-- pendiente implementacion --}}
                {{-- @if ($has)
                    <button class="btn btn-xs btn-danger">Eliminar</button>
                @endif --}}

                <input type="file" class="form-control" name="{{ $dni }}" id="{{ $dni }}"
                    style="display: none">
            </div>

            <div class="dni-img">
				@if(!$has)
				<img class="img-responsive" alt="image to dni" id="{{ $dni }}-img" src="{{ "$base_url/images/dni_placeholder.png" }}">
				@elseif ($dnis[$dni]['mime'] == 'application/pdf')
					<iframe src="{{ "data:application/pdf;base64,{$dnis[$dni]['base64']}" }}" width="100%" height="400"></iframe>
				@else
					<img class="img-responsive" alt="image to dni" id="{{ $dni }}-img" src="{{ "data:imgage/jpeg;base64,{$dnis[$dni]['base64']}" }}">
				@endif
            </div>
        </div>
    @endforeach

</fieldset>
