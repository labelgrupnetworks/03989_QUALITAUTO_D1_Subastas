$(document).ready(function(){

	$('.lot-action_pujar_no_licit').on('click', function(e){
		$("#insert_msg_title").html("");
		$("#insert_msg").html(messages.error.no_licit);
		$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
		return;
	});

});
