$(document).ready(function () {

	$('.lot-action_pujar_on_line').off('click');

	$('.lot-action_pujar_on_line').on('click', function (e) {

		e.stopPropagation();
		$.magnificPopup.close();
		//si pulsan el boton de puja donde viene un valor

		if ($(e.target)[0].hasAttribute("value")) {
			precioOrden = $(e.target).attr("value");

		} else { //si pulsan el boton de autopuja
			precioOrden = $("#bid_amount").val();
		}

		$(".precio_orden").html(precioOrden.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1."));

		//divisas, debe existir el selector
		if (typeof $("#currencyExchange").val() != 'undefined') {
			changeCurrency(precioOrden, $("#currencyExchange").val(), "newBidExchange_JS");
		}


		if (typeof cod_licit == 'undefined' || cod_licit == null) {
			$('.login_desktop').fadeToggle("fast");
			$('.login_desktop [name=email]').focus();
			return;
		}
		else {

			if (!auction_info.user.is_gestor && (isNaN(parseInt($("#bid_amount").val())) || parseInt($("#bid_amount").val()) < parseInt(auction_info.lote_actual.importe_escalado_siguiente))) {
				$("#insert_msg_title").html($("#bid_amount").val() + "€ " + messages.error.lower_bid);
				$("#insert_msg").html(messages.error.your_bid + " " + auction_info.lote_actual.importe_escalado_siguiente + " € " + messages.error.as_minimum);
				$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
			}
			else {

				$('#inf_value').hide();
				$.magnificPopup.open({ items: { src: '#modalPujarFicha' }, type: 'inline' }, 0);
			}
		}
	});

});


function reservePriceNotReached(){
	$('#inf_value').show();
}
