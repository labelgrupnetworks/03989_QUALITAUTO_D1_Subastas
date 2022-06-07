const labelUtils = new LabelUtils();
let adjudicacionesChart;
let auctionForMontChart;
let awardsValuesForMonthChart;
let awardsForSectionChart;
let toogleChart = true;
let auctionsCods;

window.addEventListener('load', function () {

	const sidebarRightClicked = document.querySelector(".sidebar-right-toggle");
	const sidebarRight = document.querySelector("#sidebar-right-bi");
	const html = document.querySelector("html");

	sidebarRightClicked.addEventListener("click", () => {
		sidebarRight.classList.toggle("opened");
		html.classList.add("sidebar-left-collapsed");
	});

	const clientsData = document.querySelector('input[name="clientesForDate"]')?.value;
	const auctionsAjax = document.querySelector('input[name="auctions_ajax"]')?.value;

	if (clientsData != undefined) {
		const clientesForDate = JSON.parse(clientsData);
		usersRegisterChart(clientesForDate);
	}

	if (auctionsAjax != undefined) {

		initCharts();
		initMultiSelects();

		biReload.addEventListener('submit', e => {
			searchInfoAndReloadChart(e);
			getAwardsForSection(e);
		});
		biReload.querySelector('button[type="submit"]').click();


	}

});

function initCharts() {
	newAdjuducacionesPorSubastaChart();
	auctionsForMonhtInitChart();
	awardsValuesForMonthInitChart();
	awardsForSectionInitChart();
}

function initMultiSelects() {

	//const multiSelect = $('[name="auctions[]"]');
	const multiSelect = $('select');
	multiSelect.multiselect({
		buttonWidth : '100%',
		maxHeight: 400,
		enableFiltering: true
		/* includeSelectAllOption: true,
        selectAllNumber: false */
		/* templates: {
			li: '<a class"multiselect-option dropdown-item"><a><label style="display:inline;"></label><input type="text" /></a></a>',
			button: '<a id="" class="multiselect dropdown-toggle btn-block btn-info dropdown-toggle d-flex align-items-center" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span style="flex:1" class="multiselect-selected-text"></span><i class="fa fa-caret-right"aria-hidden="true"></i></a>'
		}, */
		/* onChange: function(option, checked, select){

			const selections = this.getSelected().map((i, input) => input.value).toArray();
			reloadCharts(subastas.filter(auction => selections.includes(auction.id_auc_sessions)), info);

		} */
	});
	//multiSelect.multiselect('dataprovider', data);

	$('.multiselect-filter i').addClass('fa');
	$('.multiselect-container .multiselect-filter', $('select').parent()).css({
		'position': 'sticky', 'top': '0px', 'z-index': 1,
	});
	return true;
}

function changeOptionsMultiselect(element, data){
	element.multiselect('dataprovider', data);
}

function refreshAll(){
	$('select').multiselect('deselectAll', true);
	$('select[name="years[]"]').multiselect('select', [new Date().getFullYear().toString()]);
	biReload.querySelector('button[type="submit"]').click();
}

async function getAwardsForSection(e) {

	try {
		const allSections = await getCategoryAwardsInfo();

		//Multiselect de familias
		/* const dataSelectOrtsec0 = allSections.reduce( (acumulador, section) => {
			if(acumulador.filter(label => label.value == section.lin_ortsec0).length > 0) {
				return [...acumulador];
			}
			return [...acumulador, {label: section.des_ortsec0, value: section.lin_ortsec0, selected: false}]
		}, []);

		changeOptionsMultiselect($('[name="lin_ortsec0[]"]'), dataSelectOrtsec0); */

		const tableData = tableFormatData(allSections);
		initOrReloadTable(tableData);


		const sections = allSections.filter(section => section.lotes.filter(lotIsAwarded).length > 0);

		const namesLabels = sections.map(({ des_sec }) => des_sec);
		const lotsAwardedForSection = sections.map(({ lotes }) => lotes.filter(lotIsAwarded).length);

		const lotsForCategory = sections.reduce( (acumulador, section) => {
			return {
				...acumulador,
				[section.des_ortsec0]: (acumulador[section.des_ortsec0] || 0) + section.lotes.filter(lotIsAwarded).length
			}
		}, {});

		const categoriesNames = Object.keys(lotsForCategory);
		const lotsAwardedForCategory = Object.values(lotsForCategory);

		const data = {
			labels: namesLabels,
			datasets: [
				{
					label: 'Test',
					data: lotsAwardedForSection,
					backgroundColor: [
						'#e7b979',
						'#ff7171',
						'#6868ec',
						'#95fab9',
						'#bf9780',
						'#f4fab4',
						'#f7cae7',
					  ],
					hoverOffset: 4
				}
			]
		};

		awardsForSectionChart.reload(data);

		let element = document.getElementById('awardsForSectionChart');
		$(element).off('click');
		$(element).on('click', e => { changeToFamily(categoriesNames, lotsAwardedForCategory, namesLabels, lotsAwardedForSection, e)});

	} catch (error) {
		console.log(error);
	} finally {
		//fin loader
	}

}

