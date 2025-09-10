document.querySelector('[name="clid_cpostal"]') && document.querySelector('[name="clid_cpostal"]').addEventListener('blur', searchCityForSecondAddress);
document.querySelector('[name="nif"]') && document.querySelector('[name="nif"]').addEventListener('blur', checkExistNif);

handleCheckedAddressShipping(document.querySelector('[name="shipping_address"]'));

$(document).ready(function () {
	document.getElementById('registerForm').addEventListener('submit', handleSubmitRegisterForm);
});

function searchCityForSecondAddress(event) {

	const country = document.querySelector('[name="clid_pais"]').value;
	const zip = event.target.value;

	if (!Boolean(country.trim()) || !Boolean(zip.trim())) {
		return;
	}

	searchCityByCode(country, zip)
		.then((response) => {
			document.querySelector('[name="clid_poblacion"]').value = response.city;
			document.querySelector('[name="clid_provincia"]').value = response.province;
		})
		.catch((error) => console.log(error));
}

async function searchCityByCode(country, zip) {

	const response = await fetch('/api-ajax/cod-zip', {
		method: 'POST',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json'
		},
		body: JSON.stringify({ country, zip })
	});

	if (!response.ok) {
		throw new Error(response.status);
	}

	const responseJson = await response.json();

	if (responseJson.status === 'error') {
		throw new Error(responseJson.status);
	}

	return {
		city: responseJson.pob,
		province: responseJson.des_prv
	};
}


/**
 * Elementos a mostrar y su requerimiento cuando seleccionamos entre empresa o particular
 * @param {HTMLSelectElement} select
 */
function changeRegisterType(select) {
	const isParticular = select.value == 'F';
	document.querySelectorAll('.register-particular').forEach((element) => {
		element.classList.toggle('d-none', !isParticular);
	});
	document.querySelectorAll('.register-empresa').forEach((element) => {
		element.classList.toggle('d-none', isParticular);
	});

	const isRequired = isParticular ? 1 : 0;
	document.querySelector("[name='last_name']").id = `texto__${isRequired}__last_name`;
}

/**
 * Elementos a mostrar y su requerimiento cuando seleccionamos dirección de envio
 * @param {HTMLInputElement} checkElement
 */
function handleCheckedAddressShipping(checkElement) {

	if(!checkElement) {
		return;
	}

	const isChecked = checkElement.checked;
	const shippingAddressBlock = document.getElementById('js-shipping_address');

	shippingAddressBlock.classList.toggle('d-none', isChecked);

	const replaceId = (element) => element.id = isChecked
		? element.id.replace('1', '0')
		: element.id.replace('0', '1');

	shippingAddressBlock.querySelectorAll('input').forEach(replaceId);
	shippingAddressBlock.querySelectorAll('select').forEach(replaceId);

	document.getElementById('clid').value = isChecked ? '0' : '1';
}

/**
 * @param {Event} event
 */
async function handleSubmitRegisterForm(event) {
	event.preventDefault();

	const captcha = await isValidCaptcha();
	if (!captcha) {
		showMessage(messages.error.hasErrors);
		return;
	}

	//en caso de no tener dirección multiple o de estar seleccionado, copiamos dirección
	const withSameAddress = document.querySelector("[name=shipping_address]").checked;
	if (withSameAddress) {
		copyPrincipalAddress();
	}

	const form = event.target;

	if (!registerValidations(form)) {
		return;
	}

	$.ajax({
		type: "POST",
		url: form.action,
		data: new FormData(form),
		processData: false,
		contentType: false,
		beforeSend: () => loadingData(true),
		success: successRegister,
		error: errorRegisterForm,
		complete: () => loadingData(false)
	});
}

function copyPrincipalAddress() {
	const inputsNames = ['pais', 'cpostal', 'provincia', 'poblacion', 'codigoVia', 'direccion'];
	inputsNames.forEach((inputName) => {
		if (document.querySelector(`[name='clid_${inputName}']`) == null) {
			return;
		}
		document.querySelector(`[name='clid_${inputName}']`).value = document.querySelector(`[name='${inputName}']`).value;
	});
}

/**
 *
 * @param {HTMLFormElement} form
 */
