window.addEventListener('load', function () {

	const cedenteAjax = document.querySelector('input[name="cedente_ajax"]')?.value;

	if (cedenteAjax) {

		getCedenteInfo(cedenteAjax)
			.then(cedenteInfo => {
				const tableData = tableCedenteFormatData(cedenteInfo);
				initOrReloadTableCedente(tableData);
			})
			.catch(console.log);
	}

});

const getCedenteInfo = async (route) => {

	const res = await fetch(route, {
		method: 'GET',
		//body: new FormData(biReload)
	});

	let resJson = await res.json();
	return resJson;
}

function tableCedenteFormatData(cedenteInfo) {

	console.log({cedenteInfo});

	return cedenteInfo.hojas_cesion_cabecera.map(hojas_cesion => {

		const lotesEnSubasta = hojas_cesion.hoja_cesion_lineas.filter(linea => linea.ref_asigl0);
		const lotesVendidos =  lotesEnSubasta.filter(lote => lote.implic_hces1 != '0' && lote.cerrado_asigl0 === 'S');
		const percentVendidos = (lotesVendidos.length * 100)/lotesEnSubasta.length;
		const salida_total = lotesEnSubasta.reduce((acc, current) => {
			return acc + parseInt(current.impsalhces_asigl0);
		}, 0);

		const salida_media = salida_total / lotesEnSubasta.length;
		const precioAdjudicacion = lotesVendidos.reduce((acc, lot) => acc + parseInt(lot.implic_hces1), 0);
		const adjudicacionAvg = precioAdjudicacion / lotesEnSubasta.length;

		return {
			num_hoja: hojas_cesion.num_hces0,
			lotes: hojas_cesion.hoja_cesion_lineas.length,
			lotes_subastasdos: lotesEnSubasta.length,
			lotes_sin_subasta: hojas_cesion.hoja_cesion_lineas.filter(lineas => !lineas.ref_asigl0).length,
			lotes_vendidos: lotesVendidos.length,
			porcentaje_vendidos: percentVendidos,
			salida_total: {
				display: labelUtils.numberFormat(salida_total) + ' €',
				value: salida_total
			},
			salida_media: {
				display: labelUtils.numberFormat(salida_media) + ' €',
				value: salida_media
			},
			adjudicacion_total: {
				display: labelUtils.numberFormat(precioAdjudicacion) + ' €',
				value: precioAdjudicacion
			},
			adjudicacion_avg: {
				display: labelUtils.numberFormat(adjudicacionAvg) + ' €',
				value: adjudicacionAvg
			},
		}
	});

	/*	ejemplo
		return {
			name: names[index],
			sections: value,
			totalLots,
			totalAwardValue,
			num_lots: labelUtils.numberFormat(lots.length),
			starting_price: {
				display: labelUtils.numberFormat(startingPrice) + ' €',
				value: startingPrice
			},
			lots_awarded: labelUtils.numberFormat(lotsAwarded.length),
			awarded_value: {
				display: labelUtils.numberFormat(awardedValue) + ' €',
				value: awardedValue
			},
			revaluation: labelUtils.numberFormat(revaluation) + ' %',
			increment: labelUtils.numberFormat(increment) + ' %',
			weight_lots: {
				display: labelUtils.numberFormat(weightLots) + ' %',
				value: weightLots
			},
			weight_award_value: {
				display: labelUtils.numberFormat(weightAwardValue) + ' %',
				value: weightAwardValue
			},
			starting_awarded_price: startingAwardedPrice
		} */

}

