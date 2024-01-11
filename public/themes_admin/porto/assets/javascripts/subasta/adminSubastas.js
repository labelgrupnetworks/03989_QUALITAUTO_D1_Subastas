window.addEventListener('load', function (event) {

	if($(`[name='subabierta_sub'], label[for='subabierta_sub'], label[for='subabierta_sub'] + i`).length > 0){
		reloadInputs($('select[name="tipo_sub"]').val());
	}


	//Actualización miniatura imagen
	$('input[name="imagen_sub"]').val(null);
	$('input[name="imagen_sub"]').on('change', e => changeImageAuction(e));

	//Actualización de Imagen en edit
	$('#subastaUpdate input[name="imagen_sub"]').val(null);
	$('#subastaUpdate input[name="imagen_sub"]').on('change', e => updateImageAuction(e));

	//Opciones subasta
	$('select[name="tipo_sub"]').on('change', e => reloadInputs(e.target.value));

	//Escalado
	$('#addEscalado').off('click');
	$('#addEscalado').on('click', addEscaladoAuction);

	$('#subastaUpdate').on('submit', confirmUploadSession);

	//Guardar tab activo en session
	$('#show-auction-tab a[data-toggle="tab"]').on('shown.bs.tab', e => {
		sessionStorage.setItem('tab-subastas', $(e.target).attr('aria-controls'));
	});
	//Mostrar tab guardado al cargar
	if(sessionStorage.getItem(`tab-subastas`)){
		$(`#${sessionStorage.getItem(`tab-subastas`)}-tab`).tab('show')
	}

	$(".addAutor_JS").on("click", function() {
		const idFeature =  $(this).data("feature");
		const newValue = $("#feature_input_" + idFeature).val();

		$.ajax({
				type: "POST",
				url:"/admin/subastas/lote/addFeature",
				data: {"idFeature" :idFeature, "newValue" : newValue  },

				success: function (response) {
					const keyNewValue = response.idFeatureValue;
					if(response.new){
						$("select[name=valorcol_sub]").append('<option value="'+ keyNewValue +'">' +newValue + '</option>');
					}
					//marca el campo como seleccionado
					$("select[name=valorcol_sub]").val(keyNewValue);
					//vacia el campo de texto
					$("select[name=valorcol_sub]").val("");
				}
			});
	});

	$(".addFeatureValue_JS").on("click", function() {
		const idFeature =  $(this).data("feature");
		const newValue = $("#feature_input_" + idFeature).val();

		$.ajax({
				type: "POST",
				url:"/admin/subastas/lote/addFeature",
				data: {"idFeature" :idFeature, "newValue" : newValue  },

				success: function (response) {
					const keyNewValue = response.idFeatureValue;
					if(response.new){
						$("#feature_select_"+ idFeature).append('<option value="'+ keyNewValue +'">' +newValue + '</option>');
					}
					//marca el campo como seleccionado
					$("#feature_select_"+ idFeature).val(keyNewValue);
					//vacia el campo de texto
					$("#feature_input_" + idFeature).val("");
				}
			});
	});

	$(".js-create-feature").on("click", event => {

		const idFeature = event.target.dataset.feature;

		callCreateOrEditMultilanguageFeature(idFeature, 0);
	});

	$(".js-edit-feature").on("click", event => {

		const idFeature = event.target.dataset.feature;
		const idFeatureValue = $(`#feature_select_${idFeature}`).val();

		if(idFeatureValue == ''){
			return;
		}

		callCreateOrEditMultilanguageFeature(idFeature, idFeatureValue);
	});


	$(".show_subalia_JS").on("click", function() {

		$.ajax({
			url: $(this).data("url"),
			type: "get",

			success: function (result) {
				if(result.status == "success"){
					saved("Subasta activa en subalia");
				}else{
					error(result.message);
				}

			},
			error: function () {
				//si hay problema de cors almenos mostramos mensaje positivo
				saved("Subasta activa en subalia");

			}
		});
	});

	$(".hide_subalia_JS").on("click", function() {

		$.ajax({
			url: $(this).data("url"),
			type: "get",

			success: function (result) {
				if(result.status == "success"){
					saved("Subasta ocultada en subalia");
				}else{
					error(result.message);
				}

			},
			error: function () {
				//si hay problema de cors almenos mostramos mensaje positivo
				saved("Subasta ocultada en subalia");

			}
		});
	});


	// Publicar nft
	$("#js-nft-publish").on("click", updateAndPublishNft);
	$("#js-nft-mint").on("click", mintNft);

	$('select[name="tipo_sub_select"]').on("change", hideAndShowOrlinInputs);
});

