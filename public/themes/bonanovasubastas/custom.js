$(document).ready(function () {

	$('.search-component-form').submit(function () {
		$('.btn-custom-search').find('i').hide()
		$('.btn-custom-search').find('.loader.mini').show()
	})

	$('.switcher').click(function () {
		$(this).toggleClass('switcher-active');

	});

	$('.tabs-custom ul li').click(function (e) {

		var elFather = $(this).parents('.tabs-custom');
		var elBro = $(elFather).siblings('.tab-content');


		$(elBro).fadeOut(200);
		$('.loading-page').show(500);
		if ($('.adj').length) {
			$('.adj').hide();
		}

	});
	$(document).scroll(function (e) {
		if ($(document).scrollTop() > 100) {
			$('.button-up').show(500)
		}
		if ($(document).scrollTop() <= 100) {
			$('.button-up').hide(500)
		}
	})

	$('.button-up').click(function () {

		$('html,body').animate({ scrollTop: 0 }, 500);
	});

	$().owlCarousel && $("#owl-carousel-responsive").owlCarousel({
		items: 1,
		autoplay: true,
		margin: 20,
		dots: true,
		nav: true,
		responsiveClass: true,
	});

	$("#accerder-user-form input[name='password']").on('keyup', function (e) {
		if (e.keyCode == 13) {
			$("#accerder-user").click()
		}
	});

	$("#accerder-user-form-responsive input[name='password']").on('keyup', function (e) {
		if (e.keyCode == 13) {
			$("#accerder-user-responsive").click()
		}
	});
	$('.login').on('click', function () {
		$('#loginResponsive').removeClass('fadeOutDown');
		$('#loginResponsive').show().addClass('animated fadeInDown');
	});
	$('#closeResponsive').on('click', function () {
		$('#loginResponsive').addClass('animated fadeOutDown').removeClass('fadeInDown');
	})
	$('#btnResponsive').on('click', function () {
		$('#menuResponsive').show().addClass('animated fadeInRight').removeClass('fadeOutRight');
	});
	$('#btnResponsiveClose').on('click', function () {
		$('#menuResponsive').addClass('animated fadeOutRight').removeClass('fadeInRight');
	});
	$('.btn_login_desktop').on('click', function () {
		$('.login_desktop').fadeToggle("fast");
	});
	$('.closedd').on('click', function () {
		$('.login_desktop').fadeToggle("fast");
	});

	$("#accerder-user").click(function () {
		$(this).find('span').hide()
		$(this).find('.loader.mini').show()
		$.ajax({
			type: "POST",
			url: '/login_post_ajax',
			data: $('#accerder-user-form').serialize(),
			success: function (response) {
				if (response.status == 'success') {
					location.reload();
				} else {
					$("#accerder-user").find('span').show()
					$("#accerder-user").find('.loader.mini').hide()
					$(".message-error-log").text('').append(messages.error[response.msg]);
				}

			}
		});
	});

	$('#newsletter-btn').on('click', newsletterSuscription);
	$('#newsletterForm').on('submit', newsletterFormSuscription);

	$('#frmUpdateUserPasswordADV').validator().on('submit', function (e) {

		if (e.isDefaultPrevented()) {
			// formulario incorrecto
		} else {

			e.preventDefault();
			var $this = $(this);

			$('button', $this).attr('disabled', 'disabled');
			// Datos correctos enviamos ajax
			$.ajax({
				type: "POST",
				url: '/api-ajax/client/update/password',
				data: $('#frmUpdateUserPasswordADV').serialize(),
				beforeSend: function () {
					//$('#btnRegister').prepend(' <i class="fa fa-spinner fa-pulse fa-fw margin-bottom"></i> ');
				},
				success: function (response) {

					$('button', $this).attr('disabled', false);

					res = jQuery.parseJSON(response);

					if (res.err == 1) {
						$('.insert_msg').html('<div class="alert alert-danger">' + messages.error[res.msg] + '</div>');
					} else {
						$('.insert_msg').html('<div class="alert alert-success">' + messages.success[res.msg] + '</div>');
					}

				}
			});

		}

	});

	$('#frmUpdateUserInfoADV').validator().on('submit', function (e) {

		if (e.isDefaultPrevented()) {
			// formulario incorrecto
		} else {

			e.preventDefault();
			var $this = $(this);

			$('button', $this).attr('disabled', 'disabled');
			// Datos correctos enviamos ajax
			$.ajax({
				type: "POST",
				url: '/api-ajax/client/update',
				data: $('#frmUpdateUserInfoADV').serialize(),
				beforeSend: function () {
					$('#btnRegister').prepend(' <i class="fa fa-spinner fa-pulse fa-fw margin-bottom"></i> ');
				},
				success: function (response) {

					$('button', $this).attr('disabled', false);

					res = jQuery.parseJSON(response);

					if (res.err == 1) {
						$('.col_reg_form').html('<div class="alert alert-danger">' + messages.error[res.msg] + '</div>');
					} else {
						$('.col_reg_form').html('<div class="alert alert-success">' + messages.success[res.msg] + '</div>');
					}
				}

			});

			$('button', $this).attr('disabled', false);

		}
	});

	$("#confirm_orden").click(function () {
		imp = $("#bid_modal_pujar").val();
		$.ajax({
			type: "POST",
			url: routing.ol + '-' + cod_sub,
			data: { cod_sub: cod_sub, ref: ref, imp: imp },
			success: function (data) {
				if (data.status == 'error') {

					$("#insert_msg_title").html("");
					$("#insert_msg").html(data.msg_1);
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				} else if (data.status == 'success') {
					$("#tuorden").html(data.imp);
					$("#text_actual_no_bid").addClass("hidden");
					$("#text_actual_max_bid").removeClass("hidden");
					$("#actual_max_bid").html(data.open_price);
					$("#insert_msg_title").html("");
					$("#insert_msg").html(data.msg);
					$(".hist_new").removeClass("hidden");
					$(".custom").removeClass("hidden");
					$("#bid_modal_pujar").val(data.imp_actual);
					if (data.winner) {
						$("#text_actual_max_bid strong").removeClass("no_winner").addClass('winner');
					} else {
						$("#text_actual_max_bid strong").removeClass("winner").addClass('no_winner');
					}
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				}

			}
		});


	});

	$("#confirm_orden_lotlist").click(function () {
		imp = $(".precio_orden").html();
		ref = $(".ref_orden").html();
		$.ajax({
			type: "POST",
			url: routing.ol + '-' + cod_sub,
			data: { cod_sub: cod_sub, ref: ref, imp: imp },
			success: function (data) {
				if (data.status == 'error') {

					$("#insert_msg_title").html("");
					$("#insert_msg").html(data.msg_1);
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				} else if (data.status == 'success') {
					$("#insert_msg_title").html("");
					$("#insert_msg").html(data.msg);
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				}

			}
		});


	});

	$("#accerder-user-responsive").click(function (event) {
		event.preventDefault();
		$('#accerder-user-responsive').find('span').hide()
		$('#accerder-user-responsive').find('.loader.mini').show()
		$.ajax({
			type: "POST",
			url: '/login_post_ajax',
			data: $('#accerder-user-form-responsive').serialize(),
			success: function (response) {
				if (response.status == 'success') {
					location.reload();
				} else {
					$('#accerder-user-responsive').find('span').show()
					$('#accerder-user-responsive').find('.loader.mini').hide()
					$(".message-error-log").text('').append(messages.error[response.msg]);
				}

			}
		});
	});

	$('.lot-action_comprar_lot').on('click', function (e) {
		e.stopPropagation();
		$.magnificPopup.close();
		if (typeof cod_licit == 'undefined' || cod_licit == null) {
			$("#insert_msg").html("");
			$("#insert_msg").html(messages.error.mustLogin);
			$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
		} else {
			$.magnificPopup.open({ items: { src: '#modalComprarFicha' }, type: 'inline' }, 0);
		}

	});

	$(window).resize(function () {
		if ($(window).width() < 1200) {
			$('.small_square .item_lot').removeClass('col');
		}
	});

	$(".confirm_delete").click(function () {
		var ref = $(this).attr("ref");
		var sub = $(this).attr("sub");
		$.magnificPopup.close();
		$.ajax({
			type: "POST",
			url: '/api-ajax/delete_order',
			data: { ref: ref, sub: sub },
			success: function (response) {
				res = jQuery.parseJSON(response);
				if (res.status == 'success') {
					$("#" + res.respuesta).remove();
					$("#insert_msg").html(messages.success[res.msg]);
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
					change_price_saved_offers();
				} else {
					if ($(res.respuesta).empty()) {
						$("#" + res.respuesta + " .form-group-custom input").addClass("has-error-custom");
					}
					$("#insert_msg").html(messages.error[res.msg]);
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				}

			}

		});
	});

	$("#form-valoracion-adv").submit(function (event) {

		event.preventDefault();
		formData = new FormData(this);
		var max_size = 20;
		var size = 0;
		$(event.target.files.files).each(function (index, element) {

			size = parseInt((element.size / 1024 / 1024).toFixed(2)) + parseInt(size);
			//console.log(size);
		});
		if (size < max_size) {
			$.ajax({
				type: "POST",
				url: "valoracion-articulos-adv",
				data: formData,
				enctype: 'multipart/form-data',
				processData: false,
				contentType: false,
				success: function (result) {
					if (result.status == 'correct') {
						window.location.href = result.url;
					} else if (result.status == 'error_size') {
						$("#modalMensaje #insert_msg").html('');
						$("#modalMensaje #insert_msg").html(messages.error[result.msg]);
						$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
					} else {
						$(".msg_valoracion").removeClass('hidden');
					}
				},
				error: function (result) {
					$(".msg_valoracion").removeClass('hidden');
				}
			});
		} else {
			$("#insert_msg").html(messages.error.max_size_img);
			$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
		}
	});

	function showElementInCenterToViewPort(event, element) {
		var viewportHeight = $(window).height(),
			viewportWidth = $(window).width(),
			elementHeight = $(element).outerHeight(),
			elementWidth = $(element).outerWidth(),
			elementTop = (viewportHeight / 2) - (elementHeight / 2) + $(document).scrollTop(),
			elementLeft = (viewportWidth / 2) - (elementWidth / 2);

		//get mouse position
		var mouseX = event.pageX;
		var mouseY = event.pageY;

		//if mouse inside new element position, move element to the bottom
		const mouseIsInside = mouseX > elementLeft && mouseX < elementLeft + elementWidth && mouseY > elementTop && mouseY < elementTop + elementHeight;
		if (mouseIsInside) {
			elementTop = mouseY + 50;
		}

		$(element).css({
			"top": elementTop + "px",
			"left": elementLeft + "px"
		});
	}

	$('.content_item_mini').hover(function (event) {

		if (!$('.list_lot').hasClass('small_list')) return;

		const target = event.currentTarget;
		const largeImage = $(target).data('large-src');

		$element = $('.capaOculta');
		$element.find('img').attr('src', largeImage);
		$element.show();
		showElementInCenterToViewPort(event, $element);
	}, function () {
		$element = $('.capaOculta');
		$element.hide();
	});

	$('#admin_settings_box').on('click', '.desplegable', function () {
        if ($('#admin_settings_box').hasClass('opened_box')) {
            $('#admin_settings_box').removeClass('opened_box');
            $('[data-id="left"]', this).addClass('hidden');
            $('[data-id="right"]', this).removeClass('hidden');
        } else {
			$('#admin_settings_box').addClass('opened_box');
            $('[data-id="right"]', this).addClass('hidden');
            $('[data-id="left"]', this).removeClass('hidden');

        }
    });

});