function initOrReloadTableCedente(dataForTable){


	var table;
	if ($.fn.dataTable.isDataTable('#tableBiCedente')) {
		table = $('#tableBiCedente').DataTable();
		table.clear();
		table.rows.add(dataForTable).draw();
		return
	}


	table = $('#tableBiCedente').DataTable( {
		paging: false,
        //ordering: true,
        info: false,
		searching: false,
		//order: [[ 2, "desc" ]],
        data: dataForTable,
        columns: [
			{
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
			{data: 'num_hoja'},
			{data: 'lotes'},
			{data: 'lotes_subastasdos'},
			{data: 'lotes_sin_subasta'},
			{data: 'lotes_vendidos'},
			{data: 'porcentaje_vendidos'},
			{data: 'salida_total', render: {_: 'display', sort: 'value'}},
			{data: 'salida_media', render: {_: 'display', sort: 'value'}},
			{data: 'adjudicacion_total', render: {_: 'display', sort: 'value'}},
			{data: 'adjudicacion_avg', render: {_: 'display', sort: 'value'}},
			/* {data: 'num_lots'},
			{data: 'starting_price', render: {_: 'display', sort: 'value'}},
			{data: 'lots_awarded'},
			{data: 'awarded_value', render: {_: 'display', sort: 'value'}},
			{data: 'revaluation'},
			{data: 'increment'},
			{data: 'weight_lots', render: {_: 'display', sort: 'value'}},
			{data: 'weight_award_value', render: {_: 'display', sort: 'value'}}, */
        ],
		footerCallback: function ( row, data, start, end, display ) {
			var api = this.api(), data;

			const totalStartigPrice = api.column(7).data().map(({value}) => value).reduce(labelUtils.sumArrayValues, 0);
			//const totalStartigAvg = api.column(7).data().map(({value}) => value).reduce(labelUtils.sumArrayValues, 0);
			const totalAwardPrice = api.column(8).data().map(({value}) => value).reduce(labelUtils.sumArrayValues, 0);

			/** data == dataForTable */
			$( api.column( 1 ).footer() ).html(labelUtils.numberFormat(end));
			$( api.column( 2 ).footer() ).html(labelUtils.numberFormat(api.column(2).data().reduce(labelUtils.sumArrayValues, 0)));
			$( api.column( 3 ).footer() ).html(labelUtils.numberFormat(api.column(3).data().reduce(labelUtils.sumArrayValues, 0)));
			$( api.column( 4 ).footer() ).html(labelUtils.numberFormat(api.column(4).data().reduce(labelUtils.sumArrayValues, 0)));
			$( api.column( 5 ).footer() ).html(labelUtils.numberFormat(api.column(5).data().reduce(labelUtils.sumArrayValues, 0)));
			$( api.column( 6 ).footer() ).html(labelUtils.numberFormat(api.column(6).data().reduce(labelUtils.sumArrayValues, 0)));
			$( api.column( 7 ).footer() ).html(labelUtils.numberFormat(totalStartigPrice) + ' €');
			$( api.column( 8 ).footer() ).html('-');
			$( api.column( 9 ).footer() ).html(labelUtils.numberFormat(totalAwardPrice) + ' €');
			$( api.column( 10 ).footer() ).html('-');


			/* const totalStartingAwarded = dataForTable.map(({starting_awarded_price}) => starting_awarded_price).reduce(labelUtils.sumArrayValues, 0);

			const totalLots = api.column(2).data().reduce(labelUtils.sumArrayValues, 0);
			const totalStartigPrice = api.column(3).data().map(({value}) => value).reduce(labelUtils.sumArrayValues, 0);
			const totalLotsAwarded = api.column(4).data().reduce(labelUtils.sumArrayValues, 0);
			const totalAwardedValue = api.column(5).data().map(({value}) => value).reduce(labelUtils.sumArrayValues, 0);

			const totalRevaluation = (totalAwardedValue /  totalStartingAwarded) * 100;
			const totalIncrement = (totalAwardedValue - totalStartingAwarded) / totalStartingAwarded * 100;

			const totalWeightLots = api.column(8).data().map(({value}) => value).reduce(labelUtils.sumArrayValues, 0);
			const totalWeightAward = api.column(9).data().map(({value}) => value).reduce(labelUtils.sumArrayValues, 0);

			// Update footer
            $( api.column( 2 ).footer() ).html(labelUtils.numberFormat(totalLots));
			$( api.column( 3 ).footer() ).html(labelUtils.numberFormat(totalStartigPrice) + ' €');
			$( api.column( 4 ).footer() ).html(labelUtils.numberFormat(totalLotsAwarded));
			$( api.column( 5 ).footer() ).html(labelUtils.numberFormat(totalAwardedValue) + ' €');
			$( api.column( 6 ).footer() ).html(labelUtils.numberFormat(totalRevaluation) + ' %');
			$( api.column( 7 ).footer() ).html(labelUtils.numberFormat(totalIncrement) + ' %');
			$( api.column( 8 ).footer() ).html(labelUtils.numberFormat(totalWeightLots) + ' %');
			$( api.column( 9 ).footer() ).html(labelUtils.numberFormat(totalWeightAward) + ' %'); */

		}
    });


	/* $('#example tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );

        if ( row.child.isShown() ) {
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            row.child(sectionsRows(row.data())).show();
            tr.addClass('shown');
			let table = row.child()[0].querySelector('table');
			$(table).dataTable({
				paging: false,
				ordering: true,
				info: false,
				searching: false
			});
        }
    } ); */
}

/* Formatting function for row details - modify as you need */
function sectionsRowsCedente(data) {

	let html = '<table class="table table-striped" style="width: 100%;">';
	html += '<thead style="font-size: 11px"><tr><th>Sub Familia</th><th>Nº Lotes</th><th>€ Salida</th><th>Lotes Adjudicados</th><th>€ Adjudicado</th><th>% Revalorización</th><th>% Incremento</th><th>% Peso</th><th>% Peso €</th></tr></thead>';
	html += '<tbody>';
	data.sections.map(section => {

		const lots = section.lotes;
		const lotsAwarded = lots.filter(lotIsAwarded);

		const startingPrice = lots.map(lot => lot.impsalhces_asigl0).reduce(labelUtils.sumArrayValues);
		const startingAwardedPrice = lotsAwarded.map(lot => lot.impsalhces_asigl0).reduce(labelUtils.sumArrayValues, 0);

		const awardedValue = lotsAwarded.map(lot => parseInt(lot.implic_hces1)).reduce(labelUtils.sumArrayValues, 0);
		const revaluation = (awardedValue / startingAwardedPrice) * 100;
		const increment = (awardedValue - startingAwardedPrice) / startingAwardedPrice * 100;

		const weightLots = (lots.length * 100) / data.totalLots;
		const weightAwardValue = (awardedValue * 100) / data.totalAwardValue;

		html += '<tr>'+
		`<td>${section.des_sec}</td>`+
		`<td>${labelUtils.numberFormat(lots.length)}</td>`+
		`<td>${labelUtils.numberFormat(startingPrice)} €</td>`+
		`<td>${labelUtils.numberFormat(lotsAwarded.length)}</td>`+
		`<td>${labelUtils.numberFormat(awardedValue)} €</td>`+
		`<td>${labelUtils.numberFormat(revaluation)} %</td>`+
		`<td>${labelUtils.numberFormat(increment)} %</td>`+
		`<td>${labelUtils.numberFormat(weightLots)} %</td>`+
		`<td>${labelUtils.numberFormat(weightAwardValue)} %</td>`+
		'</tr>';

	});
	html += '</tbody>';
	html += '</table>';
	return html;
}
