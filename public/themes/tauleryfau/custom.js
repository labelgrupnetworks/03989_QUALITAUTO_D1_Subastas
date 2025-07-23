window.onscroll =
	function () {
		if ($('.online-time-foot').length > 0) {
			timeForAuctions()
		}
	}

const rootElement = document.querySelector(':root');


$(function () {

	$("input[name=shipping]").on("change", function () {
		reload_carrito();
	})

	$("input[name=clidd]").on("change", function () {
		reload_carrito();
	})

	$("input[name=paymethod]").on("change", function () {

		$('.summary-body').hide();

		const paymethodBlock = $("input[name=paymethod]:checked").val() === 'transfer' ? 'transfer' : 'creditcard';
		$(`.summary-body[data-paymethod="${paymethodBlock}"]`).show();

		reload_carrito();
	});

	$('.js-pay-bill').on('submit', payFactura)

	viewVideoBtnEvents();

	$('.gotoauction').click(function () {

		$('#modal-current-auction_LABELP').modal('hide')
	})


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

	$('.account.payment').click(function (e) {
		if (e.target.className !== 'open-modal-info btn info') {
			$('.info-pay-modal').each(function () {
				$(this).hide()
			})
		}
	})


	$('.open-modal-info').click(function () {
		$('.info-pay-modal').each(function () {
			$(this).hide()
		})
		$(this).siblings().toggle()
	})
	$('.info-pay-modal-close').click(function () {
		$(this).parent().toggle()
	})

	$(".add-carrito").on('change', function () {
		$(this).parent().parent().toggleClass('selected')
	})

	$('.close-view-info').click(function () {
		$(this).parents('.view-info').fadeOut()
	})

	function showInfo(id) {
		$('.row-pay').find('.view-info-' + id).fadeIn()

	}
	$('.img-dat').click(function () {
		showInfo($(this).attr('id'))
	})


	$('.view-data-custom').hover(function () {
		$(this).find('.view-data-menu').show();
	}, function () {
		$('.view-data-menu').hide();
	})




	// $('[data-toggle="tooltip"]').tooltip()




	$('.myAccount').click(function () {
		open_secondpanel()
	})



	/**********************************
	JAVASCRIPT PERSONALIZADO
	**********************************/


	$('.ship-info').hover(function () {
		$('.data-ship').toggle('show')
	})

	$('.view-data-menu ul li a').click(function () {
		$(this).parent().parent().parent().toggle()
	})

	$('.close-modal-360').click(function () {
		$('#modal360').modal('hide')
	})

	$('.btn-responsive .btn-360').click(function () {
		$(this).toggleClass('active-360')
	})




	$('.files').on("change", function (e) {
		var input = '#' + e.target.id
		var parent = '.' + e.target.id

		var reader = new FileReader();
		reader.onload = function (e) {
			var filePreview = $('.add-picture ' + parent).find('img').attr('src', e.target.result)
			console.log(filePreview)
			filePreview.id = 'file-preview';
			//e.target.result contents the base64 data from the image uploaded
			filePreview.src = e.target.result;

		};

		reader.readAsDataURL(e.target.files[0]);

		$('.add-picture ' + parent).find('label i').hide()
		$('.add-picture ' + parent).find('span i').show()
		//$('.add-picture ' + parent).find('span').text(e.target.files[0].name)

		console.log(e.target.results)
	});












	$('.add-picture span').find('i').click(function () {
		var input = '#' + $(this).parents('.text-center.fill').find('input').attr('id')
		var parent = '.' + $(this).parents('.text-center.fill').find('input').attr('id')
		$(input).val(null)
		$(this).parents('.text-center.fill').find('img').attr('src', '')
		$('.add-picture ' + parent).find('label i').show()
		$('.add-picture ' + parent).find('span i').hide()

	})


	$('.search-button-content input').focus(function () {
		$('.search-bar').addClass('focus');
	}).blur(function () {
		$('.search-bar').removeClass('focus');
	})


	$('.menu .nav ul li a').click(function (e) {
		e.preventDefault();

		if (this.dataset.target) {
			return;
		}

		var t = this.href;
		$(".loaderTauler").fadeIn(), setTimeout(function () {
			window.location.href = t
		}, 1e3)

	});
	$('.footer-navbar ul li a').click(function (e) {

		e.preventDefault();
		var t = this.href;
		$(".loaderTauler ").fadeIn(), setTimeout(function () {
			window.location.href = t
		}, 1e3)

	});



	$('.tabs-custom .nav li').click(function (ev) {

		if (ev.originalEvent) {
			if ($(window).width() < 480 && $(window).scrollTop() < ($('#carousel').height() / 2)) {
				$("html, body").animate({ scrollTop: $('.tabs-custom').offset().top - 50 }, 600);

			}
			if ($(window).scrollTop() < ($('#carousel').height() / 2) && $(window).width() > 991) {
				$("html, body").animate({ scrollTop: $('.tabs-custom').offset().top }, 600);


			}
			if ($(window).width() < 991 && $(window).scrollTop() < ($('#carousel').height() / 2)) {
				$("html, body").animate({ scrollTop: $('.tabs-custom').offset().top - 60 }, 600);

			}

		}

	})



	$('.close-menu').click(function () {
		closeMenuResponsive()
	})
	$('.search-img-mobile').click(function () {
		searchResponsive()
	})
	$('.hamburguer i').click(function () {
		showMenuResponsive()
	})
	$('#accerder-user-form').submit(function (e) {
		e.preventDefault();
		$("#accerder-user").click()
	})

	$('.switcher').on('click', (e) => {
		$('.switcher').toggleClass('switcher-active');
		$('.filters').toggle();

		const showFilter = $('.switcher')[0].classList.contains('switcher-active');
		sessionStorage.setItem('showFilter', showFilter.toString());
	});

	$('select[name="pagination-select"]').on('change', (event) => {
		const page = event.target.value;
		const form = document.forms["pagination"];
		const url = form.action.slice(0, -1) + page;
		location.href = url;
	});

	if ($('.switcher').length) {
		const showFilterString = JSON.parse(sessionStorage.getItem('showFilter'));
		const switcherIsVisible = document.querySelector('.switcher')?.offsetWidth;

		if (showFilterString && switcherIsVisible) {
			$('.switcher').trigger('click');
		}
	}

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
		 const cssOK = CSS.supports("animation-timeline: scroll()");

		if ($(document).scrollTop() > 100) {
			$('.button-up').show(500);

			if (!cssOK) {
				rootElement.style.setProperty('--header-height', 54 + 'px');
			}


		}
		if ($(document).scrollTop() <= 100) {
			$('.button-up').hide(500)
			if (!cssOK) {
				rootElement.style.setProperty('--header-height', 94 + 'px');
			}
		}
	})


	$('.button-up').click(function () {

		$('html,body').animate({ scrollTop: 0 }, 500);
	});

	$("#owl-carousel").owlCarousel({

		loop: true,
		nav: false,
		dots: true,
		margin: 10,
		navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right ">'],


		responsive: {
			0: {
				items: 1
			},
			600: {
				items: 2
			},
			1000: {
				items: 3
			},
			1200: {
				items: 3
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
		autoplay: false,
		margin: 20,
		dots: true,
		nav: false,
		responsiveClass: true,
	});


	$(".owl-carousel-home").owlCarousel({
		items: 1,
		autoplay: true,
		dots: true,
		loop: true,
		nav: false,
		responsiveClass: true,
		navText: ['<i class="fa fa-5x fa-caret-left">', '<i class="fa fa-5x fa-caret-right">']

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
		$(this).find('.loader').show();
		$(this).find('span').hide();
		$('.signin-content').removeClass('animationShaker')
		$.ajax({
			type: "POST",
			url: '/login_post_ajax',
			data: $('#accerder-user-form').serialize(),
			success: function (response) {
				if (response.status == 'success') {
					location.reload();
				} else {
					$(".message-error-log").text('').append(messages.error[response.msg]);
					$('#accerder-user').find('.loader').hide();
					$('#accerder-user').find('span').show();
					$('.signin-content').addClass('animationShaker')
				}

			}
		});

	});

	/**
	 * Cambiado ya que con el nuevo controlador se obliga a rellenar ciertos campos que
	 * actualmente no utilizamos en la vista.
	 */
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
						$("#insert_msg_title").html("");
						$("#insert_msg").html(messages.success[res.msg]);
						$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
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

			const formData = new FormData(this);

			// Datos correctos enviamos ajax
			$.ajax({
				type: "POST",
				url: '/api-ajax/client/update',
				data: formData,
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
						$("#insert_msg_title").html("");
						$("#insert_msg").html(messages.success[res.msg]);
						$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
					}
				}

			});

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
					$("#actual_max_bid").html(data.open_price + " €");
					$("#insert_msg_title").html("");
					$("#insert_msg").html(data.msg);
					$(".hist_new").removeClass("hidden");
					$(".custom").removeClass("hidden");
					$("#bid_modal_pujar").val(data.imp_actual);
					if (data.winner) {
						$("#actual_max_bid").addClass("winner");
						$("#actual_max_bid").removeClass("no_winner");
					} else {
						$("#actual_max_bid").removeClass("winner");
						$("#actual_max_bid").addClass("no_winner");
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
			$('#modalLogin').modal('show')
			//$("#insert_msg").html("");
			//$("#insert_msg").html(messages.error.mustLogin);
			//$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
		} else {
			$.magnificPopup.open({ items: { src: '#modalComprarFicha' }, type: 'inline' }, 0);
		}

	});



	$('#catalogue').on('click', function () {
		var sub = $(this).attr('data-subasta');
		$(this).addClass('send')

		$.ajax({
			type: "POST",
			data: { idSubasta: sub },
			url: '/api-ajax/mail-peticion-catalogo',
			success: function (res) {

				$('.insert_msg').html(messages.success.catalogo);
				$.magnificPopup.open({ items: { src: '#newsletterModal' }, type: 'inline' }, 0);

				$('#catalogue').addClass('complete')
				setTimeout(function () {
					$('#catalogue').removeClass('complete').removeClass('send')
				}, 1500)
			},
			error: function (e) {
				$.magnificPopup.open({ items: { src: '#newsletterModal' }, type: 'inline' }, 0);
				$('.insert_msg').html('<p class="text-danger">' + messages.error.generic + '</p>');
				$('#catalogue').removeClass('send')
			}
		});
	});


	$(window).resize(function () {
		if ($(window).width() < 1200) {
			$('.small_square .item_lot').removeClass('col');
		}
	});

	$(".submit_carrito").click(function () {
		var cod_sub = $(this).attr('cod_sub');
		$(".submit_carrito").html("<div class='loader mini' style='width: 20px;height: 20px;margin-top: 0px;margin-bottom: 0;'></div>");
		$(".submit_carrito").attr("disabled", "disabled");
		$('#paymethod_' + cod_sub).val($(this).data("paymethod"));
		var pay_lote = $('#pagar_lotes_' + cod_sub).serialize();
		$.ajax({
			type: "POST",
			url: '/gateway/pagarLotesWeb',
			data: pay_lote,
			success: function (data) {
				if (data.status == 'success') {
					window.location.href = data.msg;
				} else if (data.status == 'error') {
					$("#modalMensaje #insert_msg").html('');
					$("#modalMensaje #insert_msg").html(messages.error.generic);
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
					$(".submit_carrito").html(" ");
					(".submit_carrito").prop("disabled", false);
				}
			},
			error: function (response) {
				$("#modalMensaje #insert_msg").html('');
				$("#modalMensaje #insert_msg").html(messages.error.generic);
				$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
			}
		});

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

	$("#form-valoracion-adv").submit(function (event) {


		event.preventDefault();
		$(this).find('.loader-container').fadeIn();

		formData = new FormData(this);
		var max_size = 20;
		var size = 0;
		for (var i = 0; i < $('.files').length; i++) {
			if ($('.files')[i].files.length !== 0) {
				size = size + parseInt(($('.files')[i].files[0].size / 1024 / 1024).toFixed(2))
			}
		}
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
						console.log(result)
						$(".msg_valoracion").removeClass('hidden');
					}
					$('#form-valoracion-adv').find('.loader-container').fadeOut();
				},
				error: function (result) {

					$(".msg_valoracion").removeClass('hidden');
					$('#form-valoracion-adv').find('.loader-container').fadeOut();
				}
			});
		} else {
			$("#insert_msg").html(messages.error.max_size_img);
			$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
		}
	});

	$("#add_addres").change(function () {
		if ($(this).is(":checked")) {
			$(".add-addres").removeClass('hidden');
			$(".add-addres input").attr('required', true);
			$(".add-addres select").attr('required', true);
		} else {
			$(".add-addres").addClass('hidden');
			$(".add-addres input").attr('required', false);
			$(".add-addres select").attr('required', false);
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
	})

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

	//funcion para cerrar el video
	$('#modalVideo').on('hide.bs.modal', function (e) {
		let video = document.getElementById('elvideo');
		video.pause();
		video.currentTime = 0;
	});

	$('#searchLot').on('submit', (event) => {
		event.preventDefault();

		const reference = $("[name=reference]").val();
		if (!reference) {
			return;
		}

		let action = event.target.action;
		const goTo = action.replace(":ref", reference.trim())
		window.location = goTo;
	})

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


function payFactura(event) {
	event.stopPropagation();
	event.preventDefault();

	const showErrorMessage = () => {
		$("#modalMensaje #insert_msg").html('');
		$("#modalMensaje #insert_msg").html(messages.error.generic);
		$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
	}

	const form = event.target;
	$.ajax({
		type: "POST",
		url: form.action,
		data: $(form).serializeArray(),
		success: function (data) {
			if (data.status == 'success') {
				window.location.href = data.msg;
				return;
			}
			showErrorMessage();
		},
		error: showErrorMessage
	});
}


function modalVideo(video, ref, codSub, title, description, textButton, url) {
	addReproduccion(video, ref, codSub);
	$("#insert_msgweb").html('');
	$("#insert_msg_title").html('');
	let h3 = $('<h3>').html(title);
	let p = $('<p>').html(description);
	$("#insert_msg_title").append(h3, p);
	$("#insert_msgweb").html('<video width="100%" controls autoplay  id="elvideo" onplay="addReproduccion(\'' + video + '\',\'' + ref + '\',\'' + codSub + '\')"><source src="' + video + '" type="video/mp4"></video>');
	$('.button_modal_confirm').html(textButton);
	$('.button_modal_confirm').unbind('click');
	$('.button_modal_confirm').click(function (e) {
		location.href = url;
	});

	$.magnificPopup.open({ items: { src: '#modalMensajeWeb' }, type: 'inline' }, 0);

	$('.mfp-close').click(function (e) {
		$("#elvideo")[0].pause();
		$("#elvideo")[0].remove();
	});


}


function moreImagesGrid(ref_asigl0, cod_sub, num_hces1, lin_hces1, titulo, descripcion, textButton, url) {

	token = $("meta[name='csrf-token']").prop('content');
	let params = {
		_token: token,
		ref_asigl0: ref_asigl0,
		cod_sub: cod_sub,
		num_hces1: num_hces1,
		lin_hces1: lin_hces1,
		titulo: titulo,
		descripcion: descripcion
	};

	$.post("/subasta/modal_images", params, function (response) {

		$("#modalFotosGrid #insert_msgweb").html('');
		$("#modalFotosGrid #insert_msg_title").html('');
		$("#modalFotosGrid #insert_msgweb").html(response);

		$('#modalFotosGrid .button_modal_confirm').html(textButton);
		$('#modalFotosGrid .button_modal_confirm').off('click');
		$('#modalFotosGrid .button_modal_confirm').on('click', function (e) {
			location.href = url;
		});

		$("#modalFotosGrid #owl-carousel-responsive").owlCarousel({
			items: 1,
			autoplay: true,
			margin: 20,
			dots: true,
			nav: false,
			responsiveClass: true,
		});

		$.magnificPopup.open({ items: { src: '#modalFotosGrid' }, type: 'inline' }, 0);
	});
}

function moreImagesGridMobile(num_hces1, lin_hces1, page) {

	token = $("meta[name='csrf-token']").prop('content');
	let params = {
		_token: token,
		num_hces1: num_hces1,
		lin_hces1: lin_hces1,
		page: page
	};

	$.post("/subasta/modal_images_fullscreen", params, function (response) {
		$("body").append(response);
	});
}

function cerrarLogin() {
	$('.login_desktop').fadeToggle("fast");
}

var replaceTemp;
function ajax_carousel(key, replace, loteInicial) {
	//if ($("#" + key).html().length == 0) {
	$carrousel = $("#" + key);
	$carrousel.siblings().removeClass('hidden');
	$carrousel.parents().addClass('active');
	$.ajax({
		type: "POST",
		url: "/api-ajax/carousel",
		data: { key: key, replace: replace },
		success: function (result) {
			$carrousel.siblings('.loader').addClass('hidden');
			replaceTemp = replace;
			let loop = true;
			if (key == 'lotes_subasta') {
				result = `<a href="javascript:reloadAjaxCarousel('${key}', ${replaceTemp.ref_asigl0}, true);"><div class="item d-flex align-items-center justify-content-center"><p class="btn-carrousel btn-color">${messages.neutral.see_less}</p></div></a>` + result + `<a href="javascript:reloadAjaxCarousel('${key}', ${replaceTemp.ref_asigl0}, false);"><div class="item d-flex align-items-center justify-content-center"><p class="btn-carrousel btn-color">${messages.neutral.see_more}</p></div></a>`;
				loop = false;
			}


			$carrousel.html(result);
			carrousel_molon($carrousel, loteInicial, loop);
			$carrousel.animate({
				opacity: 1,
			}, 00)


			$('[data-countdown]').each(function () {
				$(this).data('ini', new Date().getTime());
				countdown_timer($(this))
			});
			if (key == 'lotes_destacados' && result == '') {
				$('.lotes_destacados').addClass('hidden');
				$('.mas_reciente a').click();
			}
		}

	});


	//}
};

function reloadAjaxCarousel(key, ref, prev) {

	let search = (prev ? -20 : 20) + ref;

	if (search <= minLotAuction) {
		search = minLotAuction - 1;
	}
	else if (search >= maxLotAuction) {
		return;
	}

	replaceTemp.ref_asigl0 = search;
	ajax_carousel(key, replaceTemp);
}

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


function carrousel_molon(carrousel, loteInicial, loop) {

	let dots = true;
	/*if(loteInicial != null){
		dots = false;
	}*/

	if (carrousel.data('hasSlick')) {
		carrousel.owlCarousel('destroy');
	}


	carrousel.owlCarousel({
		items: 3,
		merge: true,

		loop: loop,
		dots: dots,
		margin: 10,
		nav: true,
		navText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right ">'],


		responsive: {
			0: {
				items: 1
			},
			600: {
				items: 2
			},
			1000: {
				items: 3
			},
			1200: {
				items: 3
			},
		}
	});

	carrousel.data('hasSlick', true);

	if (loteInicial != null && $('.owl-item.active > .item').length > 0) {
		while ($('.owl-item.active > .item')[0].dataset.ref != loteInicial && parseInt($('.owl-item.active > .item')[0].dataset.ref) + 1 < loteInicial && !$('.owl-next').hasClass('disabled')) {
			$('.owl-next').trigger("click")
		}
	}
	$('.owl-next').trigger("click");
};

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



//@TODO:

function activate_account(lang) {

	var validate = true;

	if (!$("#conditions")[0].checked) {
		document.getElementById("error-check").style.display = 'block';
		validate = false;
	}
	else {
		document.getElementById("error-check").style.display = 'none';
	}

	if ($("#emailActivate")[0].value.trim().length == 0) {
		document.getElementById("error-recovery").style.display = 'block';
		validate = false;
	}
	else {
		document.getElementById("error-recovery").style.display = 'none';
	}

	if (validate) {
		document.getElementById("error-recovery").style.display = 'none';
		document.getElementById("error-check").style.display = 'none';

		var pass_recov = $("#password_recovery").serialize();
		$.ajax({
			type: "POST",
			url: '/' + lang + '/ajax-send-password-recovery',
			data: pass_recov,
			success: function (data) {
				document.getElementById("error-recovery").style.display = 'block';
				if (data.status == 'error') {
					$("#password_recovery").html(data.msg);
				} else if (data.status == 'succes') {
					$("#password_recovery").html(data.msg);
				}
			}
		});



	}

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

	$.magnificPopup.close();
	if (typeof cod_licit == 'undefined' || cod_licit == null) {
		$('#modalLogin').modal('show')
	} else {

		$.ajax({
			type: "GET",
			url: routing.favorites + "/" + action,
			data: { cod_sub: cod_sub, ref: ref, cod_licit: cod_licit },
			success: function (data) {
				//$('.button-follow').hide();
				//$('.button-follow-responsive').hide();


				if (data.status == 'error') {
					$("#insert_msg").html("");
					$("#insert_msg").html(messages.error[data.msg]);
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);

				} else if (data.status == 'success') {

					$("#insert_msg").html("");
					$("#insert_msg").html(messages.success[data.msg]);
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
					if (action == 'add') {

						$("a#add_fav").addClass('hidden');
						$("a#del_fav").removeClass('hidden');
						$(".add_fav-responsive").addClass('hidden');
						$(".del_fav-responsive").removeClass('hidden');
						$(".del_fav-responsive").find('i').addClass('active');


					} else {
						$("a#del_fav").addClass('hidden');
						$("a#add_fav").removeClass('hidden');
						$(".del_fav-responsive").addClass('hidden');
						$(".add_fav-responsive").removeClass('hidden');

					}


				}

			}

		});
	}

};

