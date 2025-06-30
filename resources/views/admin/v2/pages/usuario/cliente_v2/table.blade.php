<div class="table-responsive">
    <table class="table table-striped align-middle table-condensed">
        <thead>
            <tr>
                <th>
                    <div class="form-check">
                        <input class="form-check-input" id="selectAllClients" name="js-selectAll" data-objective="cli_ids"
                            type="checkbox" value="true">
                        <input class="form-check-input" id="urlAllSelected" name="url-allSelected" type="hidden"
                            value="{{ route('clientes.update_with_filters') }}">
                    </div>
                </th>
                @foreach ($visibleColumns as $col)
                    <th>{{ $availableColumns[$col] }}</th>
                @endforeach
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($clientes as $cliente)
                <tr>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" name="cli_ids" type="checkbox"
                                value="{{ $cliente->cod2_cli }}">
                        </div>
                    </td>
                    @foreach ($visibleColumns as $col)
                        <td>
                            @if ($col === 'es_activo')
                                @if ($cliente->es_activo ?? true)
                                    {{-- Si es activo, mostrar badge verde --}}
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            @else
                                {{ $cliente->{$col} }}
                            @endif
                        </td>
                    @endforeach
                    <td>
                        <a class="text-info me-2" href="#"><i class="bi bi-eye-fill"></i></a>
                        <a class="text-warning me-2" href="#"><i class="bi bi-pencil-fill"></i></a>
                        <a class="text-secondary me-2" href="#"><i class="bi bi-slash-circle-fill"></i></a>
                        <a class="text-danger" href="#"><i class="bi bi-trash-fill"></i></a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-center" colspan="{{ count($visibleColumns) + 1 }}">No hay usuarios.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