function changeToFamily(familiesNames, lotsAwardedForFamilies, sectionNames, lotsAwardedForSection, e){

	const pointClick = awardsForSectionChart.chart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, true);

	if (!pointClick.length > 0) return;

	if(toogleChart){
		awardsForSectionChart.chart.options.plugins.title.text = 'Adjudicaciones por Familia';
		awardsForSectionChart.chart.data.labels = familiesNames;
		awardsForSectionChart.chart.data.datasets[0].data = lotsAwardedForFamilies;
		awardsForSectionChart.chart.update();
	}
	else{
		awardsForSectionChart.chart.options.plugins.title.text = 'Adjudicaciones por Sub Familia';
		awardsForSectionChart.chart.data.labels = sectionNames;
		awardsForSectionChart.chart.data.datasets[0].data = lotsAwardedForSection;
		awardsForSectionChart.chart.update();
	}

	toogleChart = !toogleChart;
}

async function searchInfoAndReloadChart(e) {
	e.preventDefault();

	document.getElementById('loader-page').style.display = 'block';
	try {

		let { subastas, info } = await getAuctionAwardsInfo();
		subastas = Object.values(subastas);

		const selectAuctions = subastas.reduce((acumulador, auction) => [...acumulador, {label: auction.name, value: auction.id_auc_sessions, selected: false}], []);

		reloadCharts(subastas, info);
		changeOptionsMultiselect($('[name="auctions[]"]'), selectAuctions);

	} catch (error) {
		console.log(error);
	} finally {
		document.getElementById('loader-page').style.display = 'none';
	}

}

function reloadCharts(subastas, info){

	let {
		auctionsIds,
		auctionsNames,
		lotsForAuction,
		lotsAwardedForAuction,
		totalValueAwards,
		licitsForAuction
	} = desestructureAuctionInfo(subastas);

	auctionsCods = auctionsIds;

	let totalLots = lotsForAuction.reduce(labelUtils.sumArrayValues, 0);
	let totalAwarded = lotsAwardedForAuction.reduce(labelUtils.sumArrayValues, 0);
	let totalLicits = licitsForAuction.reduce(labelUtils.sumArrayValues, 0);

	reloadBoxsInfo(auctionsNames.length, totalLots, totalAwarded, totalValueAwards, totalLicits);
	const data = adjudicacionesPorSubastaFormatData(auctionsNames, lotsForAuction, lotsAwardedForAuction);
	adjudicacionesChart.reload(data);

	const dataRadar1 = auctionsForMonthFormatData(info.auctionsForMonth);
	auctionForMontChart.reload(dataRadar1);

	const dataRadar2 = awardsForMonthFormatData(info.auctionsForMonth);
	awardsValuesForMonthChart.reload(dataRadar2);

}


/**
 * Test de rendimiento al final del documento
 */
