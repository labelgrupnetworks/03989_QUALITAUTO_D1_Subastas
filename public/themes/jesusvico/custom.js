project = {
	...project,
	theme: 'jesusvico',
	version: 2
};

$(document).ready(function () {

	//Eliminamos el menu superior fijo
	$(document).unbind("scroll");
	$(document).scroll(function (e) {

		if ($(document).scrollTop() > 100) {
			$('.button-up').show(500)
		}
		if ($(document).scrollTop() <= 100) {
			$('.button-up').hide(500)
		}
	})

	//Comportamiento de menu deplegable según dispositivo
	if ($(window).width() < 1200) {

		$('#js-menu-subastas').click(function(e) {
			$('#menu_desp_subastas').toggle('blind',100);
			e.stopPropagation();
		});

		$('#js-menu-empresa').click(function(e) {
			$('#menu_desp_empresa').toggle('blind',100);
			e.stopPropagation();
		});

	}
	else{

		$(document).click(function(e) {
			if($('#menu_desp_subastas').is(':visible')){
				$('#menu_desp_subastas').fadeOut();
			};

			if($('#menu_desp_empresa').is(':visible')){
				$('#menu_desp_empresa').fadeOut();
			};

		});

		/**
		 * Desplegable de empresa
		 */
		$('#js-menu-empresa').hover(function () {
			$('#menu_desp_empresa').show()
		});

		$('#menu_desp_empresa, #js-menu-empresa').hover(function () {
			$('#menu_desp_empresa').show()
		}, function () {
			$('#menu_desp_empresa').hide()
		})

		/**
		 * Desplegable de subasta
		 */
		$('#js-menu-subastas').hover(function () {
			$('#menu_desp_subastas').show()
		});

		$('#menu_desp_subastas, #js-menu-subastas').hover(function () {
			$('#menu_desp_subastas').show()
		}, function () {
			$('#menu_desp_subastas').hide()
		})

	}

	/**
	 * Leer más descripción en ficha de lote
	 */
	if($('.read-more').length){

		if($('.single-lot-desc-content').get(0).scrollHeight > 82){
			$('.read-more').show(500, 'linear');
		}

		$('.read-more').click(function(){
			readMore(this, '.single-lot-desc-content');
		})
	}


	/**
	 * Cambio del comportamiento de full screen en lista de lotes
	 */
	$('#full-screen').off('click');

	$('#full-screen').click(function () {
		if ($('.filters-auction-content').css('display') === 'none') {
			$('.filters-auction-content').parent().show()
			$('.filters-auction-content').show("slide", { direction: "left" }, 500);

			$('.fullscreen-content').removeClass('col-lg-12').addClass('col-lg-9')
			$('.square').removeClass('col-lg-3').addClass('col-lg-4')
			$(this).removeClass('active')

		} else {
			$('.filters-auction-content').hide("slide", { direction: "left" }, 500, function () {

				$('.filters-auction-content').parent().hide()
				$('.fullscreen-content').removeClass('col-lg-9').addClass('col-lg-12')
				$('.square').removeClass('col-lg-4').addClass('col-lg-3')
				$(this).addClass('active')
			});

		}

	});

	/**
	 * Comportamientos de filtros
	 * Desactivar para la version 2
	 */
	if(project.version == 1){
		eventsToGridFilters();
	}

	$('.mosaic-container .post-container').on('click', function () {

		var modal = document.getElementById("mosaic-modal");
		var img = this.querySelector('img');
		var modalImg = document.getElementById("img-modal");
		var captionText = document.getElementById("mosaic-caption");

		modal.style.display = "block";
		modalImg.src = img.src;
		captionText.innerHTML = img.alt;
	});

	$('.mosaic-close').on('click', function(){
		document.getElementById("mosaic-modal").style.display = "none";
	});

	$('#button-open-user-menu').off('click');
	$('#button-open-user-menu').on('click', function () {
		$('#user-account-ul').toggle(500);
	})

});

function eventsToGridFilters() {
	$('#js-shop_links').click(() => {
		var categories = $('#shop_links');
		var icon = $('#js-shop_links i');
		if (categories.is(':hidden')) {
			icon.removeClass();
			icon.addClass('fas fa-minus');
			categories.show(400);
		} else {
			icon.removeClass();
			icon.addClass('fas fa-plus');
			categories.hide(400);
		}
	});


	$('#auction_category_top').click(() => {
		var categories = $('#auction_categories');
		var icon = $('#auction_category_top i');
		if (categories.is(':hidden')) {
			icon.removeClass();
			icon.addClass('fas fa-minus');
			categories.show(400);
		} else {
			icon.removeClass();
			icon.addClass('fas fa-plus');
			categories.hide(400);
		}
	});

	$('#estado_lotes').hide();
	$('#auction_filters_top').click(() => {
		var filtros = $('#estado_lotes');
		var icon = $('#auction_filters_top i');
		if (filtros.is(':hidden')) {
			filtros.show(400);
			icon.removeClass();
			icon.addClass('fas fa-minus');
		} else {
			filtros.hide(400);
			icon.removeClass();
			icon.addClass('fas fa-plus');
		}
	});

	$('#my_lots').hide();
	$('#auction_filters_top_my_lots').click(() => {
		var filtros = $('#my_lots');
		var icon = $('#auction_filters_top_my_lots i');
		if (filtros.is(':hidden')) {
			filtros.show(400);
			icon.removeClass();
			icon.addClass('fas fa-minus');
		} else {
			filtros.hide(400);
			icon.removeClass();
			icon.addClass('fas fa-plus');
		}
	});

	$('.js-link-to-shop').on('change', function (e) {
		window.location.href = e.target.dataset.to;
	});
}

function loadVideo(video) {
	$('#video_main_wrapper').empty();
	$('.img-global-content').hide();
	$videoDom = $('<video width="100%" height="auto" autoplay="true" controls>').append($(`<source src="${video}">`));
	$('#video_main_wrapper').append($videoDom);
	$('#video_main_wrapper').show();
}


function readMore(elemntClick, elementSelector){

	if(!$(elemntClick).hasClass('mostrar')){
		$(elementSelector).css({
			//'white-space' : 'initial',
			'-webkit-line-clamp': 'initial'
		});

		//$(elementSelector).animate({height: $(elementSelector).get(0).scrollHeight, maxHeight: $(elementSelector).get(0).scrollHeight}, 1000);
		$(elemntClick).addClass('mostrar');
		$(elemntClick).text('Ver menos');
	}
	else{
		$(elementSelector).css({'-webkit-line-clamp': '4'});
		/*
		$(elementSelector).animate({height: $(elementSelector).get(0).scrollHeight, maxHeight: '80px'}, 1000, function(){
			$(this).css({'white-space' : 'nowrap',});
		});
		*/
		$(elemntClick).removeClass('mostrar');
		$(elemntClick).text('Ver más');
	}
}

function viewFilter(){

	if($('#span-viewFilter').hasClass("ocultar")){
		$('#span-viewFilter').text($('#js-hiddenFilter').val());
		$('#span-viewFilter').removeClass("ocultar").addClass("mostrar");
		$('.auction-lots-view').fadeIn();
	}
	else{
		$('#span-viewFilter').text($('#js-viewFilter').val());
		$('#span-viewFilter').removeClass("mostrar").addClass("ocultar")
		$('.auction-lots-view').fadeOut();
	}

}

