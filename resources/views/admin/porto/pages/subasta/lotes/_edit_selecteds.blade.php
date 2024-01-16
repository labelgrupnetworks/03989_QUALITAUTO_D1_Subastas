@php
	use App\Models\V5\FgAsigl0;
	use App\Models\V5\FxSec;

	$boolValues = ['N' => 'No', 'S' => 'Si'];

	$formulario = [
	    [
			'id' => 'lot_info',
			'title' => 'Info',
			'class' => '',
	    	'inputs' => [
				'owner' => FormLib::Select2WithAjax('owner_select', 0, '', '', config('app.useProviders', 0) ? route('provider.list') : route('client.list'), trans('admin-app.placeholder.owner'), 'editMultpleLotsModal'),
	    		'idsubcategory' => FormLib::select("idsubcategory_select", 0, '', FxSec::GetActiveFxSec()),
	    		"withstock" => FormLib::Select('withstock_select', 0, '', $boolValues),
			]
		],
	    [
			'id' => 'lot_states',
			'title' => 'Estados',
			'class' => '',
	    	'inputs' => [
				'highlight' => FormLib::Select('highlight_select', 0, '', $boolValues),
				'retired' => FormLib::Select('retired_select', 0, '', $boolValues),
				'close' => FormLib::Select('close_select', 0, '', $boolValues),
				'soldprice' => FormLib::Select('soldprice_select', 0, '', $boolValues),
				'buyoption' => FormLib::Select('buyoption_select', 0, '', $boolValues),
				'hidden' => FormLib::Select('hidden_select', 0, '', $boolValues),
				'disclaimed' => FormLib::Select('disclaimed_select', 0, '', $boolValues),
			]
		],
	    [
			'id' => 'lot_dates',
			'title' => 'Fechas',
			'class' => '',
	    	'inputs' => [
				'startdate' => FormLib::Date("startdate_select", 0),
				'starthour' => FormLib::Hour("starthour_select", 0),
				'enddate' => FormLib::Date("enddate_select", 0),
				'endhour' => FormLib::Hour("endhour_select", 0)
			]
		],
	    [
			'id' => 'lot_prices',
			'title' => 'Precios',
			'class' => '',
	    	'inputs' => [
				'biddercommission' => FormLib::Int('biddercommission_select', 0, ''),
				'ownercommission' => FormLib::Int('ownercommission_select', 0, '')
			]
		],
	];
@endphp

<div class="modal fade" id="editMultpleLotsModal" role="dialog" aria-hidden="true" tabindex="-1">
	<div class="modal-dialog modal-lg" role="document">

		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title">{{ trans("admin-app.title.lot_mass_update") }}</h5>
				<button class="close" data-dismiss="modal" type="button" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body" id="modal-create-body">
				<form id="edit_multiple_lots" name="edit_multiple_lots" action="{{ route('subastas.lotes.update_selections') }}"
					method="POST">
					@if (config('app.admin_upload_first_session', 1))
						<input type="hidden" name="upload_first_session" value="1"
							data-question="¿Desea actualizar los datos de la primera sesión?">
					@endif
					<div class="row">
						@include('admin::pages.subasta.lotes._form_selecteds', ['formulario' => $formulario])
					</div>
				</form>
			</div>

			<div class="modal-footer">
				<button class="btn btn-primary" form="edit_multiple_lots" type="submit">
					{{ trans("admin-app.button.save") }}
				</button>
				<button class="btn btn-secondary" data-dismiss="modal" type="button">{{ trans("admin-app.button.close") }}</button>
			</div>

		</div>

	</div>
</div>
