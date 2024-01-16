$(document).on('ready', function () {

	$('[name=starthour], [name=endhour]').on('blur', function (e) {
		let element = e.target;

		if (element.value.length == '5') {
			element.value = element.value + ':00';
		}

	});

	//Convertir imagenes de iphone a jpg
	$('[name="images[]"]').on('change', e => convertHeicToJpg(e.target));

	$('[name=startprice]').on('input', e => document.getElementById('biddercommission_importe').value = calculateCommision(document.querySelector('[name=biddercommission]'), OPERATION_TYPES.PERCENTATGE));
	$('[name=biddercommission]').on('input', e => document.getElementById('biddercommission_importe').value = calculateCommision(e.target, OPERATION_TYPES.PERCENTATGE));
	$('#biddercommission_importe').on('input', e => document.querySelector('[name=biddercommission]').value = calculateCommision(e.target, OPERATION_TYPES.PERCENTATGE_INVERSE));

	$('[name=ownercommission]').on('input', (e) => document.getElementById('ownercommission_importe').value = calculateCommision(e.target, OPERATION_TYPES.PERCENTATGE));
	$('#ownercommission_importe').on('input', (e) => document.querySelector('[name=ownercommission]').value = calculateCommision(e.target, OPERATION_TYPES.PERCENTATGE_INVERSE))

	$('#js-nft-unpublish').on('click', unpublishNft);

	$('.js-actionSelectedLots').on('click', actionSelectedLots);
	$('#js-selectAllLots').on('click', selectedAllLots);


	$(".js-send_webservice_close_lot").on('click', function (event) {
		event.preventDefault();


		const sub = this.dataset.sub;
		const ref = this.dataset.ref;



		bootbox.confirm("¿Estás seguro que quieres enviar el lote cómo finalizado a la casa de subastas?", function (result) {
			token = $("[name='_token']").val();
			if (result) {
				$.ajax({
					type: "POST",
					url: "/admin/lote/send_end_lot_ws",
					data: { sub, ref,  _token: token },
					success: function (response) {
						bootbox.alert('Se ha enviado el lote cómo finalizado  a través del webservice');
					},
					error: function (error) {
						bootbox.alert('Ha ocurrido un error al enviar el lote como finalizado a través del webservice');
					}
				});
			}
		});
	});

	$('input[name="lot_ids"]').on('change', verifyUncheckAllSelected);
	$('input[name="lot_ids"]').on('change', hideLotMassDestroyButton);
	$('input[name="js-selectAll"]').on('change', hideLotMassDestroyButton);
});

const OPERATION_TYPES = {
	PERCENTATGE: (a, b) => a * b * 0.01,
	PERCENTATGE_INVERSE: (a, b) => (a * 100) / b
}

/**
 * @param {HTMLElement} thisElement
 * @param {Function} operationType
 * @returns {number}
 */
const calculateCommision = (thisElement, operationType) => {

	const initialValue = parseInt(thisElement.value);
	const startPrice = parseInt(document.querySelector('[name=startprice]').value);

	if (isNaN(initialValue) || isNaN(startPrice)) {
		return 0;
	}

	return parseInt(operationType(initialValue, startPrice));
}

/**
 * Esta sera la funcion buena
 * @param {HTMLElement} element
 */
const convertHeicToJpg = async (element) => {

	//obtenemos el array de archivos
	const files = [...element.files];

	//Filtramos solo los de tipo heic
	const filesHeic = files.filter((file) => file.name.toUpperCase().includes(".HEIC"));

	//Si no existen, no tenemos que hacer nada
	if (!filesHeic.length) {
		return;
	}

	try {

		//Inicio spinner
		$('#loadMe').data('bs.modal', null);
		$("#loadMe").modal({
			backdrop: 'static', //remove ability to close modal with click
			keyboard: false, //remove option to close with keyboard
			show: true //Display loader!
		});

		//Creamos dataTransder para envolver file
		const container = new DataTransfer();

		for (const file of filesHeic) {

			//convertimos imagen a jpg
			let conversionResult = await heic2any({ blob: file, toType: "image/jpg" });

			//Lo envolvemos en objeto File
			let newFile = new File([conversionResult], `${file.name.slice(0, -5)}.jpg`, { type: "image/jpeg", lastModified: new Date().getTime() });

			//Añadimos los files al dataTransfer
			container.items.add(newFile);
		}

		element.files = container.files;

	} catch (error) {
		console.log(error)
	}

	//fin spinner
	$("#loadMe").modal('hide');
}

function unpublishNft(event) {

	event.preventDefault();

	bootbox.confirm("¿Estás seguro que quieres despublicar el NFT?", function (result) {
		if(result){
			document.location = event.target.href;
		}
	});
}

