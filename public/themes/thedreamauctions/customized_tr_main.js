$(document).ready(function () {

	$('.lot-action_pujar_on_line').off('click');
	$('.lot-action_pujar_on_line').on('click', function (e) {

		e.stopPropagation();
		$.magnificPopup.close();

		//este codigo abre directamente la ventana emergente de login
		if (typeof cod_licit == 'undefined' || cod_licit == null) {

			$('.login_desktop').fadeToggle("fast");
			$('.login_desktop [name=email]').trigger('focus');

			return;
		}

		//si pulsan el boton de puja donde viene un valor
		if ($(e.target)[0].hasAttribute("value")) {
			precioOrden = $(e.target).attr("value");

		}
		//Para pujas en firme
		else if (typeof e.target.dataset?.tipopuja != "undefined") {
			$('#tipo_puja_gestor').val(e.target.dataset?.tipopuja);
			$('#bid_amount').val($('#bid_amount_firm').val());
			precioOrden = $('#bid_amount_firm').val();
		}
		else { //si pulsan el boton de autopuja
			precioOrden = $("#bid_amount").val();
			$('#tipo_puja_gestor').val('');
		}

		$(".precio_orden").html(precioOrden.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1."));

		//divisas, debe existir el selector
		if (typeof $("#currencyExchange").val() != 'undefined') {
			changeCurrency(precioOrden, $("#currencyExchange").val(), "newBidExchange_JS");
		}

		if (!auction_info.user.is_gestor && (isNaN(parseInt($("#bid_amount").val())) || parseInt($("#bid_amount").val()) < parseInt(auction_info.lote_actual.importe_escalado_siguiente))) {
			$("#insert_msg_title").html($("#bid_amount").val() + "€ " + messages.error.lower_bid);
			$("#insert_msg").html(messages.error.your_bid + " " + auction_info.lote_actual.importe_escalado_siguiente + " € " + messages.error.as_minimum);
			$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);

		}
		else {
			$.magnificPopup.open({ items: { src: '#modalPujarFicha' }, type: 'inline' }, 0);
		}

	});



	/**
	 * Observer
	 * Ocultar bloque precio de salida al relizar una primera puja
	 */
	var $element = $("#text_actual_max_bid");
	var observer = new MutationObserver(function (mutations) {
		mutations.forEach(function (mutation) {
			if (mutation.attributeName === "class") {
				var attributeValue = $(mutation.target).prop(mutation.attributeName);

				console.log(attributeValue);
				if(!attributeValue.includes("hidden")){
					$('.starting-price-wrapper').hide();
				}
			}
		});
	});

	if ($element.length > 0) {
		observer.observe($element[0], {
			attributes: true,
		});
	}

});


actionResponseDesign_W = function(data) {
	if(auction_info.subasta.currency.symbol == '$' || auction_info.subasta.currency.symbol == 'US$'){
		$('#actual_max_bid').html(auction_info.subasta.currency.symbol + data.formatted_actual_bid )
	}else{
		$('#actual_max_bid').html(data.formatted_actual_bid + " "+ auction_info.subasta.currency.symbol);
	}

	//$('#text_actual_no_bid').addClass('hidden');
	$('#text_actual_max_bid').removeClass('hidden');

	if (typeof auction_info.user != 'undefined' && data.winner == auction_info.user.cod_licit) {

		winner();


		//no lo estamos usando
		/* $('#tupuja').html(data.formatted_actual_bid);

		if (auction_info.user.cod_licit == data.cod_licit_actual && data.test[0] == "Entramos en OL") {
			$('#tuorden').html(data.imp_original_formatted);
			$(".delete_order").removeClass("hidden");
		}

		$('#cancelarPujaUser').removeClass('hidden'); */

	} else {

		looser();

		/* if (typeof auction_info.user != 'undefined' && auction_info.user.cod_licit == data.cod_licit_actual) {
			$('#tupuja').html(data.imp_original_formatted);
		} */

		/* Si es gestor nunca se oculta el cancelar puja*/
		/* Falta una comprobación para el gestor en caso de que no haya nada que cancelar (una puja)*/
		/* if (typeof auction_info.user != 'undefined' && auction_info.user.is_gestor) {
			$('#cancelarPujaUser').removeClass('hidden');
		} else {
			$('#cancelarPujaUser').addClass('hidden');
		} */
	}

	if( typeof data.actual_bid != 'undefined' && typeof auction_info.lote_actual.impres_asigl0 != 'undefined' &&  parseInt(auction_info.lote_actual.impres_asigl0) > parseInt(data.actual_bid)){
		reservePriceNotReached();
	}
}

function winner(){
	$('#actual_max_bid').addClass('mine');
	$('#actual_max_bid').removeClass('other');
	$('#text_highest_bidder').removeClass('hidden');

	$('.reserv-price .reached').removeClass('hidden');
	$('.reserv-price .not-reached').addClass('hidden');
}

function looser(){
	$('#actual_max_bid').addClass('other');
	$('#actual_max_bid').removeClass('mine');
	$('#text_highest_bidder').addClass('hidden');

	$('.reserv-price .reached').removeClass('hidden');
	$('.reserv-price .not-reached').addClass('hidden');
}

function reservePriceNotReached(){
	$('#actual_max_bid').removeClass('mine');
	$('#actual_max_bid').removeClass('other');

	$('.reserv-price .reached').addClass('hidden');
	$('.reserv-price .not-reached').removeClass('hidden');
}


