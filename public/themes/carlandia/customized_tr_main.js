/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



function action_success(data){
	//si el la accion del usuario actual ha tenido exito
	if (auction_info.lote_actual.tipo_sub == 'O' || auction_info.lote_actual.tipo_sub == 'P' ){
		if(typeof auction_info.user != 'undefined' && auction_info.user.cod_licit == data.cod_licit_actual){
			if (data.status == 'success') {
				//añadir eventos compra ya venta directa
				matricula = $("#matricula_JS").val();
				precio =$("#price_compra_ya_JS").val();
				coche = $("#nombre_coche_JS").val();
				typePuja = $("#typePuja_JS").val();
				if(typePuja=='manual'){
					ga('send','event','FORMULARIO SUBASTA PUJA MANUAL',coche  + "/" +  matricula,precio);
				}else if(typePuja=='automatica'){
					ga('send','event','FORMULARIO SUBASTA PUJA AUTOMÁTICA',coche  + "/" +  matricula,precio);
				}
			//evento fbq
				fbq('track', 'Lead', {value: 1,  });
			}
		}
	}
}


/*
   |--------------------------------------------------------------------------
   | Repinta la caja de pujas
   |--------------------------------------------------------------------------
   */
$(document).ready(function () {

	$('.lot-action_pujar_on_line').off('click');
	$('.lot-action_pujar_on_line').on('click', function (e) {

		e.stopPropagation();
		$.magnificPopup.close();

		//este codigo abre directamente la ventana emergente de login
		if (typeof cod_licit == 'undefined' || cod_licit == null) {

			$('.ficha_login_desktop').fadeToggle("fast");
			$('.ficha_login_desktop [name=email]').trigger('focus');

			return;
		}

		//si pulsan el boton de puja donde viene un valor
		if ($(e.target)[0].hasAttribute("value")) {
			precioOrden = $(e.target).attr("value");
			$('#typePuja_JS').val("manual");
		}
		//Para pujas en firme
		else if (typeof e.target.dataset?.tipopuja != "undefined") {
			$('#tipo_puja_gestor').val(e.target.dataset?.tipopuja);
			$('#bid_amount').val($('#bid_amount_firm').val());
			precioOrden = $('#bid_amount_firm').val();
			$('#typePuja_JS').val("manual");
		}
		else { //si pulsan el boton de autopuja
			$('#tipo_puja_gestor').val('');
			precioOrden = $("#bid_amount").val();
			$('#typePuja_JS').val("automatica");
		}

		$(".precio_orden").html(precioOrden.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1."));

		//divisas, debe existir el selector
		if (typeof $("#currencyExchange").val() != 'undefined') {
			changeCurrency(precioOrden, $("#currencyExchange").val(), "newBidExchange_JS");
		}

		if (!auction_info.user.is_gestor && (isNaN(parseInt($("#bid_amount").val())) || parseInt($("#bid_amount").val()) < parseInt(auction_info.lote_actual.importe_escalado_siguiente))) {
			$("#insert_msg_title").html($("#bid_amount").val() + "€ " + messages.error.lower_bid);
			$("#insert_msg").html(messages.error.your_bid + " " + formatMoney({money: auction_info.lote_actual.importe_escalado_siguiente, decimals: 0}));
			$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);

		}
		else {
			$.magnificPopup.open({ items: { src: '#modalPujarFicha' }, type: 'inline' }, 0);
		}

	});

	/**
	 * Observer
	 * Cuando se realiza la primera puja, se modfica el color
	 * del bloque puja actual, si se ha iniciado sesiona
	 */
	 const $element = $("#text_actual_max_bid");
	 const observer = new MutationObserver(function (mutations) {
		 mutations.forEach(function (mutation) {

			if (mutation.attributeName === "class") {

				 const attributeValue = $(mutation.target).prop(mutation.attributeName);
				 if(!attributeValue.includes("hidden") && typeof cod_licit != 'undefined' && cod_licit != null){

					$('.explanation_bid .min_bid').removeClass('hidden');
					$('.explanation_bid .no_bids').addClass('hidden');

					//¿? comprobar si la puja maxima es mia o no
					 //$('div.pre-actualbid').addClass('mine');
				 }
			 }
		 });
	 });

	 if ($element.length > 0) {
		 observer.observe($element[0], {
			 attributes: true,
		 });
	 }

	 const $maxBid = $('#actual_max_bid');
	 const observerMaxBId = new MutationObserver(function (mutations) {
		mutations.forEach(function (mutation) {

		   if (mutation.attributeName === "class" && typeof cod_licit != 'undefined' && cod_licit != null) {

				const attributeValue = $(mutation.target).prop(mutation.attributeName);

				if(attributeValue.includes("mine")){
					$('.pre-actualbid').addClass('mine').removeClass('other');
				}
				else if(attributeValue.includes("other")){
					$('.pre-actualbid').addClass('other').removeClass('mine');
				}

			}
		});
	});

	if ($maxBid.length > 0) {
		observerMaxBId.observe($maxBid[0], {
			attributes: true,
		});
	}

});


