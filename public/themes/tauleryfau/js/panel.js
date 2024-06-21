const CURRENCY_FORMATS = {
	SMALL: '0,0',
	FULL: '0,0.00',
}

/**
 * eventos panel de usuario
 */
$(function () {
	if ($('.user-panel-body').length == 0) {
		return;
	}

	addHeaderHeight();
	$(window).resize(addHeaderHeight);
	initializeSubmenuPanel();
	refreshCurrency();
});

function initializeSubmenuPanel() {
	$('#collapseSubMenu').on('show.bs.collapse', function () {
		$('.toggle-icon').find('.fa-bars').hide();
		$('.toggle-icon').find('.fa-times').show();
	});

	$('#collapseSubMenu').on('hide.bs.collapse', function () {
		$('.toggle-icon').find('.fa-bars').show();
		$('.toggle-icon').find('.fa-times').hide();
	});
}

function addHeaderHeight() {
	$headerHeight = $('body > header').height();
	$('aside').css('--header-height', $headerHeight + 'px');
}

function salesAnimationCounter() {
	$('.sales-counter').each(function () {
		const element = this;

		const options = {
			finalValue: $(element).attr('value'),
		}

		const updateWithCurrency = (now) => {
			const text = changeCurrencyNew(now, $('#actual_currency').val(), $(element));
			$(element).text(text);
		};

		const methodToUpdate = $(element).hasClass('js-divisa') ? updateWithCurrency : null;

		animationCounter(element, options, methodToUpdate);
	});

}

function animationCounter(element, options = {}, callback) {
	options = {
		initValue: options.initValue || 0,
		finalValue: options.finalValue || 100,
		duration: options.duration || 1000,
	};

	callback = callback || ((now) => $(element).text(Math.ceil(now)));

	$(element).prop('Counter', options.initValue)
		.animate({
			Counter: options.finalValue
		}, {
			duration: options.duration,
			easing: 'swing',
			step: callback
		});
}

/**
 * user panel sales and adjudicaciones events
 */
$(function () {
	salesAnimationCounter();
	$('.sales-auction-wrapper[data-type=active]').on('click', (event) => {
		salesAuctionOnClickHandler(event, refreshActiveSummary, refreshActiveSummaryWithTotals);
	});
	$('.sales-auction-wrapper[data-type=finish]').on('click', (event) => {
		salesAuctionOnClickHandler(event, refreshFinishSummary, refreshFinishSummaryWithTotals);
	});

	//en ventas y compras desplazar la pantalla a la sección de detalles de la subasta
	$('a[data-toggle="tab"][href^="#auction-details"]').on('shown.bs.tab', salesAuctionDetailsOnClickHandler);

	if ($('a[data-toggle="tab"][href^="#auction-details"]').length > 0 && window.location.hash) {
		const hash = window.location.hash;
		$(`[href="${hash}"]`).tab('show');
	}

	$('.modal-lots-details').on('show.bs.modal', function (e) {

		const $modal = $(this);
		const $button = $(e.relatedTarget);
		const auctionId = $button.data('id');

		//if match query media
		if (!isMatchMedia('992')) {

			//width main element
			const margin = 60;
			const $mainElement = $('main');
			const width = $mainElement.width() - margin;

			const $asideElement = $('aside');
			const asideWidth = $asideElement.width() + margin;

			//set width modal
			$modalDialog = $modal.find('.modal-dialog');
			$modalDialog.css('width', `${width}px`);
			$modalDialog.css('margin-left', `${asideWidth}px`);

		}
		//clone sales-auctions
		const $wrapperElement = $('[data-detail-block]').clone();

		//inside $wrapperElement remove all sales-auction-wrapper except the one with data-sub
		$wrapperElement.find('[data-auction-wrapper]').not(`[data-id="${auctionId}"]`).remove();
		$wrapperElement.find('[data-auction-wrapper]').removeClass('active');

		//insert element clon in modal modal-table-header
		$modal.find('.modal-table-header').html($wrapperElement);
	});

	$('.modal-lots-details').on('hide.bs.modal', function (e) {
		$(this).find('.modal-table-header').empty();
	});
});

function salesAuctionOnClickHandler(event, callbackAnAuction, callbackTotals) {
	const $wrapperElement = $(event.currentTarget);
	const isActive = $wrapperElement.hasClass('active');

	if (event.target instanceof HTMLAnchorElement && isActive) {
		return;
	}

	$('.sales-auction-wrapper').removeClass('active');

	if (isActive) {
		callbackTotals();
		return;
	}

	$wrapperElement.addClass('active');
	const cod_sub = $wrapperElement.data('sub');
	callbackAnAuction(cod_sub);
}

