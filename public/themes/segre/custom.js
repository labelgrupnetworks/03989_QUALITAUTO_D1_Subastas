$(document).ready(function () {
	$(document).off( "scroll" );

	$('.user-account').click(function() {
		$(this).find('.mega-menu').toggle();
	})
});

/*
$(document).ready(function () {

	$("#accerder-user").off('click');

	$("#accerder-user").on('click', custom_login);

});

function custom_login(){
	$(this).addClass('loadbtn')
	$('.login-content-form').removeClass('animationShaker')
	$.ajax({
		type: "POST",
		url: '/custom_login',
		data: $('#accerder-user-form').serialize(),
		success: function (response) {
			if (response.status == 'success') {
				location.reload();
			} else {
				$(".message-error-log").text('').append(messages.error[response.msg]);
				$("#accerder-user").removeClass('loadbtn')
				$('.login-content-form').addClass('animationShaker')
			}
		}
	});
}


*/
