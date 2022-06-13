<div class="row">

	<div class="col-xs-12 col-md-8">

		<div class="row">
			<div class="col-xs-4 col-sm-4 cir-pro-wra">
				<div id="cp-sales" role="button" title="{{ trans(\Config::get('app.theme').'-app.lot_list.sold_lots') }}" data-toggle="popover" data-placement="auto right"
				data-trigger="hover" data-content=""></div>
				<p>{{ trans(\Config::get('app.theme').'-app.lot_list.sold_lots') }}</p>
			</div>
			<div class="col-xs-4 cir-pro-wra">
				<div id="cp-revalorizacion" role="button" title="{{ trans(\Config::get('app.theme').'-app.user_panel.revaluation') }}" data-toggle="popover"
				data-trigger="hover" data-placement="auto bottom" data-content=""></div>
				<p>{{ trans(\Config::get('app.theme').'-app.user_panel.revaluation') }}</p>
			</div>
			<div class="col-xs-4 cir-pro-wra">
				<div id="cp-pujas" role="button" title="{{ trans(\Config::get('app.theme').'-app.user_panel.bids_per_lot') }}" data-toggle="popover" data-placement="auto right"
				data-trigger="hover" data-content=""></div>
				<p>{{ trans(\Config::get('app.theme').'-app.user_panel.bids_per_lot') }}</p>
			</div>
			<div class="col-xs-4 mt-1 cir-pro-wra">
				<div id="cp-consignados" role="button" title="{{ trans(\Config::get('app.theme').'-app.user_panel.consigned_lots') }}" data-toggle="popover"
				data-trigger="hover" data-placement="auto right" data-content=""></div>
				<p>{{ trans(\Config::get('app.theme').'-app.user_panel.consigned_lots') }}</p>
			</div>
			<div class="col-xs-4 mt-1 cir-pro-wra">
				<div id="cp-adjudicado" role="button" title="{{ trans(\Config::get('app.theme').'-app.user_panel.awarded_amount') }}" data-toggle="popover" data-placement="auto bottom"
				data-trigger="hover" data-content=""></div>
				<p>{{ trans(\Config::get('app.theme').'-app.user_panel.awarded_amount') }}</p>
			</div>
			<div class="col-xs-4 mt-1 cir-pro-wra">
				<div id="cp-pujadores" role="button" title="{{ trans(\Config::get('app.theme').'-app.user_panel.bidders_per_lot') }}" data-toggle="popover" data-placement="auto right"
				data-trigger="hover" data-content=""></div>
				<p>{{ trans(\Config::get('app.theme').'-app.user_panel.bidders_per_lot') }}</p>
			</div>
		</div>

	</div>

	<div class="col-xs-12 col-md-4">

		<div class="auctions-estadistics-table">
			<table class="table table-striped text-center">
				<thead>
					<tr>
						<th>
							<div class="btn-group w-100" role="group">

								<select id="auctions-select-multiple" multiple="multiple" name="">

									<option data-type="all" value="">{{ trans(\Config::get('app.theme').'-app.lot.see-all') }}</option>

									<optgroup label="{{ trans("$theme-app.user_panel.for_time") }}" class="time-group">
										<option data-type="time" value="1">30 {{ trans("$theme-app.msg_neutral.days") }}</option>
										<option data-type="time" value="3">3 {{ trans("$theme-app.msg_neutral.months") }}</option>
										<option selected="selected" data-type="time" value="6">6 {{ trans("$theme-app.msg_neutral.months") }}</option>
										<option data-type="time" value="12">12 {{ trans("$theme-app.msg_neutral.months") }}</option>
									</optgroup>

									<optgroup label="{{ trans("$theme-app.user_panel.for_auction") }}" class="auction-group">
										@foreach ($subastasActivas as $subasta)
											<option data-type="auction" value="{{$subasta->sub_asigl0}}">{{$subasta->name}}</option>
										@endforeach
									</optgroup>

								</select>

							</div>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>
							<span id="panel-subasta">
								{{ $infoSales->auctions }}
							</span>
							{{ trans(\Config::get('app.theme').'-app.subastas.auctions') }}
						</th>
					</tr>
					<tr>
						<th><span
								id="panel-adjudicacion">{{ \Tools::moneyFormat($infoSales->sumPrecioAdjudicacionProp) }}</span>
								{{ trans(\Config::get('app.theme').'-app.user_panel.EUR') }} {{ trans(\Config::get('app.theme').'-app.sheet_tr.awarded') }}</th>
					</tr>
					<tr>
						<th><span id="panel-lotes">{{ $infoSales->lotesProp }}</span> {{ trans(\Config::get('app.theme').'-app.user_panel.consigned_lots') }}</th>
					</tr>
					<tr>
						<th><span id="panel-vendidos">{{ $infoSales->lotesVendidosProp }}</span> {{ trans(\Config::get('app.theme').'-app.lot_list.sold_lots') }}</th>
					</tr>
					<tr>
						<th><span id="panel-pujas">{{ $infoSales->pujasProp }}</span> {{ trans(\Config::get('app.theme').'-app.sheet_tr.bids') }}</th>
					</tr>
					<tr>
						<th><span id="panel-pujadores">{{ $infoSales->pujadoresProp }}</span> {{ trans(\Config::get('app.theme').'-app.user_panel.bidders') }}</th>
					</tr>
				</tbody>
			</table>


		</div>

	</div>

