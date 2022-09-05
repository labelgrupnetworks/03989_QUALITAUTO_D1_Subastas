$(function(){
	$('.lot-action_pujar_no_licit').on('click', (event) => magnificPopupSimpleMessage(messages.error.no_licit));
	$('.lot-action_pujar_no_deposit').on('click', (event) => magnificPopupSimpleMessage(messages.error.no_deposit));
});

function magnificPopupSimpleMessage(message) {
	$("#insert_msg_title").html("");
	$("#insert_msg").html(message);
	$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
}


