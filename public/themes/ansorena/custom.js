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
	//al pulsar botón de orden telefónica
	$("#pujar_orden_telefonica").click(function() {
		ga('send', 'event', 'Puja', 'Realizada');
	});
	$("#pujar_ordenes_w_ansorena").click(function() {

		$("#orderphone").val("");
		$(".phonebid_js").addClass("hide");
		ga('send', 'event', 'Puja', 'Realizada');

		confirmar_orden()
   });

   $('#confirm_orden').unbind('click');

	$("#confirm_orden").click(function () {

		imp = $("#bid_modal_pujar").val();
		if ($("#orderphone").val() == "S") {
			tel1 = $("#phone1Bid_JS").val();
			tel2 =  $("#phone2Bid_JS").val();
		} else {
			tel1 = "";
			tel2 = "";
		}
		$.magnificPopup.close();
		$.ajax({
			type: "POST",
			url: routing.ol + '-' + cod_sub,
			data: { cod_sub: cod_sub, ref: ref, imp: imp,tel1: tel1,tel2: tel2 },
			success: function (data) {
				if (data.status == 'error') {

					$("#insert_msg_title").html("");
					$("#insert_msg").html(data.msg_1);
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				} else if (data.status == 'success') {
					//divisas, debe existir el selector
					if(typeof $("#currencyExchange").val() != 'undefined'){
						changeCurrency(data.imp, $("#currencyExchange").val(),"yourOrderExchange_JS");
					}

					$("#tuorden").html(data.imp.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1."));
					$("#text_actual_no_bid").addClass("hidden");
					$("#text_actual_max_bid").removeClass("hidden");
					$("#actual_max_bid").html(data.open_price);
					$("#insert_msg_title").html("");
					$("#insert_msg").html(data.msg);
					$(".hist_new").removeClass("hidden");
					$(".custom").removeClass("hidden");
					$("#bid_modal_pujar").val(data.imp_actual);
					ga('send', 'event', 'Puja', 'Confirmada');

					if (data.winner) {
						$("#max_bid_color").addClass("winner");
						$("#max_bid_color").removeClass("no_winner");
					} else {
						$("#max_bid_color").removeClass("winner");
						$("#max_bid_color").addClass("no_winner");
					}
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				}

			}
		});


	});




	$('#newsletter-btn-ansorena').on('click', function () {
        var email = $('.newsletter-input').val();
        var lang = $('#lang-newsletter').val();
        var families = [];
        //coge los ocultos
        $(".newsletter").each(function (index) {
			if ($(this).prop("checked")) {
            	families.push($(this).val());
			}
        });

        var entrar = false;

        if ($('#condiciones').prop("checked")) {
            entrar = true;
        }
        if ($('#bool__1__condiciones').prop("checked")) {
            entrar = true;
        }


        if (entrar) {
            $(".newsletter-input").removeClass("has-error");
            $(".checknewsletter").removeClass("has-error");
            $.ajax({
                type: "POST",
                data: { email: email, families: families, condiciones:1, lang: lang },
                url: '/api-ajax/newsletter/add',
                beforeSend: function () {
                },
                success: function (msg) {
                    if (msg.status == 'success') {
                        $('.insert_msg').html(messages.success[msg.msg]);
                    } else {
                        $('.insert_msg').html(messages.error[msg.msg]);
                        $(".newsletter-input").addClass("has-error");
                    }
                    $.magnificPopup.open({ items: { src: '#newsletterModal' }, type: 'inline' }, 0);
                }
            });
        } else {
            $(".newsletter-input").addClass("has-error");
            $(".checknewsletter").addClass("has-error");
            $("#insert_msgweb").html('');
            $("#insert_msgweb").html(messages.neutral.accept_condiciones);
            $.magnificPopup.open({ items: { src: '#modalMensajeWeb' }, type: 'inline' }, 0);
        }
    });



});
