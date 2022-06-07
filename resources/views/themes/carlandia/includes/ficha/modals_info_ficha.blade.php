<div id="info-next-bid" class="container modal-block info-modal mfp-hide">
	<section class="panel">
		<div class="panel-body">
			<div class="modal-wrapper">
				<h3 class="modal-title text-center">Siguiente puja mínima</h3>
				<p>Importe mínimo requerido para hacer una nueva puja válida. Si quieres, puedes pujar por un importe
					superior.<br></p>

				<div class="table-responsive">
					<table class="table modal-table" style="width: 100%;" border="1" cellpadding="10">
						<tbody>
							<tr>
								<td>ÚLTIMA PUJA (€)</td>
								<td>< 20.000</td>
								<td>20.000 - 30.000</td>
								<td>> 30.000</td>
							</tr>
							<tr>
								<td>Incremento mínimo</td>
								<td>100 €</td>
								<td>200 €</td>
								<td>300 €</td>
							</tr>
						</tbody>
					</table>
				</div>

				<div class="text-right">
					<button class="btn modal-dismiss">{{ trans(\Config::get('app.theme').'-app.head.close') }}</button>
				</div>

			</div>
		</div>
	</section>
</div>
