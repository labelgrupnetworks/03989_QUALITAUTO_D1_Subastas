$(document).ready(function () {

	let $carousel = $("#owl-carousel-responsive");
	$carousel.trigger("destroy.owl.carousel");
	$("#owl-carousel-responsive").owlCarousel({
		items: 1,
		autoplay: false,
		margin: 20,
		dots: true,
		nav: true,
		responsiveClass: true,
	});

});

function loadVideo(video) {
	$('#video_main_wrapper').empty();
	$('.img-global-content').hide();
	$videoDom = $('<video width="100%" height="auto" autoplay="true" controls>').append($(`<source src="${video}">`));
	$('#video_main_wrapper').append($videoDom);
	$('#video_main_wrapper').show();
}

function openLogin() {
	$('.login_desktop').fadeToggle("fast");
	$('.login_desktop [name=email]').trigger('focus');
	return;
}

async function shareLot(title, text, url) {

	const shareData = { title, text, url };

	try {
		await navigator.share(shareData)
		//exit
	} catch (err) {
		console.log('only found in https');
	}
}


action_fav_modal = function(action) {

	$.magnificPopup.close();

	if (typeof cod_licit == 'undefined' || cod_licit == null) {
		$("#insert_msg").html(messages.error.mustLogin);
		$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
		return;
	}

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

				if (action == 'add') {
					$("#add_fav_lg, #add_fav_xs").addClass('hidden');
					$("#del_fav_lg, #del_fav_xs").removeClass('hidden');
				} else {
					$("#del_fav_lg, #del_fav_xs").addClass('hidden');
					$("#add_fav_lg, #add_fav_xs").removeClass('hidden');
				}

			}

		}
	});

};

$(function () {
	$('[data-toggle="popover"]').popover()
});
