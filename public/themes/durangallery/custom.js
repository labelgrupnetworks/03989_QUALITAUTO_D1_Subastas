
$(function () {

	$('#js-ficha-login').on('click', (event) => {
		$.magnificPopup.open({items: {src: '#modalCustomLogin'}, type: 'inline'}, 0);
	});

	$('.search-header').unbind('click');
	$('.search-header').on('click', e => searchHeader(e));

	/* $('.category-col a').on('mouseenter', function() {
		$('.tab-content.tab-header > div').removeClass('active').removeClass('in');
		$(this).tab('show');
	}); */

	$('.button-up i').removeClass('fa-chevron-up').addClass('fa-arrow-up');

	$('#js-artist-read-less').on('click', artistReadLess);
	$('#js-artist-read-more').on('click', artistReadMore);
	$('#filters-control').on('click', showOrRemoveGridFilters);



	let readMoreMaxLine;
	// Si existe el elemento con el ID 'js-read-desc' ejecutar función readMoreHiddenOrNot
	if (document.getElementById('js-read-desc')) {
		readMoreHiddenOrNot();
	}
	$('#js-read-less').on('click', readLess);
	$('#js-read-more').on('click', readMore);


	$('.fav_element, #lotsGrid').on('click', action_fav_lote);

	/* $("#accerder-user").unbind('click');
	$("#accerder-user").on('click', function () {
		$(this).addClass('loadbtn')
		$('.login-content-form').removeClass('animationShaker')
		$.ajax({
			type: "POST",
			url: '/custom_login',
			data: $('#accerder-user-form').serialize(),
			success: function (response) {
				console.log(response);
				if (response.status == 'success') {
					//location.reload();
				} else {
					$(".message-error-log").text('').append(messages.error[response.msg]);
					$("#accerder-user").removeClass('loadbtn')
					$('.login-content-form').addClass('animationShaker')
				}


			}


		});
	}); */

});

function showOrRemoveGridFilters(event) {

	const element = document.querySelector('.auction-lots-view');
    const estateFilter = window.getComputedStyle(element, null).display;
    estateFilter == 'none'
		? reduceGrid(element)
		: $(element).hide('slow', enlargeGrid);

}

function enlargeGrid(element) {
	if (window.innerWidth <= 768 == false) {
		$('#aucion-lots-container').removeClass('col-md-9');
		$('#lotsGrid').removeClass('grid-2').addClass('grid-3');
	}
}
function reduceGrid(element) {
	$(element).show('slow');
	if (window.innerWidth <= 768 == false) {
		$('#aucion-lots-container').addClass('col-md-9', );
		$('#lotsGrid').addClass('grid-2').removeClass('grid-3');
	}
}

function artistReadMore(event){

	$('#js-artist-read-more').addClass('hidden');
	$('#js-artist-read-less').removeClass('hidden');
	document.getElementById('js-artist-bio').style.setProperty('--max-line', 0);
	return;
}

function artistReadLess(event){
	$('#js-artist-read-more').removeClass('hidden');
	$('#js-artist-read-less').addClass('hidden');
	document.getElementById('js-artist-bio').style.setProperty('--max-line', 3);
	return;
}

function readMoreHiddenOrNot(event) {
	// Guardar el contenido html del elemento don el ID 'js-read-desc'
	let descElement = document.getElementById('js-read-desc');
	let descElementContent = descElement.innerHTML;

	// Si el contenido tiene menos de 200 caracteres, ocultar el boton de leer mas
	if (descElementContent.length < 200) {
		$('#js-read-more').addClass('hidden');
		$('#js-read-less').addClass('hidden');
		document.getElementById('js-read-desc').style.setProperty('--max-line', 0);
		return;
	}

	return;
}

function readMore(event){
	// Recoge la cantidad de líneas que tiene la propiedad '--max-line'
	let descElement = document.getElementById('js-read-desc');
	let descElementStyle = window.getComputedStyle(descElement);
	readMoreMaxLine = descElementStyle.getPropertyValue('--max-line');

	$('#js-read-more').addClass('hidden');
	$('#js-read-less').removeClass('hidden');
	document.getElementById('js-read-desc').style.setProperty('--max-line', 0);
	return;
}

function readLess(event){
	$('#js-read-more').removeClass('hidden');
	$('#js-read-less').addClass('hidden');
	document.getElementById('js-read-desc').style.setProperty('--max-line', readMoreMaxLine);
	return;
}

action_fav_lote = function (event) {

	$.magnificPopup.close();

	const element = event.target;
	const { dataset } = element;

	if(element && typeof dataset.action == 'undefined') {
		return;
	}

	if (typeof dataset.cod_licit == 'undefined' || dataset.cod_licit == null) {
		$("#insert_msg").html(messages.error.mustLogin);
		$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
		return;
	}

	const url = new URL(location.origin + "/api-ajax/favorites/" + dataset.action);
	url.search = new URLSearchParams({...dataset}).toString();

	fetch(url)
	.then(res => res.json())
	.then(({data, msg, status}) => {
		if (status == 'error') {
			$("#insert_msg").html("");
			$("#insert_msg").html(messages.error[msg]);
			$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
			return;
		}

		if (status == 'success') {
			$("#insert_msg").html("");
			$("#insert_msg").html(messages.success[msg]);
			$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);

			if (dataset.action == 'add') {
				element.dataset.action  = 'remove';
				element.classList.remove('fa-heart-o');
				element.classList.add('fa-heart');

			} else {
				element.dataset.action = 'add';
				element.classList.add('fa-heart-o');
				element.classList.remove('fa-heart');
			}
		}
	})

	$('#newsletter-btn').on('click', function () {
		var email = $('.newsletter-input').val();
		var lang = $('#lang-newsletter').val();

		var entrar = false;
		if ($('#condiciones').prop("checked")) {
			entrar = true;
		}

		if (entrar) {
			$.ajax({
				type: "POST",
				data: { email: email, lang: lang, condiciones: 1, families: [1] },
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
			$("#insert_msgweb").html('');
			$("#insert_msgweb").html(messages.neutral.accept_condiciones);
			$.magnificPopup.open({ items: { src: '#modalMensajeWeb' }, type: 'inline' }, 0);
		}
	});


};



/**  */
function searchHeader(event) {

	const icon = event.target;

	if (icon.classList.contains('fa-search')) {
		$('#formsearchResponsive input').trigger('focus');

		$('.menu-principal-search').addClass('active')
	} else {
		$('.menu-principal-search').removeClass('active')
	}

	icon.classList.toggle('fa-search');
	icon.classList.toggle('fa-close');
}

carrousel_molon_new = function(carrousel) {

	if (carrousel.data('hasSlick')) {
		carrousel.slick('unslick');
	}

	var rows = 1;

	carrousel.slick({
		slidesToScroll: 1,
		slidesToShow: 3,
		arrows: true,
		dots: false,
		swipeToSlide: true,
		prevArrow: $('#ultimas-obras-container .prev'),
		nextArrow: $('#ultimas-obras-container .next'),
		responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 3,
					infinite: true,
					dots: false,
				}
			},
			{
				breakpoint: 600,
				settings: {
					slidesToShow: 2,
				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
				}
			}

		]
	});

	carrousel.data('hasSlick', true);
}
