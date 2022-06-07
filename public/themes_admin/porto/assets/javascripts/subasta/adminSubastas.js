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


	// Publicar nft
	$("#js-nft-publish").on("click", publishNft);

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
	O: ['dfecorlic_sub', 'dhoraorlic_sub', 'hfecorlic_sub', 'hhoraorlic_sub', 'subabierta_sub']
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