function action_fav_lote(action, ref, cod_sub, cod_licit) {


	if (typeof cod_licit == 'undefined' || cod_licit == null) {
		$('#modalLogin').modal('show')
	} else {
		routing.favorites = '/api-ajax/favorites';
		//$.magnificPopup.close();
		$('.' + ref + '-' + cod_sub).fadeOut();
		$.ajax({
			type: "GET",
			url: routing.favorites + "/" + action,
			data: { cod_sub: cod_sub, ref: ref, cod_licit: cod_licit },
			success: function (data) {

				if (data.status == 'error') {

					$("#insert_msg").html("");
					$("#insert_msg").html(messages.error[data.msg]);
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
					$('.' + ref + '-' + cod_sub).show();
				} else if (data.status == 'success') {

					$("#insert_msg").html("");
					$("#insert_msg").html(messages.success[data.msg]);
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
					$('.' + ref + '-' + cod_sub).remove();

					//Estrella del grid de lotes
					if ($(`.star-${ref}`).length && $(`.star-${ref}`).hasClass("fa")) {
						$(`.star-${ref}`).removeClass("fa");
						$(`.star-${ref}`).addClass("far");
						$(`.star-${ref}`).parent().attr('href', `javascript:action_fav_lote('add', ${ref}, '${cod_sub}', ${cod_licit})`);
					}
					else if ($(`.star-${ref}`).length) {
						$(`.star-${ref}`).removeClass("far");
						$(`.star-${ref}`).addClass("fa");
						$(`.star-${ref}`).parent().attr('href', `javascript:action_fav_lote('remove', ${ref}, '${cod_sub}', ${cod_licit})`);
					}



				}

			}
		});
	}

};

