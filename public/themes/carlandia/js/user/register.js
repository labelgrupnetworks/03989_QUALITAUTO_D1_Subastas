Date.prototype.toDateInputValue = (function() {
    var local = new Date(this);
	if(local == 'Invalid Date'){
		return false;
	}

    local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
    return local.toJSON().slice(0,10);
});

$(document).ready(function () {


	$('input[name=year]').on('keyup', function(){

		if(this.value.match(/[a-zA-z]+/) || this.value.length < $(this).attr('minlength')){
			$(this).addClass('effect-26').addClass('has-error');
			return;
		}

		$(this).removeClass('effect-26').removeClass('has-error');
	});


	$('input[name=year]').on('keypress', function(e){
		if(this.value.length == 4){
			return false;
		}

	});

	$('input[name=year]').on('blur', function(e){

		let date = new Date(this.value).toDateInputValue();

		if(!date){
			$(this).addClass('effect-26').addClass('has-error');
			return false;
		}
		$('input[name=date]').val(date);
	});


	$('[name="cpostal"]').on('blur', (event) => {
		document.querySelector('[name=provincia]').closest('div').classList.add('withfocus');
		document.querySelector('[name=poblacion]').closest('div').classList.add('withfocus');
	})
});

function reloadPlaceholders() {

	$('input[type="text"]').each(function () {
		$(this).attr('placeholder', $(this).siblings('label').text().trim());
	});

	$('input[type="password"]').each(function () {
		$(this).attr('placeholder', $(this).siblings('label').text());
	});

}

function forceRequiredInputs(){
	$('input[name="usuario"]').attr('id', 'texto__1__usuario');
	$('input[name="last_name"]').attr('id', 'texto__1__last_name');
}


submit_register_form = function() {

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
            success: function (response) {
                response = $.parseJSON(response);
                if (response.err == 1) {
                    $('.submitButton').html(aux);
                    response.message = response.msg;
                    response.status = "error";
                    showMessage(response);
                } else if (response.err == 0) {

					fbq('track', 'CompleteRegistration', {value: 1.00,});
					if(	$("input[name=type_user]").val() =="V"){
						ga('send','event','CUENTA CLIENTE','Vendedor');
					}else{
						if ($("input[name=pri_emp]").val() == "J"){
							ga('send','event','CUENTA CLIENTE','Comprador profesional');
						}else{
							ga('send','event','CUENTA CLIENTE','Comprador particular');
						}
					}

					if (response.backTo){
						//Para que en la ficha de los lotes funcione el histoy.state es necesario volver hacia atras
						history.go(-1);
						//document.location = response.backTo;
					}/*
					if(sessionStorage.getItem('returnUrl')){
						document.location = sessionStorage.getItem('returnUrl');

					}*/
					else if(response.info == undefined){
                        document.location = response.msg;
                    }

                    else {

                        $("#info_sent").val(JSON.stringify(response.info));
                        $("#cod_auchouse_sent").val(response.cod_auchouse);
                        $("#redirect_sent").val(response.redirect);

                        document.getElementById("formToSubalia").submit();

                    }
                }

            },
            error: function (response) {
                $('.submitButton').html(aux);
                showMessage(messages.error.error_contact_emp, "Error");
            }
        });
    } else {
        showMessage(messages.error.hasErrors);
    }
}