function salesAuctionDetailsOnClickHandler() {
	const element = document.getElementById('auction-details');
	element.scrollIntoView({ behavior: "smooth", block: "start", inline: "nearest" });
}

function refreshFinishSummaryWithTotals() {
	const total = statistics.total;
	$('#settlementPrice').attr('value', total.total_liquidation);
	$('#percentageAwards').attr('value', total.total_awarded_lots / total.total_lots * 100);
	$('#revaluation').attr('value', total.total_award / total.total_impsalhces * 100);
	$('#consignedLots').attr('value', total.total_lots);
	$('#awardedLots').attr('value', total.total_awarded_lots);
	salesAnimationCounter();
}

function refreshFinishSummary(cod_sub) {
	const auction = statistics.auction[`${cod_sub}`];
	$('#settlementPrice').attr('value', auction.total_liquidation);
	$('#percentageAwards').attr('value', auction.total_awarded_lots / auction.total_lots * 100);
	$('#revaluation').attr('value', auction.total_award / auction.total_impsalhces * 100);
	$('#consignedLots').attr('value', auction.total_lots);
	$('#awardedLots').attr('value', auction.total_awarded_lots);
	salesAnimationCounter();
}

function refreshActiveSummaryWithTotals() {
	const total = statistics.total;
	$('#actualPrice').attr('value', total.total_award);
	$('#percentage_lots_bid').attr('value', total.percentage_lots_with_bid);
	$('#revaluation').attr('value', total.revaluation);
	$('#consigned_lots').attr('value', total.total_lots);
	$('#bid_lots').attr('value', total.total_bids_lots);
	salesAnimationCounter();
}

function refreshActiveSummary(cod_sub) {
	const auction = statistics.auction[`${cod_sub}`];

	$('#actualPrice').attr('value', auction.total_award);
	$('#percentage_lots_bid').attr('value', auction.total_bids_lots / auction.total_lots * 100);
	$('#revaluation').attr('value', auction.total_award / auction.total_impsalhces * 100);
	$('#consigned_lots').attr('value', auction.total_lots);
	$('#bid_lots').attr('value', auction.total_bids_lots);
	salesAnimationCounter();
}


/**
 * user panel profile events
 * @todo
 * [] - modificar selectores para que sean más específicos
 */
$(function () {
	$('.address-form-section [data-toggle="collapse"]').click(addressCollapsesClickHandler);
	$('select[name="pais"]').on('change', () => reloadPrefix('pais', 'preftel_cli'));

	$('input[name="avatar"]').on('change', function () {
		const file = this.files[0];
		const reader = new FileReader();

		reader.onload = function (e) {
			$('.profile-avatar img').attr('src', e.target.result);
		}

		reader.readAsDataURL(file);
	});

	//if form name="summary-form" submit
	$('#summary-form').on('submit', function (event) {
		event.preventDefault();

		//this select values to array
		const data = $(this).serializeArray();
		//only values
		yearsSelected = data.map(item => item.value);

		getAllotmentsAndBills();
		$buttonSales = $('.sales-menu .btn-lb-primary');
		if ($buttonSales.data('refresh')) {
			$buttonSales.trigger('click');
		}
	});
});

function reloadPrefix(fromNameElement, toNameElement) {

	if (typeof prefix === 'undefined' || !prefix) {
		return;
	}

	$(`input[name=${toNameElement}]`).val(prefix[$(`select[name=${fromNameElement}]`).val()]);
}

function addressCollapsesClickHandler(event) {
	event.preventDefault();
	event.stopPropagation();

	const element = event.currentTarget;

	if ($(element).hasClass('active')) {
		$('.collapse').collapse('hide');
		$('.address-form-section [data-toggle="collapse"]').removeClass('active');
		return false;
	}

	$('.collapse').collapse('hide');

	$('.address-form-section [data-toggle="collapse"]').not(element).removeClass('active');
	$(element).toggleClass('active');

	const addressCod = $(element).attr('cod');
	$.when(ajax_shipping(addressCod, locale)).done(function (data) {
		$('.collapse').collapse('show');
	});
}

function saveAddress(buttonElement) {
	buttonElement.disabled = true;
	const data = $('#ajax_shipping_add').find('input, select').serialize();
	$.when(submit_shipping_addres(data)).done((response) => {
		buttonElement.disabled = false;
		$('#modalMensaje .button_modal_confirm').on('click', function () {
			location.reload();
		});
	});
}

