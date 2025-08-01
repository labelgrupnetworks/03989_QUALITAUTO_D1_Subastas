<option value="">Selecciona un operador</option>
@foreach ($operadores as $key => $value)
    <option value="{{ $key }}">{{ $value }}</option>
@endforeach