function change_price_saved_offers() {
	var precio = 0;
	$('input[name=order]').each(function () {
		precio = parseInt($(this).val()) + parseInt(precio);
	})
	$("#change_price").html('');
	$("#change_price").html(parseFloat(precio, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString());
}

function showMenuResponsive() {
	$('.menu.top-bar').addClass('active')
	$('.hamburguer i').hide();
	$('.hamburguer .close-menu').show();

}

function searchResponsive() {
	if ($('.search-bar-responsive').hasClass('show-search-responsive')) {
		$('.facebook-mobile').removeClass('hide')
		$('.search-img-lenguaje').removeClass('hide')
		$('.search-bar-responsive').removeClass('show-search-responsive')
		$('.search-img-mobile .fa.fa-times-circle').hide()
		$('.search-img-mobile .fa.fa-search').fadeIn()

	} else {
		$('.search-bar-responsive').addClass('show-search-responsive')
		$('.facebook-mobile').addClass('hide')
		$('.search-img-lenguaje').addClass('hide')
		$('.search-img-mobile .fa.fa-search').hide()
		$('.search-img-mobile .fa.fa-times-circle').fadeIn()
		$('.search-bar-responsive form input').focus()


	}
}


function closeMenuResponsive() {
	$('.menu.top-bar').removeClass('active')
	$('.hamburguer i').show();
	$('.hamburguer .close-menu').hide();
}


function letsClock() {
	$('.timer').appendTo('.timeLeft.clone')
}


function open_secondpanel() {
	$('.selectMyAcount').toggleClass('openactive')
}


function timeForAuctions() {

	if ($(window).width() > 991 < 1200) {
		$('.online-time-foot').css('width', '100%');
	}

	if ($('footer').offset() == 'undefined') {
		return;
	}

	if ($(window).width() > 1575) {
		if ($(document).scrollTop() > $('.lot-count').offset().top) {
			$('.online-time-foot')
				.css('position', 'fixed')
				.css('top', '0')
				.css('bottom', '')
				.css('width', $('.lot-count').width() + 'px')


		}
		if ($(document).scrollTop() < $('.lot-count').offset().top) {
			$('.online-time-foot').css('position', 'relative')
		}
	}
	if ($(window).width() < 1576 && $(window).width() > 600) {

		if ($(document).scrollTop() > $('.lot-count').offset().top) {
			$('.online-time-foot')
				.css('position', 'fixed')
				.css('bottom', '0')
				.css('top', '')
				.css('left', '0')
				.css('width', '200px')
				.css('z-index', '5')
		}
		if ($(document).scrollTop() < $('.lot-count').offset().top) {
			$('.online-time-foot').css('position', 'relative')
			$('.online-time-foot').css('width', '100%')
		}
	}
	if ($(window).width() < 600) {
		if ($(document).scrollTop() > ($('footer').offset().top - $('footer').height())) {
			$('.lot-count')
				.css('background', 'white')
				.css('color', '#283747')
			$('.online-time-foot').css('color', '#283747')
		}
		if ($(document).scrollTop() < ($('footer').offset().top - $('footer').height())) {
			$('.lot-count')
				.css('background', '#283747')
				.css('color', 'white')
			$('.online-time-foot').css('color', 'white')
		}
	}
}
function closeOpenModal() {
	if ($('.context-content').css('display') === 'none') {
		$('.context-content').fadeIn();
	} else {
		$('.context-content').fadeOut();
	}
}

function reload_carrito() {

	$.each(info_lots, function (index_sub, value_sub) {
		var precio_envio = 0;
		var sum_precio_envio = 0;
		var precio_final = 0;
		$.each(value_sub.lots, function (index, value) {
			precio_final = precio_final + value.himp + value.iva + value.base + value.licencia_exportacion;
			sum_precio_envio = sum_precio_envio + value.himp + value.base + value.iva + value.licencia_exportacion;
		});
		if (sum_precio_envio > 0) {
			$.ajax({
				type: "POST",
				async: false,
				url: '/api-ajax/gastos_envio',
				data: { 'precio_envio': sum_precio_envio, 'cod_sub': index_sub, 'clidd': $("input[name=clidd]:checked").val() },
				success: function (data) {
					precio_envio_express = data.imp + data.iva;
					precio_envio_min = data.imp_min + data.iva_min;
				}
			});
		}
		$(".gasto-envio-express-" + index_sub + "_JS").text(precio_envio_express.toFixed(2).replace(".", ","));

		if (precio_envio_min != 0) {
			$("label[for=shipping_express_min]").show();
			$(".gasto-envio-min-" + index_sub + "_JS").text(precio_envio_min.toFixed(2).replace(".", ","));
		}
		else {
			$("label[for=shipping_express_min]").hide();
			if ($("input[name=shipping]:checked").val() == "min") {
				$('#shipping_express').prop("checked", true).trigger('change');
			}
		}


		if ($("input[name=shipping]:checked").val() == "express") {
			precio_envio = precio_envio_express;
		}
		else if ($("input[name=shipping]:checked").val() == "min") {
			precio_envio = precio_envio_min;
		}
		else {
			precio_envio = 0;
		}

		$(".text-gasto-envio-" + index_sub).text(precio_envio.toFixed(2).replace(".", ","));
		$(".js-divisa.text-gasto-envio-" + index_sub).attr('value', precio_envio);
		precio_final = parseFloat(precio_final) + parseFloat(precio_envio);

		let gastosExtra = parseFloat(0);
		const paymethodsWithExtra = ["creditcard", "bizum"];
		if (paymethodsWithExtra.includes($("input[name=paymethod]:checked").val())) {
			gastosExtra = precio_final * 0.01;
		}

		precio_final = precio_final + gastosExtra;

		$(".text-gastos-extra-" + index_sub).text(gastosExtra.toFixed(2).replace(".", ","));
		$(".precio_final_" + index_sub).text(precio_final.toFixed(2).replace(".", ","));

		$(".js-divisa.precio_final_" + index_sub).attr('value', precio_final.toFixed(2));

		if (precio_final <= 0) {
			$('.submit_carrito[cod_sub="' + index_sub + '"]').attr("disabled", "disabled");
		} else {
			$('.submit_carrito[cod_sub="' + index_sub + '"]').removeAttr("disabled");
		}

	});
	$("#actual_currency").trigger("change");
}

function reload_carrito_pagados() {

	$.each(info_lots, function (index_sub, value_sub) {
		var precio_envio = 0;
		var sum_precio_envio = 0;
		var precio_final = 0;
		$.each(value_sub.lots, function (index, value) {
			precio_final = precio_final + value.himp + value.iva + value.base + value.licencia_exportacion;
			sum_precio_envio = sum_precio_envio + value.himp + value.base;
		});

		precio_final = parseFloat(precio_final) + parseFloat(precio_envio);
		$(".precio_final_" + index_sub).text(precio_final.toFixed(2).replace(".", ","));
		$(".js-divisa.precio_final_" + index_sub).attr('value', precio_final.toFixed(2));

		$("#actual_currency").trigger("change");

	});
}


function abrirNuevaVentana(parametros) {
	var url = parametros;
	var nuevaVentana = (window.open(url, $(this).attr('href'), "width=300", "height=300"));
	if (nuevaVentana) {
		nuevaVentana.focus();
	}
}

function specificModalEmailExist() {

	$.magnificPopup.open({ items: { src: '#modalActivateAccount' }, type: 'inline' }, 0);

}

//@TODO
function sendRecoveryPassword() {

	var email = $('#registerForm [name="email"]').val();
	var token = $("[name='_token']").val();
	var lang = $("html").attr("lang").split("-")[0].replace('”', '');

	$.ajax({
		type: "POST",
		url: '/' + lang + '/ajax-send-password-recovery',
		data: { email: email, _token: token },
		success: function (data) {

			$("#modalMensaje #insert_msg").html(data.msg);
			$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);

		}
	});


}



