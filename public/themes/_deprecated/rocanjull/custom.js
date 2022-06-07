
window.onload = function(){

	$('.lot-action_info_lot').on('click', function (e) {
		e.stopPropagation();
		$.magnificPopup.close();
		if (typeof cod_licit == 'undefined' || cod_licit == null) {
			$("#insert_msg").html("");
			$("#insert_msg").html(messages.error.need_login);
			$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
			return;
		}

		let lin_hces1 = e.target.dataset.linhces1;
		let num_hces1 = e.target.dataset.numhces1;
		let to_owner = e.target.dataset.toowner;

		$.ajax({
			type: "POST",
			url: '/api-ajax/info-lot-email',
			data: {cod_licit: cod_licit, cod_sub: cod_sub, ref: ref, num_hces1: num_hces1, lin_hces1: lin_hces1, to_owner: to_owner},
			success: function( data ) {

				$("#insert_msg").html("");

				if (data.status == 'error'){
						$("#insert_msg").html(data.msg);
						$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
				}
				else if(data.status == 'success'){
					$("#insert_msg").html(data.msg);
					$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
				}


			}
		});

	});
}
