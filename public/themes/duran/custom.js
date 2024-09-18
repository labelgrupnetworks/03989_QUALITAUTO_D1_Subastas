function verifyLang(actualLang) {

	//comprobamos si el idoma actual de label a cambiado
	if (actualLang !== sessionStorage.getItem('lang')) {

		sessionStorage.setItem('lang', actualLang)
		var iframe = document.getElementsByClassName('goog-te-banner-frame')[0];

		if (!iframe) return;


		//seteamos el nuevo idoma
		//nativo de label
		var innerDoc = iframe.contentDocument || iframe.contentWindow.document;
		var restore_el = innerDoc.getElementsByTagName("button");

		for (var i = 0; i < restore_el.length; i++) {
			if (restore_el[i].id.indexOf("restore") >= 0) {
				restore_el[i].click();
				var close_el = innerDoc.getElementsByClassName("goog-close-link");
				close_el[0].click();
				return;
			}
		}
	}
}


/* he pasado la funcion al common 26-05-21
function openLogin(){
	//por si venimos de un moda, no afecta a nada mas
	$.magnificPopup.close();
	$('.login_desktop').fadeToggle("fast");
	$('.login_desktop [name=email]').focus();
}
*/
function seeMoreInfo(){
	$('.info-lot-extra').toggle('slow');
	$('.seemore').hide();
	$('.seeless').show();
}

function seeLessInfo(){
	$('.info-lot-extra').toggle('slow');
	$('.seemore').show();
	$('.seeless').hide();
}

function newsletterDay(){
	// First check, if localStorage is supported.
	if (window.localStorage) {

		var nextPopup = localStorage.getItem('nextNewsletter');

		if (new Date(nextPopup) > new Date()) {
			return;
		}

		var expires = new Date();
		expires = new Date(expires.getFullYear(), expires.getMonth(), expires.getDate() + 1);

		localStorage.setItem('nextNewsletter', expires);
	}

	let newsletterDiv = $('.newsletter').clone();
	$('#modalAjax .modal-title').html('');
	$('#modalAjax .modal-body').html(newsletterDiv);
	$('#modalAjax').modal('toggle');

	$('#modalAjax .newsletter-input').focus(function () {
		$('#modalAjax .newsletter-placeholder').fadeOut()
	});

	$('#modalAjax .newsletter-input').focusout(function () {
		if ($(this).val().length > 0) {
			$('#modalAjax .newsletter-placeholder').hide()
		} else {
			$('#modalAjax .newsletter-placeholder').show()
		}
	});

	$('#modalAjax #newsletter-btn').on('click', newsletterSuscriptionFromModal);
}

function updateWalletInfo(event) {
	event.preventDefault();

	const walletForm = event.target;

	$.ajax({
		type: "POST",
		data: $(walletForm).serialize(),
		url: '/api-ajax/wallet/update',
		dataType: 'json',
		beforeSend: function () {
			walletForm.querySelector('button[type="submit"]').disabled = true;
		 },
		 success: function (response) {
			const alertClass = response.status == 'success' ? 'alert-success' : 'alert-danger';
			const htmlResult = `<div class="alert ${alertClass}">${response.message}</div>`;
			document.getElementById('wallet-call-result').innerHTML = htmlResult;
		 },
		 error: function (xhr, status) {
			const htmlResult = `<div class="alert alert-danger">${xhr.responseJSON.message}</div>`
			document.getElementById('wallet-call-result').innerHTML = htmlResult;
		 },
		 complete: function () {
			walletForm.querySelector('button[type="submit"]').removeAttribute('disabled');
		 }
	})

}

function createWallet(event) {
	event.preventDefault();
	console.log(event.target);
}