function deleteAddress(buttonElement) {
	buttonElement.disabled = true;
	$.when(delete_shipping_addres(buttonElement)).done(() => {
		buttonElement.disabled = false;
		$('#modalMensaje .button_modal_confirm').on('click', function () {
			location.reload();
		});
	});
}

function favAddress(buttonElement) {
	buttonElement.disabled = true;
	$.when(fav_addres(buttonElement)).done(() => {
		buttonElement.disabled = false;
		$('#modalMensaje .button_modal_confirm').on('click', function () {
			location.reload();
		});
	});
}

function showModalAddNewAddress() {
	const addressCod = 'new';
	$.when(ajax_shipping(addressCod, locale)).done(function (data) {
		$('#modal_new_address').modal('show');
	});
}

submit_shipping_addres = function (data) {
	return $.ajax({
		type: "POST",
		url: '/change_address_shipping',
		data,
		success: function (response) {
			if (response.status == 'success' || response.status == 'new') {
				$("#modalMensaje #insert_msg").html('');
				$("#modalMensaje #insert_msg").html(messages.success.success_saved);
				$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				return response;
			}
		}
	}).then((response) => {
		return response;
	});
}

function getAllotmentsAndBills() {
	const $block = $('#summary-allotments_table');
	const $loader = $block.parent().find('.loader-box');

	$.ajax({
		url: `/${locale}/user/panel/allotments-bills`,
		data: {
			years: yearsSelected
		},
		type: 'GET',
		beforeSend: function () {
			$loader.show();
		},
		success: function (response) {
			$block.html(response);
		},
		error: function (error) {
			console.log(error);
		},
		complete: function () {
			$loader.hide();
		}

	});
}

function getFavorites() {
	$.ajax({
		url: `/${locale}/user/panel/summary/favorites`,
		type: 'GET',
		success: function (response) {

			const $block = $('#summary-favorites');
			const $loader = $block.parent().find('.loader-box');
			$loader.hide();
			$block.html(response);
		},
		error: function (error) {
			console.log(error);
		}
	});
}

function getSales(anchorElement) {
	refreshAnchorActive(anchorElement);
	getSalesTab(`/${locale}/user/panel/summary/active-sales`);
}

function getFinishSales(anchorElement) {
	refreshAnchorActive(anchorElement);
	getSalesTab(`/${locale}/user/panel/summary/finish-sales`);
}

function getPendingSales(anchorElement) {
	refreshAnchorActive(anchorElement);
	getSalesTab(`/${locale}/user/panel/summary/pending-sales`);
}

function getSalesTab(url) {

	const $block = $('#summary-sales');
	const $loader = $block.parent().find('.loader-box');

	$.ajax({
		url: url,
		type: 'GET',
		data: {
			years: yearsSelected
		},
		beforeSend: function () {
			$loader.show();
		},
		success: function (response) {
			$block.html(response);
		},
		error: function (error) {
			console.log(error);
		},
		complete: function () {
			$loader.hide();
		}
	});
}

function refreshAnchorActive(anchorElement) {
	if (typeof anchorElement === 'undefined') {
		return;
	}
	$('.sales-menu a').removeClass('btn-lb-primary').addClass('btn-lb-outline');
	$(anchorElement).addClass('btn-lb-primary').removeClass('btn-lb-outline');
}

changeCurrencyNew = function (price, exchange, object) {

	price = Math.round(price * currency[exchange].impd_div * 100) / 100;

	const currencyFormat = elementCurrencyFormat(object);

	//si el formato es small, redondear el número
	if (currencyFormat == CURRENCY_FORMATS.SMALL) {
		price = roundNumber(price);
	}

	newPrice = numeral(price).format(currencyFormat);

	if (currency[exchange].pos_div == 'R') {
		newPrice += " " + currency[exchange].symbolhtml_div;
	} else {
		newPrice = currency[exchange].symbolhtml_div + " " + newPrice;
	}

	$(object).html(newPrice);
}

function elementCurrencyFormat($element) {

	/* if(!isMatchMedia('760')) {
		return CURRENCY_FORMATS.FULL;
	} */
	let format;

	try {
		format = $element.data('small-format') ?? $element.data('format');
	}
	catch (error) {
		format = CURRENCY_FORMATS.FULL;
	}

	if (typeof format == 'undefined') {
		format = CURRENCY_FORMATS.FULL;
	}

	return format;
}

function isMatchMedia(mediaQuery) {
	return window.matchMedia(`(max-width: ${mediaQuery}px)`).matches;
}

function roundNumber(number) {
	let decimalPart = number - Math.floor(number);
	return (decimalPart >= 0.5)
		? Math.ceil(number)
		: Math.floor(number);
}


