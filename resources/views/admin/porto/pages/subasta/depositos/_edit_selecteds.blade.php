@php
	use App\Models\V5\FgDeposito;
	$fieldEstadoDeposito = FormLib::Select('estado_deposito_edit', 1, '', (new FgDeposito())->getEstados());
@endphp

<div class="modal fade" id="editMultpleDepositsModal" role="dialog" aria-hidden="true" tabindex="-1">
	<div class="modal-dialog modal-lg" role="document">

		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title">Archivo/s</h5>
				<button class="close" data-dismiss="modal" type="button" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body" id="modal-create-body">
				<form id="edit_multple_deposits" name="edit_multple_deposits"
					action="{{ route('subastas.deposit.update_selection') }}" method="POST">
					<div class="row">
						<div class="col-xs-12 col-md-12">
							<div class="row d-flex flex-wrap">
								<div class="col-xs-12 col-sm-6">
									<label class="mt-1" for="estado_deposito_edit">{{ trans("admin-app.fields.estado_deposito") }}</label>
									<i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right"
										data-original-title="{{ trans("admin-app.help_fields.estado_deposito") }}" aria-hidden="true"
										style="cursor: pointer; margin-left: 3px"></i>
									{!! $fieldEstadoDeposito !!}
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>

			<div class="modal-footer">
				<button class="btn btn-primary" form="edit_multple_deposits" type="submit">
					Guardar
				</button>
				<button class="btn btn-secondary" data-dismiss="modal" type="button">Cerrar</button>
			</div>

		</div>

	</div>
</div>