function loadVideoMobile(video) {

	var elem = document.getElementById(video);
	if (elem.requestFullscreen) {
		elem.requestFullscreen();
	} else if (elem.mozRequestFullScreen) {
		elem.mozRequestFullScreen();
	} else if (elem.webkitRequestFullscreen) {
		elem.webkitRequestFullscreen();
	} else if (elem.msRequestFullscreen) {
		elem.msRequestFullscreen();
	}
	elem.play();
}

function loadVideoMobileGrid(video, ref, sub) {

	addReproduccion(video, ref, sub);
	location.href = video;

}

function addReproduccion(video, ref, sub) {

	token = $("#token").val();

	$.post("/subasta/reproducciones", { _token: token, video: video, ref: ref, sub: sub }, function (data) {

		a = data.split("-");
		if (a[2] == 1) {
			$("#corazon").html('<i class="fa fa-heart red"></i>');
		}
		else {
			$("#corazon").html('<i class="fa fa-heart"></i>');
		}
		$("#reproducciones").html(a[0]);
		$("#megusta").html(a[1]);

	});
}

function loadVideo(video, ref, sub) {

	addReproduccion(video, ref, sub);
	actualiza_importes();
	$("#modalVideo").modal("show");
	$("#reproductor").html('');
	$("#reproductor").append('<video width="100%" controls autoplay  id="elvideo" onplay="addReproduccion(\'' + video + '\',\'' + ref + '\',\'' + sub + '\')"><source src="' + video + '" type="video/mp4"></video>');
	$("#modalVideo").find(".read-more").remove();
	$(".description").parent().css("height", "auto");
	setTimeout('readMore( $("#box2"), 2)', 1000);

}