function actionSelectedLots(event) {
	event.preventDefault();

	const lots = selectedCheckItemsByName('lote');

	if(lots.length === 0){
		bootbox.alert("Debes seleccionar al menos un lote");
		return;
	}

	bootbox.confirm(event.target.dataset.title, function (result) {
		if(!result) return;

		fetch(event.target.href, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({lots})
		})
		.then(handleToJson)
		.then(data => {

			if(data.success){
				bootbox.alert(event.target.dataset.respuesta);
				location.reload();
			}
			else if(data.status === 'success'){
				console.log(data);
				bootbox.alert(data.message);
			}
			else{
				bootbox.alert("Ha ocurrido un error");
			}
		})
		.catch(handleFetchingErrorWithBootbox);
	});

}
/*
function removeStockSelectedLots(event) {
	event.preventDefault();

	const lots = selectedCheckItemsByName('lote');

	if(lots.length === 0){
		bootbox.alert("Debes seleccionar al menos un lote");
		return;
	}

	bootbox.confirm("¿Estás seguro de quitar el stock en todos los lotes seleccionados", function (result) {
		if(!result) return;

		fetch(event.target.href, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({lots})
		})
		.then(handleToJson)
		.then(data => {

			if(data.success){
				bootbox.alert("Se ha quitado el stock a los lotes seleccionados");
				location.reload();
			}
			else{
				bootbox.alert("Ha ocurrido un error");
			}
		})
		.catch(handleFetchingErrorWithBootbox);
	});

}
*/
function selectedAllLots(event) {
	event.preventDefault();
	const lots = Array.from(document.getElementsByName('lote'));

	lots.forEach((element) => element.checked = true);
}

$('[name="js-selectAll"').on('click', function () {
	const isChecked = this.checked;
	const objectiveInputs = this.dataset.objective;
	isChecked ? selectAllTable(objectiveInputs) : unselectAllTable(objectiveInputs);
});


function selectedCheckItemsByName(name) {
	return Array.from(document.getElementsByName(name))
		.filter((element) => element.checked)
		.map((element) => element.value);
}

function removeSelecteds({ objective, url, title, response }, callback) {

	const ids = selectedCheckItemsByName(objective);

	bootbox.confirm(title, function (result) {
		if(!result) return;

		$.ajax({
			url,
			type: "post",
			data: {
				_token: $('[name="_token"]').val(),
				ids
			},
			success: function(result) {
				saved(response);
				if(callback){
					callback(result);
				}
			},
			error: function() {
				error();
			}
		});

	});
}

function refreshFilesRows(result) {
	$('#lotFilesRows').html(result);
}

function selectAllTable(inputName) {
	const inputs = Array.from(document.getElementsByName(inputName));
	inputs.forEach((element) => element.checked = true);
}

function unselectAllTable(inputName) {
	const inputs = Array.from(document.getElementsByName(inputName));
	inputs.forEach((element) => element.checked = false);
}

function editFile(button) {
	const url = button.dataset.action;

	$.ajax({
		url: url,
		type: "get",

		success: function(result) {
			$('#modal-create-body').html(result);
			$('#addFileModal').modal('show');
		},
		error: function() {
			error();
		}
	});
}

function removeFile(button) {
	const url = button.dataset.action;

	bootbox.confirm("¿Estás seguro que quieres borrar el archivo seleccionado?", function(result) {

		if (!result) {
			return;
		}

		$.ajax({
			url: url,
			type: "delete",

			success: function(result) {
				$('#lotFilesRows').html(result);
				saved('Archivo borrado correctamente');
			},
			error: function() {
				error();
			}
		});
	});
}

function changeStatusFile(button) {
	const url = button.dataset.action;

	$.ajax({
		url: url,
		type: "post",

		success: function(result) {
			$('#lotFilesRows').html(result);
			saved('Archivo actualizado correctamente');
		},
		error: function() {
			error();
		}
	});
}

$("#js-dropdownItems").on('show.bs.dropdown', function (event) {

	const button = event.relatedTarget;
	const objective = button.dataset.objective;
	const ids = selectedCheckItemsByName(objective);

	if(ids.length === 0){
		bootbox.alert("Debes seleccionar al menos un elemento");
		return false;
	}
});

$('#edit_multple_files').on('submit', function(event){
	event.preventDefault();

	const ids = selectedCheckItemsByName("files_ids");
	const formData = new FormData(edit_multple_files);
	ids.forEach(id => formData.append('ids[]', id));

	$.ajax ({
		url: edit_multple_files.action,
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,

		success: function(result) {
			$('#lotFilesRows').html(result);
			$('#editMultpleFilesModal').modal('hide');
			saved('Archivos actualizados correctamente');
		},
		error: function() {
			error();
		}
	});
});


$('#addFile').on('click', function() {

	$.ajax({
		url: $(this).data('url'),
		type: "get",

		success: function(result) {
			$('#modal-create-body').html(result);
			$('#addFileModal').modal('show');
		},
		error: function() {
			error();
		}
	});
});

