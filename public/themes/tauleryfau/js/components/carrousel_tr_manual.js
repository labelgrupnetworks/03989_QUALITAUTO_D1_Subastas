let arrayViews = [];
let ajax;

$(document).ready(function () {

	$('.next-arrow-carrousel').on('click', nextCarrousel);
	$('.prev-arrow-carrousel').on('click', prevCarrousel);

	$(window).resize(reloadCarrousel);
	reloadCarrousel();

	favoritesCheck();

	$('.lots .check-input').on('change', addFavoriteCarrousel);
});

function favoritesCheck(){

	if (typeof auction_info.user == 'undefined' || typeof auction_info.user.favorites == 'undefined') {
		return;
	}

	$.each(auction_info.user.favorites, function (key, item) {
		if (typeof item == 'undefined') {
			return true;
		}

		$(`.lots #j-addFavoriteCarrousel-${item.ref_asigl0}`).prop('checked', true);
	});

}

function addFavoriteCarrousel(){

	let action = this.checked ? 'add' : 'remove';
	let ref = $(this).closest(".lots")[0].dataset.ref_asigl0;
	updateFavs(action, ref);
}

function updateFavs(action, ref) {
	if (typeof auction_info.user == 'undefined') {
		return;
	}
	var already_did = false;

	$.each(auction_info.user.favorites, function (key, item) {
		if (typeof item == 'undefined') {
			return true;
		}

		if (action == 'add' && parseInt(item.ref_asigl0) == parseInt(ref)) {
			already_did = true;
			return false;
		}

	});

	if (already_did) {
		return;
	}

	/*mirar si no está añadido ya a favoritos antes de enviar el ajax.*/
	$.getJSON('/api-ajax/favorites/' + action, {cod_sub: auction_info.subasta.cod_sub, cod_licit: auction_info.user.cod_licit, ref: ref}, function (data) {

		/*console.log(data);*/
		if (data.status == 'error') {
			displayAlert(0, messages.error[data.msg]);
			return;
		}

		displayAlert(1, messages.success[data.msg]);

		if (action == 'add' && typeof data.data != 'undefined') {
			auction_info.user.favorites.push(data.data);
		} else {

			if (typeof auction_info.user.favorites == 'undefined' || ref == '') {
				return;
			}

			$.each(auction_info.user.favorites, function (key, item) {
				if (typeof item == 'undefined') {
					return true;
				}

				if (parseInt(item.ref_asigl0) == parseInt(ref)) {
					delete(auction_info.user.favorites[key]);
				}
			});
		}
	});
}

function nextCarrousel() {
	if (arrayViews[arrayViews.length - 1][0].dataset.ref_asigl0 == $('.lots').last()[0].dataset.ref_asigl0) {
		return;
	}

	arrayViews.push(arrayViews[arrayViews.length - 1].next());
	arrayViews.shift();

	viewArrayLots();
}

function prevCarrousel() {
	if (arrayViews[0][0].dataset.ref_asigl0 == $('.lots').first()[0].dataset.ref_asigl0) {
		return;
	}

	arrayViews.unshift(arrayViews[0].prev());
	arrayViews.pop();

	viewArrayLots();

	//changePrevOrder()
}

function hoverOverCarrousel() {

	let cod_sub = this.dataset.cod_sub;
	let ref_asigl0 = this.dataset.ref_asigl0;

	ajax = $.ajax({
		type: "GET",
		url: `/api-ajax/award_price/${cod_sub}/${ref_asigl0}`,
		beforeSend: function () {
			//$('.j-lots-data .loader').css("display", "block");
			$('.j-lots-data .j-lots-data-load').css("display", "none");
		},
		success: function (response) {
			//console.log(response);// = JSON.parse(response);
			$('.j-lots-data .j-lots-state').html(response.html);

			if (response.purchasable) {
				$('.j-btn-custom-add .j-text-add').css("display", "block");
				$('.j-btn-custom-add .j-text-view').css("display", "none");
			}
		},
		error: function (error) {

		},
		complete: function () {
			//$('.j-lots-data .loader').css("display", "none");
			$('.j-lots-data .j-lots-data-load').css("display", "flex");
			$('.j-lots-state').css("display", "block");
		}
	});
}

function hoverOutCarrousel() {
	if (ajax != null) {
		ajax.abort();
		ajax = null;
	}
	$('.j-btn-custom-add .j-text-add').css("display", "none");
	$('.j-btn-custom-add .j-text-view').css("display", "block");
}


function viewArrayLots() {

	$('.lots').css('display', 'none');

	for (let lot of arrayViews) {
		lot.css('display', 'flex');

		//carga diferida de imagenes
		if (typeof lot[0] != 'undefined' && typeof lot[0].dataset != 'undefined') {
			lot.css('background-image', lot[0].dataset.backgroundImage);
		}
	}

}

function reloadCarrousel() {

	if (typeof auction_info != 'undefined' && typeof auction_info.lote_actual != 'undefined') {

		let actualLotRef = parseInt(auction_info.lote_actual.ref_asigl0);
		let $actualLot = $(`.lots[data-ref_asigl0 = ${actualLotRef}]`);

		$('.lots').removeClass('actual-lot');
		$actualLot.addClass('actual-lot');//.addClass('j-active-info');

		//$('.actual-lot .carrousel-lots-check').hide(); //Esconde check de aviso

		//$('.lots.j-active-info').unbind('mouseenter mouseleave'); //elimina eventos anteriores para no acumular
		//$('.lots.j-active-info').hover(hoverOverCarrousel, hoverOutCarrousel); //añade evento a hover a lotes


		if ($('#j-followCarrousel').prop('checked')) {

			automaticCarrousel($actualLot);

		}

	}

}



/**
 *
 * @param {*jquery_element elemento que quedara seleccionado} $selectedLot
 */
function automaticCarrousel($selectedLot) {

	arrayViews = [];

	arrayViews.push($selectedLot);

	//Separamos por tamaño de pantalla
	if ($("#size-col-sm").is(":visible")) {
		arrayViews.push(arrayViews[arrayViews.length - 1].next());
	}
	else if ($("#size-col-md").is(":visible")) {
		arrayViews.push(arrayViews[arrayViews.length - 1].next());
		arrayViews.push(arrayViews[arrayViews.length - 1].next());

		//si no es el primer lote, cargamos un lote antes que el actual
		if ($selectedLot[0].dataset.ref_asigl0 != $('.lots').first()[0].dataset.ref_asigl0) {
			arrayViews.unshift(arrayViews[0].prev());
		}
		else {
			arrayViews.push(arrayViews[arrayViews.length - 1].next());
		}
	}

	viewArrayLots();
}

/**
 * @deprecated
 */
function changePrevOrder() {

	if ($('.lots').first()[0].style.order == '0') {
		return;
	}

	for (let lot of $('.lots')) {

		if (lot.style.order == $('.lots').length - 1) {
			lot.style.order = 0;
		}
		else {
			lot.style.order = parseInt(lot.style.order) + 1;
		}
	}
}
