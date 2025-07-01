
successRegister = function (response, aux) {
	response = $.parseJSON(response);

	if (response.err == 1) {
		$('.submitButton').html(aux);
		response.message = response.msg;
		response.status = "error";
		showMessage(response);

	} else if (response.err == 0) {

		if (response.info == undefined) {

			//Si venimos de una web externa volvemos a ella.
			const queryParams = new URLSearchParams(window.location.search);
			if(Boolean(queryParams.get('context_url'))){

				document.location = `${queryParams.get('context_url')}${response.msg}`;
				return;
			}

			document.location = response.msg;
			return;
		}

		//subalia
		$("#info_sent").val(JSON.stringify(response.info));
		$("#cod_auchouse_sent").val(response.cod_auchouse);
		$("#redirect_sent").val(response.redirect);

		document.getElementById("formToSubalia").submit();
	}
}

/**
 * Cuand el registro tiene exito, se realiza el login en subastas y en presta
 * Si se tiene la variable back, nos mantenemos en presta, si no se regresa a substas.
 */
/**
 * ¿Deprecated?
 * Se debe validar email para poder realizar login, por lo que no tiene sentido
 */
function login_session(){

	const data = {
		_token: $("input[name=_token]").val(),
		email: $("input[name=email]").val(),
		password: $("input[name=password]").val(),
		back: $("input[name=back]").val(),
		context_url: $("input[name=context_url]").val()
	}

	$.ajax({
		type: "POST",
		url: '/login_post_ajax',
		data: data,
		success: function(response) {
			if (response.status == 'success') {

				//En Duran es necesario que posteriormente al registro, te validen el usuario, por lo que
				//nunca irá al success...

				externalLogin(response.context_url, response.data);
				//prestaLoginRegister(JSON.stringify(response.data));
				//setTimeout(function(){ document.getElementById("logo_link").click(); }, 500);
			}
		},
		complete: function () {
			setTimeout(function(){ document.getElementById("logo_link").click(); }, 500);
		}


	});
}

particular = function() {
	$("#pri_emp").val("F");
	$(".tipo_usuario .empresa").removeClass("selected");
	$(".tipo_usuario .particular").addClass("selected");
	$(".registerParticular").show();
	$(".registerEnterprise").hide();
	$(".datos_contacto .cif").hide();
	$(".datos_contacto .nif").show();
	$(`select[name='tdocid_cli']`).prop("id", `select__1__tdocid_cli`);
	inputRequired('last_name', true);

};
empresa = function() {

	$("#pri_emp").val("J");
	$(".tipo_usuario .empresa").addClass("selected");
	$(".tipo_usuario .particular").removeClass("selected");
	$(".registerParticular").hide();
	$(".registerEnterprise").show();
	$(".datos_contacto .cif").show();
	$(".datos_contacto .nif").hide();
	$(`select[name='tdocid_cli']`).prop("id", `select__0__tdocid_cli`);
	inputRequired('last_name', false);
};


/**
 * ¿Deprecated?
 */
function prestaLoginRegister(res) {
	$("#valoresPresta").val(JSON.stringify(res));

	const iframe = document.getElementById("iframePresta");

	if (!iframe) return;

	var innerDoc = iframe.contentDocument || iframe.contentWindow.document;

	let $bodyFrame =  $(innerDoc.querySelector('body'));
	$bodyFrame.append($('#formPresta'));

	$bodyFrame[0].querySelector('#formPresta').submit();

	return true;
}

function showNIFMessage() {
	$.magnificPopup.open({ items: { src: '#nifFilePopUpInformation' }, type: 'inline' }, 0);
}