</div>

<div class="modal fade loading-modal-lg" id="loading-modal" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-sm text-center spinner-loading-wrapper">
        <div>
            <span class="fa fa-spinner fa-spin fa-3x spinner-loading-modal"></span>
        </div>
		<span style="font-weight: 600">{{ trans("$theme-app.user_panel.data_is_loading") }}</span>
    </div>
</div>

<script src="{{ URL::asset('vendor\progressbar.js\dist\progressbar.js') }}"></script>
<script>

var infoSales = @json($infoSales);
var subastasActivas = @json($subastasActivas->pluck('sub_asigl0')->toArray());

var spinnerSales = new ProgressBar.Circle('#cp-sales', circleOptions(100, true, '%'));
var spinnerRevalorizacion = new ProgressBar.Circle('#cp-revalorizacion', circleOptions(100, true, '%'));
var spinnerPujas = new ProgressBar.Circle('#cp-pujas', circleOptions( (infoSales.pujas / infoSales.lotes), false, ''));
var spinnerConsignados = new ProgressBar.Circle('#cp-consignados', circleOptions(infoSales.lotes, true, ''));
var spinnerAdjudicado = new ProgressBar.Circle('#cp-adjudicado', circleOptions(infoSales.sumPrecioSalidaProp, true, '€'));
var spinnerPujadores = new ProgressBar.Circle('#cp-pujadores', circleOptions( (infoSales.pujadores / infoSales.lotes), false, ''));

var spinners = [spinnerSales, spinnerRevalorizacion, spinnerPujas, spinnerConsignados, spinnerAdjudicado, spinnerPujadores];
for (const spinner of spinners) {
	//spinner.text.style.fontSize = '4rem';
	spinner.text.style.fontWeight = 'bold';
}

reloadSpinners(infoSales);


$( document ).ready(function() {

	// Initialize tooltip & popover component
	$('[data-toggle="tooltip"]').tooltip();
	$('[data-toggle="popover"]').popover();

	$('#auctions-select-multiple').multiselect({
				templates: {
					button: '<a id="" class="multiselect dropdown-toggle btn-block btn-info dropdown-toggle d-flex align-items-center" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span style="flex:1" class="multiselect-selected-text"></span><i class="fa fa-caret-right"aria-hidden="true"></i></a>'
				},
				onChange: selectSalesChange,
				//,buttonClass: 'btn-info btn-block'
				//,includeSelectAllOption: true
				//,selectAllText: 'Check all!'
				buttonText: selectButtonText,
				maxHeight: 400,
	});

});

function selectSalesChange(option, checked, select) {
	//checked: true or false
	//alert(option.length + ' options ' + (checked ? 'selected' : 'deselected'));
	let typeChecker = option.data('type');
	refreshSelects(this, typeChecker, option.attr('value'));

	let selections = this.getSelected().map((i, input) => input.value).toArray();
	if(selections.length == 0){
		this.select($('[data-type=all]').map((indice, input) => input.value).toArray());
	}

	//Si seleccionamos todas buscamos todas las subastas activas
	if(typeChecker == 'all' || selections.length == 0){
		selections = subastasActivas;
	}

	let data = {
		'_token': '{{ csrf_token() }}',
		'selections': selections,
		'type': typeChecker
	}

	$.ajax({
		type: "POST",
		url: "{{ route('panel.salesInfo', ['lang' => Config::get('app.locale')]) }}",
		data: data,
		beforeSend: function(){
			loadingSpinners();
			$('#loading-modal').modal('show');
		},
		success: function(response){
			reloadSpinners(response);
			reloadPanel(response, Array.isArray(selections));
			$('#panel-payment-finish').empty();
			$('#panel-payment-finish').html(response.factura);
			$('#loading-modal').modal('hide');
		},
		error: function(error){
			$('#loading-modal').modal('hide');
		}
	});
}

