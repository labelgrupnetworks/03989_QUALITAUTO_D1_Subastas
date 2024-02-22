$(function () {

	/**Funcion a lanzar cuando se inizializa el carousel de movil en la ficha del lote*/
	$("#owl-carousel-responsive").on('initialized.owl.carousel', function (event) {
		if (typeof initOwlResponsive !== 'undefined') {
			initOwlResponsive(event);
		}
	});

	//carousel imagenes ficha lote
	$("#owl-carousel-responsive").owlCarousel({
		items: 1,
		autoplay: $('.video-item').length > 0 ? false : true,
		margin: 20,
		dots: true,
		nav: true,
		responsiveClass: true,
	});

	$('.btn_login').on('click', showLogin);
	$('.closedd').on('click', closeLogin);
	$("#accerder-user-form").on('submit', handleSubmitLoginForm);
	$('.newsletter-btn-js').on('click', handleSubmitNewsletterForm);

	$('#frmUpdateUserPasswordADV').validator().on('submit', handleSubmitUpdatePasswordForm);
	$('#frmUpdateUserInfoADV').validator().on('submit', handleSubmitUpdateUser);

	$("#confirm_orden").click(function () {

		imp = $("#bid_modal_pujar").val();
		if ($("#orderphone").val() == "S") {
			tel1 = $("#phone1Bid_JS").val();
			tel2 = $("#phone2Bid_JS").val();
			ortherphone = true;
		} else {
			tel1 = "";
			tel2 = "";
			ortherphone = false;
		}
		$.magnificPopup.close();
		$.ajax({
			type: "POST",
			url: routing.ol + '-' + cod_sub,
			data: { cod_sub: cod_sub, ref: ref, imp: imp, tel1: tel1, tel2: tel2, ortherphone: ortherphone },
			success: function (data) {
				if (data.status == 'error') {

					$("#insert_msg_title").html("");
					$("#insert_msg").html(data.msg_1);
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				} else if (data.status == 'success') {
					//divisas, debe existir el selector
					if (typeof $("#currencyExchange").val() != 'undefined') {
						changeCurrency(data.imp, $("#currencyExchange").val(), "yourOrderExchange_JS");
					}

					$("#tuorden").html(data.imp.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1."));
					$("#text_actual_no_bid").addClass("hidden");
					$("#text_actual_max_bid").removeClass("hidden");
					$("#actual_max_bid").html(data.open_price);
					$("#insert_msg_title").html("");
					$("#insert_msg").html(data.msg);
					$(".hist_new").removeClass("hidden");
					$(".custom").removeClass("hidden");
					$("#bid_modal_pujar").val(data.imp_actual);
					if (data.winner) {
						$("#max_bid_color").addClass("winner");
						$("#max_bid_color").removeClass("no_winner");
					} else {
						$("#max_bid_color").removeClass("winner");
						$("#max_bid_color").addClass("no_winner");
					}
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				}

			}
		});


	});

	$("#confirm_orden_lotlist").click(function () {
		imp = $(".precio_orden").html();
		ref = $(".ref_orden").html();
		$.ajax({
			type: "POST",
			url: routing.ol + '-' + cod_sub,
			data: { cod_sub: cod_sub, ref: ref, imp: imp },
			success: function (data) {
				if (data.status == 'error') {

					$("#insert_msg_title").html("");
					$("#insert_msg").html(data.msg_1);
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				} else if (data.status == 'success') {
					$("#insert_msg_title").html("");
					$("#insert_msg").html(data.msg);
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				}

			}
		});


	});

	$('.lot-action_comprar_lot').on('click', function (e) {
		e.stopPropagation();
		$.magnificPopup.close();
		if (typeof cod_licit == 'undefined' || cod_licit == null) {



			//este codigo abre directamente la ventana emergente de login
			$('.login_desktop').fadeToggle("fast");
			$('.login_desktop [name=email]').focus();


		} else {
			if ($(this).hasClass("makeOffer_JS")) {
				bid_make_offer = $("#bid_make_offer").val()
				$(".imp_make_offer").html(bid_make_offer.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1."));
				$.magnificPopup.open({ items: { src: '#modalMakeOffer' }, type: 'inline' }, 0);
			} else {
				$.magnificPopup.open({ items: { src: '#modalComprarFicha' }, type: 'inline' }, 0);
			}

		}

	});

	$("#form-valoracion-adv").submit(async function (event) {
		$('#images').remove()
		$(".loader").removeClass("hidden");
		$("#valoracion-adv").addClass("hidden");

		event.preventDefault();

		var max_size = 6000;
		var size = 0;

		$("#form-valoracion-adv").find('input[type="file"]').each(function (index, element) {

			$(element.files).each(function (index, el) {
				size = size + ((el.size / 1024))
			})
		});

		if (Math.floor(size) > max_size) {
			$(".loader").addClass("hidden");
			$("#valoracion-adv").removeClass("hidden");
			$("#insert_msg").html(messages.error.max_size_img);
			$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
			return;
		}

		await executeCaptchaV3();

		if(!checkCaptcha()) {
			$(".loader").addClass("hidden");
			$("#valoracion-adv").removeClass("hidden");
			$("#insert_msg").html(messages.error.generic);
			$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
			return;
		}

		formData = new FormData(this);
		$.ajax({
			type: "POST",
			url: "valoracion-articulos-adv",
			data: formData,
			enctype: 'multipart/form-data',
			processData: false,
			contentType: false,
			success: function (result) {
				if (result.status == 'correct') {
					window.location.href = result.url;
				} else if (result.status == 'error_size' || result.status == 'error_no_image') {
					$("#modalMensaje #insert_msg").html('');
					$("#modalMensaje #insert_msg").html(messages.error[result.msg]);
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				} else {
					$(".msg_valoracion").removeClass('hidden');
				}
				$(".loader").addClass("hidden");
				$("#valoracion-adv").removeClass("hidden");
			},
			error: function (result) {
				$(".loader").addClass("hidden");
				$("#valoracion-adv").removeClass("hidden");
				$(".msg_valoracion").removeClass('hidden');
			}
		});

	});

	//eliminar ordenes	desde el panel de pujas
	$(".confirm_delete_order").click(function () {
		var ref = $(this).attr("ref");
		var sub = $(this).attr("sub");
		$.magnificPopup.close();
		$.ajax({
			type: "POST",
			url: '/api-ajax/delete_order',
			data: {
				ref: ref,
				sub: sub
			},
			success: function (response) {
				res = jQuery.parseJSON(response);
				if (res.status == 'success') {
					location.reload();

				} else {
					if ($(res.respuesta).empty()) {
						$("#" + res.respuesta + " .form-group-custom input").addClass("has-error-custom");
					}
					$("#insert_msg").html(messages.error[res.msg]);
					$.magnificPopup.open({
						items: {
							src: '#modalMensaje'
						},
						type: 'inline'
					}, 0);
				}

			}

		});
	});

	$(".delete_order_panel").click(function () {
		var ref = $(this).attr("ref");
		var sub = $(this).attr("sub");
		$(".confirm_delete_order").attr("price", $("#" + sub + "-" + ref + " input").val());
		$(".confirm_delete_order").attr("ref", ref);
		$(".confirm_delete_order").attr("sub", sub);
		$("#msg_delete_order").html(messages.neutral.confirm_delete_order);
		$.magnificPopup.open({
			items: {
				src: '#modalMensajeDeleteOrder'
			},
			type: 'inline'
		}, 0);
	});

	$('#save-wallet').on('submit', updateWalletInfo);
	$('#create-wallet').on('click', createWallet);

	$("#submit_fact").click(function () {
		payFacs();
	});

	document.getElementById('contactForm')?.addEventListener('submit', sendContactForm, false);
	document.querySelectorAll('.js-auction-files').forEach(el => {
		el.addEventListener('click', showAuctionsFiles);
	});

});


function ajax_newcarousel(key, replace, lang, options) {

	const $carrouselElement = $(`#${key}`);
	const container = $carrouselElement.data('container');

	$.ajax({
		type: "POST",
		url: '/api-ajax/newcarousel',
		data: { key, replace, lang },
		success: (result) => {

			if (!result) {
				$carrouselElement.hide();

				if (container) {
					//change visibility of the container
					$(`#${container}`).css('visibility', 'hidden');
				}

			}

			$carrouselElement.siblings('.loader').addClass('hidden');
			$carrouselElement.html(result);

			if (key === 'lotes_destacados') {
				carrousel_molon($carrouselElement, options);
			} else {
				carrousel_molon_new($carrouselElement, options);
			}

			$('[data-countdown]').each(function () {
				$(this).data('ini', new Date().getTime());
				countdown_timer($(this));
			});

		}
	});
}

const defaultCarouselOptions = {
	slidesToScroll: 1,
	rows: 1,
	autoplay: true,
	slidesToShow: 4,
	arrows: true,
	dots: true,
	responsive: [
		{
			breakpoint: 1024,
			settings: {
				slidesToShow: 3,
				slidesToScroll: 3,
				infinite: true,
				rows: 1,
				slidesPerRow: 3,
			}
		},
		{
			breakpoint: 600,
			settings: {
				slidesToShow: 2,
				slidesToScroll: 2,
				rows: 1,
				slidesPerRow: 2,
			}
		},
		{
			breakpoint: 480,
			settings: {
				slidesToShow: 1,
				slidesToScroll: 1,
				rows: 1,
				slidesPerRow: 1,
			}
		}
	]
};

function ajaxStaticCarousel(key, {path, options}) {
	const $carrouselElement = $(`#${key}`);

	$.ajax({
		type: "POST",
		url: "/api-ajax/static-carousel",
		data: { path },
		success: (result) => {

			$carrouselElement.html(result)
			if ($carrouselElement.data('hasSlick')) {
				$carrouselElement.slick('unslick');
			}

			$carrouselElement.slick({
				...defaultCarouselOptions,
				...options
			});

			$carrouselElement.data('hasSlick', true);
		}
	});
}


function ajax_carousel(key, replace, lang, options) {
	//$( "#"+key ).siblings().removeClass('hidden');
	const $carrouselElement = $(`#${key}`);


	$.ajax({
		type: "POST",
		url: "/api-ajax/carousel",
		data: { key: key, replace: replace },
		success: function (result) {

			if (result === '') {
				$("#" + key + '-content').hide()
			}
			$("#" + key).siblings('.loader').addClass('hidden');
			$("#" + key).html(result);
			if (key === 'lotes_destacados') {
				carrousel_molon($carrouselElement, options);
			} else {
				carrousel_molon_new($carrouselElement, options);
			}


			$('[data-countdown]').each(function () {
				$(this).data('ini', new Date().getTime());
				countdown_timer($(this));
			});
		}

	});

};

/**
 * Solamente se llama desde customized_tr_main.js
 * @mover
 */
function format_date(fecha) {

	var horas = fecha.getHours();
	var minutos = fecha.getMinutes();
	var mes;
	if (horas < 10) {
		horas = '0' + horas
	}
	if (minutos < 10) {
		minutos = '0' + minutos
	}

	$.each(traductions, function (key, value) {
		if (key == $.datepicker.formatDate("M", fecha)) {
			mes = value;
		}
	});

	var formatted = $.datepicker.formatDate("dd ", fecha) + mes + " " + horas + ":" + minutos;
	return formatted;
}

function addStylesheetIfNotExist(nameFile) {

	var headElements = document.head.childNodes;
	const existStylesheet = Array.from(headElements)
		.filter((node) => node.nodeType === 1 && node.tagName === 'LINK')
		.some((link) => link.href.includes(nameFile));

	if (existStylesheet) return;

	try {
		document.head.insertAdjacentHTML('beforeend', `<link typs="text/css" rel="stylesheet" href="/${defaultTheme}/css/${nameFile}">`);
		document.head.insertAdjacentHTML('beforeend', `<link typs="text/css" rel="stylesheet" href="/${theme}/css/${nameFile}">`);
	}
	catch (e) {
		console.log(e);
	}
}



function carrousel_molon(carrousel, options) {

	addStylesheetIfNotExist('lot.css');

	if (carrousel.data('hasSlick')) {
		carrousel.slick('unslick');
	}

	carrousel.slick({
		...defaultCarouselOptions,
		...options
	});

	carrousel.data('hasSlick', true);
}

function carrousel_molon_new(carrousel, options) {
	if (carrousel.data('hasSlick')) {
		carrousel.slick('unslick');
	}

	carrousel.slick({
		...defaultCarouselOptions,
		...options
	});

	carrousel.data('hasSlick', true);
}

function password_recovery(event, lang) {

	event.preventDefault();

	var pass_recov = $("#password_recovery").serialize();
	$.ajax({
		type: "POST",
		url: '/' + lang + '/ajax-send-password-recovery',
		data: pass_recov,
		success: function (data) {
			if (data.status == 'error') {
				$(".error-recovery").html(data.msg);
			} else if (data.status == 'succes') {
				$("#password_recovery").html(data.msg);
			}
		}
	});
	return false;
};

function format_date_large(fecha) {

	const options = {
		year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric'
	};

	return new Intl.DateTimeFormat('es-ES', options).format(fecha) + " h";
}

function action_fav_modal(action) {

	$('.lds-ellipsis').show()
	$('.ficha-info-fav-ico a').addClass('hidden')

	$.magnificPopup.close();
	if (typeof cod_licit == 'undefined' || cod_licit == null) {
		$("#insert_msg").html(messages.error.mustLogin);
		$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
		return;
	} else {
		$.ajax({
			type: "GET",
			url: routing.favorites + "/" + action,
			data: { cod_sub: cod_sub, ref: ref, cod_licit: cod_licit },
			success: function (data) {
				$('.lds-ellipsis').hide()


				if (data.status == 'error') {
					$("#insert_msg").html("");
					$("#insert_msg").html(messages.error[data.msg]);
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				} else if (data.status == 'success') {
					$("#insert_msg").html("");
					$("#insert_msg").html(messages.success[data.msg]);
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);

					const isAdding = action == 'add';

					if (action == 'add') {
						$('.ficha-info-fav-ico a.added').removeClass('hidden')
						$('.ficha-info-fav-ico').addClass('active')
						$("#add_fav").addClass('hidden');
						$("#del_fav").removeClass('hidden');
						$("#add_fav_responsive").addClass('hidden');
						$("#del_fav_responsive").removeClass('hidden');
						$(".slider-thumnail-container #add_fav").addClass('hidden');
						$(".slider-thumnail-container #del_fav").removeClass('hidden');


					} else {
						$('.ficha-info-fav-ico').removeClass('active')

						$("#del_fav").addClass('hidden');
						$("#add_fav").removeClass('hidden');
						$(".slider-thumnail-container #add_fav").removeClass('hidden');
						$(".slider-thumnail-container #del_fav").addClass('hidden');

					}

				}

			}
		});
	}
};

function action_fav_lote(action, ref, cod_sub, cod_licit) {
	routing.favorites = '/api-ajax/favorites';
	//$.magnificPopup.close();

	$.ajax({
		type: "GET",
		url: routing.favorites + "/" + action,
		data: { cod_sub: cod_sub, ref: ref, cod_licit: cod_licit },
		success: function (data) {

			if (data.status == 'error') {

				$("#insert_msg").html("");
				$("#insert_msg").html(messages.error[data.msg]);
				$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
			} else if (data.status == 'success') {
				$("#insert_msg").html("");
				$("#insert_msg").html(messages.success[data.msg]);
				$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				$('.' + ref + '-' + cod_sub).remove();

			}

		}
	});

};


function payFacs(button) {

	$("#btoLoader").siblings().addClass('hidden');
	$("#btoLoader").removeClass('hidden');

	var pay_fact = $('#pagar_fact').serializeArray();
	var total = 0;

	for (const factura of pendientes) {
		if ($(`#checkFactura-${factura.anum_pcob}-${factura.num_pcob}-${factura.efec_pcob}`).is(":checked")) {
			total += parseFloat(factura.imp_pcob);
		}
	}

	if (total > 0) {
		$.ajax({
			type: "POST",
			url: '/gateway/pagarFacturasWeb',
			data: pay_fact,
			success: function (data) {
				if (data.status == 'success') {
					window.location.href = data.msg;
				} else
					if (data.status == 'error') {
						$("#modalMensaje #insert_msg").html('');
						$("#modalMensaje #insert_msg").html(messages.error.generic);
						$.magnificPopup.open({
							items: {
								src: '#modalMensaje'
							},
							type: 'inline'
						}, 0);

					}
				$("#btoLoader").siblings().removeClass('hidden');
				$("#btoLoader").addClass('hidden');

			},
			error: function (response) {
				$("#modalMensaje #insert_msg").html('');
				$("#modalMensaje #insert_msg").html(messages.error.generic);
				$.magnificPopup.open({
					items: {
						src: '#modalMensaje'
					},
					type: 'inline'
				}, 0);
				$("#btoLoader").siblings().removeClass('hidden');
				$("#btoLoader").addClass('hidden');
			}
		});

	}
}

function reload_facturas() {

	var total = 0;

	for (const factura of pendientes) {

		if ($(`#checkFactura-${factura.anum_pcob}-${factura.num_pcob}-${factura.efec_pcob}`).is(":checked")) {
			total += parseFloat(factura.imp_pcob);
		}
	}

	if (total > 0) {
		$("#submit_fact").removeClass('hidden');
	} else {
		$("#submit_fact").addClass('hidden');
	}
	$("#total_bills").html(change_currency(total));
}

function change_currency(price) {

	var price = numeral(price).format('0,0.00');

	return price;
}

/**
 * @param {Event} event
 */
function changeGrid(event) {
	const button = event.currentTarget;
	const changeType = button.dataset.grid;

	const gridSection = document.querySelector('.section-grid-lots');
	gridSection.classList.toggle('large', changeType === 'large');

	saveConfigurationCookies({ lot: changeType });
}

function hideFilters(event) {
	event.preventDefault();
	changeFilters(false);
}

function showFilters(event) {
	event.preventDefault();
	changeFilters(true);
}

function changeFilters(show) {
	const filtersElement = document.getElementById('js-filters-col');
	const lotsElement = document.getElementById('js-lots-col');
	const buttonShow = document.getElementById('js-show-filters');

	const colsToLotsElement = calculateLotsGridColumns(filtersElement);

	filtersElement.classList.toggle('d-none', !show);
	lotsElement.classList.toggle(`col-lg-${colsToLotsElement}`, show);
	lotsElement.classList.toggle('col-lg-12', !show);
	buttonShow.classList.toggle('d-none', show);
}

function calculateLotsGridColumns(filtersElement) {
	const maxCols = 12;
	const filterClasses = [...filtersElement.classList].find(className => className.includes('col-lg'));
	const filterCols = filterClasses.slice(-1);
	const elementsCols = maxCols - filterCols;
	return elementsCols;
}

(function ($) {

	$.fn.thumbPopup = function (options) {

		//Combine the passed in options with the default settings
		settings = jQuery.extend({
			popupId: "thumbPopup",
			popupCSS: {
				'border': '1px solid #000000',
				'background': '#FFFFFF'
			},
			imgSmallFlag: "lote_medium",
			imgLargeFlag: "lote_large",
			cursorTopOffset: 15,
			cursorLeftOffset: 15,
			loadingHtml: "<span style='padding: 5px;'>Loading</span>"
		}, options);

		//Create our popup element
		popup =
			$("<div />")
				.css(settings.popupCSS)
				.attr("id", settings.popupId)
				.css("position", "absolute")
				.css('z-index', 99999999)
				.appendTo("body").hide();

		//Attach hover events that manage the popup
		$(this)
			.hover(setPopup)
			.mousemove(updatePopupPosition)
			.mouseout(hidePopup);

		function setPopup(event) {

			var fullImgURL = $(this).attr("src").replace(settings.imgSmallFlag, settings.imgLargeFlag);
			$(this).data("hovered", true);

			var style = "style";
			if ($(this).attr("src").indexOf("portada") > -1) {
				style = "styleX";
			}

			//Load full image in popup
			$("<img />")
				.attr(style, "height:450px;max-width:100%; padding: 5px;z-index:9999999;")
				.bind("load", {
					thumbImage: this
				}, function (event) {
					//Only display the larger image if the thumbnail is still being hovered
					if ($(event.data.thumbImage).data("hovered") == true) {
						$(popup).empty().append(this);
						updatePopupPosition(event, style);
						$(popup).show();
					}
					$(event.data.thumbImage).data("cached", true);
				})
				.attr("src", fullImgURL);

			//If no image has been loaded yet then place a loading message
			if ($(this).data("cached") != true) {
				$(popup).append($(settings.loadingHtml));
				$(popup).show();
			}

			updatePopupPosition(event);
		}

		function updatePopupPosition(event, style) {
			var windowSize = getWindowSize();
			var popupSize = getPopupSize(style);

			var rectificaY = 0;
			var rectificaX = 0;

			if (event.pageX + popupSize.width > screen.width) {
				rectificaX = -(popupSize.width + 40);
			}
			$(popup).css("left", event.pageX + settings.cursorLeftOffset + rectificaX);

			if (event.pageY + popupSize.height > windowSize.height + windowSize.scrollTop) {
				rectificaY = (windowSize.height + windowSize.scrollTop) - (event.pageY + popupSize.height + 10);
			}
			$(popup).css("top", event.pageY + settings.cursorTopOffset + rectificaY);

		}

		function hidePopup(event) {
			$(this).data("hovered", false);
			$(popup).empty().hide();
		}

		function getWindowSize() {
			return {
				scrollLeft: $(window).scrollLeft(),
				scrollTop: $(window).scrollTop(),
				width: $(window).width(),
				height: $(window).height()
			};
		}

		function getPopupSize(style) {
			if (style == "styleX") {
				return {
					width: $(popup).width(),
					height: $(popup).height()
				};
			}

			return {
				width: 450,
				height: 450
			};
		}

		//Return original selection for chaining
		return this;
	};

})(jQuery);


/**
 * FICHA
 */
function goToImage(positionImage) {
	const viewer = OpenSeadragon.getViewer(document.getElementById("img_main"));
	showImageContainer();
	viewer.goToPage(parseInt(positionImage));
}

function showImageContainer() {
	$('#resource_main_wrapper').hide();
	if (typeof $resource != 'undefined' && $resource.lenght > 0 && typeof $resource[0].pause != 'undefined') {
		$resource[0].pause();
	}
	$('.img-global-content').show();
}

function loadSeaDragon(images = [], conf = {}) {

	showImageContainer();

	const defaultConf = {
		id: "img_main",
		tileSources: images,
		showNavigator: false, //mostrar miniatura de la imagen
		showReferenceStrip: false, //mostrar miniatura de todas las imagenes

		//id de contenedor y elementos de las toolbar
		toolbar: "js-toolbar",
		zoomInButton: "zoom-in",
		zoomOutButton: "zoom-out",
		homeButton: "home",
		fullPageButton: "full-page",
		nextButton: "next",
		previousButton: "previous",

		maxZoomPixelRatio: 2.5,
		minZoomImageRatio: 0.1,
		zoomPerScroll: 1.5,
		visibilityRatio: 1.0,
		constrainDuringPan: true, //no permitir salirse del cuadro
		preserveImageSizeOnResize: false, //mantener zoom al ampliar a pantalla completa

		sequenceMode: true, //permitir desplazarse entre imagenes
	};

	return OpenSeadragon({ ...defaultConf, ...conf });
}

function viewResourceFicha($src, $format) {
	$('#resource_main_wrapper').empty();
	$('.img-global-content').hide();
	if ($format == "GIF") {
		$resource = $('<img  src=' + $src + ' style="max-width: 100%;">');
	} else if ($format == "VIDEO") {
		$resource = $('<video width="100%" height="auto" autoplay="true" controls>').append($('<source src="' + $src + '">'));
	}
	$('#resource_main_wrapper').append($resource);
	$('#resource_main_wrapper').show();
}

function image360Init() {
	const image360 = {
		init: function () {
			this.cache();
			this.bindEvents();
		},
		cache: function () {
			this.btn = $('#360img-button');
			this.btnDesktop = $('.img-360-desktop');
			this.backgroundCache = $('.img-360-desktop').css('background-image');
			this.btnMobile = $('#js-360-btn-mobile');
			this.btnPic = $('.pic');
			this.actualImage = $('.zoomPad').find('img');
			this.img360 = $('#360img');
			this.img360Mobile = $('#js-360-img-mobile');
			this.zoomPad = $('.jqzoom');
			this.gif = $('#360img').find('img');
			this.gifMobile = $('#js-360-img-mobile img');
			this.no360 = $('.no-360');
			this.btnMobile.show();
			this.btn.show();

		},
		show: function (e) {

			if (this.btn.attr('data-active') === 'active') {
				this.hideContainer();

			} else {
				this.showContainer(false);
				//this.loadGif()
				this.activeBtn();
			}
		},

		hideContainer: function () {

			this.img360.addClass('d-none');
			this.img360Mobile.hide();
			$('#owl-carousel-responsive').show();
			this.zoomPad.show();
			this.disabledBtn();

		},
		showContainer: function (isMobile) {
			if ($(window).width() > 1199) {
				// this.img360.css('min-height', '350px');
			} else {
				if ($(window).width() > 991) {
					// this.img360.css('min-height', '400px');
				}
			}
			if ($(window).width() < 991) {
				this.img360Mobile.append($('.orbitvu-viewer'));
				// this.img360Mobile.css('min-height', '320px');
			}

			this.zoomPad.hide();
			this.img360.removeClass('d-none');

			$('#owl-carousel-responsive').hide();
			this.img360Mobile.show();
		},

		loadGif: function () {

			this.gif
				.attr('src', srcImage)
				.load(function () {
					$('.loader').hide();
					$(this).fadeIn();
				});

			this.gifMobile
				.attr('src', srcImage)
				.load(function () {
					$('.loader').hide();
					$(this).fadeIn();
				});
		},

		activeBtn: function () {

			this.btnDesktop.css('background-color', '#eee');
			this.btnDesktop.css('background-image', 'none');
			this.btnMobile.css('background-color', '#eee');
			this.btnMobile.css('background-image', 'none');
			this.btn.attr('data-active', 'active');
			this.btnMobile.attr('data-active', 'active');
			this.btnMobile.attr('data-active', 'active');

		},
		disabledBtn: function () {

			this.btnDesktop.css('background-image', this.backgroundCache);
			this.btnDesktop.css('background-color', 'transparent');
			this.btnMobile.css('background-image', this.backgroundCache);
			this.btnMobile.css('background-color', 'transparent');
			this.btn.attr('data-active', 'disabled');
			this.btnMobile.attr('data-active', 'disabled');
		},
		bindEvents: function () {
			this.btn.on('click', this.show.bind(this));
			this.btnMobile.on('click', this.show.bind(this));
			this.btnPic.on('click', this.hideContainer.bind(this));
			this.no360.on('click', this.hideContainer.bind(this));
		}
	};

	image360.init();
}

function calendarInitialize(...allEvents) {

	const urlSearchParams = new URLSearchParams(window.location.search);
	const currentYear = urlSearchParams.get('year') || new Date().getFullYear();

	const events = allEvents.flat().map((event, index) => {
		return {
			...event,
			id: index,
			startDate: new Date(event.startDate),
			endDate: new Date(event.endDate),
		}
	});

	const calendar = new Calendar('#calendar', {
		enableRangeSelection: false,
		language: 'es',
		startMonth: 1,
		startYear: currentYear,
		minDay: new Date().getDay(),
		maxDaysToChoose: false,
		displayHeader: false,
		mouseOnDay: function (e) {

			if (e.events.length > 0) {
				let content = '';
				e.events.forEach((eventCalendar) => {
					content += `<div class="event-tooltip-content">
									<div class="event-name" style="color: ${eventCalendar.color}">${eventCalendar.description}</div>
									</div>`;
				});

				$(e.element).popover({
					trigger: 'manual',
					container: 'body',
					html: true,
					content: content
				});

				$(e.element).popover('show');
			}
		},
		mouseOutDay: function (e) {
			if (e.events.length > 0) {
				$(e.element).popover('hide');
			}
		},
		dayContextMenu: function (e) {
			$(e.element).popover('hide');
		},
		/**Para que color pinte el fondo  */
		style: 'background',
		customDayRenderer: function (element, date) {
			/* pintar fin de semana */
			if (date.getDay() === 6 || date.getDay() === 0) {
				/* lo añadimso al padre para asi dejar poner mas clases si cae un dia especial */
				element.closest('.day').style.setProperty('--calendar-background-color', '#ff0000');
			}
		},
		clickDay: function (el) {
			if (el.events.length == 0) return;
			if (!Boolean(el.events[0].url)) return;

			const { url } = el.events[0];
			window.open(url, '_blank');
		},
		dataSource: events,
	});

	return calendar;
}

async function sendContactForm(event) {

	event.preventDefault()
	event.stopPropagation()

	const form = event.target;
	form.classList.add('was-validated');

	if (!form.checkValidity()) {
		showMessage(messages.error.hasErrors);
		return false;
	}

	await executeCaptchaV3();

	if(!checkCaptcha()) {
		showMessage(messages.error.hasErrors);
		return false;
	}

	$.ajax({
		type: "POST",
		url: "/contactSendmail",
		data: $(contactForm).serialize(),
		success: function (response) {
			if (response.status == "error") {
				showMessage(response.message);
			} else {
				showMessage(response, "");
				setTimeout("location.reload()", 4000);
			}
		},
		error: function (response) {
			showMessage("Error");
		}
	});
}

function showAuctionsFiles(event) {

	const modalElement = document.getElementById('documentsModal');
	modalElement.querySelector('.modal-body').innerHTML = '';

	const filesModal = new bootstrap.Modal(modalElement);

	const element = event.target.classList.contains('js-auction-files')
		? event.target
		: event.target.parentElement;

	const reference = element.dataset.reference;
	const auction = element.dataset.auction;

	auctionFiles(auction, reference)
		.then(data => {
			modalElement.querySelector('.modal-body').innerHTML = data.html;
			filesModal.show();
		});
}

async function auctionFiles(auction, reference) {
	const response = await fetch('/api-ajax/sessions/files', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify({ auction, reference })
	});
	return await response.json();
}