function megusta(ref, sub) {

	token = $("#token").val();

	video = $("#reproductor").find("source").attr("src");

	$.post("/subasta/megusta", { _token: token, video: video, ref: ref, sub: sub }, function (data) {

		a = data.split("-");
		if (a[1] == 1) {
			$("#corazon").html('<i class="fa fa-heart red"></i>');
		}
		else {
			$("#corazon").html('<i class="fa fa-heart"></i>');
		}
		$("#megusta").html(a[0]);

	});

}

function actualiza_importes() {
	$(".actualizable").html($(".origenactualizable").html());
	total_pujas = 0;
	pujadores = new Array();

	$("#pujas_list .hist_item").each(function (key, value) {
		total_pujas = total_pujas + 1;
		a = $(this).find(".uno").html();
		if (a != "") {
			pujadores[a] = 1;
		}
	});
	$(".tot_pujas").html(total_pujas);
	if (pujadores.length > 0) {
		$(".total_postores").html(pujadores.length - 1);
	}
	else {
		$(".total_postores").html(pujadores.length);
	}
	setTimeout("actualiza_importes()", 3000);
}

function viewVideoBtnEvents() {
	$('.timeLeft .btn.view-video').unbind('mouseenter mouseleave');
	$('.timeLeft .btn.view-video').mouseenter(function (e) {
		$(this).children(".video-text").hide().show("slide", { direction: "left" }, 500);
		e.stopPropagation();
	});
	$('.timeLeft .btn.view-video').mouseleave(function (e) {
		$(this).children(".video-text").hide("slide", { direction: "left" }, 500, function () {
			$(this).clearQueue();
		});
		e.stopPropagation();
	});
}