$('#lotFilesRows').sortable({
	handle: '.js-soratble-button',
	items: 'tr',
	axis: 'y',
	cursor: "grabbing",
	cancel: '',
	update: function(event, ui) {
		const order = $(this).sortable('toArray', {
			attribute: 'data-id'
		});

		$.ajax({
			url: "/admin/subastas/lotes/files/order",
			type: "post",
			data: {
				_token: $('[name="_token"]').val(),
				order
			},
			success: function(result) {
				saved('Orden actualizado correctamente');
			},
			error: function() {
				error();
			}
		});
	}
});

$('#edit_multiple_lots').on('submit', function (event) {
	event.preventDefault();

	if (validateLotDateFields(new FormData(this))) {
		return;
	}
	const formData = new FormData(this);
	const isSelectAllDepositsChecked = getValueFromInput('js-selectAll');

	const url = isSelectAllDepositsChecked
		? urlAllSelected.value
		: edit_multiple_lots.action;

	isSelectAllDepositsChecked
		? appendFiltersToFormData(formData)
		: appendLotIdsToFormData(formData);

	appendAucIdToFormData(formData);

	updateLotsAjax(url, formData);
});

function appendFiltersToFormData(formData) {
	const searchParams = new URLSearchParams(window.location.search);
	const params = [...searchParams.entries()];
	const cleanParams = params.filter(param => param[1] !== '');

	// Add params to formData
	cleanParams.forEach((entryParams) => {
		const [key, value] = entryParams;
		formData.append(key, value);
	});
}

function appendLotIdsToFormData(formData) {
	const ids = selectedCheckItemsByName("lot_ids");
	ids.forEach(id => formData.append('ids[]', id));
}

function appendAucIdToFormData(formData) {
	const id = getValueFromInput("auc_id");
	formData.append('auc_id', id);
}

function updateLotsAjax(url, formData) {
	$.ajax({
		url,
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,

		success: function (result) {
			$('#editMultpleLotsModal').modal('hide');
			saved(result.message);
			location.reload(true);
		},
		error: function (result) {
			error(result.responseJSON.message);
		}
	});
}

function verifyUncheckAllSelected() {
	const isSelectAllDepositsChecked = getValueFromInput('js-selectAll');

	if (isSelectAllDepositsChecked) {
		document.querySelector('input[name="js-selectAll"]').checked = false;
	}
}

function getValueFromInput(inputName) {
	const input = document.querySelector(`input[name="${inputName}"]`);
	if(input.type == 'checkbox' && input.checked){
		return input.value;
	}
	if(input.type == 'hidden' && input.value != null && input.value != ''){
		return input.value;
	}
	return null;
}
function getSearchParams() {
	let params = [...new URLSearchParams(window.location.search).entries()];
	params = params.filter(([key, value]) => value != '');
	return params.reduce((acc, [key, value]) => {
		return { ...acc, [key]: value }
	}, {});
}

function makeDataToSendInRemoveSelecteds(ids) {
	let data = {};
	data['_token'] = $('[name="_token"]').val();
	data['auc_id'] = getValueFromInput('auc_id');
	data['ids'] = ids;
	const searchParams = getSearchParams();
	for (const [key, value] of Object.entries(searchParams)) {
		data[key] = value;
	}
	return data;
}

function validateLotDateFields(formData) {
	const fields = {
		startdate_select: formData.get('startdate_select'),
		starthour_select: formData.get('starthour_select'),
		enddate_select: formData.get('enddate_select'),
		endhour_select: formData.get('endhour_select'),
	};

	let error = false;

	function handleValidationError(fieldName) {
		$(`input[name="${fieldName}"], select[name="${fieldName}"]`).addClass('has-error');
		error = true;
	}

	let field = validateTwoFields(fields, ['startdate_select', 'starthour_select']);
	if (field) {
		handleValidationError(field);
	}

	field = validateTwoFields(fields, ['enddate_select', 'endhour_select']);
	if (field) {
		handleValidationError(field);
	}

	return error;
}

function removeLotsSelecteds({ objective, allselected, url, urlwithfilters, title, response }) {

	const valueAllSelected = getValueFromInput(allselected);
	const urlAjax = valueAllSelected ? urlwithfilters : url;
	const ids = !valueAllSelected ? selectedCheckItemsByName(objective) : '';

	bootbox.confirm(title, function (result) {
		if(!result) return;

		$.ajax({
			url: urlAjax,
			type: "post",
			data: makeDataToSendInRemoveSelecteds(ids),
			success: function(result) {
				saved(response);
				location.reload(true);
			},
			error: function() {
				error();
			}
		});

	});
}

function hideLotMassDestroyButton() {
	const ids = selectedCheckItemsByName("lot_ids");
	const hasBidsAndOrders = $('input[name="has_orders_or_bids"]');
	const massDeleteButton = document.querySelector('[data-id="mass_delete_button"]');

	massDeleteButton.classList.remove('hidden');

	ids.forEach(id => {
		let input = hasBidsAndOrders.filter(`[data-lot_ref="${id}"]`);
		if (input.val() == 1) {
			massDeleteButton.classList.add('hidden');
		}
	});

}