function showLogin(event) {
	$('.login_desktop').fadeToggle("fast");
	$('.login_desktop [name=email]').focus();
}

function closeLogin(event) {
	$('.login_desktop').fadeToggle("fast");
}

/**
 * @todo
 * - [X] añadir evento loading mientras carga
 * - [X] eliminar evento al recibir respuesta
 * - [ ] controlar respuesta de error
 * @param {Event} event
 */
function handleSubmitLoginForm(event) {
	event.preventDefault();

	const button = document.getElementById('accerder-user');
	button.classList.toggle('loading', true);

	$.ajax({
		type: "POST",
		url: '/login_post_ajax',
		data: $('#accerder-user-form').serialize(),
		success: successLoginForm,
		complete: () => {
			button.classList.toggle('loading', false);
		}
	});
}

function successLoginForm(response) {
	if (response.status == 'success') {
		location.reload();
	} else {
		$(".message-error-log").text('').append(messages.error[response.msg]);
	}
}

function handleSubmitNewsletterForm(event) {

	var parent = $(this).closest(".newsletter-js");

	var email = parent.find('.newsletter-input-email-js').val();

	var lang = parent.find('.lang-newsletter-js').val();
	var entrar = parent.find('.condiciones-newsletter-js').prop("checked");
	const families = {};
	parent.find("[name^=families]").each(function(index) {
		if($(this).prop("checked")|| $(this).is(":hidden")) {
			families[$(this).val()] = 1;
		}
	});

	if (entrar) {
		$.ajax({
			type: "POST",
			data: { email: email, lang: lang, condiciones: 1, families: families },
			url: '/api-ajax/newsletter/add',
			beforeSend: function () {
			},
			success: function (msg) {
				if (msg.status == 'success') {
					$('.insert_msg').html(messages.success[msg.msg]);
				} else {
					$('.insert_msg').html(messages.error[msg.msg]);
				}
				$.magnificPopup.open({ items: { src: '#newsletterModal' }, type: 'inline' }, 0);
			}
		});
	} else {
		$("#insert_msgweb").html(messages.neutral.accept_condiciones);
		$.magnificPopup.open({ items: { src: '#modalMensajeWeb' }, type: 'inline' }, 0);
	}
}

