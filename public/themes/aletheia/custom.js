$(document).ready(function () {

	$(document).off('scroll');
	$(document).on('scroll', function (e) {

		//Por el momento elminamos el menu en scroll
		/* if ($(document).scrollTop() > 33) {
			$('header').addClass('fixed w-100 top-0 left-0')

		}
		if ($(document).scrollTop() <= 33) {
			$('header').removeClass('fixed w-100 top-0 left-0')

		} */
		if ($(document).scrollTop() > 100) {
			$('.button-up').show(500);		}
		if ($(document).scrollTop() <= 100) {
			$('.button-up').hide(500)
		}
	});

});