function desestructureAuctionInfo(auctions) {

	const initInfo = {
		auctionsIds: [],
		auctionsNames: [],
		lotsForAuction: [],
		lotsAwardedForAuction: [],
		totalValueAwards: 0,
		licitsForAuction: [],
		auctionsForMonht: []
	};

	return auctions.reduce((acumulador, auction) => {

		let lotsAwarded = auction.lotes.filter(lotIsAwarded);

		return {
			...acumulador,
			auctionsIds: [...acumulador.auctionsIds, auction.id_auc_sessions],
			auctionsNames: [...acumulador.auctionsNames, auction.name],
			lotsForAuction: [...acumulador.lotsForAuction, auction.lotes.length],
			lotsAwardedForAuction: [...acumulador.lotsAwardedForAuction, lotsAwarded.length],
			totalValueAwards: acumulador.totalValueAwards + lotsAwarded.reduce((sum, lot) => sum + parseInt(lot.implic_hces1), 0),
			licitsForAuction: [...acumulador.licitsForAuction, auction.licitadores.length]
		}
	}, initInfo);
}

function reloadBoxsInfo(auctions, lots, awarded, valueAwarded, licits) {

	let boxAuction = document.getElementById('box-auction');
	let boxLots = document.getElementById('box-lots');
	let boxLicits = document.getElementById('box-licits');

	boxAuction.querySelector('.box-title-value').innerHTML = labelUtils.numberFormat(auctions);
	boxLots.querySelector('.box-title-value').innerHTML = labelUtils.numberFormat(lots);
	boxLots.querySelector('.box-subtitle-value').innerHTML = labelUtils.numberFormat(awarded);
	boxLots.querySelector('.box-number-value').innerHTML = labelUtils.numberFormat(valueAwarded) + ' €';
	boxLicits.querySelector('.box-title-value').innerHTML = labelUtils.numberFormat(licits);

	return;
}

function awardsForSectionInitChart() {
	const options = {
		responsive: true,
		plugins: {
			tooltip: {
				callbacks: {
					footer: toolTipPercent
				}
			},
			legend: {
				position: 'top',
				//align: 'start',
				align: 'center',
				labels: {
					font: {
						size: 8
					}
				}

			},
			title: {
				display: true,
				text: 'Adjudicaciones por subFamilia',
			}
		}
	};

	let element = document.getElementById('awardsForSectionChart');
	awardsForSectionChart = new CustomChart(element, options, 'doughnut');
}

function awardsValuesForMonthInitChart() {
	const options = {
		responsive: true,
		scales: {
			r: {
				beginAtZero: true
			}
		},
		plugins: {
			tooltip: {
				callbacks: {
					footer: toolTipPercent
				}
			}
		}
	};

	let element = document.getElementById('awardsValuesForMonthChart');
	awardsValuesForMonthChart = new CustomChart(element, options, 'radar');

	element.addEventListener('click', eventMonthClick);
}

function auctionsForMonhtInitChart() {

	const options = {
		responsive: true,
		scales: {
			r: {
				beginAtZero: true,
			},
			/* myScale: {
				axis: 'r'
			} */
		},
		plugins: {
			tooltip: {
				callbacks: {
					footer: toolTipPercent
				}
			}
		}
	};

	let element = document.getElementById('auctionForMonthChart');
	auctionForMontChart = new CustomChart(element, options, 'radar');

	element.addEventListener('click', eventMonthClick);
}

function awardsForMonthFormatData(auctionsForMonth) {
	const labels = Object.values(labelUtils.months);
	const monthValues = new Array(12).fill(0).map((value, key) => auctionsForMonth[key + 1]?.awardValue || 0);

	const data = {
		labels: labels,
		datasets: [
			{
				//type: 'bar',
				label: 'Adjudicaciones €',
				data: monthValues,
				backgroundColor: '#ff71718c',
				borderColor: 'red'
				//borderWidth: 2,
				//order: 0
			}
		]
	};

	return data;
}

function auctionsForMonthFormatData(auctionsForMonth) {

	//const labels = Object.keys(auctionsForMonth).map((monthValue) => labelUtils.months[monthValue]);
	const labels = Object.values(labelUtils.months);
	const monthValues = new Array(12).fill(0).map((value, key) => auctionsForMonth[key + 1]?.count || 0);

	const data = {
		labels: labels,
		datasets: [
			{
				//type: 'bar',
				label: 'Subastas',
				data: monthValues,
				backgroundColor: '#e7b9797d',
				borderColor: '#ed9c28'
				//borderWidth: 2,
				//order: 0
			}
		]
	};

	return data;
}