function callCreateOrEditMultilanguageFeature(idFeature, idFeatureValue){
	$.ajax({
		url: `/admin/features/${idFeature}/${idFeatureValue}`,
		success: function(response) {
			$(document.body).append(response);
			$("#modalAddFeature").modal('show');
		}
	});
};


function confirmUploadSession(e) {

	e.preventDefault();
	let uploadSession = $('input[name="upload_first_session"]')[0];

	if(!uploadSession){
		e.target.submit();
		return;
	}

	let bootboxConfig = {
		message: uploadSession.dataset.question,
		buttons: {
			confirm: {
				label: 'Si',
				className: 'btn-success'
			},
			cancel: {
				label: 'No',
				className: 'btn-danger'
			}
		},
		callback: function (result) {
			if (!result) {
				uploadSession.value = "0";
			}
			e.target.submit();
		}
	}

	bootbox.confirm(bootboxConfig);
}

const stateInputs = {
	W: [],
	E: ['dfecorlic_sub', 'dhoraorlic_sub', 'hfecorlic_sub', 'hhoraorlic_sub', 'compraweb_sub','subabierta_sub'],
	V: ['dfecorlic_sub', 'dhoraorlic_sub', 'hfecorlic_sub', 'hhoraorlic_sub', 'compraweb_sub', 'subabierta_sub'],
	O: ['dfecorlic_sub', 'dhoraorlic_sub', 'hfecorlic_sub', 'hhoraorlic_sub', 'subabierta_sub'],
	M: ['dfecorlic_sub', 'dhoraorlic_sub', 'hfecorlic_sub', 'hhoraorlic_sub', 'compraweb_sub', 'subabierta_sub'],
	I: ['dfecorlic_sub', 'dhoraorlic_sub', 'hfecorlic_sub', 'hhoraorlic_sub', 'subabierta_sub'],
}

function reloadInputs(value) {

	if(typeof value == 'undefined'){
		return;
	}

	$('input').attr("disabled", false);
	$('select').attr("disabled", false);

	for (const element of stateInputs[value]) {
		$(`[name='${element}']`).attr("disabled", true);
	}

	(value != 'W')
		? $(`[name='subabierta_sub'], label[for='subabierta_sub'], label[for='subabierta_sub'] + i`).hide()
		: $(`[name='subabierta_sub'], label[for='subabierta_sub'], label[for='subabierta_sub'] + i`).show();

}

function changeImageAuction(e) {

	// Creamos el objeto de la clase FileReader
	let reader = new FileReader();

	// Leemos el archivo subido y se lo pasamos a nuestro fileReader
	reader.readAsDataURL(e.target.files[0]);

	// Le decimos que cuando este listo ejecute el código interno
	reader.onload = function () {
		document.querySelector('#img_subasta').src = reader.result;
	}
}