function cerrarLogin() {
	$('.login_desktop').fadeToggle("fast");
}

function ajax_carousel(key, replace) {
	$("#" + key).siblings().removeClass('hidden');
	$.ajax({
		type: "POST",
		url: "/api-ajax/carousel",
		data: { key: key, replace: replace },
		success: function (result) {
			$("#" + key).siblings('.loader').addClass('hidden');
			$("#" + key).html(result);
			carrousel_molon($("#" + key));
			$('[data-countdown]').each(function () {
				$(this).data('ini', new Date().getTime());
				countdown_timer($(this));
			});
		}

	});

};

function format_date(fecha) {

	const horas = fecha.getHours().toString().padStart(2, '0');
	const minutos = fecha.getMinutes().toString().padStart(2, '0');
	const day = fecha.getDate();

	const months = Object.keys(traduction_large);
	const monthName = traductions[months[fecha.getMonth()]];

	$.each(traductions, function (key, value) {
		if (key == $.datepicker.formatDate("M", fecha)) {
			mes = value;
		}
	});

	const formatted = `${day} ${monthName} ${horas}:${minutos} h`;
	return formatted;
}

function carrousel_molon(carrousel) {
	if (carrousel.data('hasSlick')) {
		carrousel.slick('unslick');
	}

	var rows = 1;
	/*Si se añaden más de una fila, estas no cambian al reducir pantalla
	//Establecer desde el inicio
	if(window.innerWidth < 1024){
		rows = 1;
	}*/

	/**
	 * Si se utilizan más de un row, se tiene en cuenta slidesPerRow
	 * En caso de usar un solo row, se utiliza slidesToShow
	 * Utilizar los dos, crea conflictos...
	 */

	carrousel.slick({
		slidesToScroll: 4,
		rows: rows,
		infinite: true,
		dots: true,
		/*slidesPerRow: 4,*/
		slidesToShow: 4,
		arrows: true,
		responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 3,
					infinite: true,
					dots: true,
					rows: 1,
					slidesPerRow: 3,
					arrows: false,
				}
			},
			{
				breakpoint: 600,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2,
					rows: 1,
					slidesPerRow: 2,
					arrows: false,

				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					rows: 1,
					slidesPerRow: 1,
					arrows: false,
				}
			}

		]
	});

	carrousel.data('hasSlick', true);
}

