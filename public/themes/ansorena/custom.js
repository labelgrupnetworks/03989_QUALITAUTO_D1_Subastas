globalThis.posistionOriginal = 0;
globalThis.scrollElement = 0;

$(() => {
	/* $(document).off("scroll");

	let options = {
		mobile: {
			slidesPerView: "auto",
			spaceBetween: 10,
			lazyPreloadPrevNext: 1,
			wrapperClass: 'gallery-lots-xs',
			navigation: {
				nextEl: ".swiper-button-next",
				prevEl: ".swiper-button-prev",
			},
		},
		desktop: {
			spaceBetween: 30,
			lazyPreloadPrevNext: 4,
			mousewheel: {
				releaseOnEdges: true,
			},
			slidesPerView: "auto",
			scrollbar: {
				el: ".swiper-scrollbar",
			},
			pagination: false
		}
	};


	if ($('.gallery-lots').length) {
		new Swiper(".mySwiper", window.screen.width > 992 ? options.desktop : options.mobile);
	} */

	//al pulsar botón de orden telefónica
	$("#pujar_orden_telefonica").click(function () {
		ga('send', 'event', 'Puja', 'Realizada');
		$('#precio_orden_modal').val($('#bid_modal_pujar').val());
	});
	$("#pujar_ordenes_w_ansorena").click(function () {

		$("#orderphone").val("");
		$(".phonebid_js").addClass("hide");
		ga('send', 'event', 'Puja', 'Realizada');

		confirmar_orden()
	});

	$('#confirm_orden').unbind('click');

	$("#confirm_orden").click(function () {

		imp = $("#bid_modal_pujar").val();
		if ($("#orderphone").val() == "S") {
			tel1 = $("#phone1Bid_JS").val();
			tel2 = $("#phone2Bid_JS").val();
			if(tel1.length == 0 && tel2.length == 0){
				$("#errorOrdenFicha").removeClass("hidden");
				$("#errorOrdenFicha").html(messages.error["noPhoneInPhoneBid"]);
				/* Evitamos que se cierre */
				event.preventDefault();
				return ;
			}
		} else {
			tel1 = "";
			tel2 = "";
		}
		$.magnificPopup.close();
		$.ajax({
			type: "POST",
			url: routing.ol + '-' + cod_sub,
			data: { cod_sub: cod_sub, ref: ref, imp: imp, tel1: tel1, tel2: tel2 },
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
					ga('send', 'event', 'Puja', 'Confirmada');

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

	$('#newsletter-btn-ansorena').on('click', function () {
		var email = $('.newsletter-input').val();
		var lang = $('#lang-newsletter').val();
		var families = [];
		//coge los ocultos
		$(".newsletter").each(function (index) {
			if ($(this).prop("checked")) {
				families.push($(this).val());
			}
		});

		var entrar = false;

		if ($('#condiciones').prop("checked")) {
			entrar = true;
		}
		if ($('#bool__1__condiciones').prop("checked")) {
			entrar = true;
		}


		if (entrar) {
			$(".newsletter-input").removeClass("has-error");
			$(".checknewsletter").removeClass("has-error");
			$.ajax({
				type: "POST",
				data: { email: email, families: families, condiciones: 1, lang: lang },
				url: '/api-ajax/newsletter/add',
				beforeSend: function () {
				},
				success: function (msg) {
					if (msg.status == 'success') {
						$('.insert_msg').html(messages.success[msg.msg]);
					} else {
						$('.insert_msg').html(messages.error[msg.msg]);
						$(".newsletter-input").addClass("has-error");
					}
					$.magnificPopup.open({ items: { src: '#newsletterModal' }, type: 'inline' }, 0);
				}
			});
		} else {
			$(".newsletter-input").addClass("has-error");
			$(".checknewsletter").addClass("has-error");
			$("#insert_msgweb").html('');
			$("#insert_msgweb").html(messages.neutral.accept_condiciones);
			$.magnificPopup.open({ items: { src: '#modalMensajeWeb' }, type: 'inline' }, 0);
		}
	});

	document.querySelector('.js-button-up').addEventListener('click', goUpPage)

	$('#locale-select').on('select2:opening', () => toogleLocaleSelect(true));
	$('#locale-select').on('select2:closing', () => toogleLocaleSelect(false));
	$('#locale-select').on('change', changeLocale);

	$('#order-select').on('select2:opening', () => toogleOrderSelect(true));
	$('#order-select').on('select2:closing', () => toogleOrderSelect(false));
	$('#order-select').on('change', changeOrder);

	$('#filter-select').on('select2:opening', () => toogleFilterTeamSelect(true));
	$('#filter-select').on('select2:closing', () => toogleFilterTeamSelect(false));
	$('#filter-select').on('change', changeFilterTeam);

	$('.accordion-show').on('click', toogleAccordionFaqs);

	document.querySelector('.search-component .icon')?.addEventListener('click', handleClickSearchComponent);
	document.querySelector('.search-button')?.addEventListener('click', handleClickSearchButton);
	document.querySelector('.search-button span[type="reset"]')?.addEventListener('click', handleClickCloseSearchButton);

	window.addEventListener("scroll", logScroll);
	//document.querySelector('.mandatory-block')?.addEventListener('scroll', logElementScroll);

	const tabsHeader = document.querySelectorAll('#menu-header a[role=tab]').forEach((element) => {
		element.addEventListener('click', (event) => {
			event.preventDefault();
			const id = event.target.getAttribute("href");
			hiddeSubmenu();
			document.getElementById('submenu-header').classList.add('open');
			document.querySelector(id).classList.add('active');
		})
	});

	document.getElementById('toogleFilters')?.addEventListener('click', toogleGridFilters);
	document.getElementById('toogleOrders')?.addEventListener('click', toogleGridOrders);
	document.querySelector('.lots-filters .btn-close')?.addEventListener('click', closeGridMenu);

	//showMessageModalOneTimeToSession();

	$('.order-auction-lot input[name="order"]').on('change', function (event) {
		document.getElementById('form_lotlist').submit();
	})

	$('#button-map').click(function () {

		if ($(this).hasClass('active')) {
			$('.maps-house-auction').animate({
				left: '100%'
			}, 300)
			$(this)
				.removeClass('active')
				.find('i').addClass('fa-map-marker-alt').removeClass('fa-times')
		} else {
			$('.maps-house-auction').animate({
				left: 0
			}, 0)
			$(this)
				.addClass('active')
				.find('i').removeClass('fa-map-marker-alt').addClass('fa-times')
		}

	})


	$(".input-effect").val("");

	$(".input-effect input").focusout(function () {
		if ($(this).val() != "") {
			$(this).addClass("has-content");
		} else {
			$(this).removeClass("has-content");
		}
	})
	$(".input-effect textarea").focusout(function () {
		if ($(this).val() != "") {
			$(this).addClass("has-content");
		} else {
			$(this).removeClass("has-content");
		}
	})
});

function toogleAccordionFaqs(event) {
	event.preventDefault();
	const elementClick = event.currentTarget;

	const toShow = elementClick.dataset.show === 'true' ? false : true;
	elementClick.dataset.show = toShow ? 'true' : 'false';
	elementClick.innerText = toShow ? elementClick.dataset.textHidden : elementClick.dataset.textShow;

	const accordion = elementClick.closest('.accordion');
	accordion.querySelectorAll('.accordion-item.hideable-item').forEach((element) => {
		element.classList.toggle('d-none', !toShow);
	});
}

function goUpPage(event) {
	$('html,body').animate({ scrollTop: 0 }, 500);
}

function toogleLocaleSelect(addOpen) {
	const selectCointainer = document.querySelector('.select-container');
	selectCointainer.classList.toggle('open', addOpen);
}

function changeLocale(event) {
	/* const selectCointainer = document.querySelector('.select-container'); */
	location.href = event.target.value;
};

function toogleOrderSelect(addOpen) {
	const selectCointainer = document.querySelector('.select-container.order-select');
	selectCointainer.classList.toggle('open', addOpen);
}

function changeOrder(event) {
	const form = event.target.closest('form');
	form.submit();
};

function handleClickCloseSearchButton(event) {
	event.stopPropagation();
	const button = event.currentTarget.closest('button');
	const inputText = button.querySelector('input');

	button.classList.remove('open');
	inputText.value = "";
	return;
}

function toogleFilterTeamSelect(addOpen) {
	const selectCointainer = document.querySelector('.select-container.order-select');
	selectCointainer.classList.toggle('open', addOpen);
}

function changeFilterTeam(event) {
	const selectValue = event.target.value;
	document.querySelectorAll('[data-titulo]').forEach((element) => {

		if (selectValue === 'ALL') {
			element.classList.remove('d-none');
			return;
		}

		element.classList.toggle('d-none', element.dataset.titulo !== selectValue);
	});
}

/**
 * @param {Event} event
 */
function handleClickSearchButton(event) {
	const button = event.currentTarget;
	const isOpen = button.classList.contains('open');
	const inputText = button.querySelector('input');

	if (!isOpen) {
		button.classList.add('open');
		inputText.focus();
		return
	}

	const clickElement = event.target;
	const isIconClick = clickElement.classList.contains('icon');
	if (!isIconClick) return

	const searchValue = inputText.value.trim();

	if (searchValue) {
		const form = button.closest('form');
		form.submit();
		/* const url = new URL(window.location.href);
		url.searchParams.append(inputText.name, searchValue);
		window.location.href = url.href; */
		return
	}

	button.classList.remove('open');
}

function handleClickSearchComponent(event) {
	const searchComponent = document.querySelector('.search-component');
	const isOpen = searchComponent.classList.contains('open');
	searchComponent.classList.toggle('open', !isOpen);

	const searchingWrapper = searchComponent.parentNode;
	const isGallerySearch = searchingWrapper.classList.contains('search-gallery-wrapper');
	if (isGallerySearch) {
		searchingWrapper.classList.toggle('open', !isOpen);
	}

	isOpen && searchComponent.querySelector('input').focus();
}

/**
 * Crea movimientos de pantalla extraños, es mejor deshabilitarlo
 */
function logElementScroll(event) {

	return;
	if (isHeaderOpenOrInMobile()) {
		return;
	}

	const element = event.target;

	//Si el elemento a partir del cual se mide el scroll está por encima del viewport, no hacemos nada
	if (element.getBoundingClientRect().top < 0) {
		return;
	}

	//Retrasamos la acción a desplazarse un mínimo
	if (Math.abs(element.scrollTop - globalThis.scrollElement) < 300) {
		return;
	}

	const isGoingUp = element.scrollTop < globalThis.scrollElement;
	globalThis.scrollElement = element.scrollTop;

	const menuHeader = document.querySelector('.menu-header');
	menuHeader.classList.toggle('open', isGoingUp);

	const sticky = isGoingUp ? "180px" : "120px";
	document.documentElement.style.setProperty('--top-sticky-sections', sticky);
}

function isHeaderOpen() {
	return document.querySelector('#submenu-header').classList.contains('open');
}

function isInMobileScreen() {
	return window.screen.width < 992;
}

function isHeaderOpenOrInMobile() {
	return isHeaderOpen() || isInMobileScreen();
}

function logScroll(event) {

	if (isInMobileScreen()) {
		document.documentElement.style.setProperty('--top-sticky-sections', "72px");
		return;
	}

	if (isHeaderOpen()) {
		return;
	}

	//Retrasamos la acción a desplazarse un mínimo
	if (Math.abs(window.scrollY - globalThis.posistionOriginal) < 300) {
		return;
	}

	const isGoingUp = window.scrollY < globalThis.posistionOriginal;
	globalThis.posistionOriginal = window.scrollY;

	const menuHeader = document.querySelector('.menu-header');
	menuHeader.classList.toggle('open', isGoingUp);

	const sticky = isGoingUp ? "180px" : "120px";
	document.documentElement.style.setProperty('--top-sticky-sections', sticky);
}

function hiddeSubmenu() {
	document.querySelectorAll("#submenu-header [role=tabpanel]").forEach((element) => {
		element.classList.remove('active');
	});
}

function closeSubmenu() {
	document.getElementById('submenu-header').classList.remove('open');
}

function toogleMenu(menuButton) {
	const menuHeader = document.querySelector('.menu-header');
	const isOpen = menuButton.getAttribute("aria-expanded") === "true";
	const logo = document.querySelector(".logo-link img");

	menuButton.setAttribute("aria-expanded", !isOpen);

	menuHeader.classList.remove('open-lg');
	menuHeader.classList.toggle('open', !isOpen);
	logo.classList.toggle('d-none', !isOpen);

	closeSubmenu();
}

function toogleGridFilters() {
	const menu = document.querySelector('.lots-filters');
	const filtersMenu = document.querySelector('.form-filters');
	const orderMenu = document.querySelector('.order-auction-lot');

	filtersMenu.classList.remove('d-none');
	orderMenu.classList.add('d-none');
	menu.classList.add('open');
}

function toogleGridOrders() {
	const menu = document.querySelector('.lots-filters');
	const filtersMenu = document.querySelector('.form-filters');
	const orderMenu = document.querySelector('.order-auction-lot');

	filtersMenu.classList.add('d-none');
	orderMenu.classList.remove('d-none');
	menu.classList.add('open');
}

function closeGridMenu() {
	const menu = document.querySelector('.lots-filters');
	menu.classList.remove('open');
}

carrousel_molon_new = function (carrousel) {
	if (carrousel.data('hasSlick')) {
		carrousel.slick('unslick');
	}

	carrousel.slick({
		slidesToScroll: 1,
		rows: 1,
		slidesToShow: 4,
		arrows: false,
		swipeToSlide: true,
		infinite: true,
		responsive: [
			{
				breakpoint: 1200,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 3,
					slidesPerRow: 3,
				}
			},
			{
				breakpoint: 992,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2,
					slidesPerRow: 2,
				}
			},
			{
				breakpoint: 600,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					slidesPerRow: 1,
				}
			}

		]
	});

	carrousel.data('hasSlick', true);
}

window.collapseWithIconClick = function (event) {

	const element = (event.target.nodeName != 'I') ? event.target : event.target.closest('div[data-toggle="collapse"]');
	const icon = element.querySelector('i');

	if (element.ariaExpanded === "true") {
		icon.classList.remove('fa-caret-down');
		icon.classList.add('fa-caret-up');
		return;
	}

	icon.classList.add('fa-caret-down');
	icon.classList.remove('fa-caret-up');
}

function selectToSelect2(jqSelect) {

	const $parentElement = $(`#${jqSelect[0].parentElement.id}`);

	jqSelect.select2({
		dropdownParent: $parentElement,
		minimumResultsForSearch: Infinity,
		width: 'resolve',
		templateSelection: (state) => {
			if (!state.id) {
				return $(`<span class="select2-selection__rendered">${jqSelect[0].dataset.label}</span>`)
			}

			return $(`<span class="select2-selection__rendered">TALLA ${state.text}</span>`);
		}
	});

	//hacer el select2 visible
	$parentElement.removeClass('d-none');
}

function sendInfoLot(event) {
	event.preventDefault();
	event.stopPropagation();

	const submitButton = event.target.querySelector('button[type=submit]');
	submitButton.disabled = true;

	$.ajax({
		type: "POST",
		data: $("#infoLotForm").serialize(),
		url: '/api-ajax/ask-info-lot',
		success: function(res) {

			showMessage("¡Gracias! Hemos sido notificados.  ");
			$("input[name=nombre]").val('');
			$("input[name=email]").val('');
			$("input[name=telefono]").val('');
			$("textarea[name=comentario]").val('');

		},
		error: (e) => showMessage("Ha ocurrido un error y no hemos podido ser notificados"),
		complete: () => submitButton.disabled = false

	});

	return false;
}


$(() => {
	const horizontalScroll = document.querySelector(".gallery-exhibition-swipper");
	const scrollContent = document.querySelector(".gallery-exhibition-content");

	function setScrollGalleryHeight(element) {
		const scrollHeight = scrollContent.scrollWidth - window.innerWidth + window.innerHeight + 150;
		element.style.height = `${scrollHeight}px`;
	}

	if (horizontalScroll && window.innerWidth > 768) {

		window.addEventListener("load", () => {
			setScrollGalleryHeight(horizontalScroll);
		});

		window.addEventListener("scroll", function () {
			if (window.innerWidth > 768) {

				if(horizontalScroll.style.height == "") {
					setScrollGalleryHeight(horizontalScroll);
				}

				const scrollGallery = horizontalScroll.querySelector(".gallery-lots");
				const topPosition = scrollContent.getBoundingClientRect().top;
				const topStickyString = document.documentElement.style.getPropertyValue('--top-sticky-sections');
				const topSticky = parseInt(topStickyString.substring(0, topStickyString.length - 2));

				if (topPosition >= (topSticky - 1) && topPosition <= (topSticky + 1)) {
					scrollGallery.style.transform = "translateX(-" + (-1 * horizontalScroll.getBoundingClientRect().top) + "px)";
				}
			}
		});

	} else if (horizontalScroll && window.innerWidth <= 768) {
		$('.gallery-lots').slick({
			dots: false,
			infinite: true,
			speed: 300,
			slidesToShow: 1,
			centerMode: false,
			variableWidth: true,
			slide: '.gallery-lot',
			prevArrow: $('.swiper-button-prev'),
      		nextArrow: $('.swiper-button-next'),
		  });
	}
});

//ficha observer
$(() => {

	const targetElement = document.getElementById('precio_orden_modal');
	const spanOrderPriceElement = document.querySelector('.precio_orden');
	if (targetElement) {

		$('#precio_orden_modal').autoComplete({
			minChars: 1,
			cache: false,
			source: function (term, response) {
				try {
					xhr.abort();
				} catch (e) {
				}
				$.getJSON('/api-ajax/calculate_bids/' + auction_info.lote_actual.actual_bid + '/' + term, function (data) {
					var matches = [];
					for (i = 0; i < data.length; i++) {
						matches.push(data[i].toString());
					}
					response(matches);
				});
			}
		});

		['input', 'change'].forEach((eventType) => {
			targetElement.addEventListener(eventType, (event) => {
				const value = event.target.value.replace(/[^0-9]/g, '') || 0;

				$('#bid_modal_pujar').val(value);

				//const valueFormat = new Intl.NumberFormat('es-ES').format(value);
				const valueFormat = value.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.")
				spanOrderPriceElement.innerText = valueFormat;
			});
		});
	}
});

function newsletterDay(){
	// First check, if localStorage is supported.
	if (!window.localStorage) {
		return;
	}

	const nextPopup = localStorage.getItem('nextNewsletter');
	if (new Date(nextPopup) > new Date()) {
		return;
	}

	const now = new Date();
	const expires = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1);
	localStorage.setItem('nextNewsletter', expires);

	$.magnificPopup.open({ items: { src: '#newsletterDailyModal' }, type: 'inline' }, 0);
}

function showRematesModal() {
	//si ya hay un modal abierto, volvemos a lanzar el evento hasta que se cierre
	if ($('.mfp-bg.mfp-ready').length) {
		setTimeout(showRematesModal, 1000);
		return;
	}
	$.magnificPopup.open({ items: { src: '#rematesModal' }, type: 'inline' }, 0);
}

function showMessageModalOneTimeToSession() {
	if (!$('.message_modal').length) {
		return;
	}

	if (!sessionStorage.getItem('message_modal')) {
		$('.message_modal')[0].style.display = 'block';
		sessionStorage.setItem('message_modal', true);
	}
}

function showLoginFromModal() {
	$.magnificPopup.close();

	$('.login_desktop').fadeToggle("fast");
	$('.login_desktop [name=email]').focus();
}
