$(document).ready(function () {

	//name de inputs que no puedan ni copiar ni pegar información
	let noCopyInputs = ['email', 'confirm_email', 'password', 'confirm_password'];

	for (const inputName of noCopyInputs) {

		$(`[name="${inputName}"]`).on('copy', function(e){
			e.preventDefault();
		});
		$(`[name="${inputName}"]`).on('paste', function(e){
			e.preventDefault();
		})

	}

	inputRequired('last_name', true);


	$("[name=tdocid_cli_select]").on('change', addTdocidValue);
	$("[name=pais]").on('change', addTdocidValue);

});


/**
 * Si el pais es España o el documento es pasaporte, añade el valor de tdocid_cli_select,
 * si no, si el valor de tdocid_cli_select es 02, añade el valor 04.
 * Significado de valores:
 * 02 - NIF IVA Contraparte
 * 03 - Pasaporte
 * 04 - ID en país de residencia
 */
function addTdocidValue() {

	const pais = $('[name=pais]').val();
	const tdocid = $('[name=tdocid_cli_select]').val();
	const $tdocidInput = $('[name=tdocid_cli]');

	const value = (pais === 'ES' || tdocid === '03') ? tdocid : '04';
	$tdocidInput.val(value);

	// Si el valor es 02, el campo es obligatorio, en caso contrario no
	if(value !== '02') {
		inputRequired('nif', false);
	}
}


function custom_checks(campo){

	if(campo.name == 'usuario' && campo.value.includes('@')){
		return false;
	}
	if(campo.name == 'nif' && campo.value == '') {
		return false;
	}
	return true;
}


