$(document).ready(function () {

	$("#country").change(function () {
		var selected_country = $("#frmRegister-adv #country").val();
		if (selected_country == "ES") {
			$('#dni').prop("required", true);
			$('#cpostal').prop("required", true);
		} else {
			$('#dni').removeAttr("required");
			$('#cpostal').removeAttr("required");
		}
	});

	$('.lazy').Lazy({
		// your configuration goes here
		scrollDirection: 'vertical',
		effect: 'fadeIn',
		visibleOnly: true,
		onError: function (element) {
			console.log('error loading ' + element.data('src'));
		}
	});


	//aber el desplegable de login una vez por session
	//en cualquier ruta exepto en /es o /en
	appearLogin();

	var d = document.documentElement.style;
	if (('flexWrap' in d) || ('WebkitFlexWrap' in d) || ('msFlexWrap' in d)) {

	} else {
		$('.login-desktop-container').css('top', '30%');
		$('.login-desktop-container').css('left', '30%');
		$('.logo').addClass('col-xs-3');
		$('.menu-access').addClass('col-xs-9');
		$('.search-component').css('display', 'inline-block');
		$('.menu-access li').css('display', 'inline-block');
		$('.items_top').css('float', 'left');
		$('.items_top').css('margin-top', '6px');

	}


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


	$('.search-component-form').submit(function () {
		$('.btn-custom-search').find('i').hide()
		$('.btn-custom-search').find('.loader.mini').show()

	})



	$("#owl-carousel").owlCarousel({
		items: 1,
		loop: true,
		autoplay: true,
		dots: true,
		nav: true,
		navText: ['<i class="fa fa-angle-left visible-lg">', '<i class="fa fa-angle-right visible-lg">']
	});
	$(".owl-carousel-home").owlCarousel({
		items: 4,
		loop: true,
		autoplay: true,
		margin: 20,
		dots: false,
		nav: false,
		responsiveClass: true,
		responsive: {
			0: {
				items: 1
			},
			600: {
				items: 2
			},
			1000: {
				items: 4
			},
			1200: {
				items: 4
			},
		}
	});
	$(".owl-carousel-single").owlCarousel({
		items: 4,
		loop: true,
		autoplay: true,
		margin: 20,
		dots: true,
		nav: false,
		responsiveClass: true,
		responsive: {
			0: {
				items: 1
			},
			600: {
				items: 2
			},
			1000: {
				items: 4
			},
			1200: {
				items: 4
			},
		}
	});

	$("#owl-carousel-responsive").owlCarousel({
		items: 1,
		autoplay: true,
		margin: 20,
		dots: true,
		nav: false,
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

		/* setTimeout(showAlertComisionMessage, 500); */

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
		$('.login-desktop-container').removeClass('loginClose');
		$('.login_desktop').fadeToggle("fast");
	});

	$('.closedd').on('click', function () {
		$('.login-desktop-container').addClass('loginClose')
		$('.login_desktop').fadeToggle("fast");
	});

	$("#accerder-user").click(function () {


		$('#accerder-user-form').animate({
			opacity: 0.25,
		}, 500)
		$.ajax({
			type: "POST",
			url: '/login_post_ajax',
			data: $('#accerder-user-form').serialize(),
			success: function (response) {
				if (response.status == 'success') {
					location.reload();
				} else {
					$(".message-error-log").text('').append(messages.error[response.msg]);
					$('#accerder-user-form').animate({
						opacity: 1,
					}, 500)

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

			$('button', $this).attr('disabled', false);

		}
	});

	$(() => {

		$('[name=nif]').attr('id', 'nif__1__nif');

		$('input[name=creditcard]').on('keyup', function(){

			if(this.value.match(/[a-zA-z]+/) || this.value.length < $(this).attr('minlength')){
				$(this).addClass('effect-26').addClass('has-error');
				return;
			}

			$(this).removeClass('effect-26').removeClass('has-error');
		});


		$('input[name=card-expired-month], input[name=card-expired-year]').on('keypress', function(e){
			if(this.value.length == 2){
				return false;
			}
		});

		$('input[name=card-expired-month]').on('blur', function(e){
			if(this.value > 12){
				this.value = 12;
			}
			if(this.value < 10 && this.value.length == 1){
				this.value = `0${this.value}`;
			}
			if(this.value == 0){
				this.value = '01';
			}
		});

		$('input[name=card-expired-year]').on('blur', function(e){

			let actualYear = new Date().getUTCFullYear().toString().substring(2,4);
			if(this.value.length == 2 && this.value < actualYear){
				this.value = actualYear;
			}
		});

		/* Si se modifica cualquier campo con los nombres "creditcard", "card-expired-month" y "card-expired-year" acceder a la función creditCardInputHidden() */
		/* $('input[name=creditcard], input[name=card-expired-month], input[name=card-expired-year]').on('keyup', creditCardInputHidden()); */

		$('input[name=card-expired-month], input[name=creditcard], input[name=card-expired-year]').on('blur', function() {
			creditCardInputHidden()
		});


	});

	if($('[name=creditcard]').val() != '' && $('[name=card-expired-month]').val() != '' && $('[name=card-expired-year]').val() != ''){
		creditCardInputHidden();
	}



	$('#frmUpdateUserInfoADV').validator().on('submit', function (e) {

		if (e.isDefaultPrevented()) {
			var text = $(".error-form-validation").html();
			$("#insert_msgweb").html('');
			$("#insert_msgweb").html(text);
			$.magnificPopup.open({ items: { src: '#modalMensajeWeb' }, type: 'inline' }, 0);
		} else {

			e.preventDefault();
			var $this = $(this);

			var error = 0;

			// Validamos el nif
			if ($('input[name=nif]').val() == '') {
				$('input[name=nif]').addClass('effect-26').addClass('has-error');
				error++;
			} else {
				$('input[name=nif]').removeClass('effect-26').removeClass('has-error');
			}

			// Validamos la tarjeta
			if ($('input[name=creditcard]').val() != '') {

				var numcreditcard = $('input[name=creditcard]').val();
				if(numcreditcard.match(/[a-zA-z]+/) || numcreditcard.length < $('input[name=creditcard]').attr('minlength')){
					$('input[name=creditcard]').addClass('effect-26').addClass('has-error');
					error++;
				} else {
					$('input[name=creditcard]').removeClass('effect-26').removeClass('has-error');
				}

			} else {

				error++;
				$('input[name=creditcard]').addClass('effect-26').addClass('has-error');

			}

			// Validamos el mes
			if ($('input[name=card-expired-month]').val() != '') {

				var numcardexpiredmonth = $('input[name=card-expired-month]').val();
				if(numcardexpiredmonth.length != 2){
					error++;
					$('input[name=card-expired-month]').addClass('effect-26').addClass('has-error');
				} else {
					$('input[name=card-expired-month]').removeClass('effect-26').removeClass('has-error');
				}

			} else {
				error++;
				$('input[name=card-expired-month]').addClass('effect-26').addClass('has-error');
			}

			// Validamos el año
			if ($('input[name=card-expired-year]').val() != '') {
				if(numcardexpiredmonth.length != 2){
					error++;
					$('input[name=card-expired-year]').addClass('effect-26').addClass('has-error');
				} else {
					$('input[name=card-expired-year]').removeClass('effect-26').removeClass('has-error');
				}
			} else {
				error++;
				$('input[name=card-expired-year]').addClass('effect-26').addClass('has-error');
			}

			if (error > 0) {
				// Mostrar popup de error
				var text = $(".error-form-validation").html();
				$("#insert_msgweb").html('');
				$("#insert_msgweb").html(text);
				$.magnificPopup.open({ items: { src: '#modalMensajeWeb' }, type: 'inline' }, 0);
				return false;
			}

			$('button', $this).attr('disabled', 'disabled');
			// Datos correctos enviamos ajax
			var form_update_data = new FormData($('#frmUpdateUserInfoADV')[0]);
			$.ajax({
				type: "POST",
				url: '/api-ajax/client/update',
				data: form_update_data,
				contentType: false,
    			processData: false,
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

	$("#pujar_ordenes_w").click(function () {
		var $imp = $("#bid_modal_pujar").val();
		$(".precio_orden").text($imp);
		if (typeof cod_licit == 'undefined' || cod_licit == null) {
			$("#insert_msg_title").html("");
			$("#insert_msg").html(messages.error.mustLogin);
			$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);

		} else {
			$.magnificPopup.open({ items: { src: '#ordenFicha' }, type: 'inline' }, 0);
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
					ga('send', 'event', 'deja-puja', 'click');
					if (data.winner) {
						$(".no_winner").addClass("hidden");
						$(".winner").removeClass("hidden");
					} else {
						$(".no_winner").removeClass("hidden");
						$(".winner").addClass("hidden");
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
		$.ajax({
			type: "POST",
			url: '/login_post_ajax',
			data: $('#accerder-user-form-responsive').serialize(),
			success: function (response) {
				if (response.status == 'success') {
					location.reload();
				} else {
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


	var precio_final = 0;
	var send_pay = [];

	$('.add-carrito').change(function () {
		reload_carrito();
	});

	$('.envios').on('change', function () {
		reload_carrito();
	});

	$('.seguro').on('change', function () {
		reload_carrito();
	});

	$("#save_change_orden").click(function () {

		var cod_sub = $(this).attr('cod_sub');
		var ref = $(this).attr('ref');
		var order = $(this).attr('order');
		$.ajax({
			type: "POST",
			url: url_orden + '-' + cod_sub,
			data: { cod_sub: cod_sub, ref: ref, imp: order },
			success: function (res) {
				if (res.status == 'success') {
					$("#modalMensaje #insert_msg").html('');
					$("#modalMensaje #insert_msg").html(res.msg);
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
					change_price_saved_offers();
				} else {
					$("#modalMensaje #insert_msg").html('');
					$("#modalMensaje #insert_msg").html(res.msg_1);
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				}
			}

		});
	});


	$('.save_orders').validator().on('submit', function (e) {
		var order;
		if (e.isDefaultPrevented()) {

		} else {
			e.preventDefault()
			var save_orders = $(this).serializeArray();
			$.each(save_orders, function (i, field) {
				$("#save_change_orden").attr(field.name, field.value);
				if (field.name == 'order') {
					order = field.value
				}
			});
			$(".precio_orden").html('');
			$(".precio_orden").html(order);

			$.magnificPopup.open({ items: { src: '#changeOrden' }, type: 'inline' }, 0);
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

	$(".delete_order").click(function () {
		var ref = $(this).attr("ref");
		var sub = $(this).attr("sub");
		$(".confirm_delete").attr("price", $("#" + sub + "-" + ref + " input").val());
		$(".confirm_delete").attr("ref", ref);
		$(".confirm_delete").attr("sub", sub);
		$("#insert_msg_delete").html(messages.neutral.confirm_delete);
		$.magnificPopup.open({ items: { src: '#modalMensajeDelete' }, type: 'inline' }, 0);
	});

	$("#send-consult-lot").submit(function (event) {
		event.preventDefault();
		$.ajax({
			type: "POST",
			url: "/consult-lot/email",
			data: $(this).serializeArray(),
			success: function (result) {
				$("#respuesta-consult-lot").removeClass("hidden");
				if (result.status == 'error') {
					$("#respuesta-consult-lot").html(messages.error[result.msg]).addClass("text-danger");
				} else if (result.status == 'success') {
					$("#respuesta-consult-lot").html(messages.success[result.msg]).addClass("text-success").removeClass("text-danger");
					$("#send-consult-lot").remove();
				}
			},
		})
	});

	$("#form-contact").submit(function () {
		if ($(window).width() < 768) {
			$('#buttonSend').attr('disabled', true)
			$(this).append('<div style="position: fixed;width: 100%;height: 100%;z-index: 10000;background: rgba(0,0,0,.2);top: 0;left: 0;"><div class="loader"></div></div>')
		}
	})

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

	$('.content_item_mini').hover(function (e) {

		var el, newPos, capaOculta, vwCapaOculta, vwWindow;
		el = $(this)
		posEl = el.offset()
		capaOculta = $(this).siblings($('.capaOculta'))
		capaOculta.show()
		posLeft = posEl.left
		vwWindow = $(window).width() / 2

		if (posLeft > vwWindow) {
			vwCapaOculta = ($('.capaOculta').width() / 2);
			newPos = posLeft - vwCapaOculta;
			newpos2 = ($('.capaOculta').offset().left - vwCapaOculta) - 90
			capaOculta.css("left", newpos2 + 'px');


		} else {

			newpos2 = 0
		}
		capaOculta.css("left", newpos2 + 'px');

		var posElTop = el.offset().top
		vhWindow = $(window).height() / 2

		if (posElTop > vhWindow) {
			console.log(vhWindow)
			if ($(document).scrollTop() > 200) {

			}

			var newPosTop = -400 + ($(document).scrollTop());

			capaOculta.css("top", '-400px');

		}


	}, function () {
		var capaOculta = $(this).siblings($('.capaOculta'))
		capaOculta.hide()
	});


	/*****************
	* Control de switches en vista de busquedas
	*
	*********************/
	$("#switchH").click(function () {
		$("#switchS").removeClass("switcher-active");
		$("#onlyActive").removeAttr("checked");
	});

	$("#switchS").click(function () {
		$("#switchH").removeClass("switcher-active");
		$("#onlyHistory").removeAttr("checked");
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

			if (!result) {
				$('.lotes_destacados').addClass('hidden');
				return;
			}

			$("#" + key).html(result);

			carrousel_molon($("#" + key));

			//--- Añadimos tres puntitos al final
			$('.desc_lot p').each(function () {


				if ($(this).height() > 40) {
					var valText = $(this).text();

					valText = valText.slice(0, 74)
					valText = valText + ' ...';
					$(this).parent().addClass('truncat')
				}


			})

			//-----
			$('[data-countdown]').each(function () {
				$(this).data('ini', new Date().getTime());
				countdown_timer($(this));
			});

		}

	});

};

function format_date(fecha) {

	var horas = fecha.getHours();
	var minutos = fecha.getMinutes();
	var mes;
	if (horas < 10) {
		horas = '0' + horas
	}
	if (minutos < 10) {
		minutos = '0' + minutos
	}

	$.each(traductions, function (key, value) {
		if (key == $.datepicker.formatDate("M", fecha)) {
			mes = value;
		}
	});

	var formatted = $.datepicker.formatDate("dd ", fecha) + mes + " " + horas + ":" + minutos;
	return formatted;
}


/*function carrousel_molon(carrousel){
	carrousel.owlCarousel({
		items:4,
		autoplay:true,
		margin: 20,
		dots:true,
		nav: false,
		responsiveClass: true,
		responsive: {
			0: {
				items: 1
			},
			600: {
				items: 2
			},
			1000: {
				items: 4
			},
			1200: {
				items: 4
			}
		}
	});
};*/

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
		slidesToScroll: 1,
		dots: true,
		rows: rows,
		slidesToShow: 4,
		arrows: false,
		responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 3,
					infinite: true,
					dots: true,
					rows: 1
				}
			},
			{
				breakpoint: 600,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2,
					rows: 1
				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					rows: 1
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

function format_date_large(fecha, text) {

	var horas = fecha.getHours();
	var minutos = fecha.getMinutes();
	var mes;
	if (horas < 10) {
		horas = '0' + horas
	}
	if (minutos < 10) {
		minutos = '0' + minutos
	}

	$.each(traduction_large, function (key, value) {
		if (key == $.datepicker.formatDate("M", fecha)) {
			mes = value;
		}
	});

	var formatted = $.datepicker.formatDate("dd ", fecha) + mes + " " + text + " " + horas + ":" + minutos + " h";
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
				if (action == 'remove' && $('#heading-'+cod_sub+' #'+cod_sub+' .user-accout-items-content .user-accout-item-wrapper').length <= 0) {
					$('#heading-'+cod_sub).remove();
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

function appearLogin(){

	if (!window.localStorage) {
		return;
	}

	if ($('.btn_login_desktop').length === 0) {
		return;
	}

	const thisRoute = window.location.href;
	const originRoute = window.location.origin;

	//check if not in home
	if (thisRoute === originRoute + '/es' || thisRoute === originRoute + '/en') {
		return;
	}

	const nextPopup = localStorage.getItem('enterLogin');
	if (new Date(nextPopup) > new Date()) {
		return;
	}

	const now = new Date();
	const expires = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1);
	localStorage.setItem('enterLogin', expires);

	if ($('.btn_login_desktop').parents('ul').css('display') !== 'none') {
		$('.login_desktop').fadeIn();
	}
}

function reload_carrito() {
	$.each(info_lots, function (index_sub, value_sub) {
		var precio_envio = 0;
		var sum_precio_envio = 0;
		var precio_final = 0;

		$.each(value_sub.lots, function (index, value) {
			if ($("#add-carrito-" + index_sub + "-" + index + "").is(":checked")) {
				precio_final = precio_final + value.himp + value.iva + value.base;
				sum_precio_envio = sum_precio_envio + value.himp + value.base;

			}
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

function showAlertComisionMessage() {
	$.magnificPopup.open({ items: { src: '#modalAlertComision' }, type: 'inline' }, 0);
}

window.comprarLoteFicha = function comprarLoteFicha() {

	$.ajax({
		type: "POST",
		url: routing.comprar + '-' + cod_sub,
		data: { cod_sub: cod_sub, ref: ref },
		success: comprarLoteResponse,
		error: () => {
			const data = {
				status: 'error',
				msg_1: messages.error.code_500,
			};
			comprarLoteResponse(data);
		}
	});
}

function comprarLoteResponse(response) {

	const isSuccess = response.status == 'success';

	document.getElementById('js-payLot').classList.toggle('hidden', !isSuccess);
	document.querySelector('.lot-action_comprar_lot').classList.toggle('hidden', isSuccess);

	const responseMessage = isSuccess ? response.msg : response.msg_1;
	$("#modalMensajeBuy #insert_msg").html(responseMessage);

	$.magnificPopup.open({
		items: { src: '#modalMensajeBuy' },
		type: 'inline',
		callbacks: {
			close: function () {
				if (isSuccess) {
					document.location.reload();
				}
			}
		}
	});
}

async function newsletterSuscription(event) {
	const email = $('.newsletter-input').val();
	const lang = $('#lang-newsletter').val();

	const captcha = await isValidCaptcha();
	if(!captcha){
		showMessage(messages.error.recaptcha_incorrect);
		return;
	}

	if (!$('#condiciones').prop("checked") || !$('#accept_new').prop("checked")) {
		$("#insert_msgweb").html('');
		$("#insert_msgweb").html(messages.neutral.accept_condiciones);
		$.magnificPopup.open({ items: { src: '#modalMensajeWeb' }, type: 'inline' }, 0);
		return;
	}

	const newsletters = {};
	document.querySelectorAll(".js-newletter-block [name^=families]").forEach((element) => {
		if (element.checked || element.type === "hidden") {
			newsletters[`families[${element.value}]`] = '1';
		}
	});

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

function creditCardInputHidden (e){
	let card = $('input[name=creditcard]').val();
	let month = $('input[name=card-expired-month]').val();
	let year = $('input[name=card-expired-year]').val();
	$('input[name=creditcard_fxcli]').val(`${card} ${month}/${year}`);
}

$(document).on("change", ".add_factura", function() {
	reload_facturas();
});

$(document).on("click", "#submit_fact", function() {
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
	$("#total_bills").html(change_currency(total));
	// Hacer la línea $("#total_bills").html(change_currency(total)); sin jquery

}

function payFacs(button){

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

function change_currency(price) {
	var price = numeral(price).format('0,0.00');
	return price;
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

	hideAllStylesLots();
	options[style] ? options[style]() : options['img']();

	if(!save) return;
	saveConfigurationCookies({ lot: style });
}

function hideAllStylesLots() {
	$(".square").addClass("hidden");
	$(".small_square").addClass("hidden");
	$(".large_square").addClass("hidden");
	$('.bar-lot-large').addClass("hidden");
}

function see_desc() {
	$(".large_square").removeClass("hidden");
	$('.bar-lot-large').removeClass("hidden");
}

function see_img() {
	$(".square").removeClass("hidden");
}

function see_img_samll() {
	$(".small_square").removeClass("hidden");
}

function sendContactForm(event) {
	event.preventDefault();
	const form = event.currentTarget;
	validateCaptchaMiddleware(() => form.submit())
}

function viewResourceFicha($src, $format) {
	$('#resource_main_wrapper').empty();
	$('.img-global-content').hide();
	$('#toolbarDiv').hide();
	if ($format == "GIF") {
		$resource = $('<img  src=' + $src + ' style="max-width: 100%;">');
	} else if ($format == "VIDEO") {
		$resource = $('<video width="100%" height="auto" autoplay="true" controls>').append($('<source src="' + $src + '">'));
	}
	$('#resource_main_wrapper').append($resource);
	$('#resource_main_wrapper').show();
}
