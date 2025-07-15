
/*
|--------------------------------------------------------------------------
| Repinta la caja de pujas
|--------------------------------------------------------------------------
*/
$(document).ready(function () {

	$('.lot-action_pujar_on_line').on('click', pujarAction);
	$('.lot-action_pujar_no_licit').on('click', (e) => { pujarWithoutDeposit(e.currentTarget) });


	$('#representante').on('change', checkBidCondition);
	$('#send_form_ficha').on('click', sendDepositForm);

	// Con representantes revisamos si es necesario solicitar un depósito
	const isNecesaryRequestDeposit = document.getElementById('isNecesaryRequestDeposit') ? document.getElementById('isNecesaryRequestDeposit').value : false;
	if (isNecesaryRequestDeposit) {
		checkBidCondition();
	}

	initSlick();
});

const frontCurrencies = ['$', 'US$', 'COP '];

function requestDataForDeposit({cod_sub, ref, lang}) {

	$.ajax({
		type: "POST",
		url: "/api-ajax/formulario-pujar",
		data: {
			cod_sub,
			ref,
			lang
		},
		success: function (response) {
			$("#insert_msg").html(response);
			$.magnificPopup.open({ items: { src: '#modalFormularioFicha' }, type: 'inline' }, 0);

			$('[name="representado"]').val($('#representante').val());
		},
		error: function (result) {
			$("#insert_msg_title").html("");
			$("#insert_msg").html(messages.error.no_licit);
			$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
		}
	});
}

function actionResponseDesign(data) {
	if (auction_info.subasta.sub_tiempo_real == 'S') {

		if(typeof customResponseDesign_W !== "undefined"){
			customResponseDesign_W(data);
		}

		actionResponseDesign_W(data)
	} else {
		actionResponseDesign_O(data)
	}
}

