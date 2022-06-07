$(document).ready(function () {

	//anulamos metodo orifinal para permitir subastas inferiores
	$('.lot-action_pujar_on_line').off('click');
	$('.lot-action_pujar_on_line').on('click', function (e) {
		e.stopPropagation();
		$.magnificPopup.close();

		//si pulsan el boton de puja donde viene un valor
		if ($(e.target)[0].hasAttribute("value")) {
			$(".precio_orden").html($(e.target).attr("value"));
		} else { //si pulsan el boton de autopuja
			$(".precio_orden").html($("#bid_amount").val());
		}

		if (typeof cod_licit == 'undefined' || cod_licit == null) {

			$("#insert_msg_title").html("");
			$("#insert_msg").html(messages.error.mustLogin);
			$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);

			return;
		} else {


			if (isNaN(parseInt($("#bid_amount").val())) || parseInt($("#bid_amount").val()) < parseInt(auction_info.lote_actual.importe_escalado_siguiente)) {
				$.magnificPopup.open({ items: { src: '#modalPujarInfFicha' }, type: 'inline' }, 0);
			}
			else {
				$.magnificPopup.open({ items: { src: '#modalPujarFicha' }, type: 'inline' }, 0);
			}
		}
	});


	//preparación para realizar pujasinferiores
	$('.confirm_puja_inf').on('click', function(){

		$.magnificPopup.close();

		let params = {
			sub_asigl0: auction_info.lote_actual.cod_sub,
			ref_asigl0: auction_info.lote_actual.ref_asigl0,
			licit_asigl0: auction_info.user.cod_licit,
			imp_asigl0: $("#bid_amount").val(),
			is_gestor: auction_info.user.is_gestor,
			ges_cod_licit: $('#ges_cod_licit').val() || null
		};

		$.ajax({
			type: "POST",
			url: "/api-ajax/add_lower_bid",
			data: params,
			success: function (response) {

				if (response.status == 'error') {

					$("#insert_msg_title").html("");
					$("#insert_msg").html(messages[`${response.status}`][`${response.msg}`]);
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);

				}
				else if (response.status == 'success') {

					$("#insert_msg_title").html("");
					$("#insert_msg").html(messages[`${response.status}`][`${response.msg}`]);
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				}

			},
			error: function (result) {
				$("#insert_msg_title").html("");
				$("#insert_msg").html(messages.error.inserting_bid);
				$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
			}

		});


	});

});
