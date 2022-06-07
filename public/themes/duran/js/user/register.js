
successRegister = function (response, aux) {
	response = $.parseJSON(response);

	if (response.err == 1) {
		$('.submitButton').html(aux);
		response.message = response.msg;
		response.status = "error";
		showMessage(response);

	} else if (response.err == 0) {

		if (response.info == undefined) {

			if($("input[name=back]").val() == 'gallery'){
				login_session();
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
function login_session(){

	$.ajax({
		type: "POST",
		url: '/login_post_ajax',
		data: { _token: $("input[name=_token]").val(), email: $("input[name=email]").val(), password: $("input[name=password]").val(), back: $("input[name=back]").val() },
		success: function(response) {
			if (response.status == 'success') {

				prestaLoginRegister(JSON.stringify(response.data));

				//setTimeout(function(){ document.getElementById("logo_link").click(); }, 500);
			}
		},
		complete: function () {
			setTimeout(function(){ document.getElementById("logo_link").click(); }, 500);
		}


	});
}


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