function password_recovery(lang) {
	var pass_recov = $("#password_recovery").serialize();
	$.ajax({
		type: "POST",
		url: '/' + lang + '/ajax-send-password-recovery',
		data: pass_recov,
		success: function (data) {
			if (data.status == 'error') {
				$(".error-recovery").html(data.msg);
			} else if (data.status == 'succes') {
				$("#password_recovery").html(data.msg);
			}
		}
	});
};

/**
 * @param {Date} fecha //ejemplo: Thu Dec 29 2022 13:00:00 GMT+0100
 * @param {string} text //ejemplo: a partir de
 * @returns //ejemplo: 29 Dic 2022 a partir de 13:00 h
 */
function format_date_large(fecha, text) {

	const horas = fecha.getHours().toString().padStart(2, '0');;
	const minutos = fecha.getMinutes().toString().padStart(2, '0');
	const day = fecha.getDate();

	const months = Object.keys(traduction_large);
	const monthName = traduction_large[months[fecha.getMonth()]];

	const formatted = `${day} ${monthName} ${text} ${horas}:${minutos} h`;
	return formatted;
}

function close_modal_session() {

	$("#closeResponsive").trigger("click");
}

function action_fav_modal(action) {

	$('.button-follow').show();
	$('.button-follow-responsive').show();

	$.magnificPopup.close();
	if (typeof cod_licit == 'undefined' || cod_licit == null) {
		$("#insert_msg").html(messages.error.mustLogin);
		$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
		return;
	} else {
		$.ajax({
			type: "GET",
			url: routing.favorites + "/" + action,
			data: { cod_sub: cod_sub, ref: ref, cod_licit: cod_licit },
			success: function (data) {
				$('.button-follow').hide();
				$('.button-follow-responsive').hide();

				if (data.status == 'error') {
					$("#insert_msg").html("");
					$("#insert_msg").html(messages.error[data.msg]);
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				} else if (data.status == 'success') {
					$("#insert_msg").html("");
					$("#insert_msg").html(messages.success[data.msg]);
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
					if (action == 'add') {
						$("#add_fav").addClass('hidden');
						$("#del_fav").removeClass('hidden');
						$(".slider-thumnail-container #add_fav").addClass('hidden');
						$(".slider-thumnail-container #del_fav").removeClass('hidden');


					} else {
						$("#del_fav").addClass('hidden');
						$("#add_fav").removeClass('hidden');
						$(".slider-thumnail-container #add_fav").removeClass('hidden');
						$(".slider-thumnail-container #del_fav").addClass('hidden');

					}

				}

			}
		});
	}





};