function actionResponseDesign_W(data) {
	if(frontCurrencies.includes(auction_info.subasta.currency.symbol)){
		$('#actual_max_bid').html(auction_info.subasta.currency.symbol + data.formatted_actual_bid )
	}else{
		$('#actual_max_bid').html(data.formatted_actual_bid + " "+ auction_info.subasta.currency.symbol);
	}

	$('#value-view').html(numeral(data.siguiente).format('0,0' ) + " €");

	$('#text_actual_no_bid').addClass('hidden');
	$('#text_actual_max_bid, .text_actual_max_bid').removeClass('hidden');


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
function actionResponseDesign_O(data) {
	/* sirve para Duran boton directo con valor de puja */
	$("#boton_puja_directa_JS").html(data.siguiente);

	//$(".js-lot-action_pujar_escalado").attr("value", data.siguiente);
	//actualizar botones escalado
	if ( $(".js-lot-action_pujar_escalado").length) {
		reloadPujasButtons(data.pujasAll[0].cod_sub, data.actual_bid);
 	}


	actionResponseDesign_W(data);


}


function reloadPujasList() {

	if (auction_info.subasta.sub_tiempo_real == 'S') {
		reloadPujasList_W()
	} else {
		reloadPujasListO();
		//reloadPujasList_O()
	}
	if(typeof loadDivisa === 'function') {
		loadDivisa();
	}

}

function reloadPujasList_W() {
	var model = $('#type_bid_model').clone();
	var container = $('.aside.pujas #pujas_list');

	if (typeof auction_info.lote_actual != 'undefined' && typeof auction_info.lote_actual.pujas != 'undefined' && typeof container != 'undefined' && container.length > 0) {

		$('.aside.pujas .pujas_model:not(#type_bid_model)').remove();

		$.each(auction_info.lote_actual.pujas, function (key, value) {

			/* limite de pujas a mostrar*/
			if (key >= auction_info.subasta.max_bids_shown && auction_info.subasta.max_bids_shown != -1) {
				return false;
			}

			var $this = model.clone().removeClass('hidden').removeAttr('id');

			$('.importePuja .puj_imp', $this).html(value.formatted_imp_asigl1);

			var name_licit = messages.neutral.new_licit;
			if (typeof licitadores != 'undefined' && typeof licitadores[value.cod_licit] != 'undefined') {
				name_licit = licitadores[value.cod_licit];
			} else if (value.cod_licit == auction_info.subasta.dummy_bidder) {
				name_licit = '-';
			}
			/*Fin de nombre de los licitadores*/

			$('.importePuja .licitadorPuja', $this).html('(' + value.cod_licit + ')<span style="font-size: 12px;"> ' + name_licit + '</span>');

			$('.tipoPuja p:not(.hidden)', $this).addClass('hidden');
			$('.tipoPuja p[data-type="' + value.pujrep_asigl1 + '"]', $this).removeClass('hidden');

			container.append($this);
		});

	}
}

function reloadPujasListO() {

	const { lote_actual: loteActual, subasta } = auction_info;

	if(!loteActual){
		return;
	}

	const { importe_escalado_siguiente, pujas, impres_asigl0 } = loteActual;

	$('.siguiente_puja').html(new Intl.NumberFormat("de", {}).format(importe_escalado_siguiente));

	const pujasReverse = [...pujas].reverse();

	if(typeof auction_info.lote_actual.inversa_sub != 'undefined' && auction_info.lote_actual.inversa_sub == 'S'){
		 firstBidToExceedReservePriece = pujasReverse.find((licitador) => parseInt(licitador.imp_asigl1) <= parseInt(loteActual.impres_asigl0));
	}else{
		 firstBidToExceedReservePriece = pujasReverse.find((licitador) => parseInt(licitador.imp_asigl1) >= parseInt(loteActual.impres_asigl0));
	}


	const min_price_surpass = firstBidToExceedReservePriece?.imp_asigl1 || false;

	//mostramso si se ha alcanzado el precio mínimo
	document.querySelector('.precio_minimo_no_alcanzado')?.classList.toggle('hidden', min_price_surpass);
	document.querySelector('.precio_minimo_alcanzado')?.classList.toggle('hidden', !min_price_surpass);

	const codsLicits = [...new Set(pujasReverse.map((licitador) => licitador.cod_licit))];
	const licits = Object.assign({}, ...codsLicits.map((codLicit, index) => ({[codLicit]: index + 1})));

	reloadHistory({subasta, pujas, licits, importeReserva: impres_asigl0, minPriceSurpass : min_price_surpass});
}

/**
 * Reload block bids in online lots detail
 * @param {array} pujas
 * @param {object} licits
 * @param {object} subasta
 */
function reloadHistory({subasta, pujas, licits, importeReserva, minPriceSurpass}){

	const importeReservaFormatted = new Intl.NumberFormat("de", {minimumFractionDigits: 0, maximumFractionDigits: 2, style: 'currency', currency: 'EUR'}).format(parseFloat(importeReserva));

	const historyList = document.getElementById('pujas_list');
	historyList.innerHTML = '';

	document.getElementById('historial_pujas').classList.toggle('hidden', pujas.length == 0);
	document.getElementById('num_pujas').innerText = pujas.length;

	const viewAllPujasIsActive = document.getElementById('view_all_pujas_active').value == '1';
	const view_num_pujas = document.getElementById('view_num_pujas').value;

	//traducciones necesarias
	const transTextI = document.getElementById('trans_lot_i').value;
	const transTextAuto = document.getElementById('trans_lot_puja_automatica').value;
	const transMinimalPrice = document.getElementById('trans_minimal_price').value;

	//HTMLElement de la linea
	const iElement = `<span class="yo">${transTextI}</span>`;
	const otherElement = (numLicit) => `<span class="otherLicit hint--top hint--medium" data-hint="${messages.neutral.puja_corresponde} ${numLicit}">${numLicit}</span>`;
	const autoElement = ` <span class="dos hint--top hint--medium" data-hint="${transTextAuto}">A</span>`;
	const reservePriceSurpassElement = `<p class="info">${transMinimalPrice} ${importeReservaFormatted}</p>`;

	pujas.forEach((puja, index) => {

		const numLot = index + 1;

		//se puede utilizar para mostrar o no la linea, por el momento prefiero mostrar todas y añadir scroll
		const show = viewAllPujasIsActive || (numLot <= view_num_pujas && !viewAllPujasIsActive);

		// si la puja es del licitador ocultamos el numero y se mostrará el YO
		const bidderElement = (auction_info?.user?.cod_licit == puja.cod_licit) ? iElement : otherElement(licits[puja.cod_licit]);

		const date = new Date(puja.bid_date.replace(/-/g, "/"));
		const dateFormatted = format_date(date);

		const pirceClass = (parseInt(puja.imp_asigl1) >= parseInt(importeReserva)) ? 'winner' : 'loser';

		const currencySimbol = subasta.currency.symbol;
		let pujaFormatted = puja.imp_asigl1.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
		pujaFormatted = frontCurrencies.includes(currencySimbol) ? `${currencySimbol} ${pujaFormatted}` : `${pujaFormatted} ${currencySimbol}`;

		const licitUnit = puja.cod_licit.toString().slice(-1);

		const line = `<p class="hist_item">
					<span class="bidder">Pujador</span>
					<span class="bidder-identifier" data-licitunit="${licitUnit}" style="--multiplier:${licitUnit}">
						<span class="semi-colon">(</span>
							${bidderElement}${puja.type_asigl1 != 'A' ? '' : autoElement}
						<span class="semi-colon">)</span>
					</span>
					<span class="date">${dateFormatted}</span>
					<span class="price ${pirceClass}">${pujaFormatted}</span>
				</p>`;

		historyList.innerHTML += line;

		if(minPriceSurpass && parseInt(minPriceSurpass) == parseInt(puja.imp_asigl1) && importeReserva > 0) {
			historyList.innerHTML += reservePriceSurpassElement ;
		}

	});
}

function reloadPujasList_O() {
	var model = $('#duplicalte_list_pujas').clone();
	var container = $('#pujas_list');
	$('.siguiente_puja').html(new Intl.NumberFormat("de", {}).format(auction_info.lote_actual.importe_escalado_siguiente));
	if (typeof auction_info.lote_actual != 'undefined' && typeof auction_info.lote_actual.pujas != 'undefined') {
		//se borran todos lso contenidos del listado de pujas
		$('div', container).remove();
		$('#pujas-collapse', container).remove();
		$('#historial_pujas').removeClass('hidden');
		$('.num_pujas').html(auction_info.lote_actual.pujas.length);


		var num_lot = 1;
		var cont_licit = 1;
		var licits = new Array();
		var min_price_surpass = false;
		var view_num_pujas = $('#view_num_pujas').val();
		var pujas_licits = auction_info.lote_actual.pujas.slice();
		pujas_licits.reverse();

		$.each(pujas_licits, function (key, licitador) {
			//cogemos el primer valor que supere o iguale el importe de reserva
			if (min_price_surpass == false && parseInt(licitador.imp_asigl1) >= parseInt(auction_info.lote_actual.impres_asigl0)) {
				min_price_surpass = licitador.imp_asigl1;
			}
			//obtenemos los valores que identifican lso licitadores, necesitamos ordenar las pujas al reves, por eso se ha hecho un reverse antes
			if (typeof licits[licitador.cod_licit] == 'undefined') {
				licits[licitador.cod_licit] = cont_licit;
				cont_licit++;
			}
		})

		$.each(auction_info.lote_actual.pujas, function (key, puja) {
			var $this = model.clone().removeAttr('id');

			//mostramos todos los lotes si esta activo ver todo o solo lso que cumplen el rango
			if ($("#view_all_pujas_active").val() == '1' || (num_lot <= view_num_pujas && $("#view_all_pujas_active").val() == '0')) {
				$this.removeClass('hidden');
			}

			// si la puja es del licitador ocultamos el numero y se mostrará el YO
			if (typeof auction_info.user != 'undefined' && typeof auction_info.user.cod_licit != 'undefined' && puja.cod_licit == auction_info.user.cod_licit) {
				$('.uno', $this).addClass('hidden')
			}
			//Si la puja no es del licitador mostramso el numero del licitador que corresponde
			else {
				$('.yo', $this).addClass('hidden');
				$('.uno', $this).html(licits[puja.cod_licit]);
				$('.uno', $this).attr('data-hint', messages.neutral.puja_corresponde + " " + licits[puja.cod_licit]);
			}
			//si es una sobrepuja debe aparecer la letra A
			if (puja.type_asigl1 != 'A') {
				$('.dos', $this).addClass('hidden');
			}

			if (parseInt(puja.imp_asigl1) >= parseInt(auction_info.lote_actual.impres_asigl0)) {
				$('.price', $this).addClass('verde');
			} else {
				$('.price', $this).addClass('rojo');
			}
			var impPuja = puja.imp_asigl1.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");

			if(frontCurrencies.includes(auction_info.subasta.currency.symbol)){
				$('.price', $this).html(auction_info.subasta.currency.symbol + impPuja );
			}else{
				$('.price', $this).html(impPuja + " " + auction_info.subasta.currency.symbol );
			}
			var fecha = new Date(puja.bid_date.replace(/-/g, "/"));
			var formatted = format_date(fecha)
			$('.date', $this).html(formatted);
			container.append($this);
			//si superamos el valor de reseva
			if (parseInt(min_price_surpass) == parseInt(puja.imp_asigl1)) {
				// se mostrará si esta dentro del rango de numero de pujas visibles o estan todas visibles
				if ($("#view_all_pujas_active").val() == '1' || (num_lot <= view_num_pujas && $("#view_all_pujas_active").val() == '0')) {
					container.append($("#price_min_surpass").clone().removeClass("hidden"));
				} else {
					container.append($("#price_min_surpass").clone());
				}
			}
			num_lot++;
		});
		//mostramso si se ha alcanzado el precio mínimo
		if (min_price_surpass == false) {
			$('.precio_minimo_no_alcanzado').removeClass('hidden');
			$('.precio_minimo_alcanzado').addClass('hidden');
		} else {
			$('.precio_minimo_alcanzado').removeClass('hidden');
			$('.precio_minimo_no_alcanzado').addClass('hidden');
		}
		// mostramos el boton de ver todos los lotrs si es necesario, num_lot siempre lleva un ode mas
		if ((num_lot - 1) > view_num_pujas) {
			container.append($("#view_more").clone().removeClass("hidden"));
			//como al recargar el listado se perdia el valor actual, modifico el valor y simulo una llamada
			if ($("#view_all_pujas_active").val() == '0') {
				$("#view_all_pujas_active").val('1');
			}else{
				$("#view_all_pujas_active").val('0');
			}
			view_all_bids();
		}


	}
}

/*
|--------------------------------------------------------------------------
| END Repinta la caja de pujas
|--------------------------------------------------------------------------
*/
function action_success(data) {
	//si el la accion del usuario actual ha tenido exito
	if (auction_info.lote_actual.tipo_sub == 'O' || auction_info.lote_actual.tipo_sub == 'P') {
		if (typeof auction_info.user != 'undefined' && auction_info.user.cod_licit == data.cod_licit_actual) {
			if (data.status == 'success') {
				//Poner codigo de analytics
			}
		}
	}
}

function view_all_bids() {

	//si estan ocultos los mostramos y cambiamso el texto del boton
	if ($("#view_all_pujas_active").val() == '0') {
		$('#pujas_list div').each(function (index) {
			$(this).removeClass("hidden");

		});
		$("#view_all_pujas_active").val('1');
		$('#view_more_text').addClass("hidden");
		$('#hide_bids_text').removeClass("hidden");
	}
	//si estan visibles los ocultamos y cambiamso el texto del boton
	else {
		$('#pujas_list div').each(function (index) {
			if (index >= $('#view_num_pujas').val()) {
				$(this).addClass("hidden");
			}
		});

		$("#view_all_pujas_active").val('0');
		$('#view_more').removeClass("hidden");
		$('#view_more_text').removeClass("hidden");
		$('#hide_bids_text').addClass("hidden");
	}
}



/*
    |--------------------------------------------------------------------------
    | Mostrar alertas
    |--------------------------------------------------------------------------
    */
function displayAlert(type, msg) {
	if (auction_info.lote_actual.tipo_sub == 'O' || auction_info.lote_actual.tipo_sub == 'P') {
		displayAlert_O(type, msg)
	} else if (auction_info.lote_actual.tipo_sub == 'W') {
		displayAlert_W(type, msg)
	}
}
function displayAlert_W(type, msg) {
	if (type == null || typeof type == 'undefined' || !$.isNumeric(type))
		return false;

	/*var type = ''; */

	switch (type) {
		case 0:
			type = 'error';
			break;
		case 1:
			type = 'success';
			break;
		case 2:
			type = 'info';
			break;
		case 3:
			type = 'alert';
			break;
	}

	playAlert(['notification']);

	var notice = new PNotify({
		title: messages.neutral.notification,
		text: msg,
		type: type,
		shadow: true,
		addclass: 'stack-topleft'
	});
}

function displayAlert_O(type, msg) {

	$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
	$("#insert_msg_title").html("");
	$("#insert_msg").html(msg);

}


/*
|--------------------------------------------------------------------------
| END Mostrar alertas
|--------------------------------------------------------------------------
*/

/*
 * Metodo para mostrar popup pidiendo inicio de sesion cuando no se esta logeado
 */
function initSesion() {
	$.magnificPopup.close();

	$("#insert_msg_login_required").html("");
	//$("#insert_msg_login_required").html(messages.error.login_required);
	$("#insert_msg_log_in").html("");
	//$("#insert_msg_log_in").html(messages.error.log_in);
	$("#insert_msg").html("");
	$("#insert_msg").html(messages.error.register_required_nolink);


	$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
}

function clickLogin() {
	const dropdownElement = document.getElementById('dropdownLogin');
	const dropdown = new bootstrap.Dropdown(dropdownElement);
	dropdown.show();
}

function reloadCarrousel() {

	//Si se muestrán más de tres retrasamos uno, sino avanzamos uno.
	const prevLot = window.screen.width >= 768 ? 1 : -1;

	if (typeof auction_info != 'undefined' && typeof auction_info.lote_actual != 'undefined') {

		let actualLotRef = parseInt(auction_info.lote_actual.ref_asigl0);
		let $actualLot = $(`.lots[data-ref_asigl0 = ${actualLotRef}]`);

		if(!$actualLot.length){
			return;
		}

		let position = $actualLot[0].dataset.order;

		$('.lots').removeClass('actual-lot');

		$actualLot.addClass('actual-lot');//.addClass('j-active-info');

		if ($('#j-followCarrousel').prop('checked')) {
			$('.lots-carrousel')[0].slick.slickGoTo(parseInt(position - prevLot));
		}

	}

}

function initSlick(){
	let slidesToShow = 4;

	const breackPoints = [
		{ breakpoint: 993, settings: {slidesToShow: 3} },
		{ breakpoint: 768, settings: {slidesToShow: 2} },
		{ breakpoint: 576, settings: {slidesToShow: 1} }
	];

	const slickOptions = {
		dots: false,
		infinite: false,
		arrows: true,
		slidesToShow: slidesToShow,
		slidesToScroll: 1,
		swipeToSlide: true,
		prevArrow: $('.prev-arrow-carrousel'),
		nextArrow: $('.next-arrow-carrousel'),
		responsive: breackPoints
	}

	$('.lots-carrousel').slick(slickOptions);

	reloadCarrousel();
}

function login_web() {
	const loading = document.querySelector(".loading-wrapper");

	$.ajax({
		type: "POST",
		url: '/login_post_ajax',
		data: $('#accerder-user-form').serialize(),
		beforeSend: () => {
			loading.classList.remove('d-none');
			return true;
		},
		success: function(response) {
			if (response.status == 'success') {
				location.reload();
			} else {
				$("#accerder-user-form .message-error-log").text('').append(messages.error[response.msg]);
			}
		}
	})
	.fail(() => {
		$("#accerder-user-form .message-error-log").text('').append(messages.error.code_500);
	})
	.always(() => {
		loading.classList.add('d-none');
	});
}

function reloadPujasButtons(codSub, actualBid){

	$.ajax({
		type: "GET",
		url: '/api-ajax/calculate_bids/' + actualBid + '/' + actualBid + '?cod_sub=' + codSub,
		success: function( response )
		{
			response = JSON.parse(response);
			//let buttons = $(".js-lot-action_pujar_escalado");

			for (let index = 0; index < 3; index++) {

				if(typeof response[index] != 'undefined'){
					$(`[data-escalado-position=${index}]`).attr('value', response[index]);
					$(`[data-escalado-position=${index}] span`).attr('value', response[index]).text(new Intl.NumberFormat("de-DE").format(response[index]));
				}else{
					$(`[data-escalado-position=${index}]`).addClass('hidden');

				}


				/* $(buttons[index]).attr('value', response[index]);
				$(buttons[index].firstElementChild).attr('value', response[index]).text(new Intl.NumberFormat("de-DE").format(response[index])); */
			}
		}
	});
}

function showStreaming() {
	document.getElementById('nav-streaming').style.opacity = '1';
}

function hideStreaming() {
	document.getElementById('nav-streaming').style.opacity = '0';
}

function checkBidCondition() {
	const bidButton = document.getElementById('button-bid');
	bidButton.disabled = true;
	bidButton.classList.add('disabled');

	$('.lot-action_pujar_no_licit').off('click');
	$('.lot-action_pujar_on_line').off('click');

	const representedValue = document.getElementById('representante').value;
	const representedCod = representedValue == 'N' ? null : representedValue;

	fetch(`/api-ajax/check-bid-conditions`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify({
			codSub: auction_info.lote_actual.cod_sub,
			ref: auction_info.lote_actual.ref_asigl0,
			representedCod
		})
	})
		.then(response => response.json())
		.then(data => {
			if (!data.deposito) {
				bidButton.onclick = () => pujarWithoutDeposit(bidButton);
			} else if (!data.conditions) {
				bidButton.onclick = () => pujarWithoutConditions(bidButton);
			} else {
				bidButton.onclick = (event) => pujarAction(event);
			}
		})
		.catch(error => {
			console.error('Error:', error);
		})
		.finally(() => {
			bidButton.disabled = false;
			bidButton.classList.remove('disabled');
		});

}

