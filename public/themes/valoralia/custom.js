$(() => {
	$(".confirm_delete").off('click');
	$(".confirm_delete").on('click', deleteOrder);

	$("#confirm_orden").off('click');
	$("#confirm_orden").on('click', confirmarOrdenEvent);

	/* formatDateTimesLotSheet(document.querySelector(".init-time-container .time-value"));
	formatDateTimesLotSheet(document.querySelector(".finish-time-container .time-value")); */

	$('#infoLotForm').on('submit', sendInfoLot);
})

/**
 * @param {Event} event
 */
function deleteOrder(event) {
	const ref = event.target.getAttribute("ref");
	const sub = event.target.getAttribute("sub");

	$.magnificPopup.close();

	$.ajax({
		type: "POST",
		url: '/api-ajax/delete_order',
		data: { ref, sub },
		success: function (response) {
			const responseJson = JSON.parse(response);
			if (responseJson.status == 'success') {
				location.reload();
			} else {
				if ($(responseJson.respuesta).empty()) {
					$("#" + responseJson.respuesta + " .form-group-custom input").addClass("has-error-custom");
				}
				$("#insert_msg").html(messages.error[res.msg]);
				$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
			}
		}
	});
}

function formatDateTimesLotSheet(elementWithDate) {
	if (elementWithDate) {
		elementWithDate.innerHTML = format_date_large(new Date(elementWithDate.dataset.date.replace(/-/g, "/")), '');
	}
}

//cambiar el mensaje de respuesta
function confirmarOrdenEvent(event) {

	imp = $("#bid_modal_pujar").val();
	tel1 = "";
	tel2 = "";
	ortherphone = false;

	if ($("#orderphone").val() == "S") {

		tel1 = $("#phone1Bid_JS").val();
		tel2 = $("#phone2Bid_JS").val();
		ortherphone = true;

		if (tel1.length == 0 && tel2.length == 0) {
			$("#errorOrdenFicha").removeClass("hidden");
			$("#errorOrdenFicha").html(messages.error["noPhoneInPhoneBid"]);
			/* Evitamos que se cierre */
			event.preventDefault();
			return;
		}
	}

	$("#errorOrdenFicha").addClass("hidden");
	$.magnificPopup.close();

	$.ajax({
		type: "POST",
		url: routing.ol + '-' + cod_sub,
		data: { cod_sub, ref, imp, tel1, tel2, ortherphone },
		success: confirmarOrdenResponse
	});
}

function confirmarOrdenResponse(data) {

	if (data.status == 'error') {

		$("#insert_msg_title").html("");
		$("#insert_msg").html(data.msg_1);
		$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
		return;
	}

	//divisas, debe existir el selector
	if (typeof $("#currencyExchange").val() != 'undefined') {
		changeCurrency(data.imp, $("#currencyExchange").val(), "yourOrderExchange_JS");
	}

	$("#insert_msg_title").html("");
	$("#insert_msg").html(messages.success.correct_bid_concursal);

	$("#tuorden").html(data.imp.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1."));
	$("#actual_max_bid").html(data.open_price);
	$("#bid_modal_pujar").val(data.imp_actual);

	hideElements('#text_actual_no_bid');
	showElements('#text_actual_max_bid', '.hist_new', '.custom', '.delete_order');

	if (data.winner) {
		$("#max_bid_color").addClass("winner");
		$("#max_bid_color").removeClass("no_winner");
	} else {
		$("#max_bid_color").removeClass("winner");
		$("#max_bid_color").addClass("no_winner");
	}

	$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
}

function showElements(...elementSelector) {
	elementSelector.forEach(function (element) {
		$(element).removeClass("hidden");
	});
}

function hideElements(...elementSelector) {
	elementSelector.forEach(function (element) {
		$(element).addClass("hidden");
	});
}

function userLogin(event) {
	$('.login_desktop').fadeToggle("fast");
	$('.login_desktop [name=email]').focus();
}

function sendInfoLot(event) {
	event.preventDefault();

	if (!submit_form(event.target, 1)) {
		showMessage(messages.error.hasErrors);
		return;
	}

	$.ajax({
		type: "POST",
		data: $("#infoLotForm").serialize(),
		url: '/api-ajax/ask-info-lot',
		success: function (res) {
			showMessage("¡Gracias! Hemos sido notificados.  ");
			document.querySelector("[name=telefono]").value = "";
			document.querySelector("[name=comentario]").value = "";
			document.querySelector("[name=user_price]").value = "";
			document.querySelector("#infoLotForm [name=condiciones]").checked = false;
		},
		error: function (e) {
			showMessage("Ha ocurrido un error y no hemos podido ser notificados");
		}
	});
}

ajax_newcarousel = function (key, replace, lang) {

	$.ajax({
		type: "POST",
		url: "/api-ajax/newcarousel",
		data: { key: key, replace: replace, lang: lang, orders: 'cerrado_asigl0, close_at' },
		success: function (result) {

			if (result === '') {
				$("#" + key + '-content').hide();
			}
			$("#" + key).siblings('.loader').addClass('hidden');
			$("#" + key).html(result);
			//cargar cuenta atras
			$('[data-countdown]').each(function (event) {

				var countdown = $(this);
				countdown.data('ini', new Date().getTime());
				countdown_timer(countdown);

			});

		}

	});
}

function hideOpenTimer(countdown, params) {
	params = params.split(',');
	$('.' + params[0]).addClass('hidden');
	$('.' + params[1]).removeClass('hidden');
}