function newsletterSuscription(event) {
	var email = $('.newsletter-input').val();
	var lang = $('#lang-newsletter').val();

	if (!$('#condiciones').prop("checked")) {
		$("#insert_msgweb").html('');
		$("#insert_msgweb").html(messages.neutral.accept_condiciones);
		$.magnificPopup.open({ items: { src: '#modalMensajeWeb' }, type: 'inline' }, 0);
		return;
	}

	const newsletters = {};
	document.querySelectorAll(".js-newletter-block [name^=families]").forEach((element) => {
		if (element.checked) {
			newsletters[`families[${element.value}]`] = '1';
		}
	});

	const data = {
		email,
		lang,
		condiciones: 1,
		...newsletters
	}

	addNewsletter(data);
}

function newsletterFormSuscription(event) {
	event.preventDefault();

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

/**
 * Se utiliza para el las miniaturas del listado de lotes
 * No eliminar la variable.
 */
const lightboxs = [];

function selectGaleryMiniature(idx, imgcontainer) {
	imgcontainer.find('.image-selector .micro-image').removeClass('selected');
	imgcontainer.find('.image-selector').eq(idx).find('.micro-image').addClass('selected');
}

function deselectAllGaleryMiniature(imgcontainer) {
	imgcontainer.find('.image-selector .micro-image').removeClass('selected');
}

function moveMiniatureScroll(idx, imgcontainer) {
	const image = imgcontainer.find('.image-selector').eq(idx);
	const scroll = image.position().left - (imgcontainer.width() / 2) + (image.width() / 2);
	imgcontainer.animate({
		scrollLeft: scroll
	}, 'fast');
}

function openLotGallery(num_hces1, lin_hces1) {
	lightboxs.find(lightbox => lightbox.num_hces1 === num_hces1 && lightbox.lin_hces1 === lin_hces1).instance.loadAndOpen(0);
}

$(function () {

	lightboxs.forEach(lightbox => {

		const num_hces1 = lightbox.num_hces1;
		const lin_hces1 = lightbox.lin_hces1;
		const $container = $(`.image-lot-miniature-container-${num_hces1}-${lin_hces1}`)

		lightbox.instance.init();

		lightbox.instance.on('beforeOpen', () => {
			selectGaleryMiniature(lightbox.instance.pswp.currIndex, $container);
			$container.css('align-items', 'flex-end');
			$container.fadeIn(400, function () {
				$(this).css('display', 'flex');
			});
		});

		lightbox.instance.on('afterChange', () => {
			selectGaleryMiniature(lightbox.instance.pswp.currIndex, $container);
			moveMiniatureScroll(lightbox.instance.pswp.currIndex, $container);
		});

		lightbox.instance.on('close', () => {
			deselectAllGaleryMiniature($container);
			$container.slideUp(400);
			$container.css('align-items', 'initial');
		});

		$container.find('.image-selector').click(function () {
			lightbox.instance.pswp.goTo($(this).data('key-image'));
		});


	});

});