reloadPujasList = function() {

	if (auction_info.subasta.sub_tiempo_real == 'S') {
		reloadPujasList_W()
	} else {

		$('#bid_amount').val('');

		let precioCompra = auction_info.lote_actual.imptash_asigl0;
		let pujaActual = auction_info.lote_actual.actual_bid;

		if(pujaActual > precioCompra){
			$('#js-buylot-block').hide();
		}

		reloadPujasList_O()
	}
	if(typeof loadDivisa === 'function') {
		loadDivisa();
	}

}


/*
	Reescribo la función que está en el customized_tr_main de default para poder corregir el problema de que cuando un usuario puja
	y otro no tiene pujas que no cambie a rojo el recuadro de la puja actual.
*/
actionResponseDesign_W = function (data) {

	if(frontCurrencies.includes(auction_info.subasta.currency.symbol)){
		$('#actual_max_bid').html(auction_info.subasta.currency.symbol + data.formatted_actual_bid )
	}else{
		$('#actual_max_bid').html(data.formatted_actual_bid + " "+ auction_info.subasta.currency.symbol);
	}

	$('#text_actual_no_bid').addClass('hidden');
	$('#text_actual_max_bid, .text_actual_max_bid').removeClass('hidden');

	// Si el usuario no tiene pujas no se ejecuta el código de abajo
	/* if (!auction_info.user.pujaMaxima) {
		return;
	} */

	// En el data recibido por le socket comprueba si en el pujasAll está tu código de licitador, si está entrará en el siguiente if
	pujaFind = data.pujasAll.some((puja) => {
        return puja.cod_licit == auction_info.user.cod_licit;
    });

	if (pujaFind) {
		if (typeof auction_info.user != 'undefined' && data.winner == auction_info.user.cod_licit) {
			$('#tupuja').html(data.formatted_actual_bid);

			if (auction_info.user.cod_licit == data.cod_licit_actual && data.test[0] == "Entramos en OL") {
				$('#tuorden').html(data.imp_original_formatted);
				$(".delete_order").removeClass("hidden");
			}

			$('#actual_max_bid').addClass('mine');
			$('#actual_max_bid').removeClass('other');
			$('#cancelarPujaUser').removeClass('hidden');
			/*console.log('bid 1');*/

			$('.status_bid').addClass('mine');
			$('.status_bid').removeClass('other');


		} else {
			$('#actual_max_bid').addClass('other');
			$('#actual_max_bid').removeClass('mine');

			$('.status_bid').addClass('other');
			$('.status_bid').removeClass('mine');
			/*console.log('bid 2');*/

			if (typeof auction_info.user != 'undefined' && auction_info.user.cod_licit == data.cod_licit_actual) {
				$('#tupuja').html(data.imp_original_formatted);
			}

			/* Si es gestor nunca se oculta el cancelar puja*/
			/* Falta una comprobación para el gestor en caso de que no haya nada que cancelar (una puja)*/
			if (typeof auction_info.user != 'undefined' && auction_info.user.is_gestor) {
				$('#cancelarPujaUser').removeClass('hidden');
			} else {
				$('#cancelarPujaUser').addClass('hidden');
			}
		}
	}

}
