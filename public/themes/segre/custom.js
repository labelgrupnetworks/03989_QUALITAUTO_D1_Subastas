$(document).ready(function () {
	$(document).off("scroll");

	$('.user-account').click(function () {
		$(this).find('.mega-menu').toggle();
	})
});

function sendInfoLotRequest() {
	$.ajax({
		type: "POST",
		data: $("#infoLotForm").serialize(),
		url: '/api-ajax/ask-info-lot',
		success: function (res) {

			showMessage("¡Gracias! Hemos sido notificados.  ");
			$("input[name=nombre]").val('');
			$("input[name=email]").val('');
			$("input[name=telefono]").val('');
			$("textarea[name=comentario]").val('');

		},
		error: function (e) {
			showMessage("Ha ocurrido un error y no hemos podido ser notificados");
		}
	});
}

function sendInfoLot() {
	validateCaptchaMiddleware(sendInfoLotRequest);
}
