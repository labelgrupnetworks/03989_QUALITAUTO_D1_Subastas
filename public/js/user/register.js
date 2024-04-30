/****************************************************************************************/
/*************************** FUNCIONES PARA REGISTRO DE USUARIO *************************/
/****************************************************************************************/

    function particular() {

        $("#pri_emp").val("F").trigger("change");
        $(".tipo_usuario .empresa").removeClass("selected");
        $(".tipo_usuario .particular").addClass("selected");
        $(".registerParticular").show();
        $(".registerEnterprise").hide();
        $(".datos_contacto .cif").hide();
        $(".datos_contacto .nif").show();
		inputRequired('last_name', true);

    };
    function empresa() {

        $("#pri_emp").val("J").trigger("change");
        $(".tipo_usuario .empresa").addClass("selected");
        $(".tipo_usuario .particular").removeClass("selected");
        $(".registerParticular").hide();
        $(".registerEnterprise").show();
        $(".datos_contacto .cif").show();
        $(".datos_contacto .nif").hide();
		inputRequired('last_name', false);
    };

    function hombre() {

        $("#sexo").val("H");
        $(".tipo_sexo .mujer").removeClass("selected");
        $(".tipo_sexo .hombre").addClass("selected");
    };

    function mujer() {

        $("#sexo").val("M");
        $(".tipo_sexo .mujer").addClass("selected");
        $(".tipo_sexo .hombre").removeClass("selected");
    };

    function clidNotRequired(){
        //cambiar requerimiento de inputs
            $("#select__1__clid_pais").attr("id", "select__0__clid_pais")
            $("#texto__1__clid_cpostal").attr("id", "texto__0__clid_cpostal")
            $("#texto__1__clid_poblacion").attr("id", "texto__0__clid_poblacion");
            $("#texto__1__clid_provincia").attr("id", "texto__0__clid_provincia");
            $("#select__1__clid_codigoVia").attr("id", "select__0__clid_codigoVia");
            $("#texto__1__clid_direccion").attr("id", "texto__0__clid_direccion");

            //indicar que se utilizaran los inputs por defecto
            $('#clid').val(1);
    }

    function clidRequired(){

            $("#select__0__clid_pais").attr("id", "select__1__clid_pais")
            $("#texto__0__clid_cpostal").attr("id", "texto__1__clid_cpostal")
            $("#texto__0__clid_poblacion").attr("id", "texto__1__clid_poblacion");
            $("#texto__0__clid_provincia").attr("id", "texto__1__clid_provincia");
            $("#select__0__clid_codigoVia").attr("id", "select__1__clid_codigoVia");
            $("#texto__0__clid_direccion").attr("id", "texto__1__clid_direccion");

            $('#clid').val(0);
	}


$(document).ready(function () {

	$('[name="clid_cpostal"]').on('blur', function () {
		var country = $('[name="clid_pais"]').val();
		var zip = $(this).val();
		if (country != '') {
			$.ajax({
				type: "POST",
				data: { zip: zip, country: country },
				url: '/api-ajax/cod-zip',
				success: function (msg) {
					if (msg.status == 'success') {
						$('[name="clid_poblacion"]').val(msg.pob);
						$('[name="clid_provincia"]').val(msg.des_prv);
					}
				}
			});
		}
	});



    $(".create-account #email__1__email").on("blur", function () {

        email = $(this).val();
        $.ajax({
            type: "POST",
            data: {email: email},
            url: '/api-ajax/exist-email',
            success: function (msg) {
                if (msg.status == 'error') {

                    if (typeof specificModalEmailExist === "function") {
                        specificModalEmailExist();
                    } else {
                        muestra_error_input(document.getElementById("email__1__email"));
                        showMessage(messages.error.email_exist);
                        $("#email__1__email").addClass("email-error");
                    }

                } else {
                    $("#email__1__email").removeClass("email-error");
                }
            }
        });

    });


    $(".create-account [name='nif']").on("blur", function () {

		nif = $(this).val().toUpperCase();
        if (nif != "") {
            $.ajax({
                type: "POST",
                data: {nif: nif},
                url: '/api-ajax/exist-nif',
                success: function (msg) {
                    if (msg.status == 'error') {

                        if (typeof specificModalEmailExist === "function") {
                            specificModalEmailExist();
                        } else {
                            muestra_error_input(document.getElementById($("[name='nif']").attr("id")));
                            showMessage(messages.error.nif_exist);
                            $("[name='nif']").addClass("email-error");
                        }

                    } else {
                        $("[name='nif']").removeClass("email-error");
                    }
                }
            });
        }

    });

    $('#shipping_address').change(function () {

        let colapse = $('#collapse_direccion');

        if (this.checked) {

             $('#collapse_direccion').hide("slow");
             clidNotRequired();

        } else {
            //inversa
            $('#collapse_direccion').show("slow");
            clidRequired();

        }
    });


});

function cleanDirection(){
	$("#select__0__clid_pais").val($("#select__1__pais").val());
    $("#texto__0__clid_cpostal").val($("#texto__1__cpostal").val());
    $("#texto__0__clid_poblacion").val($("#texto__1__poblacion").val());
    $("#texto__0__clid_provincia").val($("#texto__1__provincia").val());
    $("#select__0__clid_codigoVia").val($("#select__1__codigoVia").val());
    $("#texto__0__clid_direccion").val($("#texto__1__direccion").val());
}


function submit_register_form() {

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
    } else {
		eraseSpacesOnNif();
	}

    if ($("#email__1__email").hasClass("email-error")) {
        error++;
        muestra_error_input(document.getElementById("email__1__email"));
        showMessage(messages.error.email_exist);
    }

    if ($('#pri_emp').val() == "F") {

        if (  $("#texto__0__usuario").val() != undefined && $("#texto__0__usuario").val().trim() == "") {
            error = error + 1;
            muestra_error_input(document.getElementById("texto__0__usuario"), messages.error.form_required.replace(":attribute", messages.error.form_field_name));
        }

        if (  $("#texto__0__last_name").val() != undefined && $("#texto__0__last_name").val().trim() == "" && $("#registerForm .apellidos").css("display") != "none" ) {
            error = error + 1;
            muestra_error_input(document.getElementById("texto__0__last_name"), messages.error.form_required.replace(":attribute", messages.error.form_field_surname));
        }

    }

    if ($('#pri_emp').val() == "J") {

        if ( $("#texto__0__rsoc_cli").val() != undefined && $("#texto__0__rsoc_cli").val().trim() == "") {
            error = error + 1;
            muestra_error_input(document.getElementById("texto__0__rsoc_cli"), messages.error.form_required.replace(":attribute", messages.error.form_field_company));
        }

        if ( $("#texto__0__contact").val() != undefined && $("#texto__0__contact").val().trim() == "") {
            error = error + 1;
            muestra_error_input(document.getElementById("texto__0__contact"), messages.error.form_required.replace(":attribute", messages.error.form_field_contact));

        }

    }

    response = $("#g-recaptcha-response").val();
    if (!response) {
        error = error + 1;
        $(".g-recaptcha iframe").addClass("has-error");
    } else {
        $(".g-recaptcha iframe").removeClass("has-error");
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

function successRegister(response, aux) {
	response = $.parseJSON(response);
	if (response.err == 1) {
		$('.submitButton').html(aux);
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


function inputRequired(name, required) {
	let valRequired = required ? 1 : 0;
	$(`input[name='${name}']`).prop("id", `texto__${valRequired}__${name}`);
}

function eraseSpacesOnNif() {
	$('[name="nif"]').val($('[name="nif"]').val().replace(/\s/g, ''));
}