function tableFormatData(sections) {

	const families = sections.reduce((acumulador, section) => {
		return {
			...acumulador,
			[section.des_ortsec0]: (acumulador[section.des_ortsec0] || []).concat(section)
		}
	}, {});

	const names = Object.keys(families);
	const values = Object.values(families);

	const totalLots = values.reduce((acumulador, value) => acumulador + value.map(section => section.lotes).flat().length, 0);
	const totalAwardValue = values.reduce((acumulador, value) => acumulador + value.map(section => section.lotes).flat().filter(lotIsAwarded).map(lot => lot.implic_hces1).reduce(labelUtils.sumArrayValues, 0) , 0);

	const dataFormat = values.map((value, index) => {

		const lots = value.map(section => section.lotes).flat();

		const lotsAwarded = lots.filter(lotIsAwarded);

		const startingPrice = lots.map(lot => lot.impsalhces_asigl0).reduce(labelUtils.sumArrayValues);
		const startingAwardedPrice = lotsAwarded.map(lot => lot.impsalhces_asigl0).reduce(labelUtils.sumArrayValues, 0);

		const awardedValue = lotsAwarded.map(lot => parseInt(lot.implic_hces1)).reduce(labelUtils.sumArrayValues, 0);

		const revaluation = (awardedValue / startingAwardedPrice) * 100;
		const increment = (awardedValue - startingAwardedPrice) / startingAwardedPrice * 100;

		const weightLots = (lots.length * 100) / totalLots;
		const weightAwardValue = (awardedValue * 100) / totalAwardValue;

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

		}
	});

	return dataFormat;
}