function registerValidations(form) {

	if (!submit_form(form, 1)) {
		showMessage(messages.error.hasErrors);
		return false;
	}

	if (!checkIfErrorEmail()) {
		showMessage(messages.error.email_exist);
		return false;
	}

	if (!checkNifValidations()) {
		showMessage(messages.error.hasErrors);
		return false
	}

	return true;
}

function checkNifValidations() {
	const paisInput = document.querySelector(`[name=pais]`);
	const nifInput = document.querySelector(`[name=nif]`);

	if (paisInput.value === "ES" && !nifInput.value.trim()) {
		muestra_error_input(nifInput);
		return false;
	}

	return true;
}

function checkIfErrorEmail() {
	const emaiInput = document.querySelector(`[name=email]`);
	if (emaiInput.classList.contains("email-error")) {
		muestra_error_input(emaiInput)
		return false;
	}
	return true;
}

function successRegister(response, aux) {
	response = $.parseJSON(response);
	if (response.err == 1) {
		response.message = response.msg;
		response.status = "error";
		showMessage(response);

	} else if (response.err == 0) {

		if (response.info == undefined) {
			document.location = response.msg;
		}
		else {

			$("#info_sent").val(JSON.stringify(response.info));
			$("#cod_auchouse_sent").val(response.cod_auchouse);
			$("#redirect_sent").val(response.redirect);

			document.getElementById("formToSubalia").submit();

		}
	}
}

function errorRegisterForm(response) {
	showMessage(messages.error.error_contact_emp, "Error");
}

function loadingData(isLoading) {
	const button = document.querySelector('.submitButton');
	button.classList.toggle('loading', isLoading);
}

/**
 * @param {Event} event
 */
function checkExistNif(event) {
	const nifInput = event.target;
	const nif = nifInput.value.trim().toUpperCase();

	if (!nif) return

	fetch('/api-ajax/exist-nif', {
		method: "POST",
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json'
		},
		body: JSON.stringify({ nif })
	})
		.then((response) => response.json())
		.then((data) => {

			if (data.status !== 'error') {
				nifInput.classList.remove("is-invalid");
				return;
			}

			muestra_error_input(nifInput);
			showMessage(messages.error.nif_exist);

			nifInput.classList.add("is-invalid");
			return;
		})
}

/**
 * Bloque para solamente para clientes con representación
 */

/**
 * Elementos a mostrar y su requerimiento cuando seleccionamos dirección de envio
 * @param {HTMLInputElement} checkElement
 */
function handleCheckedRepresented(checkElement) {
	const isChecked = checkElement.checked;
	const representedBlock = document.getElementById('js-representar');

	representedBlock.classList.toggle('d-none', !isChecked);

	representedBlock.querySelectorAll('input').forEach((input) => {
		input.required = isChecked;
		input.value = '';
	});

	// Puede que necesite un input donde añadir S o N
	document.querySelector('[name=representar]').value = isChecked ? 'S' : 'N';
}

function addRow() {
	const table = document.getElementById('js-repre-table');
	const rowNumber = table.rows.length - 1;
	const arrayNumber = rowNumber - 1;

	const clonRow = table.rows[1].cloneNode(true);
	clonRow.querySelector('[name*=alias]').value = '';
	clonRow.querySelector('[name*=alias]').attributes['name'].value = 'repre['+arrayNumber+'][alias]';
	clonRow.querySelector('[name*=name]').value = '';
	clonRow.querySelector('[name*=name]').attributes['name'].value = 'repre['+arrayNumber+'][name]';
	clonRow.querySelector('[name*=cif]').value = '';
	clonRow.querySelector('[name*=cif]').attributes['name'].value = 'repre['+arrayNumber+'][cif]';

	const trashIcon = document.getElementById('js-repre-delete-icon').getElementsByTagName('svg')[0].cloneNode(true);
	const trashButton = $('<button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)"></button>').append(trashIcon);

	clonRow.querySelector('td:last-child').appendChild(trashButton[0]);

	//instert row in tbody
	table.querySelector('tbody').appendChild(clonRow);
}

function removeRow(button) {
	const row = button.closest('tr');
	row.remove();
}