function updateImageAuction(e) {

	var formData = new FormData();
	formData.append("imagen_sub", e.target.files[0]);
	formData.append("cod_sub", $('input[name="cod_sub"]').val());
	formData.append("_token", $('input[name="_token"]').val());
	formData.append("force_overwritte", $('input[name="force_overwritte"]').val());

	$.ajax({
		url: $('input[name="route.update.image"]').val(),
		type: "post",
		data: formData,
		dataType: "html",
		contentType: false,
		processData: false,

		success: function (result) {
			saved("Imagen actualizada");
			document.querySelector('#img_subasta').src = result + '?a=' + Math.floor(Math.random() * 10);
			$('input[name="imagen_sub"]').val(null);
		},
		error: function () {
			error();
			$('input[name="imagen_sub"]').val(null);
		}
	});

}

function addEscaladoAuction () {
	let newRow = $("<div>", { 'class': 'row' });

	let columnImporte = $("<div>", { 'class': 'col-xs-6 mb-2' });
	let columnPuja = $("<div>", { 'class': 'col-xs-6 mb-2' });

	let inputImporte = newInput('imp_pujassub');
	let inputPuja = newInput('puja_pujassub');

	columnImporte.append(inputImporte);
	columnPuja.append(inputPuja);

	newRow.append(columnImporte, columnPuja);
	$('#fgpujassubs').append(newRow);

	return true;
}

function newInput(name) {
	return $("<input>", {
		'type': 'text',
		'class': 'form-control effect-16',
		'name': `${name}[]`,
		'id': `decimal__0__${name}[]`,
		'value': '',
		'onblur': 'comprueba_campo(this)',
		'data-placement': 'right',
		'autocomplete': 'off'
	});
}

function addResultFetchToElement(element, message, className) {
	element.innerHTML = `<div class="alert ${className}">${message}</div>`;
}

/**
 * @see updateAndPublishNft
 */
function publishNft(event) {
	event.preventDefault();

	const propertyCod = document.querySelector('select[name="owner"]').value;
	const resultElement = document.getElementById('publish-nft-result');

	if(!propertyCod) {
		addResultFetchToElement(resultElement, 'Debe seleccionar un propietario', 'alert-danger');
		return;
	}

	const buttonPublish = event.target;

	fetch(buttonPublish.href)
		.then(res => res.json())
		.then(res => {
			const status = res.status == 'success' ? 'alert-success' : 'alert-danger';
			addResultFetchToElement(resultElement, res.message, status);
		})
		.catch(err => {
			addResultFetchToElement(resultElement, err.message, 'alert-danger');
		});
}

function updateAndPublishNft(event) {
	event.preventDefault();

	const propertyCod = document.querySelector('select[name="owner"]').value;
	const resultElement = document.getElementById('publish-nft-result');

	if(!propertyCod) {
		addResultFetchToElement(resultElement, 'Debe seleccionar un propietario', 'alert-danger');
		return;
	}

	document.querySelector('[name=publish_nft]').value = 1;
	document.getElementById('loteUpdate').submit();
	return;
}

function mintNft(event) {
	event.preventDefault();

	bootbox.confirm("¿Estás seguro que quieres mintear el NFT?", function (result) {
		if(result){
			document.querySelector('[name=mint_nft]').value = 1;
			document.getElementById('loteUpdate').submit();
		}
	});




	return;
}

$('#edit_multiple_auctions').on('submit', function (event) {
	event.preventDefault();

	if (validateDateFields(new FormData(this))) {
		return;
	}
	const formData = new FormData(this);
	const isSelectAllDepositsChecked = getValueFromInput('js-selectAll');
	const uploadSession = $('input[name="upload_first_session"]')[0];



	const url = isSelectAllDepositsChecked
		? urlAllSelected.value
		: edit_multiple_auctions.action;

	isSelectAllDepositsChecked
		? appendFiltersToFormData(formData)
		: appendIdsToFormData(formData);

	if(!uploadSession){
		updateAuctionData(url, formData);
		return;
	}

	const bootboxConfig = {
		message: uploadSession.dataset.question,
		buttons: {
			confirm: {
				label: 'Si',
				className: 'btn-success'
			},
			cancel: {
				label: 'No',
				className: 'btn-danger'
			}
		},
		callback: function (result) {
			if (!result) {
				uploadSession.value = "0";
			}
			appendInputToFormData(formData, uploadSession);
			updateAuctionData(url, formData);
		}
	}

	bootbox.confirm(bootboxConfig);
});