function initOrReloadTable(dataForTable){


	var table;
	if ($.fn.dataTable.isDataTable('#example')) {
		table = $('#example').DataTable();
		table.clear();
		table.rows.add(dataForTable).draw();
		return
	}


	table = $('#example').DataTable( {
		paging: false,
        ordering: true,
        info: false,
		searching: false,
		order: [[ 2, "desc" ]],
        data: dataForTable,
        columns: [
			{
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
			{data: 'name'},
			{data: 'num_lots'},
			{data: 'starting_price', render: {_: 'display', sort: 'value'}},
			{data: 'lots_awarded'},
			{data: 'awarded_value', render: {_: 'display', sort: 'value'}},
			{data: 'revaluation'},
			{data: 'increment'},
			{data: 'weight_lots', render: {_: 'display', sort: 'value'}},
			{data: 'weight_award_value', render: {_: 'display', sort: 'value'}},


			/*
			Muestra de render
			{
				data: 'sections',
				render: (data) => {
					return labelUtils.numberFormat(data.map(section => section.lotes.filter(lotIsAwarded).map(lote => parseInt(lote.implic_hces1)).reduce(labelUtils.sumArrayValues)).reduce(labelUtils.sumArrayValues)) + ' €';
				}
			} */

        ],
		footerCallback: function ( row, data, start, end, display ) {
			var api = this.api(), data;

			const totalStartingAwarded = dataForTable.map(({starting_awarded_price}) => starting_awarded_price).reduce(labelUtils.sumArrayValues, 0);

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
			$( api.column( 9 ).footer() ).html(labelUtils.numberFormat(totalWeightAward) + ' %');

		}
    });


	$('#example tbody').on('click', 'td.details-control', function () {
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
    } );
}

/* Formatting function for row details - modify as you need */
function sectionsRows(data) {

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

function newAdjuducacionesPorSubastaChart() {

	const options = {
		responsive: true,
		scales: {
			y: {
				beginAtZero: true
			},
			myScale: {
				display: 'auto',
				position: 'right', // `axis` is determined by the position as `'y'`
				beginAtZero: true,
				//type: '',
				min: 0,
				max: 100,
				title: {
					display: true,
					text: '% Adjudicados'
				}

			}
		}
	};

	let element = document.getElementById('adjudicacionesChart');
	adjudicacionesChart = new CustomChart(element, options, '');

	element.addEventListener('click', eventAuctionsChartClick);

}

function adjudicacionesPorSubastaChart(auctionsNames, lotsForAuctions, awardLotsForAuctions) {

	const data = adjudicacionesPorSubastaFormatData(auctionsNames, lotsForAuctions, awardLotsForAuctions);
	const options = {
		scales: {
			y: {
				beginAtZero: true
			},
			myScale: {
				display: 'auto',
				position: 'right', // `axis` is determined by the position as `'y'`
				beginAtZero: true,
				//type: '',
				min: 0,
				max: 100,
				title: {
					display: true,
					text: '% Adjudicados'
				}

			}
		}
	};

	adjudicacionesChart = new CustomChart(document.getElementById('adjudicacionesChart'), data, options);
}

function adjudicacionesPorSubastaFormatData(subastasNames, countLotes, lotesAdjudicados) {
	const percentLotesAdjudicados = countLotes.map((lotes, key) => {

		if (lotesAdjudicados[key] == 0) {
			return 0;
		}
		return lotesAdjudicados[key] * 100 / lotes
	});

	const data = {
		labels: subastasNames,
		datasets: [
			{
				type: 'bar',
				label: 'Lotes',
				data: countLotes,
				backgroundColor: '#e7b979',
				borderColor: '#ed9c28',
				borderWidth: 2,
				order: 0
			}, {
				type: 'bar',
				label: 'Lotes adjudicados',
				data: lotesAdjudicados,
				backgroundColor: '#ff7171',
				borderColor: 'red',
				borderWidth: 2,
				order: 1
			},
			{
				type: 'line',
				label: '% adjudicados',
				data: percentLotesAdjudicados,
				backgroundColor: '#6868ec',
				borderColor: 'blue',
				borderWidth: 2,
				tension: 0.4,
				yAxisID: 'myScale',
				order: 2,
			}
		]
	};

	return data;
}


function lotIsAwarded(lot) {
	return lot.cerrado_asigl0 === 'S' && parseInt(lot.implic_hces1) != 0;
}

function usersRegisterChart(usersData) {
	let labels = [];
	let values = [];
	let element = [];
	let total = 0;

	for (const key in clientesForDate) {

		if (Object.hasOwnProperty.call(clientesForDate, key) && key != '') {
			labels.push(key);
			total += clientesForDate[key];
			values.push(total);
			element.push(clientesForDate[key]);
		}
	}
	const data = {
		labels: labels,
		datasets: [{
			label: '# Usuarios totales',
			type: 'line',
			data: values,
			fill: true,
			borderColor: 'rgba(255, 99, 132, 1)',
			tension: 0.1
		}, {
			label: '# Usuarios mensuales',
			type: 'bar',
			data: element,
			borderColor: 'rgba(54, 162, 235, 1)',
			backgroundColor: 'rgba(54, 162, 235, 0.2)',
			borderWidth: 1
		}
		]
	};

	var ctx = document.getElementById('myChart');
	var myChart = new Chart(ctx, {
		//type: 'line',
		data: data,
		options: {
			scales: {
				y: {
					beginAtZero: true
				}
			}
		}
	});
}

function CustomChart(element, options, type) {

	this.element = element;
	//this.data = data;
	this.options = options;
	this.chart = {};

	this.create = function () {
		this.chart = new Chart(this.element, {
			type: type,
			//data: this.data,
			options: this.options
		});
	}

	this.reload = function (data) {
		this.chart.data = data;
		this.chart.update();
	}

	this.create();
}

const getCategoryAwardsInfo = async () => {

	const res = await fetch('bi/allcategories', {
		method: 'POST',
		body: new FormData(biReload)
	});

	let resJson = await res.json();
	return resJson;
}

const getAuctionAwardsInfo = async () => {

	const data = new FormData(biReload);

	const res = await fetch(biReload.action, {
		method: 'POST',
		body: data
	});

	let resJson = await res.json();
	return resJson;
}

const getAuctionModalInfo = async (auction) => {

	const data = {id_auc_sessions: auction};

	const res = await fetch('bi/auction-modal-info', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		  },
		body: JSON.stringify(data)
	});

	let resText = await res.text();
	return resText;
}

