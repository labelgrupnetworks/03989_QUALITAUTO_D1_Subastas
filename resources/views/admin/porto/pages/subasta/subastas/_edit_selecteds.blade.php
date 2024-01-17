@php
	use App\Models\V5\FgSub;
	$formularioFiles = [
	    [
			'id' => 'auc_options',
			'title' => trans("admin-app.title.options"),
			'class' => '',
	    	'inputs' => [
				'tipo_sub' => FormLib::Select('tipo_sub_select', 0, 0, (new FgSub())->getTipoSubTypes()),
	    		'subc_sub' => FormLib::Select('subc_sub_select', 0, 0, (new FgSub())->getSubcSubTypes()),
			]
		],
	    [
			'id' => 'auc_dates',
			'title' => trans("admin-app.title.date_and_hour_auctions"),
			'class' => '',
	    	'inputs' => [
				'dfec_sub' => FormLib::Date('dfec_sub_select'),
				'dhora_sub' => FormLib::Hour('dhora_sub_select'),
				'hfec_sub' => FormLib::Date('hfec_sub_select'),
				'hhora_sub' => FormLib::Hour('hhora_sub_select'),
			]
		],
	    [
			'id' => 'auc_dates_orlic',
			'title' => trans("admin-app.title.date_and_hour_orders"),
			'class' => 'd-none',
	    	'inputs' => [
				'dfecorlic_sub' => FormLib::Date('dfecorlic_sub_select'),
				'dhoraorlic_sub' => FormLib::Hour('dhoraorlic_sub_select'),
				'hfecorlic_sub' => FormLib::Date('hfecorlic_sub_select'),
				'hhoraorlic_sub' => FormLib::Hour('hhoraorlic_sub_select'),
			]
		],
	];
@endphp

<div class="modal fade" id="editMultpleAuctionsModal" role="dialog" aria-hidden="true" tabindex="-1">
	<div class="modal-dialog modal-lg" role="document">

		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title">{{ trans("admin-app.title.subasta_mass_update") }}</h5>
				<button class="close" data-dismiss="modal" type="button" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body" id="modal-create-body">
				<form id="edit_multiple_auctions" name="edit_multple_auctions" action="{{ route('subastas.update_selections') }}"
					method="POST">
					@if (config('app.admin_upload_first_session', 1))
						<input type="hidden" name="upload_first_session" value="1"
							data-question="¿Desea actualizar los datos de la primera sesión?">
					@endif
					<div class="row">
						@include('admin::pages.subasta.subastas._form_selecteds', ['formulario' => $formularioFiles])
					</div>
				</form>
			</div>

			<div class="modal-footer">
				<button class="btn btn-primary" form="edit_multiple_auctions" type="submit">
					{{ trans("admin-app.button.save") }}
				</button>
				<button class="btn btn-secondary" data-dismiss="modal" type="button">{{ trans("admin-app.button.close") }}</button>
			</div>

		</div>

	</div>
</div>
