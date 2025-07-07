@if (!empty($formularioImport))
    <a class="btn btn-success right" href="{{ $formularioImport }}"
        style="margin-right: 5px;">{{ trans('admin-app.button.upload_excel') }}</a>
@endif

<div class="col-xs-12 d-flex mb-1 pt-1 pb-1" style="background-color: #ffe7e7; gap:5px; flex-wrap: wrap">
    @if (!empty($fgSub->cod_sub))
        <button class="btn btn-success btn-sm" onclick="openPrintWindow('{{ $fgSub->cod_sub }}')">
            Imprimir paletas
        </button>

		<button class="btn btn-success btn-sm" onclick="openPrint2Window('{{ $fgSub->cod_sub }}')">
            Imprimir Lista de Operadores
        </button>
    @endif

</div>

<div class="col-xs-12 p-0 table-responsive">
    <table class="table table-striped table-bordered table-align-middle" id="tablePhoneOrders"
        data-order-name="order_phone_orders" style="width:100%">

        <thead>
            <tr>
                @foreach ($availableColumns as $column => $label)
                    <th class="{{ $column }}" {{-- data-order="{{ $column }}" style="cursor: pointer;" --}}>
                        {{ $label }}
                        @if (request()->order_orders == $column)
                            <span style="margin-left: 5px; float: right;">
                                @if (request()->order_phone_orders == 'asc')
                                    <i class="fa fa-arrow-up" aria-hidden="true" style="color:green"></i>
                                @else
                                    <i class="fa fa-arrow-down" aria-hidden="true" style="color:red"></i>
                                @endif
                            </span>
                        @endif
                    </th>
                @endforeach
            </tr>

        </thead>
        <tbody>
            <tr id="filters">
                <form class="form-group" action="">
                    <input name="order_phone_orders" type="hidden"
                        value="{{ request('order_phone_orders', 'cod_cli') }}">
                    <input name="order_phone_orders_dir" type="hidden"
                        value="{{ request('order_phone_orders_dir', 'desc') }}">
                </form>
            </tr>


            @foreach ($phoneOrders as $phoneOrder)
                <tr data-sub="{{ "$phoneOrder->sub_orlic" }}" data-ref="{{ "$phoneOrder->ref_orlic" }}"
                    data-lin="{{ "$phoneOrder->lin_orlic" }}" data-operador="{{ "$phoneOrder->operador_orlic" }}">

                    @foreach ($availableColumns as $column => $label)
                        <td class="{{ $column }}">
                            {{-- Check if the column is a date and format it accordingly --}}
                            @if ($casts[$column] == 'blob')
                                <p class="mb-0">
                                    {{ strip_tags(data_get($phoneOrder, $column, '')) }}
                                </p>
                            @elseif(is_enum($casts[$column]))
                                {{ $casts[$column]::fromValue(data_get($phoneOrder, $column, ''))?->displayName() }}
                            @elseif($casts[$column] == 'editable')
                                <div class="d-flex align-items-center">
                                    <span>
                                        {{ data_get($phoneOrder, $column, null) }}
                                    </span>
                                    <span class="ml-auto">
                                        {{-- Botón para editar el operador de la orden --}}
                                        <button class="btn btn-default btn-sm" type="button">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </span>
                                </div>
                            @else
                                {{ data_get($phoneOrder, $column, null) }}
                            @endif
                        </td>
                    @endforeach

                </tr>
            @endforeach

        </tbody>
    </table>

    {{ $phoneOrders->links() }}
</div>

{{-- Modal con los posibles operadores --}}
<div class="modal fade" id="modalOperadores" role="dialog" aria-labelledby="modalOperadoresLabel" aria-hidden="true"
    tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalOperadoresLabel">
                    Operadores
                </h5>
                <button class="close" data-dismiss="modal" type="button" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAddOperador" method="POST" action="{{ route('orders.add_bidding_agent') }}">
                    @csrf

                    <input name="cod_sub" type="hidden" value="{{ $fgSub->cod_sub }}">
                    <input name="ref_orlic" type="hidden" value="">
                    <input name="lin_orlic" type="hidden" value="">

                    <p>Selecciona un operador para la orden:</p>
                    <div class="input-group">

                        <select class="form-control" name="phoneBiddingAgent">
                            <option value="">Selecciona un operador</option>
                            @foreach ($phoneBiddingAgents as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>

                        <span class="input-group-btn">
                            <button class="btn btn-primary" id="createAgent" data-toggle="modal" type="button">
                                <i class="fa fa-plus"></i>
                            </button>
                        </span>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" type="button">Cerrar</button>
                <button class="btn btn-primary" form="formAddOperador" type="submit">
                    Guardar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal para crear un nuevo operador: solo necesito el nombre --}}
<div class="modal fade" id="modalCrearOperador" role="dialog" aria-labelledby="modalCrearOperadorLabel"
    aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCrearOperadorLabel">
                    Crear nuevo operador
                </h5>
                <button class="close" data-dismiss="modal" type="button" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCrearOperador" method="POST" action="{{ route('subastas.operadores.store') }}">
                    <div class="form-group">
                        <label for="nom_operadores">Nombre del operador</label>
                        <input class="form-control" id="nom_operadores" name="nom_operadores" type="text"
                            placeholder="Introduce el nombre del operador" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" type="button">Cerrar</button>
                <button class="btn btn-primary" id="btnGuardarNuevoOperador" form="formCrearOperador"
                    type="submit">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('.phoneBiddingAgent\\.nom_operadores button').on('click', function() {
        // Aquí puedes manejar el evento de clic
        const parentRow = $(this).closest('tr');
        const refOrlic = parentRow.data('ref');
        const linOrlic = parentRow.data('lin');
        const operadorOrlic = parentRow.data('operador');

        // Establecer los valores en el formulario del modal
        $('#formAddOperador input[name="ref_orlic"]').val(refOrlic);
        $('#formAddOperador input[name="lin_orlic"]').val(linOrlic);
        $('#formAddOperador select[name="phoneBiddingAgent"]').val(operadorOrlic);

        $('#modalOperadores').modal('show');
    });

    //abrir un segundo modal para crear un nuevo operador
    $('#createAgent').on('click', function() {
        $('#modalCrearOperador').modal('show');
    });

    function openPrintWindow(id) {
        const url = `{{ route('subastas.operadores.print_bid_paddles', ':id') }}`.replace(':id', id);
        // Ajusta width/height según necesites
        window.open(
            url,
            'printWindow',
            'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=800,height=600'
			);
		}

	function openPrint2Window(id) {
		const url = `{{ route('subastas.operadores.print_bid_paddles_by_operator', ':id') }}`.replace(':id', id);
		// Ajusta width/height según necesites
		window.open(
			url,
			'printWindow',
			'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=800,height=600'
			);
		}
</script>