const toolTipPercent = (tooltipItems) => {

	const totalSumValues = tooltipItems[0].dataset.data.reduce(labelUtils.sumArrayValues);

	let sum = 0;
	tooltipItems.forEach(function (tooltipItem) {
		sum += tooltipItem.parsed.r || tooltipItem.parsed;
	});

	const percent = (sum * 100) / totalSumValues;

	return 'Porcentaje: ' + parseInt(percent) + ' %';
};

const eventMonthClick = (e) => {
	const pointClick = auctionForMontChart.chart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, true);

	if (pointClick[0] == undefined) {
		return;
	}

	let datasetIndex = pointClick[0].datasetIndex;
	let index = pointClick[0].index;
	index = String(index + 1).padStart(2, '0');

	$('select[name="months[]"]').multiselect('select', [index]);
	biReload.querySelector('button[type="submit"]').click();
}

const eventAuctionsChartClick = async (e) => {
	const pointClick = adjudicacionesChart.chart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, true);

	if (pointClick[0] == undefined) {
		return;
	}

	const id_auc_sessions = auctionsCods[pointClick[0].index];

	const modal = await getAuctionModalInfo(id_auc_sessions);

	//cambiar respuesta a tres tablas e incluir en cada tab antes de
	//mostrar modal
	$(document.body).append(modal);
	$(modal).modal('show');
	$(modal).on('hidden.bs.modal', function (event) {
		$(modal).remove();
	});

}



/**
 * Test1 - 3 maps + reduce vs 1 reduce
 * Para iteraciones cortas (7 subastas x 10 lotes/subasta) t2 ha rendido sensiblemente mejor y es más legible. t2 ~ 0.020 a 0.062 ms < t1 ~ 0.049 a 0.124 ms
 * Pero cuando aumentan las iteraciones sobre objetos mas pesados (20 subastas x 700-1000 lotes/subasta) t2 ha aumentado consideramblemente el tiempo mientras
 * t2 ~ 3.088 a 3.721 ms > t1 ~ 1.061ms a 1.855 ms
 * Resultado, cada map es un for, por lo tanto pese a ser más legible realizar multiples operaciones map y reduce, en estructuras grandes no renta.
 *
 * Test2 - 3 maps + reduce vs 1 reduce
 * Salir justo de la respuesta ajax, reduce el rendimiento de la primera iteracion por lo que realizando una segunda prueba llamando al metodo una
 * vez cargado toda la pantalla la diferencia entre ambos es minima, y no siempre un metodo es mas repido que el otro.
 * Realizare otra prueba cuando necesite de mas variables, por lo tanto mas iteraciones.
 *
 */
function testComparativeDeVelocidadAlEjecutarMultiplesMapOunSoloReducer(subastas) {
	const initInfo = {
		labels: [],
		totalLotes: 0,
		lotesForAuction: [],
		lotesAwardsForAuction: []
	};

	console.time("t2");
	let labels2 = subastas.map(({ name }) => name);
	let tatalLotes2 = subastas.reduce((sum, subasta) => sum + subasta.lotes.length, 0);
	let lotesForAuction2 = subastas.map(({ lotes }) => lotes.length);
	let lotesAwardsForAuction2 = subastas.map(({ lotes }) => lotes.filter(lotIsAwarded).length);
	console.timeEnd("t2");

	console.time("t1");
	let { labels, totalLotes, lotesAwardsForAuction, lotesForAuction } = subastas.reduce((acumulador, subasta) => {

		return {
			...acumulador,
			labels: [...acumulador.labels, subasta.name],
			totalLotes: subasta.lotes.length + acumulador.totalLotes,
			lotesForAuction: [...acumulador.lotesForAuction, subasta.lotes.length],
			lotesAwardsForAuction: [...acumulador.lotesAwardsForAuction, subasta.lotes.filter(lotIsAwarded).length]

			//si quisiera juntar en un mismo array
			//lotes: [...acumulador.lotes, ...subasta.lotes],
			//lotesAwards: [...acumulador.lotesAwards, ...subasta.lotes.filter(lotIsAwarded)],

		}
	}, initInfo);
	console.timeEnd("t1");
}