function action_fav_lote(action, ref, cod_sub, cod_licit) {
	routing.favorites = '/api-ajax/favorites';
	//$.magnificPopup.close();

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
				$('.' + ref + '-' + cod_sub).remove();
				if (action == 'remove' && $('#heading-' + cod_sub + ' #' + cod_sub + ' .user-accout-items-content .user-accout-item-wrapper').length <= 0) {
					$('#heading-' + cod_sub).remove();
				}

			}

		}
	});

};
function change_price_saved_offers() {
	var precio = 0;
	$('input[name=order]').each(function () {
		precio = parseInt($(this).val()) + parseInt(precio);
	})
	$("#change_price").html('');
	$("#change_price").html(parseFloat(precio, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString());
}

//FAQS
$(document).ready(function () {

	$('.primary-item').click(function (e) {


		$('.lists').removeClass('open');
		($('.lists').eq($(this).index()).addClass('open'));
		$('.secondary-item-sub').attr('data-open', '0')
		$('.lists').css('min-height', $('.lists').height() + alto)
		var alto = $('.lists').eq($(this).index()).height() + ($('.lists').eq($(this).index()).find('li').length + 300);
		console.log(alto);
		$('.lists').eq($(this).index()).css('min-height', alto)

	});

	$('.secondary-item-sub').click(function (e) {

		if ($(this).attr('data-open') === '0') {
			$('.secondary-item-dec').removeClass('open')
			$('.secondary-item-sub').attr('data-open', 0)
			$('.secondary-item-sub').removeClass('translate')
			$(this).siblings().addClass('open');
			$(this).addClass('translate')
			$(this).attr('data-open', 1)
		} else {
			$('.secondary-item-dec').removeClass('open');
			$('.secondary-item-sub').attr('data-open', 0)
			$(this).removeClass('translate')
		}
	})

});


function reload_carrito() {

	$.each(info_lots, function (index_sub, value_sub) {
		var precio_envio = 0;
		var sum_precio_envio = 0;
		var precio_final = 0;
		$.each(value_sub.lots, function (index, value) {
			precio_final = precio_final + value.himp + value.iva + value.base;
			sum_precio_envio = sum_precio_envio + value.himp + value.base;
		});
		if (sum_precio_envio > 0) {
			$.ajax({
				type: "POST",
				async: false,
				url: '/api-ajax/gastos_envio',
				data: { 'precio_envio': sum_precio_envio },
				success: function (data) {
					precio_envio = data.imp + data.iva;

				}
			});
		}
		$(".text-gasto-envio-" + index_sub).text(precio_envio);
		precio_final = parseFloat(precio_final) + parseFloat(precio_envio);
		$(".precio_final_" + index_sub).text(precio_final.toFixed(2).replace(".", ","));
		if (precio_final <= 0) {
			$('.submit_carrito[cod_sub="' + index_sub + '"]').attr("disabled", "disabled");
		} else {
			$('.submit_carrito[cod_sub="' + index_sub + '"]').removeAttr("disabled");
		}
	});
}

async function newsletterSuscription(event) {
	var email = $('.newsletter-input').val();
	var lang = $('#lang-newsletter').val();

	const newsletters = {};
	document.querySelectorAll(".js-newletter-block [name^=families]").forEach((element) => {
		if (element.checked || element.type === "hidden") {
			newsletters[`families[${element.value}]`] = '1';
		}
	});

	const captcha = await isValidCaptcha();
	if(!captcha){
		showMessage(messages.error.recaptcha_incorrect);
		return;
	}

	const data = {
		email,
		lang,
		condiciones: 1,
		...newsletters
	}

	if($('[name="captcha_token"]').length) {
		data.captcha_token = $('[name="captcha_token"]').val();
	}


	addNewsletter(data);
}

async function newsletterFormSuscription(event) {
	event.preventDefault();

	const captcha = await isValidCaptcha();
	if(!captcha){
		showMessage(messages.error.recaptcha_incorrect);
		return;
	}

	if (!$("[name=condiciones]").prop("checked")) {
		$("#insert_msgweb").html('');
		$("#insert_msgweb").html(messages.neutral.accept_condiciones);
		$.magnificPopup.open({ items: { src: '#modalMensajeWeb' }, type: 'inline' }, 0);
		return;
	}
	const data = $(event.target).serialize();

	addNewsletter(data);
}

function addNewsletter(data) {
	$.ajax({
		type: "POST",
		data: data,
		url: '/api-ajax/newsletter/add',
		success: function (msg) {
			if (msg.status == 'success') {
				$('.insert_msg').html(messages.success[msg.msg]);
			} else {
				$('.insert_msg').html(messages.error[msg.msg]);
			}
			$.magnificPopup.open({ items: { src: '#newsletterModal' }, type: 'inline' }, 0);
		},
		error: function (error) {
			$('.insert_msg').html(messages.error.message_500);
			$.magnificPopup.open({ items: { src: '#newsletterModal' }, type: 'inline' }, 0);
		}
	});
}

$(document).on("change", ".add_factura", function () {
	reload_facturas();
});

$(document).on("click", "#submit_fact", function () {
	payFacs();
});

function reload_facturas() {

	var total = 0;

	for (const factura of pendientes) {

		if ($(`#checkFactura-${factura.anum_pcob}-${factura.num_pcob}-${factura.efec_pcob}`).is(":checked")) {
			total += parseFloat(factura.imp_pcob);
		}
	}

	if (total > 0) {
		$("#submit_fact").removeClass('hidden');
	} else {
		$("#submit_fact").addClass('hidden');
	}

	$("#total_bills").html(format_money(total));
}

function payFacs(button) {

	$("#btoLoader").siblings().addClass('hidden');
	$("#btoLoader").removeClass('hidden');

	var pay_fact = $('#pagar_fact').serializeArray();
	var total = 0;

	for (const factura of pendientes) {
		if ($(`#checkFactura-${factura.anum_pcob}-${factura.num_pcob}-${factura.efec_pcob}`).is(":checked")) {
			total += parseFloat(factura.imp_pcob);
		}
	}

	if (total > 0) {
		$.ajax({
			type: "POST",
			url: '/gateway/pagarFacturasWeb',
			data: pay_fact,
			success: function (data) {
				if (data.status == 'success') {
					window.location.href = data.msg;
				} else
					if (data.status == 'error') {
						$("#modalMensaje #insert_msg").html('');
						$("#modalMensaje #insert_msg").html(messages.error.generic);
						$.magnificPopup.open({
							items: {
								src: '#modalMensaje'
							},
							type: 'inline'
						}, 0);

					}
				$("#btoLoader").siblings().removeClass('hidden');
				$("#btoLoader").addClass('hidden');

			},
			error: function (response) {
				$("#modalMensaje #insert_msg").html('');
				$("#modalMensaje #insert_msg").html(messages.error.generic);
				$.magnificPopup.open({
					items: {
						src: '#modalMensaje'
					},
					type: 'inline'
				}, 0);
				$("#btoLoader").siblings().removeClass('hidden');
				$("#btoLoader").addClass('hidden');
			}
		});

	}
};

$(document).on("click", ".panel-collapse", openCloseToggle);

function openCloseToggle() {
	let condition = $(this).find(".toggle-open").css("display") != "none" && $(this).find(".label-open").css("display") != "none";

	if (condition) {
		$(this).find(".toggle-open").hide();
		$(this).find(".toggle-close").show();
		$(this).find(".label-open").hide();
		$(this).find(".label-close").show();
	} else {
		$(this).find(".toggle-close").hide();
		$(this).find(".toggle-open").show();
		$(this).find(".label-close").hide();
		$(this).find(".label-open").show();
	}
}

$(document).on("click", "#button-open-user-menu", function () {
	$('#user-account-ul').toggle()
});

$(document).ready(function () {

	$("#square").click(() => seeLot('img'));
	$("#square_mobile").click(() => seeLot('img'));
	$("#small_square").click(() => seeLot('small_img'));
	$("#large_square").click(() => seeLot('desc'));
	$("#large_square_mobile").click(() => seeLot('desc'));

	let styleLotSee = document.querySelector('[name=lot_see_configuration]')?.value;
	if (styleLotSee) {
		seeLot(styleLotSee, false);
	}
});

function seeLot(style, save = true) {
	const options = {
		'desc': see_desc,
		'img': see_img,
		'small_img': see_img_samll
	}

	//hideAllStylesLots();
	options[style] ? options[style]() : options['img']();

	if (!save) return;
	saveConfigurationCookies({ lot: style });
}

function hideAllStylesLots() {
	$(".square").addClass("hidden");
	$(".small_square").addClass("hidden");
	$(".large_square").addClass("hidden");
	$('.bar-lot-large').addClass("hidden");
}

function see_desc() {
	$('.list_lot').removeClass("small_list").addClass("large_list");
}

function see_img() {
	$('.list_lot').removeClass("large_list").removeClass("small_list");
}

function see_img_samll() {
	$('.list_lot').removeClass("large_list").addClass("small_list");
}

function sendContactForm(event) {
	event.preventDefault();
	const form = event.currentTarget;
	validateCaptchaMiddleware(() => form.submit())
}
