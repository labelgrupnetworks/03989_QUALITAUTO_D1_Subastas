$(function () {

	const dropdownElementList = document.querySelectorAll('.nav-item.dropdown');
	const matchMedia992 = window.matchMedia("(min-width: 992px)");

	[...dropdownElementList].forEach(dropdownItemToggleEl => {

		const dropdownToggleEl = dropdownItemToggleEl.querySelector('.dropdown-toggle[data-lb-trigger="hover"]');
		if(!dropdownToggleEl) {
			return;
		}

		const dropdownElement = new bootstrap.Dropdown(dropdownToggleEl);

		dropdownItemToggleEl.addEventListener('mouseover', (e) => {
			if(!matchMedia992.matches) {
				return;
			}
			dropdownElement.show();
		});

		dropdownItemToggleEl.addEventListener('mouseleave', (e) => {
			if(!matchMedia992.matches) {
				return;
			}
			dropdownElement.hide();
		});
	});
});


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

			showMessage("¡Gracias! Hemos sido notificados.  ");
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

function copyTextToClipboard(text) {

	const input = document.createElement('input');
	input.value = text;
	document.body.appendChild(input);
	input.select();
	document.execCommand('copy');
	document.body.removeChild(input);

	showMessage("Copiado al portapapeles");
	return false;
}
