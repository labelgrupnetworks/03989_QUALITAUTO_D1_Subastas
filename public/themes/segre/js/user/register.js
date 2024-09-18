/****************************************************************************************/
/*************************** FUNCIONES PARA REGISTRO DE USUARIO *************************/
/****************************************************************************************/
$(function () {

	document.getElementById($('[name="nif"]').attr("id")).addEventListener("blur", nifVerifyError);

});

function nifVerifyError() {
	if ($('#pri_emp').val() == "J") {
		if (mostrarErrorNIFForeign($("#select__1__pais").val(), $('[name="nif"]').val()) == false) {
			muestra_error_input(document.getElementById($('[name="nif"]').attr("id")));
		}
	}
	if ($("#select__1__pais").val() == "ES" && $('[name="nif"]').val() == "") {
		muestra_error_input(document.getElementById($('[name="nif"]').attr("id")));
	}
}

function mostrarErrorNIFForeign(cod_pais, nif) {
	if (cod_pais != "ES" && nif != "") {
		if ((cod_pais == "DK" || cod_pais == "SI" || cod_pais == "FI" || cod_pais == "HU" || cod_pais == "LU" || cod_pais == "MT") && (nif.length != 8 || (verIfNum(nif) == false))) {
			return false;
		} else if ((cod_pais == "AT" || cod_pais == "CY" || cod_pais == "PT") && nif.length != 9) {
			return false;
		} else if ((cod_pais == "DE" || cod_pais == "EE" || cod_pais == "EL") && (nif.length != 9 || (verIfNum(nif) == false))) {
			return false;
		} else if (cod_pais == "PL" && nif.length != 10) {
			return false;
		} else if (cod_pais == "FR" && nif.length != 11) {
			return false;
		} else if ((cod_pais == "IT" || cod_pais == "LV" || cod_pais == "HR") && (nif.length != 11 || (verIfNum(nif) == false))) {
			return false;
		} else if ((cod_pais == "NL" || cod_pais == "SE") && nif.length != 12) {
			return false;
		} else if ((cod_pais == "BE" || cod_pais == "SK" || cod_pais == "BG") && ((nif.length < 9 || nif.length > 10) || (verIfNum(nif) == false))) {
			return false;
		} else if (cod_pais == "CZ" && ((nif.length < 8 || nif.length > 10) || (verIfNum(nif) == false))) {
			return false;
		} else if (cod_pais == "IE" && (nif.length < 8 || nif.length > 9)) {
			return false;
		} else if (cod_pais == "LT" && ((nif.length != 9 && nif.length != 12) || (verIfNum(nif) == false))) {
			return false;
		} else if (cod_pais == "RO" && ((nif.length < 2 || nif.length > 10) || (verIfNum(nif) == false))) {
			return false;
		} else {
            return true;
        }
	} else {
        return true;
    }
}

function verIfNum(string) {
	if (string == "") {
		return false;
	}
	for (i = 0; i < string.length; i++) {
		if (isNaN(string.charAt(i))) {
			return false;
		}
	}
	return true;
}

submit_register_form = async function () {
	if ($("#clid").val() == 1) {
		cleanDirection();
	}

	if (!submit_form(document.getElementById("registerForm"), 1)) {
		var error = 1;
	} else {
		var error = 0;
	}

	if ($("#select__1__pais").val() == "ES" && $('[name="nif"]').val() == "") {
		error++;
		muestra_error_input(document.getElementById($('[name="nif"]').attr("id")));
	}

	if ($("#email__1__email").hasClass("email-error")) {
		error++;
		muestra_error_input(document.getElementById("email__1__email"));
		showMessage(messages.error.email_exist);
	}

	if ($('#pri_emp').val() == "F") {

		if ($("#texto__0__usuario").val() != undefined && $("#texto__0__usuario").val().trim() == "") {
			error = error + 1;
			muestra_error_input(document.getElementById("texto__0__usuario"), messages.error.form_required.replace(":attribute", messages.error.form_field_name));
		}

		if ($("#texto__0__last_name").val() != undefined && $("#texto__0__last_name").val().trim() == "" && $("#registerForm .apellidos").css("display") != "none") {
			error = error + 1;
			muestra_error_input(document.getElementById("texto__0__last_name"), messages.error.form_required.replace(":attribute", messages.error.form_field_surname));
		}

	}

	if ($('#pri_emp').val() == "J") {

		if ($("#texto__0__rsoc_cli").val() != undefined && $("#texto__0__rsoc_cli").val().trim() == "") {
			error = error + 1;
			muestra_error_input(document.getElementById("texto__0__rsoc_cli"), messages.error.form_required.replace(":attribute", messages.error.form_field_company));
		}

		if ($("#texto__0__contact").val() != undefined && $("#texto__0__contact").val().trim() == "") {
			error = error + 1;
			muestra_error_input(document.getElementById("texto__0__contact"), messages.error.form_required.replace(":attribute", messages.error.form_field_contact));

		}

		if ($("#select__1__pais").val() == "RO") {
			let nif_int = parseInt($('[name="nif"]').val());
			$('[name="nif"]').val(nif_int);
		}

		if (mostrarErrorNIFForeign($("#select__1__pais").val(), $('[name="nif"]').val()) == false && $("#select__1__pais").val() != "ES") {
			error++;
			muestra_error_input(document.getElementById($('[name="email"]').attr("id")));
		}
	}

	const captcha = await isValidCaptcha();
	if (!captcha) {
		error++;
	}

	if (!error) {
		var aux = $('.submitButton').html();

		var form_data = new FormData($('#registerForm')[0]);
		$.ajax({
			type: "POST",
			url: routing.registro,
			data: form_data,
			processData: false,
			contentType: false,
			beforeSend: function () {
				$('.submitButton').html('<i class="fa fa-spinner fa-pulse fa-fw margin-bottom"></i>');
			},
			success: (response) => successRegister(response, aux),
			error: function (response) {
				$('.submitButton').html(aux);
				showMessage(messages.error.error_contact_emp, "Error");
			}
		});
	} else {
		showMessage(messages.error.hasErrors);
	}
}

