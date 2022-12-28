<div class="modal fade" id="deleteSessionFileModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id=" modalLabel"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<p>{{ trans("admin-app.title.sure_delete") }}</p>
			</div>

			<div class="modal-footer">

				<form id="formDeleteSessionFile" action="{{ $routeToDelete }}"
					data-action="{{ $routeToDelete }}" method="POST">
					@csrf
					<input id="auction" name="auction" type="hidden" value="">
					<input id="reference" name="reference" type="hidden" value="">
					<input id="idFile" name="idFile" type="hidden" value="">
					<button type="button" class="btn btn-secondary"
						data-dismiss="modal">{{ trans("admin-app.button.close") }}</button>
					<button type="submit" form="formDeleteSessionFile" class="btn btn-danger">{{ trans("admin-app.button.delete") }}</button>
				</form>
			</div>

		</div>
	</div>
</div>