function pujarAction(e) {
	e.stopPropagation();
	$.magnificPopup.close();
	//si pulsan el boton de puja donde viene un valor

	if ($(e.currentTarget)[0].hasAttribute("value")) {
		precioOrden = $(e.currentTarget).attr("value");
		$("#bid_amount").val(precioOrden);

	} else { //si pulsan el boton de autopuja
		precioOrden = $("#bid_amount").val();
	}

	$(".precio_orden").html(precioOrden.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1."));

	//divisas, debe existir el selector
	if (typeof $("#currencyExchange").val() != 'undefined') {
		changeCurrency(precioOrden, $("#currencyExchange").val(), "newBidExchange_JS");
	}


	if (typeof cod_licit == 'undefined' || cod_licit == null) {

		//este codigo abre directamente la ventana emergente de login
		$('.login_desktop').fadeToggle("fast");
		$('.login_desktop [name=email]').focus();

		return;

	} else {

		if (typeof auction_info.lote_actual.inversa_sub == 'undefined' || auction_info.lote_actual.inversa_sub != "S") {
			var inversa = false;
		} else {
			var inversa = true;
		}

		if (!auction_info.user.is_gestor && (isNaN(parseInt($("#bid_amount").val())) || (parseInt($("#bid_amount").val()) < parseInt(auction_info.lote_actual.importe_escalado_siguiente)) && !inversa)) {
			$("#insert_msg_title").html(messages.error.lower_bid);
			$("#insert_msg").html(messages.error.your_bid + " " + auction_info.lote_actual.importe_escalado_siguiente + " € " + messages.error.as_minimum);
			$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
		}
		else {
			$.magnificPopup.open({ items: { src: '#modalPujarFicha' }, type: 'inline' }, 0);
		}
	}
}

function pujarWithoutLogin() {
	$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
	$("#insert_msg_title").html("");
	$("#insert_msg").html(messages.error.mustLogin);


	$('.open-login').on('click', function () {
		$.magnificPopup.close();
		$('.login_desktop').fadeToggle("fast");
		$('.login_desktop [name=email]').focus();
	});
	return;
}

function pujarWithoutDeposit(buttonElement) {
	let params = {
		cod_sub: buttonElement.dataset.codsub,
		ref: buttonElement.dataset.ref,
		lang: buttonElement.dataset.lang
	};

	requestDataForDeposit(params);
}

function pujarWithoutConditions(buttonElement) {
	$.magnificPopup.open({ items: { src: '#modalCheckBasesFicha' }, type: 'inline' }, 0);
}

function sendDepositForm(e) {

	e.stopPropagation();
	$.magnificPopup.close();

	let maxSize = 2000;
	var formData = new FormData();
	$.each($("input[type='file']")[0].files, function (i, file) {

		let sizeFileInKb = file.size / 1024;
		if (sizeFileInKb > maxSize) {
			$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
			$("#insert_msg_title").html("");
			$("#insert_msg").html($('.form-authorize small').text());
			throw $('.form-authorize small').text();
		}

		formData.append('file[]', file);
	});

	formData.append('nom', $('input[name="nom"]').val());
	formData.append('cod_sub', $('input[name="cod_sub"]').val());
	formData.append('ref', $('input[name="ref"]').val());
	formData.append('represented', $('[name="representado"]').val());

	$.ajax({
		async: true,
		type: "POST",
		dataType: "html",
		contentType: false,
		processData: false,
		url: "/api-ajax/enviar-formulario-pujar",
		data: formData,
		success: function (response) {
			$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
			$("#insert_msg_title").html("");
			$("#insert_msg").html(response);

		},
		error: function (error) {
			$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
			$("#insert_msg_title").html("");
			$("#insert_msg").html(error.responseText);
		},
	});

}
