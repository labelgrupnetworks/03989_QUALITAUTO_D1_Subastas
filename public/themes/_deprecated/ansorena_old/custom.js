$(document).ready(function () {
	$(document).off( "scroll" );

	$(document).scroll(function (e) {
		if ($(document).scrollTop() > 100) {
			$('.button-up').show(500)
		}
		if ($(document).scrollTop() <= 100) {
			$('.button-up').hide(500)
		}
	})

});
