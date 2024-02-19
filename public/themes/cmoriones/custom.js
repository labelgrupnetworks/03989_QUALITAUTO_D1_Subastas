async function sendInfoLot(event) {
	event.preventDefault();
	event.stopPropagation();

	await executeCaptchaV3();

	if(!checkCaptcha()) {
		showMessage("Ha ocurrido un error y no hemos podido ser notificados");
		return;
	}

	const submitButton = event.target.querySelector('button[type=submit]');
	submitButton.disabled = true;

	$.ajax({
		type: "POST",
		data: $("#infoLotForm").serialize(),
		url: '/api-ajax/ask-info-lot',
		success: function(res) {

			showMessage(messages.success.mensaje_enviado);
			$("input[name=nombre]").val('');
			$("input[name=email]").val('');
			$("input[name=telefono]").val('');
			$("textarea[name=comentario]").val('');

		},
		error: (e) => showMessage("Ha ocurrido un error y no hemos podido ser notificados"),
		complete: () => submitButton.disabled = false

	});

	return false;
}