function validateDateFields(formData) {
	const fields = {
		dfec_sub_select: formData.get('dfec_sub_select'),
		dhora_sub_select: formData.get('dhora_sub_select'),
		hfec_sub_select: formData.get('hfec_sub_select'),
		hhora_sub_select: formData.get('hhora_sub_select'),
		dfecorlic_sub_select: formData.get('dfecorlic_sub_select'),
		dhoraorlic_sub_select: formData.get('dhoraorlic_sub_select'),
		hfecorlic_sub_select: formData.get('hfecorlic_sub_select'),
		hhoraorlic_sub_select: formData.get('hhoraorlic_sub_select'),
		tipo_sub_select: formData.get('tipo_sub_select'),
	};
	const orlicBlocks = $('[data-blockid="auc_dates_orlic"]');

	let error = false;

	function handleValidationError(fieldName) {
		$(`input[name="${fieldName}"], select[name="${fieldName}"]`).addClass('has-error');
		error = true;
	}

	if (fields.tipo_sub_select != 'W') {
		const orlicData = $('[data-blockid="auc_dates_orlic"] input');
		orlicData.val('');
	}

	let field = validateTwoFields(fields, ['dfec_sub_select', 'dhora_sub_select']);
	if (field) {
		handleValidationError(field);
	}

	field = validateTwoFields(fields, ['hfec_sub_select', 'hhora_sub_select']);
	if (field) {
		handleValidationError(field);
	}

	field = validateTwoFields(fields, ['dfecorlic_sub_select', 'dhoraorlic_sub_select']) && hasDnoneClass(orlicBlocks);
	if (field) {
		handleValidationError(field);
	}

	field = validateTwoFields(fields, ['hfecorlic_sub_select', 'hhoraorlic_sub_select']) && hasDnoneClass(orlicBlocks);
	if (field) {
		handleValidationError(field);
	}

	return error;
}

function validateTwoFields(fields, [field1, field2]) {
    if (fields[field1] === '' && fields[field2] === '') {
        return false;
    } else if (fields[field1] === '' || fields[field2] === '') {
        const campo = fields[field1] === '' ? field1 : field2;
        return campo;
    } else {
        return false;
    }
}


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

function appendIdsToFormData(formData) {
	const ids = selectedCheckItemsByName("auc_ids");
	ids.forEach(id => formData.append('ids[]', id));
}

function appendInputToFormData(formData, input) {
	formData.append(input.name, input.value);
}

function updateAuctionData(url, formData) {

	$.ajax({
		url,
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,

		success: function (result) {
			$('#editMultpleAuctionsModal').modal('hide');
			saved('Subastas actualizadas correctamente');
			location.reload();
		},
		error: function (result) {
			error(result.responseJSON.message);
		}
	});
}

$('input[name="auc_ids"]').on('change', function () {
	const isSelectAllDepositsChecked = (document.querySelector('[name=js-selectAll]')).checked;

	if (isSelectAllDepositsChecked) {
		document.querySelector('[name=js-selectAll]').checked = false;
	}
});

function getValueFromInput(inputName) {
	const input = document.querySelector(`input[name="${inputName}"]`);
	if(input.type == 'checkbox' && input.checked){
		return input.value;
	}
	return false;
}

function hideAndShowOrlinInputs()
{
	if ($('select[name="tipo_sub_select"]')) {
		const tipo_sub_select = $('select[name="tipo_sub_select"]');
		const orlicData = $('[data-blockid="auc_dates_orlic"]');

		if (tipo_sub_select.val() == 'W') {
			orlicData.removeClass('d-none');
		} else {
			orlicData.addClass('d-none');
		}
	}

}

function hasDnoneClass(element) {
	return element.hasClass('d-none');
}