/**
 *
 * @param {Event} event
 */
function handleSubmitUpdatePasswordForm(event) {
	event.preventDefault();

	var $this = $(event.target);
	$('button', $this).attr('disabled', 'disabled');

	$.ajax({
		type: "POST",
		url: '/api-ajax/client/update/password',
		data: $('#frmUpdateUserPasswordADV').serialize(),
		beforeSend: function () {
			//$('#btnRegister').prepend(' <i class="fa fa-spinner fa-pulse fa-fw margin-bottom"></i> ');
		},
		success: function (response) {

			$('button', $this).attr('disabled', false);

			res = jQuery.parseJSON(response);

			if (res.err == 1) {
				$('.insert_msg').html('<div class="alert alert-danger">' + messages.error[res.msg] + '</div>');
			} else {
				$('.insert_msg').html('<div class="alert alert-success">' + messages.success[res.msg] + '</div>');
			}

		}
	});
}

function handleSubmitUpdateUser(event) {
	event.preventDefault();
	var $this = $(event.target);

	$('button', $this).attr('disabled', 'disabled');
	// Datos correctos enviamos ajax
	$.ajax({
		type: "POST",
		url: '/api-ajax/client/update',
		data: $('#frmUpdateUserInfoADV').serialize(),
		beforeSend: function () {
			$('#btnRegister').prepend(' <i class="fa fa-spinner fa-pulse fa-fw margin-bottom"></i> ');
		},
		success: function (response) {

			$('button', $this).attr('disabled', false);

			res = jQuery.parseJSON(response);

			if (res.err == 1) {
				$('.col_reg_form').html('<div class="alert alert-danger">' + messages.error[res.msg] + '</div>');
			} else {
				$('.col_reg_form').html('<div class="alert alert-success">' + messages.success[res.msg] + '</div>');
			}
		}

	});
}

