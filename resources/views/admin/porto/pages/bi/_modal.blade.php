<div id="modalBi" class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id=" modalLabel">TOPS</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">

				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item active">
						<a class="nav-link active" id="compradores-tab" data-toggle="tab" href="#compradores" role="tab"
							aria-controls="compradores" aria-selected="true">Top compradores</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="ventas-tab" data-toggle="tab" href="#ventas" role="tab"
							aria-controls="ventas" aria-selected="false">Top ventas</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="incremento-tab" data-toggle="tab" href="#incremento" role="tab"
							aria-controls="incremento" aria-selected="false">Top incremento</a>
					</li>
				</ul>

				<div class="tab-content" id="myTabContent">
					<div class="tab-pane fade in active" id="compradores" role="tabpanel" aria-labelledby="compradores-tab"><p>test</p></div>
					<div class="tab-pane fade" id="ventas" role="tabpanel" aria-labelledby="ventas-tab"><p>test 2</p></div>
					<div class="tab-pane fade" id="incremento" role="tabpanel" aria-labelledby="incremento-tab"><p>test 3</p></div>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary"
					data-dismiss="modal">{{ trans("admin-app.button.close") }}</button>
			</div>

		</div>
	</div>
</div>