$(document).ready(function () {

	$('#save-wallet').on('submit', updateWalletInfo);
	$('#create-wallet').on('click', createWallet);

	$("#pujar_orden_telefonica_duran").on("click" ,function() {

		if (typeof cod_licit == 'undefined' || cod_licit == null )
	{
		$("#insert_msg_title").html("");
		$("#insert_msg").html(messages.error.mustLogin);
		$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);

	}else{
		$("#numLotPhoneBid").html(ref);
		$("#phone1Bid_JS").val(auction_info.user.phone1);
		$("#phone2Bid_JS").val(auction_info.user.phone2);
		$("#commentsBid_JS").val("");
		$.magnificPopup.open({items: {src: '#modalOrdenTelefonica'}, type: 'inline'}, 0);
	}

	});
	$("#confirm_orden_telefonica").on("click",function () {



		$.magnificPopup.close();
		$.ajax({
			type: "POST",
			url: "/ordenTelefonica",
			data: {
				cod_sub: cod_sub,
				ref: ref,
				comments:  $("#commentsBid_JS").val(),
				tel1: $("#phone1Bid_JS").val(),
				tel2: $("#phone2Bid_JS").val(),
				_token: $("input[name=_token]").val()
			},
			success: function (data) {
				if (data.status == 'error') {
					$("#insert_msg").html(messages.error["phoneOrder"]);
				} else if (data.status == 'success') {
					$("#insert_msg").html(messages.success["phoneOrder"]);
				}

				$("#insert_msg_title").html("");
				$.magnificPopup.open({
					items: {
						src: '#modalMensaje'
					},
					type: 'inline'
				}, 0);
			}
		});

	});



	$('.bloques-footer').slick({
		slidesToShow: 4,
		slidesToScroll: 1,
		arraws: false,
		responsive: [
		  {
			breakpoint: 768,
			settings: {
			  slidesToShow: 2,
			  slidesToScroll: 2,
			  infinite: true,
			  dots: true,
			  arrows: false,
			}
		  }
		  // You can unslick at a given breakpoint now by adding:
		  // settings: "unslick"
		  // instead of a settings object
		]
	});

	/**
	 * Leer más descripción en ficha de lote
	 */
	if ($('.read-more').length) {

		if ($('.single-lot-desc-content').get(0).scrollHeight > 240) {
			$('.read-more').show();
		}

		$('.read-more').click(function () {
			readMore('.single-lot-desc-content');
		})
		$('.read-less').click(function () {
			readLess('.single-lot-desc-content');
		})
	}
	function readMore(elementSelector) {
		$('.read-more').hide();
		$('.read-less').show();
		$(elementSelector).animate({ maxHeight: $(elementSelector).get(0).scrollHeight }, 1000);
	}
	function readLess(elementSelector) {
		$(elementSelector).animate({ maxHeight: '240px' }, 1000);
		$('.read-less').hide();
		$('.read-more').show();
	}

	setTimeout(function () {
		$('body').addClass('loaded');
	}, 3000);

	$('.panel-collapse').on('show.bs.collapse', function () {
		var id = $(this).attr('id')
		console.log($(this))
		$(this).siblings('.user-accounte-titles-link[data-id="' + id + '"]').find('.label-close').show()
		$(this).siblings('.user-accounte-titles-link[data-id="' + id + '"]').find('.label-open').hide()
	})
	$('.panel-collapse').on('hide.bs.collapse', function () {
		var id = $(this).attr('id')
		$(this).siblings('.user-accounte-titles-link[data-id="' + id + '"]').find('.label-close').hide()
		$(this).siblings('.user-accounte-titles-link[data-id="' + id + '"]').find('.label-open').show()
	})


	//Reestylin google tranlate
	$('#google_translate_element').on("click", function () {

		// Change font family and color
		$("iframe").contents().find(".goog-te-menu2-item div, .goog-te-menu2-item:link div, .goog-te-menu2-item:visited div, .goog-te-menu2-item:active div, .goog-te-menu2 *")
			.css({
				'color': '#544F4B',
				'font-family': 'Roboto',
				'width': '100%'
			});
		// Change menu's padding
		$("iframe").contents().find('.goog-te-menu2-item-selected').css('display', 'none');

		// Change menu's padding
		$("iframe").contents().find('.goog-te-menu2').css('padding', '0px');

		// Change the padding of the languages
		$("iframe").contents().find('.goog-te-menu2-item div').css('padding', '20px');

		// Change the width of the languages
		$("iframe").contents().find('.goog-te-menu2-item').css('width', '100%');
		$("iframe").contents().find('td').css('width', '100%');

		// Change hover effects
		$("iframe").contents().find(".goog-te-menu2-item div").hover(function () {
			$(this).css('background-color', '#4385F5').find('span.text').css('color', 'white');
		}, function () {
			$(this).css('background-color', 'white').find('span.text').css('color', '#544F4B');
		});

		// Change Google's default blue border
		$("iframe").contents().find('.goog-te-menu2').css('border', 'none');

		// Change the iframe's box shadow
		$(".goog-te-menu-frame").css('box-shadow', '0 16px 24px 2px rgba(0, 0, 0, 0.14), 0 6px 30px 5px rgba(0, 0, 0, 0.12), 0 8px 10px -5px rgba(0, 0, 0, 0.3)');



		// Change the iframe's size and position?
		$(".goog-te-menu-frame").css({
			'height': '100%',
			'width': '100%',
			'top': '0px'
		});
		// Change iframes's size
		$("iframe").contents().find('.goog-te-menu2').css({
			'height': '100%',
			'width': '100%'
		});
	});




	$('#button-open-user-menu').click(function () {
		$('#user-account-ul').toggle()
	})
	$('.newsletter-input').focus(function () {
		$('.newsletter-placeholder').fadeOut()
	});
	$('.newsletter-input').focusout(function () {
		if ($(this).val().length > 0) {
			$('.newsletter-placeholder').hide()
		} else {
			$('.newsletter-placeholder').show()
		}
	});

	$('.lazy').Lazy({
		effect: 'fadeIn',
		effectTime: 500,
		visibleOnly: false,
		onError: function (element) {
			console.log('error loading ' + element.data('src'));
		},
		afterLoad: function (element) {
			$(element).show()
			$('.text-input__loading--line').hide()
		},
	});

	$('.user-account').hover(function () {
		$(this).find('.mega-menu').show()
	}, function () {
		$(this).find('.mega-menu').hide()

	})

	$('.search-header').click(function () {
		$('#formsearchResponsive input').focus()
		$('.menu-principal-search').addClass('active')
		$('.search-header-close').css('left', '0')
		$('.search-header-close').addClass('bounceInLeft')


	})

	$('.search-header-close').click(function () {
		$('.menu-principal-search').removeClass('active')
		$('.search-header-close').css('left', '-90px')
		$('.search-header-close').removeClass('bounceInLeft')
	})


	$('.menu-responsive').click(function () {
		$('.menu-principal').addClass('open')
	})

	$('.close-menu-reponsive').click(function () {
		$('.menu-principal').removeClass('open')
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

	//recargar toda la suma de los lotes seleccionados


	$('#owl-carousel').fadeIn('fast');


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

	var y = $(this).scrollTop();
	var nav2 = $('.bloque-fijo');
	var nav3 = $('.bloque-fijo-grid');

	$(document).scroll(function (e) {
		if ($(document).scrollTop() > 33) {
			$('header').addClass('fixed w-100 top-0 left-0');

			if(screen.width > 992){
				nav2.css('padding-top', "80px");
			}
		}
		if ($(document).scrollTop() > 120) {
			nav3.css('padding-top', "60px");
		}
		if ($(document).scrollTop() <= 33) {
			$('header').removeClass('fixed w-100 top-0 left-0');
			nav2.css('padding-top', "0px");

		} else if ($(document).scrollTop() <= 120) {
			nav3.css('padding-top', "0px");
		}

		if ($(document).scrollTop() > 100) {
			$('.button-up').show(500)
		}
		if ($(document).scrollTop() <= 100) {
			$('.button-up').hide(500)
		}
	})


	$('.button-up').click(function () {

		$('html,body').animate({
			scrollTop: 0
		}, 500);
	});

	$("#owl-carousel").owlCarousel({
		items: 1,
		loop: true,
		autoplay: true,
		stagePadding: 0,
		margin: 0,
		dots: true,
		nav: false,
		animateOut: 'fadeOut',
		slideTransition: 'ease',
		navText: ['<img src="/themes/duran/img/back.svg">', '<img src="/themes/duran/img/next.svg">'],
	});

	$(".owl-carousel-home").owlCarousel({
		items: 4,
		loop: true,
		autoplay: true,
		margin: 20,
		padding: 20,
		dots: false,
		nav: true,
		responsiveClass: true,
		navText: ['<img src="/themes/duran/img/back.svg">', '<img src="/themes/duran/img/next.svg">'],
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
		nav: true,
		responsiveClass: true,
		navText: ['<img src="/themes/duran/img/back.svg">', '<img src="/themes/duran/img/next.svg">'],
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



	$(".owl-carousel-banners").owlCarousel({
		items: 2,
		autoplay: false,
		margin: 60,
		dots: true,
		nav: false,
		loop: true,
		mouseDrag: false,
		touchDrag: true,
		checkVisible: false,
		navText: ['<img src="/themes/duran/img/back.svg">', '<img src="/themes/duran/img/next.svg">'],
		responsiveClass: true,
		responsive: {
			0: {
				items: 1
			},
			600: {
				items: 1
			},
			1000: {
				items: 2
			},
			1200: {
				items: 2
			},
		}
	});

	$(".owl-carousel-home-3").owlCarousel({
		items: 4,
		autoplay: false,
		margin: 30,
		dots: true,
		nav: false,
		loop: true,
		mouseDrag: false,
		touchDrag: true,
		navText: ['<img src="/themes/duran/img/back.svg">', '<img src="/themes/duran/img/next.svg">'],
		checkVisible: false,
		responsiveClass: true,
		responsive: {
			0: {
				items: 1
			},
			600: {
				items: 1
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
		nav: true,
		responsiveClass: true,
		navText: ['<img src="/themes/duran/img/back.svg">', '<img src="/themes/duran/img/next.svg">'],
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
	$('.btn_login').on('click', function () {
		openLogin();
	});
	$('.closedd').on('click', function () {
		$('.login_desktop').fadeToggle("fast");
	});




	$("#accerder-user").click(function () {

		$(this).addClass('loadbtn')
		$('.login-content-form').removeClass('animationShaker')
		$.ajax({
			type: "POST",
			url: '/login_post_ajax',
			data: $('#accerder-user-form').serialize(),
			success: function (response) {
				if (response.status == 'success') {

					if ($('#presta').val() == 1 && Boolean($('#back').val())) {
						externalLogin(response.context_url, response.data);
						return;
					}

					if($('#logo_link').length > 0){
						setTimeout(function(){ document.getElementById("logo_link").click(); }, 500);
					}
					else{
						setTimeout(function(){  window.location=window.location; }, 500);
					}

				} else {
					$(".message-error-log").text('').append(messages.error[response.msg]);
					$("#accerder-user").removeClass('loadbtn')
					$('.login-content-form').addClass('animationShaker')
				}


			}


		});
	});

	$('.btn-custom-search').click(function () {
		$('.btn-custom-search').addClass('loadSearch')
	})

	//Iguala contenido de un formulario divididos por contenedores div
	function equalsForm(form) {
		var inputs = form.find('input')
		var select = form.find('select')
		$(inputs).each(function () {
			var ref = $(this).attr('data-like')
			$(this).val($(ref).val())
		})
		$(select).each(function () {
			var ref = $(this).attr('data-like')
			$(this).val($(ref).val())
		})
	}


	//verifica si las password son identicas
	function validatePass() {
		if ($('#password').val() !== $('#confirmcontrasena').val()) {
			$('input[name="password"]').parent().addClass('has-error')
			$('#confirmcontrasena').parent().addClass('has-error')
			$('#error-confirm-pass').show()
			return false
		} else {
			$('#error-confirm-pass').hide()
			return true
		}
	}


	//verifica si los emails son identicos
	function validateEmail() {
		if ($('#email').val() !== $('#emailconfirm').val()) {
			$('#email').parent().addClass('has-error')
			$("#emailconfirm").parent().addClass('has-error')
			$('#error-confirm-email').show()
			return false
		} else {
			$('#error-confirm-email').hide()
			return true
		}
	}

	//Validamos pagina uno del formulario
	$('#create-user').click(function () {
		$('#error-address').hide()
		var validate = ''
		if ($('#second-address').is(':checked')) {
			equalsForm($('#second-address-content'))
		}
		validate = validateFrom($('.personal-info'), true)
		validate = validateFrom($('.create-account-address-container'), false, true)
		if (validate === 'error') {
			$('#error-address').show()
		} else {
			if ($('#condiciones').is(':checked')) {
				$('#frmRegister-adv').submit()
			} else {
				$('#condiciones').siblings('span').addClass('text-danger')
				$('#condiciones').siblings('span').find('a').addClass('text-danger')
			}
		}


	})


	$('#condiciones').change(function () {
		$('#condiciones').siblings('span').removeClass('text-danger')
		$('#condiciones').siblings('span').find('a').removeClass('text-danger')
	})

	function validateFrom(form, hasEmailPass, hasSelect) {
		var inputs = form.find('.form-group')
		var valError = '';
		$(inputs).each(function (index, el) {
			$(this).removeClass('has-error')
			if ($(this).find('input').attr('type') === 'text' || $(this).find('input').attr('type') === 'password' || $(this).find('input').attr('type') === 'email') {
				if (!$(this).hasClass('hidden')) {
					if ($(this).find('input').val().length === 0) {
						$(this).addClass('has-error')
						valError = 'error'
					}
				}

			}
		})

		if (hasSelect) {
			var select = form.find('select')
			$(select).each(function (index, el) {
				$(this).removeClass('has-error')
				if ($(this).val().length === 0) {
					$(this).parent().addClass('has-error')
					valError = 'error'
				}
			})
		}

		if (hasEmailPass) {
			if (!$('#erroremail').hasClass('hidden')) {
				$("#insert_msgweb").html($("#erroremail").html());
				$.magnificPopup.open({
					items: {
						src: '#modalMensajeWeb'
					},
					type: 'inline'
				}, 0);
				valError = 'error'
			} else if (!validateEmail()) {
				$("#insert_msgweb").html($("#error-confirm-email").html());
				$.magnificPopup.open({
					items: {
						src: '#modalMensajeWeb'
					},
					type: 'inline'
				}, 0);
				valError = 'error'
			} else if ((!validatePass())) {
				$("#insert_msgweb").html($("#error-confirm-pass").html());
				$.magnificPopup.open({
					items: {
						src: '#modalMensajeWeb'
					},
					type: 'inline'
				}, 0);
				valError = 'error'
			}



		}
		return valError
	}



	$('#second-address').change(function () {
		if ($('#second-address-content').css('display') === 'none') {
			$('#second-address-content').fadeIn()
		} else {
			$('#second-address-content').hide()
		}
		equalsForm($('#second-address-content'))
	})


	$('#frmRegister-adv').validator().on('submit', function (e) {
		if (e.isDefaultPrevented()) {
			// formulario incorrecto
			var text = $(".error-form-validation").html();
			$("#insert_msgweb").html('');
			$("#insert_msgweb").html(text);
			$.magnificPopup.open({
				items: {
					src: '#modalMensajeWeb'
				},
				type: 'inline'
			}, 0);
		} else {
			e.preventDefault();
			var $this = $(this);
			$(this).validator('validate');
			has_errors = verifyFormLoginContent();

			if (has_errors) {
				$("#insert_msgweb").html('');
				$("#insert_msgweb").html(messages.error.generic);
				$.magnificPopup.open({
					items: {
						src: '#modalMensajeWeb'
					},
					type: 'inline'
				}, 0);
			} else if ($("#frmRegister-adv input#dni").parent().hasClass("has-error")) {
				$("#insert_msgweb").html('');
				$("#insert_msgweb").html(messages.error.dni_incorrect);
				$.magnificPopup.open({
					items: {
						src: '#modalMensajeWeb'
					},
					type: 'inline'
				}, 0);
			} else {
				$('button', $this).attr('disabled', 'disabled');
				// Datos correctos enviamos ajax
				$.ajax({
					type: "POST",
					url: routing.registro,
					data: $('#frmRegister-adv').serialize(),
					beforeSend: function () {
						$('#btnRegister').prepend(' <i class="fa fa-spinner fa-pulse fa-fw margin-bottom"></i> ');
					},
					success: function (response) {
						$('button', $this).attr('disabled', false);
						res = jQuery.parseJSON(response);
						if (res.err == 1) {
							$("#insert_msgweb").html('');
							$("#insert_msgweb").html(messages.error[res.msg]);
							$.magnificPopup.open({
								items: {
									src: '#modalMensajeWeb'
								},
								type: 'inline'
							}, 0);
						} else {
							window.location.href = res.msg;
						}

					}
				});
			}


		}

	});
	$('.eventoTelefono_JS').on('click', function () {
		gtag('event','Clic',{'event_category':'Telefono'});
	});

	$('.descargaPDF_JS').on('click', function () {
		registerSeoEvent("DESCARGA_CATALOGO");
		gtag('event','Clic',{'event_category':'Descarga_Catalogo'});
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

		}
	});



	$("#confirm_orden").click(function () {

		imp = $("#bid_modal_pujar").val();

		$.magnificPopup.close();
		$.ajax({
			type: "POST",
			url: routing.ol + '-' + cod_sub,
			data: {
				cod_sub: cod_sub,
				ref: ref,
				imp: imp

			},
			success: function (data) {
				if (data.status == 'error') {

					$("#insert_msg_title").html("");
					$("#insert_msg").html(data.msg_1);
					$.magnificPopup.open({
						items: {
							src: '#modalMensaje'
						},
						type: 'inline'
					}, 0);
				} else if (data.status == 'success') {
					$("#tuorden").html(data.imp);
					$(".delete_order").removeClass("hidden");
					$("#text_actual_no_bid").addClass("hidden");
					$("#text_actual_max_bid").removeClass("hidden");
					$("#actual_max_bid").html(data.open_price);
					$("#insert_msg_title").html("");
					$("#insert_msg").html(data.msg);
					$(".hist_new").removeClass("hidden");
					$(".custom").removeClass("hidden");
					$("#bid_modal_pujar").val(data.imp_actual);
					gtag('event','click',{'event_category':'puja'});
					if (data.winner) {
						$("#max_bid_color").addClass("winner");
						$("#max_bid_color").removeClass("no_winner");
					} else {
						$("#max_bid_color").removeClass("winner");
						$("#max_bid_color").addClass("no_winner");
					}
					$.magnificPopup.open({
						items: {
							src: '#modalMensaje'
						},
						type: 'inline'
					}, 0);
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
			data: {
				cod_sub: cod_sub,
				ref: ref,
				imp: imp
			},
			success: function (data) {
				if (data.status == 'error') {

					$("#insert_msg_title").html("");
					$("#insert_msg").html(data.msg_1);
					$.magnificPopup.open({
						items: {
							src: '#modalMensaje'
						},
						type: 'inline'
					}, 0);
				} else if (data.status == 'success') {
					$("#insert_msg_title").html("");
					$("#insert_msg").html(data.msg);
					$.magnificPopup.open({
						items: {
							src: '#modalMensaje'
						},
						type: 'inline'
					}, 0);
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


			/************METODO NUEVO PARA MOSTRAR INICIO DE SESION CON LINK**********************************/
			$("#insert_msg_login_required").html("");
			$("#insert_msg_login_required").html(messages.error.login_required);
			$("#insert_msg_log_in").html("");
			$("#insert_msg_log_in").html(messages.error.log_in);
			$("#insert_msg").html("");
			$("#insert_msg").html(messages.error.register_required);

            /**************METODO ORIGINAL**********************************
             * @OJO para utilizar, es necesario cambiar también en modals.blade el div con id "modalMensaje"
             *
             * $("#insert_msg").html("");
             * $("#insert_msg").html(messages.error.mustLogin);
             *
             */


			$.magnificPopup.open({
				items: {
					src: '#modalMensaje'
				},
				type: 'inline'
			}, 0);
		} else {
			$.magnificPopup.open({
				items: {
					src: '#modalComprarFicha'
				},
				type: 'inline'
			}, 0);
		}

	});


	$("#save_change_orden").click(function () {

		var cod_sub = $(this).attr('cod_sub');
		var ref = $(this).attr('ref');
		var order = $(this).attr('order');
		$.ajax({
			type: "POST",
			url: url_orden + '-' + cod_sub,
			data: {
				cod_sub: cod_sub,
				ref: ref,
				imp: order
			},
			success: function (res) {
				if (res.status == 'success') {
					$("#modalMensaje #insert_msg").html('');
					$("#modalMensaje #insert_msg").html(res.msg);
					$.magnificPopup.open({
						items: {
							src: '#modalMensaje'
						},
						type: 'inline'
					}, 0);
					change_price_saved_offers();
				} else {
					$("#modalMensaje #insert_msg").html('');
					$("#modalMensaje #insert_msg").html(res.msg_1);
					$.magnificPopup.open({
						items: {
							src: '#modalMensaje'
						},
						type: 'inline'
					}, 0);
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

			$.magnificPopup.open({
				items: {
					src: '#changeOrden'
				},
				type: 'inline'
			}, 0);
		}
	});


	$("#form-valoracion-adv").submit(formValoracionHandleSubmit);

	//eliminar ordenes	desde el panel de pujas
	$(".confirm_delete_order").click(function () {
		var ref = $(this).attr("ref");
		var sub = $(this).attr("sub");
		$.magnificPopup.close();
		$.ajax({
			type: "POST",
			url: '/api-ajax/delete_order',
			data: {
				ref: ref,
				sub: sub
			},
			success: function (response) {
				res = jQuery.parseJSON(response);
				if (res.status == 'success') {
					location.reload();

				} else {
					if ($(res.respuesta).empty()) {
						$("#" + res.respuesta + " .form-group-custom input").addClass("has-error-custom");
					}
					$("#insert_msg").html(messages.error[res.msg]);
					$.magnificPopup.open({
						items: {
							src: '#modalMensaje'
						},
						type: 'inline'
					}, 0);
				}

			}

		});
	});

	$(".delete_order_panel").click(function () {
		var ref = $(this).attr("ref");
		var sub = $(this).attr("sub");
		$(".confirm_delete_order").attr("price", $("#" + sub + "-" + ref + " input").val());
		$(".confirm_delete_order").attr("ref", ref);
		$(".confirm_delete_order").attr("sub", sub);
		$("#msg_delete_order").html(messages.neutral.confirm_delete_order);
		$.magnificPopup.open({
			items: {
				src: '#modalMensajeDeleteOrder'
			},
			type: 'inline'
		}, 0);
	});




	/*
		$('.content_item_mini').hover(function(e) {

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
				if ($(document).scrollTop() > 200) {

				}

				var newPosTop = -400 + ($(document).scrollTop());

				capaOculta.css("top", '-400px');

			}


		}, function() {
			var capaOculta = $(this).siblings($('.capaOculta'))
			capaOculta.hide()
		})
	*/
	/*
		var posicb = $(".bread-content").offset();
		fichablockiz = $(".ficha-left");
		fichablockiz.css('padding-left', posicb.left + "px");
	*/

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

function externalLogin(context, data){
	const form = document.createElement('form');
	const url = new URL(context);

	form.method = 'POST';
	form.action = `${url.origin}/api-ajax/external-login?back_to=${url.href}`;

	const input = document.createElement('input');
	input.type = 'hidden';
	input.name = 'valoresPresta';
	input.value = JSON.stringify(data);

	form.appendChild(input);
	document.body.appendChild(form);
	form.submit();
}


function cerrarLogin() {
	$('.login_desktop').fadeToggle("fast");
}

function ajax_carousel(key, replace) {
	//$( "#"+key ).siblings().removeClass('hidden');
	$.ajax({
		type: "POST",
		url: "/api-ajax/carousel",
		data: {
			key: key,
			replace: replace
		},
		success: function (result) {

			if (result === '') {
				$("#" + key + '-content').hide()
			}
			$("#" + key).siblings('.loader').addClass('hidden');
			$("#" + key).html(result);
			if (key === 'lotes_destacados') {



				carrousel_molon($("#" + key));

			} else {
				carrousel_molon_new($("#" + key));
			}
			$('.lazy').Lazy({
				// your configuration goes here
				scrollDirection: 'vertical',
				effect: 'fadeIn',
				effectTime: 100,
				visibleOnly: true,
				onError: function (element) {
					console.log('error loading ' + element.data('src'));
				},
				afterLoad: function (element) {
					$('.text-input__loading--line').hide()
				},
			});
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

function ajax_newcarousel(key, replace, lang) {
	//$( "#"+key ).siblings().removeClass('hidden');
	$.ajax({
		type: "POST",
		url: "/api-ajax/newcarousel",
		data: { key: key, replace: replace, lang: lang },
		success: function (result) {

			if (result === '') {
				$("#" + key + '-content').hide()
			}
			$("#" + key).siblings('.loader').addClass('hidden');
			$("#" + key).html(result);
			//cargar cuenta atras
			$('[data-countdown]').each(function (event) {

				var countdown = $(this);
				countdown.data('ini', new Date().getTime());
				countdown_timer(countdown);


			});
			if (key === 'lotes_destacados') {
				carrousel_molon($("#" + key));
			} else {
				carrousel_molon_new($("#" + key));
			}

		}

	});

};




function carrousel_molon(carrousel) {
	carrousel.owlCarousel({
		items: 8,
		autoplay: true,
		margin: 0,
		dots: false,
		nav: true,
		navText: ['<img src="/themes/duran/img/back.svg">', '<img src="/themes/duran/img/next.svg">'],
		responsiveClass: true,
		responsive: {
			0: {
				items: 1
			},
			600: {
				items: 2
			},
			991: {
				items: 3
			},
			1200: {
				items: 3
			}
		}
	});
};

/*
function carrousel_molon_new(carrousel) {
    carrousel.owlCarousel({
        items: 8,
        autoplay: true,
        margin: 0,
        dots: false,
        nav: true,
        navText: ['<img src="themes/duran/img/back.svg">', '<img src="themes/duran/img/next.svg">'],
        responsiveClass: true,
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
            }
        }
    });
};
*/

function carrousel_molon_new(carrousel) {
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
		rows: rows,
		/*slidesPerRow: 4,*/
		slidesToShow: 3,
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
				}
			},
			{
				breakpoint: 600,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2,
					rows: 1,
					slidesPerRow: 2,

				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					rows: 1,
					slidesPerRow: 1,
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

	var formatted = $.datepicker.formatDate("dd ", fecha) + mes + " " + $.datepicker.formatDate("yy", fecha) + " " + text + " " + horas + ":" + minutos + " h";
	return formatted;
}



function close_modal_session() {

	$("#closeResponsive").trigger("click");
}


function action_fav_moda(action) {
	//$('.lds-ellipsis').show()
	$('.ficha-info-fav-ico a').addClass('hidden')
	$('.ficha-info-fav-ico a.added').removeClass('hidden')
}

function action_fav_modal(action) {

	$('.lds-ellipsis').show()
	$('.ficha-info-fav-ico a').addClass('hidden')

	$.magnificPopup.close();
	if (typeof cod_licit == 'undefined' || cod_licit == null) {
		$("#insert_msg").html(messages.error.mustLogin);
		$.magnificPopup.open({
			items: {
				src: '#modalMensaje'
			},
			type: 'inline'
		}, 0);
		return;
	} else {
		$.ajax({
			type: "GET",
			url: routing.favorites + "/" + action,
			data: {
				cod_sub: cod_sub,
				ref: ref,
				cod_licit: cod_licit
			},
			success: function (data) {
				$('.lds-ellipsis').hide()


				if (data.status == 'error') {
					$("#insert_msg").html("");
					$("#insert_msg").html(messages.error[data.msg]);
					$.magnificPopup.open({
						items: {
							src: '#modalMensaje'
						},
						type: 'inline'
					}, 0);
				} else if (data.status == 'success') {
					$("#insert_msg").html("");
					$("#insert_msg").html(messages.success[data.msg]);
					$.magnificPopup.open({
						items: {
							src: '#modalMensaje'
						},
						type: 'inline'
					}, 0);
					if (action == 'add') {
						$('.ficha-info-fav-ico a.added').removeClass('hidden')
						$('.ficha-info-fav-ico').addClass('active')
						$("#add_fav").addClass('hidden');
						$("#del_fav").removeClass('hidden');
						$(".slider-thumnail-container #add_fav").addClass('hidden');
						$(".slider-thumnail-container #del_fav").removeClass('hidden');


					} else {
						$('.ficha-info-fav-ico').removeClass('active')

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
		data: {
			cod_sub: cod_sub,
			ref: ref,
			cod_licit: cod_licit
		},
		success: function (data) {

			if (data.status == 'error') {

				$("#insert_msg").html("");
				$("#insert_msg").html(messages.error[data.msg]);
				$.magnificPopup.open({
					items: {
						src: '#modalMensaje'
					},
					type: 'inline'
				}, 0);
			} else if (data.status == 'success') {
				$("#insert_msg").html("");
				$("#insert_msg").html(messages.success[data.msg]);
				$.magnificPopup.open({
					items: {
						src: '#modalMensaje'
					},
					type: 'inline'
				}, 0);
				$('.' + ref + '-' + cod_sub).remove();

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

function ajax_newcarousel(key, replace, lang) {
	//$( "#"+key ).siblings().removeClass('hidden');
	$.ajax({
		type: "POST",
		url: "/api-ajax/newcarousel",
		data: { key: key, replace: replace, lang: lang },
		success: function (result) {

			if (result === '') {
				$("#" + key + '-content').hide()
			}
			$("#" + key).siblings('.loader').addClass('hidden');
			$("#" + key).html(result);
			//cargar cuenta atras
			$('[data-countdown]').each(function (event) {

				var countdown = $(this);
				countdown.data('ini', new Date().getTime());
				countdown_timer(countdown);


			});
			if (key === 'lotes_destacados') {
				carrousel_molon($("#" + key));
			} else {
				carrousel_molon_new($("#" + key));
			}

		}

	});

};

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

	$('.lazy').Lazy({
		scrollDirection: 'vertical',
		effect: 'fadeIn',
		visibleOnly: true,
	});

	$('.add_factura').change(function () {
		reload_facturas();
	});

	$("#submit_fact").click(function () {
		$("#bizumPay").val(0);
		payFacs();

	});
	$("#btoBizumPayFact").click(function () {
		$("#bizumPay").val(1);
		payFacs();
	});





	$('#full-screen').click(function () {
		if ($('.filters-auction-content').css('display') === 'none') {
			$('.filters-auction-content').parent().show()
			$('.filters-auction-content').show("slide", {
				direction: "left"
			}, 500);

			$('.list_lot_content').removeClass('col-md-12').addClass('col-md-9')
			$('.square').removeClass('col-lg-3').addClass('col-lg-4')
			$(this).removeClass('active')

		} else {
			$('.filters-auction-content').hide("slide", {
				direction: "left"
			}, 500, function () {
				//$('.auction-container').removeClass('container').addClass('container-fluid')
				$('.filters-auction-content').parent().hide()
				$('.list_lot_content').removeClass('col-md-9').addClass('col-md-12')
				$('.square').removeClass('col-lg-4').addClass('col-lg-3')
				$(this).addClass('active')
			});

		}

	});

	$(".thumbPop").thumbPopup({
		imgSmallFlag: "lote_medium",
		imgLargeFlag: "lote_large",
		cursorTopOffset: 0,
		cursorLeftOffset: 20

	});


});

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
}

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
}

function change_currency(price) {

	var price = numeral(price).format('0,0.00');

	return price;
}

function abrirNuevaVentana(parametros) {
	var url = parametros;
	var nuevaVentana = (window.open(url, $(this).attr('href'), "width=300", "height=300"));
	if (nuevaVentana) {
		nuevaVentana.focus();
	}
}

(function ($) {



	$.fn.thumbPopup = function (options) {

		//Combine the passed in options with the default settings
		settings = jQuery.extend({
			popupId: "thumbPopup",
			popupCSS: {
				'border': '1px solid #000000',
				'background': '#FFFFFF'
			},
			imgSmallFlag: "lote_medium",
			imgLargeFlag: "lote_large",
			cursorTopOffset: 15,
			cursorLeftOffset: 15,
			loadingHtml: "<span style='padding: 5px;'>Loading</span>"
		}, options);

		//Create our popup element
		popup =
			$("<div />")
				.css(settings.popupCSS)
				.attr("id", settings.popupId)
				.css("position", "absolute")
				.css('z-index', 99999999)
				.appendTo("body").hide();

		//Attach hover events that manage the popup
		$(this)
			.hover(setPopup)
			.mousemove(updatePopupPosition)
			.mouseout(hidePopup);

		function setPopup(event) {

			var fullImgURL = $(this).attr("src").replace(settings.imgSmallFlag, settings.imgLargeFlag);
			$(this).data("hovered", true);

			var style = "style";
			if ($(this).attr("src").indexOf("portada") > -1) {
				style = "styleX";
			}

			//Load full image in popup
			$("<img />")
				.attr(style, "height:450px;max-width:100%; padding: 5px;z-index:9999999;")
				.bind("load", {
					thumbImage: this
				}, function (event) {
					//Only display the larger image if the thumbnail is still being hovered
					if ($(event.data.thumbImage).data("hovered") == true) {
						$(popup).empty().append(this);
						updatePopupPosition(event, style);
						$(popup).show();
					}
					$(event.data.thumbImage).data("cached", true);
				})
				.attr("src", fullImgURL);

			//If no image has been loaded yet then place a loading message
			if ($(this).data("cached") != true) {
				$(popup).append($(settings.loadingHtml));
				$(popup).show();
			}

			updatePopupPosition(event);
		}

		function updatePopupPosition(event, style) {
			var windowSize = getWindowSize();
			var popupSize = getPopupSize(style);

			var rectificaY = 0;
			var rectificaX = 0;

            /*  if (windowSize.width + windowSize.scrollLeft < event.pageX + popupSize.width + settings.cursorLeftOffset){
                $(popup).css("left", event.pageX - popupSize.width - settings.cursorLeftOffset);
            } else {
                $(popup).css("left", event.pageX + settings.cursorLeftOffset);
            }
            if (windowSize.height + windowSize.scrollTop < event.pageY + popupSize.height + settings.cursorTopOffset){
                $(popup).css("top", event.pageY - popupSize.height - settings.cursorTopOffset);
            } else {
                $(popup).css("top", event.pageY + settings.cursorTopOffset);
            } */

			if (event.pageX + popupSize.width > screen.width) {
				rectificaX = -(popupSize.width + 40);
			}
			$(popup).css("left", event.pageX + settings.cursorLeftOffset + rectificaX);

			if (event.pageY + popupSize.height > windowSize.height + windowSize.scrollTop) {
				rectificaY = (windowSize.height + windowSize.scrollTop) - (event.pageY + popupSize.height + 10);
			}
			$(popup).css("top", event.pageY + settings.cursorTopOffset + rectificaY);

		}

		function hidePopup(event) {
			$(this).data("hovered", false);
			$(popup).empty().hide();
		}

		function getWindowSize() {
			return {
				scrollLeft: $(window).scrollLeft(),
				scrollTop: $(window).scrollTop(),
				width: $(window).width(),
				height: $(window).height()
			};
		}

		function getPopupSize(style) {
			if (style == "styleX") {
				return {
					width: $(popup).width(),
					height: $(popup).height()
				};
			}

			return {
				width: 450,
				height: 450
			};
		}


		//Return original selection for chaining
		return this;
	};

	/*if ($(window).width() > 992) {
	  $(document).scroll(function () {
		   var total = $('html').height();
			   nav2 = $('.bloque-fijo');
			   nav2height = $('.bloque-fijo').height();
			   newsletter = $('.newsletter').height();
			   footer = $('footer').height();
			   totalsinfooter = total - newsletter - footer - nav2height - 550;
		   //stick nav to top of page
		   var y = $(this).scrollTop();
		   if (y > totalsinfooter) {
			   nav2.css('opacity', 0);
		   } else {
			   nav2.css('opacity', 1);
		   }
	   });
	   $(document).scroll(function() {
		   var y = $(this).scrollTop();
		   var nav2 = $('.bloque-fijo');
		   if ($(y).scrollTop() > 33) {
			   nav2.css('margin-top', "50px");
		   } else {
			   nav2.css('margin-top', "0px");
		   }
	   });
   }*/

})(jQuery);

async function newsletterSuscriptionFromModal(event) {
	const email = $('#modalAjax .newsletter-input').val();
	const lang = $('#modalAjax #lang-newsletter').val();

	if (!$('#modalAjax #condiciones').prop("checked")) {
		$("#insert_msgweb").html('');
		$("#insert_msgweb").html(messages.neutral.accept_condiciones);
		$.magnificPopup.open({ items: { src: '#modalMensajeWeb' }, type: 'inline' }, 0);
		return;
	}

	const captcha = await isValidCaptcha();
	if(!captcha){
		showMessage(messages.error.recaptcha_incorrect);
		return;
	}

	const newsletters = {};
	document.querySelectorAll("#modalAjax .js-newletter-block [name^=families]").forEach((element) => {
		if(element.checked || element.type === "hidden") {
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

async function newsletterSuscription (event) {
	const email = $('.newsletter-input').val();
	const lang = $('#lang-newsletter').val();

	if (!$('#condiciones').prop("checked")) {
		$("#insert_msgweb").html('');
		$("#insert_msgweb").html(messages.neutral.accept_condiciones);
		$.magnificPopup.open({ items: { src: '#modalMensajeWeb' }, type: 'inline' }, 0);
		return;
	}

	const captcha = await isValidCaptcha();
	if(!captcha){
		showMessage(messages.error.recaptcha_incorrect);
		return;
	}

	const newsletters = {};
	document.querySelectorAll(".js-newletter-block [name^=families]").forEach((element) => {
		if(element.checked || element.type === "hidden") {
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

	if (!$("[name=condiciones]").prop("checked")) {
		$("#insert_msgweb").html('');
		$("#insert_msgweb").html(messages.neutral.accept_condiciones);
		$.magnificPopup.open({ items: { src: '#modalMensajeWeb' }, type: 'inline' }, 0);
		return;
	}

	const captcha = await isValidCaptcha();
	if(!captcha){
		showMessage(messages.error.recaptcha_incorrect);
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
				gtag('event','Enviar',{'event_category':'Registro_Newsletter'});

				var expires = new Date();
				expires = new Date(9999, expires.getMonth(), expires.getDay());
				localStorage.setItem('nextNewsletter', expires);

				$('.insert_msg').html(messages.success[msg.msg]);
			} else {
				$('.insert_msg').html(messages.error[msg.msg]);
			}
			$.magnificPopup.open({ items: { src: '#newsletterModal' }, type: 'inline' }, 0);
		},
		error: function(error) {
			$('.insert_msg').html(messages.error.message_500);
			$.magnificPopup.open({ items: { src: '#newsletterModal' }, type: 'inline' }, 0);
		}
	});
}

async function formValoracionHandleSubmit(event){
	$('#images').remove()
	$(".loader").removeClass("hidden");
	$("#valoracion-adv").addClass("hidden");

	event.preventDefault();

	const captcha = await isValidCaptcha();
	if(!captcha){
		formValoracionError(messages.error.recaptcha_incorrect);
		return;
	}

	if(!formValoracionIsFilesSizeValid()) {
		formValoracionError(messages.error.max_size_img);
		return;
	}

	formData = new FormData(this);

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
			} else if (result.status == 'error_size' || result.status == 'error_no_image') {
				$("#modalMensaje #insert_msg").html('');
				$("#modalMensaje #insert_msg").html(messages.error[result.msg]);
				$.magnificPopup.open({
					items: {
						src: '#modalMensaje'
					},
					type: 'inline'
				}, 0);
			} else {
				$(".msg_valoracion").removeClass('hidden');
			}
			$(".loader").addClass("hidden");
			$("#valoracion-adv").removeClass("hidden");
		},
		error: function (result) {
			$(".loader").addClass("hidden");
			$("#valoracion-adv").removeClass("hidden");
			$(".msg_valoracion").removeClass('hidden');
		}
	});
}

function formValoracionError(message) {
	$(".loader").addClass("hidden");
	$("#valoracion-adv").removeClass("hidden");
	$("#insert_msg").html(message);
	$.magnificPopup.open({
		items: {
			src: '#modalMensaje'
		},
		type: 'inline'
	}, 0);
}

function formValoracionIsFilesSizeValid() {
	var max_size = 6000;
	var size = 0;

	$("#form-valoracion-adv").find('input[type="file"]').each(function (index, element) {
		$(element.files).each(function (index, el) {
			size = size + ((el.size / 1024))
		})
	});

	return Math.floor(size) < max_size;
}

function sendInfoLotRequest() {
	$.ajax({
		type: "POST",
		data: $("#infoLotForm").serialize(),
		url: '/api-ajax/ask-info-lot',
		success: function (res) {
			showMessage("¡Gracias! Hemos sido notificados.  ");
		},
		error: function (e) {
			showMessage("Ha ocurrido un error y no hemos podido ser notificados");
		}
	});
}

function sendInfoLot() {
	validateCaptchaMiddleware(sendInfoLotRequest);
}