function updateWalletInfo(event) {
	event.preventDefault();

	const walletForm = event.target;

	$.ajax({
		type: "POST",
		data: $(walletForm).serialize(),
		url: '/api-ajax/wallet/update',
		dataType: 'json',
		beforeSend: function () {
			walletForm.querySelector('button[type="submit"]').disabled = true;
		},
		success: function (response) {
			const alertClass = response.status == 'success' ? 'alert-success' : 'alert-danger';
			const htmlResult = `<div class="alert ${alertClass}">${response.message}</div>`;
			document.getElementById('wallet-call-result').innerHTML = htmlResult;
		},
		error: function (xhr, status) {
			const htmlResult = `<div class="alert alert-danger">${xhr.responseJSON.message}</div>`
			document.getElementById('wallet-call-result').innerHTML = htmlResult;
		},
		complete: function () {
			walletForm.querySelector('button[type="submit"]').removeAttribute('disabled');
		}
	})

}

function createWallet(event) {
	event.preventDefault();
	const createButton = event.target;

	$.ajax({
		type: "POST",
		url: '/api-ajax/wallet/create',
		dataType: 'json',
		beforeSend: function () {
			createButton.disabled = true;
		},
		success: function (response) {
			location.href = response.url;
		},
		error: function (xhr, status) {
			const htmlResult = `<div class="alert alert-danger">${xhr.responseJSON.message}</div>`
			document.getElementById('wallet-call-result').innerHTML = htmlResult;
		},
		complete: function () {
			createButton.removeAttribute('disabled');
		}
	})
}

function cerrarLogin() {
	$('.login_desktop').fadeToggle("fast");
}

function checkCaptcha() {
	const response = document.querySelector('[name="g-recaptcha-response"]').value;
	return Boolean(response);
}

async function executeCaptchaV3() {
	const captchaElemenent = document.querySelector('[name="captcha_token"]');

	if(!captchaElemenent) return;

	const key = captchaElemenent.getAttribute('data-sitekey');

	return new Promise((resolve, reject) => {

		grecaptcha.ready(function() {
			grecaptcha.execute(key, {action: 'submit'})
			.then(function(token) {

				if(!token) reject('No token found');

				captchaElemenent.value = token;
				resolve();
			});
		});
	});
}

// En la version dos este metodo no aplica, y se llama desde common.js
ajax_shipping = function(cod_ship, lang) {
	return true;
}

function executeOnceToDay(storageName, callback){
	if (!window.localStorage) {
		return;
	}

	const storage = window.localStorage;
	const today = new Date().toDateString();
	const executed = storage.getItem(storageName);

	if(executed !== today) {
		callback();
		storage.setItem(storageName, today);
	}
}
