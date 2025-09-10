/**
 * @param {Event} event
 */
handleSubmitRegisterForm = async function (event) {
	event.preventDefault();

	const captcha = await isValidCaptcha();
	if (!captcha) {
		showMessage(messages.error.hasErrors);
		return;
	}

	const form = event.target;

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

successRegister = function(response, aux) {
	document.location = '/es/usuario-registrado';
}
