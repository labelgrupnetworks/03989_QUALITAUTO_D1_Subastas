function actualPriceReload(data) {

	console.log(data);
	var puja = data.pujasAll[0];

	let $subastaElement = $(`.${puja.ref_asigl1}-${puja.cod_sub}`);

	if ($subastaElement.length == 0) {
		return false;
	}

	let $actualPrice = $subastaElement.find('.actual-price');
	$actualPrice.text(puja.formatted_imp_asigl1);

	let actionButton = $subastaElement.find('.js-lot-action_pujar_panel');

	changeCurrencyNew(puja.imp_asigl1, divisa, $subastaElement.find('.divisa-actual-price'));

	//puja maxima es mia
	if (auctions_info[puja.cod_sub]["lotes"][0].cod_licit == puja.cod_licit) {
		$actualPrice.parent().addClass('mine').removeClass('other');

		$subastaElement.find('.my-max-bid').text(puja.formatted_imp_asigl1);
		changeCurrencyNew(puja.imp_asigl1, divisa, $subastaElement.find('.divisa-my-max-bid'));

		actionButton.addClass('bid-mine').attr('disabled', true);
		actionButton.find('.js-max-bid').removeClass('hidden');
		actionButton.find('.js-place-bid').addClass('hidden');

	}
	//puja maxima es de otro
	else {
		$actualPrice.parent().addClass('other').removeClass('mine');

		actionButton.removeClass('bid-mine').attr('disabled', false).data('imp', data.siguiente);
		actionButton.find('span').attr('value', data.siguiente).text(new Intl.NumberFormat("de-DE").format(data.siguiente));
		actionButton.find('.js-max-bid').addClass('hidden');
		actionButton.find('.js-place-bid').removeClass('hidden');

		//si existe sobre puja mi puja máxima es la segunda
		if (data.status == 'error' && typeof data.pujasAll[1] != 'undefined' && data.pujasAll[1].cod_licit == auctions_info[puja.cod_sub]["lotes"][0].cod_licit) {
			$subastaElement.find('.my-max-bid').text(data.pujasAll[1].formatted_imp_asigl1);
		}

		//reloadPujasButtonsPanel(puja.cod_sub, puja.imp_asigl1, actionButton);
	}

	return true;
}

/**
 * @deprecated Al final recibo los datos del propio socket,
 * Dejar hasta publica por si acaso
 */
function reloadPujasButtonsPanel(codSub, actualBid, object) {

	$.ajax({
		type: "GET",
		url: '/api-ajax/calculate_bids/' + actualBid + '/' + actualBid + '?cod_sub=' + codSub,
		success: function (response) {
			response = JSON.parse(response);
			$(object).attr('value', response[0]).attr('disabled', false).data('imp', response[0]);
			$($(object).find('span')).attr('value', response[0]).text(new Intl.NumberFormat("de-DE").format(response[0]));
		}
	});
}

function hiddenBidButton(data) {
	$(`#${data.id_sub} .js-lot-action_pujar_panel`).addClass('hidden').attr('disabled', true);
	$(`#${data.id_sub} .js-button-bid-live`).removeClass('hidden');
}


$(document).ready(function () {

	$('.js-lot-action_pujar_panel').on('click', function (e) {
		e.stopPropagation();
		$.magnificPopup.close();

		let element = e.target;
		if (element.nodeName == 'SPAN' || element.nodeName == 'P') {
			element = e.target.closest('button');
		}

		$('#confirm_puja_panel').data('imp', $(element).data("imp"));
		$('#confirm_puja_panel').data('sub', $(element).data("sub"));
		$('#confirm_puja_panel').data('ref', $(element).data("ref"));

		$('#modalPujarPanel .precio').text($(element).data("imp"));
		$.magnificPopup.open({ items: { src: '#modalPujarPanel' }, type: 'inline' }, 0);
	});

});

$(function () {
	var socket = io.connect(routing.node_url, { 'forceNew': true });

	socket.on('connect', function () {

		for (const room of rooms) {
			socket.emit('room', { cod_sub: room, id: socket.id });
		}

		socket.on('action_response', function (data) {

			if (typeof data == 'undefined' || typeof data.pujasAll == 'undefined' || typeof auctions_info[data.pujasAll[0].cod_sub] == 'undefined') {
				return false;
			}

			return actualPriceReload(data);
		});

		socket.on('action_status_response', function (data) {

			if (typeof data == 'undefined' || typeof data.id_sub == 'undefined' || data.estado != 'in_progress') {
				return false;
			}

			return hiddenBidButton(data);
		});

	});


	$('#confirm_puja_panel').on('click', function (e) {

		let cod_sub = $(this).data('sub');
		let ref = $(this).data('ref');
		let imp = $(this).data('imp');

		$.magnificPopup.close();

		var imp_sal = null;
		var url = null;
		var cod_licit = auctions_info[cod_sub]['lotes'][0].cod_licit;

		var params = {
			'cod_licit': cod_licit, 'cod_sub': cod_sub, 'ref': ref,
			'url': url, 'imp': imp, 'type_bid': 'W',
			'impsal': imp_sal, 'can_do': null, 'cod_original_licit': cod_licit,
			'tipo_puja_gestor': false
		};

		$.ajax({
			type: "POST",
			url: '/phpsock/actionv2',
			data: params,
			beforeSend: function () {

			},
			success: function( response ) {
				if(response.status == 'error'){
					displayAlert(1, messages.error[response.msg]);
					$('#abrirLote').addClass('hidden');
				}
			}
		});
	});

});




