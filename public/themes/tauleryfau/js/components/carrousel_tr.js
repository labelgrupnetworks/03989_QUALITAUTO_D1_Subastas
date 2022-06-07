let arrayViews = [];
let ajax;
let prevLot = 1;
let isSlick = false;
let prevSize = 0;
const TABLET_IPHONE = 1024;
const TABLET = 800;
const PHONE = 600;

$(document).ready(function () {

	$(window).on('resize', initSlick);
	initSlick();
	/* reloadCarrousel(); */

});

function hoverClickCarrousel(e){

	let lotSelected = $(e.target).closest('.lots')[0];
	let cod_sub = lotSelected.dataset.cod_sub;
	let ref_asigl0 = lotSelected.dataset.ref_asigl0;

	if (cod_sub != 'undefined' && ref_asigl0 != 'undefined'){
		getInfo(cod_sub, ref_asigl0);
	}

}

function getInfo(cod_sub, ref_asigl0){

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

function hoverOverCarrousel() {

	let cod_sub = this.dataset.cod_sub;
	let ref_asigl0 = this.dataset.ref_asigl0;

	getInfo(cod_sub, ref_asigl0);
}

function hoverOutCarrousel() {
	if (ajax != null) {
		ajax.abort();
		ajax = null;
	}
	$('.j-btn-custom-add .j-text-add').css("display", "none");
	$('.j-btn-custom-add .j-text-view').css("display", "block");
}

function reloadCarrousel() {

	if (typeof auction_info != 'undefined' && typeof auction_info.lote_actual != 'undefined') {

		let actualLotRef = parseInt(auction_info.lote_actual.ref_asigl0);
		let $actualLot = $(`.lots[data-ref_asigl0 = ${actualLotRef}]`);

		if(!$actualLot.length){
			return;
		}

		let position = $actualLot[0].dataset.order;

		$('.lots').removeClass('actual-lot');

		for (const lot of $('.lots')) {
			if(lot.dataset.backgroundImage != 'undefined'){
				lot.style.backgroundImage = lot.dataset.backgroundImage;
			}
		}

		$actualLot.addClass('actual-lot');//.addClass('j-active-info');

		if ($('#j-followCarrousel').prop('checked')) {
			$('.lots-carrousel')[0].slick.slickGoTo(parseInt(position - prevLot));
		}

	}

}

function initSlick(){
	let vSize = getViewPortSize().width;
	let slidesToShow = 4;

	prevLot = 1;

	if(vSize < TABLET_IPHONE && vSize >= TABLET){
		slidesToShow = 3;
		prevLot = 1;

		/**
		* todos estos if's sirven para comprobar que realmente el tamaño de la pagina
		* se ha modificado, y no recargue la imagen de manera inecesaria.
		*/
		if(prevSize == TABLET_IPHONE){
			return;
		}
		prevSize = TABLET_IPHONE;

	}
	else if(vSize < TABLET && vSize >= PHONE){
		slidesToShow = 2;
		prevLot = 0;

		if(prevSize == TABLET){
			return;
		}
		prevSize = TABLET;
	}
	else if(vSize < PHONE){
		slidesToShow = 1;
		prevLot = 0;

		if(prevSize == PHONE){
			return;
		}
		prevSize = PHONE;
	}
	else{
		if(prevSize == 1){
			return;
		}
		prevSize = 1;
	}



	if(!isSlick){
		$('.lots-carrousel').slick({
			dots: false,
			infinite: false,
			arrows: true,
			slidesToShow: slidesToShow,
			slidesToScroll: 1,
			swipeToSlide: true,
			prevArrow: $('.prev-arrow-carrousel'),
			nextArrow: $('.next-arrow-carrousel'),
		  });

		  isSlick = true;
	}
	else{
		$('.lots-carrousel').slick("unslick").slick({
			dots: false,
			infinite: false,
			arrows: true,
			slidesToShow: slidesToShow,
			slidesToScroll: 1,
			swipeToSlide: true,
			prevArrow: $('.prev-arrow-carrousel'),
			nextArrow: $('.next-arrow-carrousel'),
		  });
	}

	reloadCarrousel();

	$('.lots.j-active-info').unbind('mouseenter mouseleave'); //elimina eventos anteriores para no acumular
	$('.lots.j-active-info').hover(hoverOverCarrousel, hoverOutCarrousel); //añade evento a hover a lotes
}


function getViewPortSize() {
    var doc = document, w = window;
    var docEl = (doc.compatMode && doc.compatMode === 'CSS1Compat')?
            doc.documentElement: doc.body;

    var width = docEl.clientWidth;
    var height = docEl.clientHeight;

    // mobile zoomed in?
    if ( w.innerWidth && width > w.innerWidth ) {
        width = w.innerWidth;
        height = w.innerHeight;
    }

    return {width: width, height: height};
}
