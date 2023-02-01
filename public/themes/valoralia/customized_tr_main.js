$(function(){
	$('.lot-action_pujar_no_licit').on('click', (event) => magnificPopupSimpleMessage(messages.error.no_licit));
	$('.lot-action_pujar_no_deposit').on('click', showNoDepositMessage);
});

function magnificPopupSimpleMessage(message) {
	$("#insert_msg_title").html("");
	$("#insert_msg").html(message);
	$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
}

function showNoDepositMessage(event) {
	const isPresencial = auction_info.lote_actual.tipo_sub === 'W';
	const { no_deposit, no_deposit_w } = messages.error;
	const message = isPresencial ? no_deposit_w : no_deposit;
	magnificPopupSimpleMessage(message)
}