function refreshSelects(multiselect, typeChecker, actualValue){
	switch (typeChecker) {
		case 'time':
			multiselect.deselect($('[data-type=auction]').map((indice, input) => input.value).toArray());

			multiselect.deselect($('[data-type=all]').map((indice, input) => input.value).toArray());

			multiselect.deselect($('[data-type=time]').map((indice, input) => input.value)
				.toArray().filter(value => actualValue != value)
			);

			break;

		case 'auction':
			multiselect.deselect($('[data-type=time]').map((indice, input) => input.value ).toArray());

			multiselect.deselect($('[data-type=all]').map((indice, input) => input.value).toArray());
			break;

		default:
			multiselect.deselect($('[data-type=auction]').map((indice, input) => input.value).toArray());

			multiselect.deselect($('[data-type=time]').map((indice, input) => input.value ).toArray());
			break;
	}
}

function selectButtonText(options, select){
	if (options.length === 0) {
		return 'Sin selección';
	}
	else if (options.length > 2) {
		return `${options.length} {{ trans(\Config::get('app.theme').'-app.user_panel.options') }}`;
	}
	else {
		var labels = [];

		options.each(function() {
			if ($(this).attr('label') !== undefined) {
				labels.push($(this).attr('label'));
			}
			else {
				labels.push($(this).html());
			}
		});

		return labels.join(', ') + '';
	}
}


function circleOptions(total, round, simbol){
	return {
		color: '#000', //color texto
		strokeWidth: 6, //porcentaje lienzo svg ¿?
		trailColor: '#b79d81', //Color del trazo no relleno
		trailWidth: 8, //Anho del trazo no relleno
		easing: 'easeInOut',
		duration: 1400,
		text: {
			autoStyleContainer: false,
		},
		from: { color: '#844c0c', width: 8 }, //de que color y ancho
		to: { color: '#844c0c', width: 8 }, //a que color y ancho
		step: circleStep(total, round, simbol)
	}
}

function circleStep(total, round, simbol){

	return function(state, circle) {
		circle.path.setAttribute('stroke', state.color);
		circle.path.setAttribute('stroke-width', state.width);
		var value = 0;

		if(round){
			value = Math.round(circle.value() * total);
		}
		else{
			value = (circle.value() * total).toFixed(1);
		}

		if(isNaN(value) || value == 'NaN'){
			value = 0;
		}
		if(simbol == '€'){
			circle.setText(numberFormat(value) + simbol);
		}
		else{
			circle.setText(value + simbol);
		}
	}

}

function reloadSpinners(infoSales){

	let vendidos = infoSales.lotesVendidosProp / infoSales.lotesProp;
	let revalorizacion = infoSales.sumPrecioAdjudicacionProp / infoSales.sumPrecioSalidaProp;
	let pujas = (infoSales.pujasProp / infoSales.lotesProp) / (infoSales.pujas / infoSales.lotes);
	let consignados = infoSales.lotesProp / infoSales.lotes;
	let adjudicados = infoSales.sumPrecioAdjudicacionProp / infoSales.sumPrecioSalidaProp;
	let pujadores = (infoSales.pujadoresProp / infoSales.lotesProp) / (infoSales.pujadores / infoSales.lotes);

	spinnerSales.animate( checkNan(vendidos), {step: circleStep(100, true, '%')});
	spinnerRevalorizacion.animate( checkNan(revalorizacion), {step: circleStep(100, true, '%')} );
	spinnerPujas.animate( checkNan(pujas), {step: circleStep((infoSales.pujas / infoSales.lotes), false, '') } );
	spinnerConsignados.animate( checkNan(consignados), {step: circleStep(infoSales.lotes, true, '')} );
	spinnerAdjudicado.animate(checkNan(adjudicados), {step: circleStep(infoSales.sumPrecioSalidaProp, true, '€')} );
	spinnerPujadores.animate( checkNan(pujadores), {step: circleStep((infoSales.pujadores / infoSales.lotes), false, '')} );

	reloadPopovers(infoSales);
}

function reloadPanel(infoSales){

	$('#panel-adjudicacion').text(numberFormat(infoSales.sumPrecioAdjudicacionProp));
	$('#panel-lotes').text(infoSales.lotesProp);
	$('#panel-vendidos').text(infoSales.lotesVendidosProp);
	$('#panel-pujas').text(infoSales.pujasProp);
	$('#panel-pujadores').text(infoSales.pujadoresProp);
	$('#panel-subasta').text(infoSales.auctions);

}

