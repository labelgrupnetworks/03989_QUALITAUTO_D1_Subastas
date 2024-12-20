displayAlert_O = function(type, msg) {

	//para el caso en el que el modal de confirmar puja este abierto, que no
	//se cierre por mostrar el mensaje de sobrepuja
	if($.magnificPopup.instance.isOpen && msg == messages.error.higher_bid) {
		return;
	}

	$("#insert_msg_title").html("");
	$("#insert_msg").html(msg);
	$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
}