function reloadPopovers(infoSales){

	c = infoSales;

	let salesSpans = [
		spanElement("{{ trans(\Config::get('app.theme').'-app.lot_list.lots') }}", infoSales.lotesProp, '', true),
		spanElement("{{ trans(\Config::get('app.theme').'-app.lot_list.sold_lots') }}", infoSales.lotesVendidosProp, '', true),
		spanElement("{{ trans(\Config::get('app.theme').'-app.lot_list.dont_sold_lots') }}", infoSales.lotesProp - infoSales.lotesVendidosProp, '', true)
	];

	$('#cp-sales').on('shown.bs.popover', function () {
		popoverContent(this, salesSpans, []);
	});

	//let revalorizacion =  ((infoSales.sumPrecioAdjudicacionProp - infoSales.sumPrecioSalidaProp) / infoSales.sumPrecioSalidaProp) * 100;
	//let revalorizaciónTauler = ((infoSales.sumPrecioAdjudicacion - infoSales.sumPrecioSalida) / infoSales.sumPrecioSalida) * 100;

	let revalorizacion =  ((infoSales.sumPrecioAdjudicacionProp) / infoSales.sumPrecioSalidaProp) * 100;
	let revalorizaciónTauler = ((infoSales.sumPrecioAdjudicacion) / infoSales.sumPrecioSalida) * 100;

	let revalorizacionSpans = [
		spanElement("{{ trans(\Config::get('app.theme').'-app.subastas.price_salida') }}", infoSales.sumPrecioSalidaProp, '€', true),
		spanElement("{{ trans(\Config::get('app.theme').'-app.user_panel.award_price') }}", infoSales.sumPrecioAdjudicacionProp, '€', true)
	];

	let revealorizacionExtras = [
		extraParrafElement(Math.round(revalorizacion) + ' % ' + "{{ trans(\Config::get('app.theme').'-app.user_panel.revaluation') }}")
	];

	$('#cp-revalorizacion').on('shown.bs.popover', function () {
		popoverContent(this, revalorizacionSpans, revealorizacionExtras);
	});

	let pujasSpans = [
		spanElement("{{ trans(\Config::get('app.theme').'-app.user_panel.average_in_tauler') }}", infoSales.pujas / infoSales.lotes, '', false),
		spanElement("{{ trans(\Config::get('app.theme').'-app.user_panel.own_average') }}", infoSales.pujasProp / infoSales.lotesProp, '', false)
	];

	$('#cp-pujas').on('shown.bs.popover', function () {
		popoverContent(this, pujasSpans, []);
	});

	let consignadosSpans = [
		spanElement("{{ trans(\Config::get('app.theme').'-app.user_panel.lots_in_tauler') }}", infoSales.lotes, '', true),
		spanElement("{{ trans(\Config::get('app.theme').'-app.user_panel.your_lots') }}", infoSales.lotesProp, '', true)
	];

	let consignadosExtras = [
		extraParrafElement(Math.round((infoSales.lotesProp / infoSales.lotes) * 100) + ' % ' + "{{ trans(\Config::get('app.theme').'-app.user_panel.consigned') }}")
	];

	$('#cp-consignados').on('shown.bs.popover', function () {
		popoverContent(this, consignadosSpans, consignadosExtras);
	});

	let adjudicadosSpans = [
		spanElement("{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}", infoSales.sumPrecioSalidaProp, '€', true),
		spanElement("{{ trans(\Config::get('app.theme').'-app.sheet_tr.not_awarded') }}", infoSales.sumPrecioNoAdjudicacionProp, '€', true),
		spanElement("{{ trans(\Config::get('app.theme').'-app.user_panel.award_price') }}", infoSales.sumPrecioAdjudicacionProp, '€', true)
	];

	$('#cp-adjudicado').on('shown.bs.popover', function () {
		popoverContent(this, adjudicadosSpans, []);
	});

	let pujadoresSpans = [
		spanElement("{{ trans(\Config::get('app.theme').'-app.user_panel.average_in_tauler') }}", infoSales.pujadores / infoSales.lotes, '', false),
		spanElement("{{ trans(\Config::get('app.theme').'-app.user_panel.own_average') }}", infoSales.pujadoresProp / infoSales.lotesProp, '', false),
	]

	$('#cp-pujadores').on('shown.bs.popover', function () {
		popoverContent(this, pujadoresSpans, []);
	});

}


function popoverContent(element, spanElements, extraParrafs){

	let text = `<p>`;

	for (const span of spanElements) {
		text += `${span}<br>`;
	}

	text += `</p>`;

	for (const extra of extraParrafs) {
		text += `${extra}`;
	}



	element.nextElementSibling.querySelector(".popover-content").innerHTML = text;

	/*
	$('body').one('click', function() {
		$(element).popover('hide');
	});
	*/
	return;
}

function spanElement(text, value, simbol, round){

	value = round ? Math.round(value) : value.toFixed(1);

	value = (isNaN(value) || value == 'NaN') ? 0 : value;

	value = (simbol == '€') ? numberFormat(value) : value;

	return `${text}: <span class="text-gold">${value + simbol}</span>`;
}

function extraParrafElement(text){
	return `<p class="text-extra">${text}</p>`;
}

function loadingSpinners(){
	for (const spinner of spinners) {
		spinner.animate(0);
	}
}

function checkNan(value){
	return isNaN(value) ? 0 : value;
}

function numberFormat(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
</script>
