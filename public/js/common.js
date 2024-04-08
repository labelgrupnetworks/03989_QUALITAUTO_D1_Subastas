let audioIsActived = false;
let project = {
	theme: 'demo',
	version: 1,
};

$(document).ready(function () {

	activedAudio(true);

	//Visualizar la contraseña en el panel de datos personales
	$('.view_password').mousedown(function () {
		$(this).siblings('input').attr('type', 'text')
	}).mouseup(function () {
		$(this).siblings('input').attr('type', 'password')
	});

	$(".control-number").keydown(function (event) {
		if (event.shiftKey) {
			event.preventDefault();
		}

		if (event.keyCode == 46 || event.keyCode == 8) {
		} else {
			if (event.keyCode < 95) {
				if (event.keyCode < 48 || event.keyCode > 57) {
					event.preventDefault();
				}
			} else {
				if (event.keyCode < 96 || event.keyCode > 105) {
					event.preventDefault();
				}
			}
		}
	});

    /*
     |--------------------------------------------------------------------------
     | Autocompletar de pujas
     |--------------------------------------------------------------------------
     */

	if ($('#bid_amount').length) {
		if($('#bid_amount').hasClass("NoAutoComplete_JS") == false){


			$('#bid_amount').autoComplete({
				minChars: 1,
				cache: false,

				source: function (term, response) {
					try {
						xhr.abort();
					} catch (e) {
					}
					$.getJSON('/api-ajax/calculate_bids/' + auction_info.lote_actual.importe_escalado_siguiente + '/' + term + '?cod_sub=' + auction_info.subasta.cod_sub , function (data) {
						var matches = [];
						for (i = 0; i < data.length; i++) {
							matches.push(data[i].toString());
						}

						response(matches);
					});
				},
			});
		}

	}



    /*
     |--------------------------------------------------------------------------
     | Auto completar
     |--------------------------------------------------------------------------
     */




	if ($('#bid_modal_pujar').length) {
		$('#bid_modal_pujar').autoComplete({
			minChars: 1,
			cache: false,
			source: function (term, response) {
				try {
					xhr.abort();
				} catch (e) {
				}
				$.getJSON('/api-ajax/calculate_bids/' + auction_info.lote_actual.actual_bid + '/' + term, function (data) {
					var matches = [];
					for (i = 0; i < data.length; i++) {
						matches.push(data[i].toString());
					}
					response(matches);
				});
			}
		});
	}


	//solamente se utiliza en la página del tiempo real
	$().timeago && $("time.timeago").timeago();

    /*
     |--------------------------------------------------------------------------
     | Selectmenu de selector de idioma ubicado en languages
     |--------------------------------------------------------------------------
     */
	// Seleccion del idioma por defecto en el desplegable
	$('#selectorIdioma option').each(function () {
		if ('/' + $(this).val() + '/' == $('#selectorIdioma').attr('actualLang')) {
			$(this).prop('selected', 'selected');
		}
	});

	$('#selectorIdioma').on('change', function () {


		var seleccionado = $(this).val();
        /*
         var url 			= $(location).attr('href');		// Url actual total
         old 				= $(this).attr('actualLang');	// Idioma actual
         nuevo 				= '/' + seleccionado + '/';		// Idioma nuevo
         lastChar 			= url.substr(url.length - 1);	// Obtenemos el último carácter
         sessionVar 			= '?setlng='+seleccionado;

         // Detectamos si hay la barra al final
         if(lastChar != '/') {
         url += '/';
         }

         if(url.indexOf(old) == -1) {
         url += seleccionado;
         } else {
         // Reemplazamos el valor viejo por el nuevo idioma
         url = url.replace(old,nuevo);
         }

         //document.location.href=url+sessionVar;
         document.location.href=url;
         */
		document.location.href = '/' + seleccionado;
	});

    /*
     |--------------------------------------------------------------------------
     | Filter Order By en la ficha de subasta, responsive options
     |--------------------------------------------------------------------------
     */
	if ($(window).width() <= '992') {
		$('#filter_order_by').css('margin-top', '20px');
	} else {
		$('#filter_order_by').css('margin-top', '-48px');
	}

	$(window).resize(function () {
		if ($(window).width() <= '992') {
			$('#filter_order_by').css('margin-top', '20px');
		} else {
			$('#filter_order_by').css('margin-top', '-48px');
		}
	});

	function load_ajax_content(url, text) {
		//var url = $(this).data().ref;
		var loader = $('.loader.copyme').clone().removeClass('hidden');

		$('#modalAjax .modal-content .modal-title').html(text);


		$.ajax({
			url: url,
			dataType: "text",

			beforeSend: function (xhr) {
				$('#modalAjax .modal-content .modal-body').html(loader);
			},

			success: function (data) {

				try {

					info = $.parseJSON(data);

					if (info.status == 'error') {
						window.location.href = '/';
					}

					//$('#modalAjax .modal-content .modal-body').html("<div style='padding:20px;'>"+info.msg+"</div>");

				} catch (e) {
					// not json
					$('#modalAjax .modal-content .modal-body').html("<div style='padding:20px;'>" + data + "</div>");
				}

				$('#modalAjax .modal-content .loader.copyme').remove();
			}
		});

	}


	$(".c_bordered").on('click', function () {
		var url = $(this).data().ref;
		load_ajax_content(url, $(this).data().title);
	});

	$(".b_header").on('click', function () {
		var url = $(this).data().ref;
		load_ajax_content(url, $(this).data().title);
	});

	// Pujas de usuario en el panel de usuario
	$('#panel').on('click', '#my_bids .pagination a', function (e) {
		e.preventDefault();
		load_ajax_content($(this).attr('href'), $(this).data().title);
	});

	// Ordenes de licitacion de usuario en el panel de usuario
	$('#panel').on('click', '#my_orders .pagination a', function (e) {
		e.preventDefault();
		load_ajax_content($(this).attr('href'), $(this).data().title);
	});

	// Ordenes de licitacion de usuario en el panel de usuario
	$('#panel').on('click', '#my_allotments .pagination a', function (e) {
		e.preventDefault();
		load_ajax_content($(this).attr('href'), $(this).data().title);
	});

	// Ordenes de licitacion de usuario en el panel de usuario
	$('#panel').on('click', '#my_favorites .pagination a', function (e) {
		e.preventDefault();
		load_ajax_content($(this).attr('href'), $(this).data().title);
	});


    /*
     |--------------------------------------------------------------------------
     | Boxes / header
     |--------------------------------------------------------------------------
     */

	//Abre una box con contenido.
	$('.open_own_box').on('click', function (e) {
		e.stopPropagation();
		var $this = $(this);

		if ($('html').find('.ob_disp').length && $this.hasClass('opened')) {
			closeBox();
			return;
		}

		closeBox();

		var this_object = {
			height: $this.height(),
			padding_left: parseInt($this.css('padding-left').replace("px", "")),
			padding_right: parseInt($this.css('padding-right').replace("px", "")),
			center: $this.offset().left + $this.width() / 2
		};

		var $ref_to = $("[data-rel='" + $this.data().ref + "']:not(.ob_disp)")
		var ref_object = {
			width: $ref_to.width(),
			padding_left: parseInt($ref_to.css('padding-left').replace("px", "")),
			padding_right: parseInt($ref_to.css('padding-right').replace("px", ""))
		};

		var calculo = {
			x: this_object.center - (ref_object.width + ref_object.padding_right + ref_object.padding_left - this_object.padding_right - this_object.padding_left) / 2,
			y: $this.offset().top + 50
		}

		$ref_to.clone().appendTo('body').addClass('ob_disp').css('top', calculo.y).css('left', calculo.x);
		$this.addClass('opened');

		//Añade la validación al form si hay
		if ($('.own_box.ob_disp form').length && $('.own_box.ob_disp form').data().toggle == 'validator') {
			$('.own_box.ob_disp form').validator();
		}
	});

	function closeBox() {
		$('.opened').removeClass('opened');
		$('.ob_disp').remove();
	}

	//Cierra las box que estén abiertas.
	$('html').on('click', function (e) {

		var p_classes = $(e.target).parents().map(function () {
			return this.className;
		}).get();

		if ($.inArray("own_box ob_disp", p_classes) === -1 && (!$(e.target).hasClass('own_box') && !$(e.target).hasClass('ob_disp'))) {
			closeBox();
		}
	});

	//$('.frmLogin').validator();

    /*
     |--------------------------------------------------------------------------
     | Registro de usuarios / login.blade / Login
     |--------------------------------------------------------------------------
     */
	$("#regCallback").hide();


	$('#btnRegister').on('click', function () {
		if (!$('#condiciones_registro').prop('checked')) {
			alert('Debes aceptar las condiciones de registro');
			return false;
		}
	});

	$('#frmRegister').validator().on('submit', function (e) {

		if (e.isDefaultPrevented()) {
			// formulario incorrecto
		} else {

			e.preventDefault();
			var $this = $(this);

			$('button', $this).attr('disabled', 'disabled');
			// Datos correctos enviamos ajax
			$.ajax({
				type: "POST",
				url: routing.registro,
				data: $('#frmRegister').serialize(),
				beforeSend: function () {
					$('#btnRegister').prepend(' <i class="fa fa-spinner fa-pulse fa-fw margin-bottom"></i> ');
				},
				success: function (response) {

					//$('button', $this).attr('disabled', false);

					res = jQuery.parseJSON(response);

					$('#btnRegister').find('i').hide();

					if (res.err == 1) {
						$("#regCallback").show().html(res.msg);
					} else {
						$('.col_reg_form').html(res.msg);
						//$('.col_login_form').removeClass('col-lg-6');
						//$('.col_login_form').addClass('col-lg-12');

					}

				}
			});

		}
	});


	$('#frmUpdateUserInfo').validator().on('submit', function (e) {

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
				data: $('#frmUpdateUserInfo').serialize(),
				beforeSend: function () {
					$('#btnRegister').prepend(' <i class="fa fa-spinner fa-pulse fa-fw margin-bottom"></i> ');
				},
				success: function (response) {

					//$('button', $this).attr('disabled', false);

					res = jQuery.parseJSON(response);

					$('#btnRegister').find('i').hide();

					if (res.err == 1) {
						$("#regCallback").show().html(res.msg);
					} else {
						$('.insert_msg').html(res.msg);
						$.magnificPopup.open({ items: { src: '#genericPopup' }, type: 'inline' }, 0);

						//$('.col_reg_form').html(res.msg);
					}

				}
			});

		}
	});

	$('#frmUpdateUserPassword').validator().on('submit', function (e) {

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
				data: $('#frmUpdateUserPassword').serialize(),
				beforeSend: function () {
					//$('#btnRegister').prepend(' <i class="fa fa-spinner fa-pulse fa-fw margin-bottom"></i> ');
				},
				success: function (response) {

					//$('button', $this).attr('disabled', false);

					res = jQuery.parseJSON(response);

					//$('#btnRegister').find('i').hide();

					if (res.err == 1) {
						$("#regCallback").show().html(res.msg);
					} else {
						$('.insert_msg').html(res.msg);
						$.magnificPopup.open({ items: { src: '#genericPopup' }, type: 'inline' }, 0);

						//$('.col_reg_form').html(res.msg);
					}

				}
			});

		}
	});

    /*
     |--------------------------------------------------------------------------
     | Botones grandes de la home / bigButtons
     |--------------------------------------------------------------------------
     */

	// Default
	$('.col2').addClass('stage2');

	$('.col1').hover(function () {
		$(this).addClass('stage2');
		$('.col2').removeClass('stage2');
	});

	$('.col2').hover(function () {
		$(this).addClass('stage2');
		$('.col1').removeClass('stage2');
	});

    /*
     |--------------------------------------------------------------------------
     | Controles de pujar o guardar en subastas permanentes / permanentAuctions
     |--------------------------------------------------------------------------
     */

	// Mostramos el overlay de opciones con los controles de la subastas
	$('.permanentAuctions #subastas').on('mouseenter', '#content .item .img', function () {
		$(this).find('.options').show();
	});

	$('.permanentAuctions #subastas').on('mouseleave', '#content .item .img', function () {
		$(this).find('.options').hide();
	});


    /*
     |--------------------------------------------------------------------------
     | Subastas de la Home / permanentAuctions.blade
     |--------------------------------------------------------------------------
     */
	page = 1;
	var is_home = 1;

	// Control de la derecha
	$('.permanentAuctions').find('.control.right').click(function () {

		var max_lot = $('.permanentAuctions').find('#maxPages').val();
		var currentPage = $('.permanentAuctions').find('#currentPage').val();

		//console.log("value: "+$('.permanentAuctions').find('.control.right').attr('value'));
		//console.log("currentPage: "+currentPage);
		//console.log("max_lot: "+max_lot);

		if ((page + 1) < max_lot) {
			page = (page * 1) + 1;

			if (page == 1) {
				page = (page + 1);
			}

			//console.log(page);
			//console.log(max_lot);

			//console.log("Next: " +page);

			if (page < max_lot) {
				//console.log('Item Load');
				$.ajax({
					type: "GET",
					url: routing.subastashome + '/' + page + '/' + is_home,
					beforeSend: function () {
						$(".loteHome").addClass('opacity');
					},
					success: function (msg) {
						$("#subastas").html("<div>" + msg + "</div>");
					}
				});
			}
		}
	});

	page = 0;

	// Control de la izquierda
	$('.permanentAuctions').find('.control.left').click(function () {

		var max_lot = $('.permanentAuctions').find('#maxPages').val();
		var currentPage = $('.permanentAuctions').find('#currentPage').val();

		if (currentPage < max_lot && page > 0) {
			page = (page * 1) - 1;

			if (page == 1) {
				page = (page - 1);
			}

			//console.log("Next: " +page);

			if (page < max_lot) {
				//console.log('Item Load');
				$.ajax({
					type: "GET",
					url: routing.subastashome + '/' + page + '/' + is_home,
					beforeSend: function () {
						$(".loteHome").addClass('opacity');
					},
					success: function (msg) {
						$("#subastas").html("<div>" + msg + "</div>");
					}
				});
			}
		}
	});



	$('.modal-block').on('click', '.modal-confirm', function (e) {
		e.stopPropagation();
		e.preventDefault();
		var func = $(this).closest('.modal-block').data('to');
		var errors = 0;

		if (typeof func != 'undefined') {
			var fn = window[func];
			if (typeof fn === 'function') {
				fn();
			} else {
				errors++;
			}
		} else {
			errors++;
		}

		$.magnificPopup.close();

		if (errors > 0) {
			displayAlert(0, messages.error.generic);
		}

	}).on('click', '.modal-dismiss', function (e) {
		e.stopPropagation();
		e.preventDefault();
		$.magnificPopup.close();
	});



	$('.submit_on_change').on('change', function () {

		$(this).closest('form').submit();

	});


	//Bloquea los elementos del form mientras carga la página para evitar posibles errores.
    /*$('.permanentAuctions form').submit(function(){
     $('select, input', $(this)).prop( "disabled", true );
     });*/


	//eliminamos todos los filtros para que el goto busque en toda la web
	$('#filer_submit').on('click', function () {
		$('#filter_main  :input').val('');
		$(this).closest('form').submit();
	});

	// Facebook share.
	var fb_share = $('#fbShareBtn');
	if (fb_share.length) {
		fb_share.on('click', function () {
			var fb_href = $(this).data().href,
				fb_picture = $(this).data().picture,
				fb_caption = $(this).data().caption,
				fb_title = $(this).data().title,
				fb_description = $(this).data().description;

			FB.ui({
				method: 'share',
				display: 'popup',
				mobile_iframe: true,
				href: fb_href,
				picture: fb_picture,
				caption: fb_caption,
				title: fb_title,
				description: fb_description
			}, function (response) {
				if (typeof response != 'undefined') {
					alert('callback compartido');
				}
			});
		});
	}

	//Twitter share.
	$('#twShareBtn').click(function (event) {
		var width = 575,
			height = 450,
			left = ($(window).width() - width) / 2,
			top = ($(window).height() - height) / 2,
			url = this.href,
			opts = 'status=1' +
				',width=' + width +
				',height=' + height +
				',top=' + top +
				',left=' + left;

		window.open(url, 'Twitter', opts);

		return false;
	});

	$('[data-countdown]').each(function (event) {

		var countdown = $(this);
		countdown.data('ini', new Date().getTime());
		countdown_timer(countdown)


	});
	$('[data-countdownficha]').each(function (event) {


		var countdown = $(this);
		countdown.data('ini', new Date().getTime());
		countdown_timer_ficha(countdown)


	});

	//countdown_timer_ficha($("[data-countdown-ficha]"));

	$("#frmRegister-adv input, #frmRegister-adv select").blur(function () {
		verifyFormLoginContent();
	});



	$('.seoAction_JS').click(function (event) {
		seoEvent= $(this).data("event");
		registerSeoEvent(seoEvent);
	})
});
//SE PUEDE AÑADIR LA CLASE seoAction_JS Y UN CAMPO DATA-EVENT CON EL NOMBRE DEL EVENTO PARA QUE LO REGISTRE GRACIAS A UNA FUNCION CREADA
function registerSeoEvent(seoEvent){
	console.log(seoEvent);
		$.ajax({
			type: "GET",

			url: '/seo_event/'+seoEvent,
		});
}

function emailSobrePuja(cod, licit, lote) {

	$.ajax({
		type: "POST",
		data: { cod: cod, licit: licit, lote: lote },
		url: '/api-ajax/email_sobrepuja',
		beforeSend: function () {
		},
		success: function (msg) {
			var hola = "" + msg;
			hola = "hola";
			//$('.insert_msg').html(msg.msg);
			//$.magnificPopup.open({items: {src: '#newsletterModal'}, type: 'inline'}, 0);
		}
	});
}

function toggleFullScreen() {
	if ((document.fullScreenElement && document.fullScreenElement !== null) ||
		(!document.mozFullScreen && !document.webkitIsFullScreen)) {
		if (document.documentElement.requestFullScreen) {
			document.documentElement.requestFullScreen();
		} else if (document.documentElement.mozRequestFullScreen) {
			document.documentElement.mozRequestFullScreen();
		} else if (document.documentElement.webkitRequestFullScreen) {
			document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
		}
	} else {
		if (document.cancelFullScreen) {
			document.cancelFullScreen();
		} else if (document.mozCancelFullScreen) {
			document.mozCancelFullScreen();
		} else if (document.webkitCancelFullScreen) {
			document.webkitCancelFullScreen();
		}
	}
}

/*
 |--------------------------------------------------------------------------
 | Configuración notificaciones
 |--------------------------------------------------------------------------
 */
(function ($) {

	'use strict';

	// use font awesome icons if available
	if (typeof PNotify != 'undefined') {
		PNotify.prototype.options.styling = "fontawesome";

		$.extend(true, PNotify.prototype.options, {
			shadow: false,
			stack: {
				spacing1: 15,
				spacing2: 15
			}
		});

		$.extend(PNotify.styling.fontawesome, {
			// classes
			container: "notification",
			notice: "notification-warning",
			info: "notification-info",
			success: "notification-success",
			error: "notification-danger",

			// icons
			notice_icon: "fa fa-exclamation",
			info_icon: "fa fa-info",
			success_icon: "fa fa-check",
			error_icon: "fa fa-times"
		});
	}

}).apply(this, [jQuery]);
/*
 |--------------------------------------------------------------------------
 | END Configuración notificaciones
 |--------------------------------------------------------------------------
 */

/*
 |--------------------------------------------------------------------------
 | Selectmenu de selector de idioma ubicado en languages
 |--------------------------------------------------------------------------
 */

function seleccionar_idioma(actualLang, seleccionado) {
    /*
     var url 			= $(location).attr ('href');		// Url actual total
     old 					= '/' + actualLang;										// Idioma actual
     nuevo 				= '/' + seleccionado + '/';			// Idioma nuevo
     lastChar 			= url.substr (url.length - 1);	// Obtenemos el último carácter
     sessionVar 		= '?setlng=' + seleccionado;

     // Detectamos si hay la barra al final
     if (lastChar != '/')
     {
     url += '/';
     }

     if (url.indexOf (old) == -1)
     {
     url += seleccionado;
     }
     else
     {
     // Reemplazamos el valor viejo por el nuevo idioma
     url = url.replace (old, nuevo);
     }
     */
	document.location.href = '/' + seleccionado;// url;
}




/*
 |--------------------------------------------------------------------------
 | Alerta sonora
 |--------------------------------------------------------------------------
 */



function playAlert(arr_items) {

	if (typeof auction_info == 'undefined' || typeof auction_info.subasta.nextLotes == 'undefined') {
		return;
	}


	if ($.inArray('favs', arr_items) !== -1) {
		if (alertForFavs()) {
			playAudio('#alarm_fav_lot');
		}
	}
	if ($.inArray('new_bid', arr_items) !== -1) {
		playAudio('#new_bid');
	}
	if ($.inArray('end_lot', arr_items) !== -1) {
		playAudio('#end_lot');
	}
	if ($.inArray('new_ol', arr_items) !== -1) {
		playAudio('#new_ol');
	}

	if ($.inArray('countdown', arr_items) !== -1) {
		/*$('#alarm_end_lot').trigger('play').prop("volume", 0.1);*/
	}

	if ($.inArray('notification', arr_items) !== -1) {
		playAudio('#alarm_notification');

	}
}


function activedAudio(actived){

	if(!actived){
		audioIsActived = false;
		return;
	}

	audioIsActived = true;
	playAudio("#alarm_notification", 0);
}


function playAudio(selector, volume = 0.5){

	if (!audioIsActived){
		return;
	}

	var sound = document.querySelector(selector); //.play();

	if(sound == null){
		return;
	}

	sound.volume = volume;

	var playPromise = sound.play();

	// In browsers that don’t yet support this functionality,
	// playPromise won’t be defined.
	if (playPromise !== undefined) {
		playPromise.then(function () {
			// Automatic playback started!
		}).catch(function (error) {
			// Automatic playback failed.
			// Show a UI element to let the user manually start playback.
			console.log(error)
		});
	}
}

function alertForFavs() {
	var found = false;
	var lot = 0;
	var lot_order = 9999;
	var match_actual = false;

	if (typeof auction_info.user == 'undefined' || typeof auction_info.user.favorites == 'undefined' || auction_info.user.is_gestor == true) {
		return;
	}

	$.each(auction_info.user.favorites, function (key, item) {

		if (typeof item == 'undefined') {
			return true;
		}

		if ($.inArray(item.ref_asigl0, auction_info.subasta.nextLotes) !== -1) {
			found = true;

			if (lot == 0 || item.orden_hces1 < lot_order) {
				lot = parseInt(item.ref_asigl0);
				lot_order = item.orden_hces1;
			}
		}

		if (item.ref_asigl0 == auction_info.lote_actual.ref_asigl0) {
			match_actual = true;
		}

	});
	if (match_actual) {
		displayAlert(1, messages.neutral.match_fav_actual_lot);
	}

	if (found) {
		displayAlert(3, messages.neutral.subasted_fav_lot_soon + lot);
	}

	return found;
}
/*
 |--------------------------------------------------------------------------
 | END Alerta sonora
 |--------------------------------------------------------------------------
 */



function calculate_exchange(currency, price) {
	var calc_exchange = price * exchanges[currency];
	calc_exchange = format_money(calc_exchange);
	return calc_exchange;
}

function format_money(money) {
	let formatPrice = parseFloat(money)
		.toFixed(2)
		.replace(".", ",");

	return formatPrice.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
}

function formatMoney({money = 0, decimals = 2, symbol = '€'}){

	money = money.toFixed(decimals);
	money = money.replace('.', ',');
	money = money.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");

	return money + ' ' + symbol;
}

function popup_frame(url) {
	$('#modal_info').magnificPopup(
		{
			src: 'http://vimeo.com/123123',
			type: 'iframe' // this overrides default type
		}
	);
}

/*
 |--------------------------------------------------------------------------
 | Mostrar alertas
 |--------------------------------------------------------------------------
 */
function displayAlert(type, msg) {
	if (auction_info.lote_actual.tipo_sub == 'O' || auction_info.lote_actual.tipo_sub == 'P') {
		displayAlertPopUp(type, msg)
	} else if (auction_info.lote_actual.tipo_sub == 'W') {
		displayAlertNotice(type, msg)
	}
}
function displayAlertNotice(type, msg) {
	if (type == null || typeof type == 'undefined' || !$.isNumeric(type))
		return false;

	/*var type = ''; */

	switch (type) {
		case 0:
			type = 'error';
			break;
		case 1:
			type = 'success';
			break;
		case 2:
			type = 'info';
			break;
		case 3:
			type = 'alert';
			break;
	}

	playAlert(['notification']);

	var notice = new PNotify({
		title: messages.neutral.notification,
		text: msg,
		type: type,
		shadow: true,
		addclass: 'stack-topleft'
	});
}

function displayAlertPopUp(type, msg) {
	$("#insert_msg_title").html("Notificación");
	$("#insert_msg").html(msg);
	$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);

}
/*
 |--------------------------------------------------------------------------
 | END Mostrar alertas
 |--------------------------------------------------------------------------
 */



function verifyFormLoginContent() {


	var valor = $("#frmRegister-adv #dni").val();
	valor = valor.toUpperCase();
	$("#frmRegister-adv #dni").val(valor);
	//cogemos el pais
	var selected_country = $("#frmRegister-adv select#country").val();
	//si el pais es Spain verificar el DNI
	if (selected_country == "ES") {

		validateDNI(valor);
	} else {
		$("#frmRegister-adv input#dni").parent().removeClass("has-error");
		$("#frmRegister-adv input#dni").parent().addClass("has-success");
	}

	var has_input_errors = 0;

	$("#frmRegister-adv input").each(function () {
		if ($(this).parent().hasClass("has-error")) {

			has_input_errors++;
		}
	});

	$("#frmRegister-adv select").each(function () {
		if ($(this).parent().hasClass("has-error")) {

			has_input_errors++;
		}
	});

	if (!$("#erroremail").hasClass("hidden")) {
		has_input_errors++;
	}

	if (has_input_errors == 0) {
		$(".btn-registro").removeAttr("disabled");
		return false;
	} else {
		$(".btn-registro").attr("disabled", "disabled");
		return true;
	}
}


function validateDNI(dni) {
	console.log(dni)
	var validated = false;
	//miramos si tiene errores para devolver el error
	if (dni.trim().length == 9) {
		var arrLetras = ['T', 'R', 'W', 'A', 'G', 'M', 'Y', 'F', 'P', 'D', 'X', 'B', 'N', 'J', 'Z', 'S', 'Q', 'V', 'H', 'L', 'C', 'K', 'E'];
		var arrResto = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22'];

		var first_char = dni.trim().charAt(0);
		var last_char = dni.trim().charAt(8);



		//si la primera y la ultima no son numericas, es una de las del NIE X,Y,Z
		if (isNaN(first_char) && isNaN(last_char) && (first_char == 'X' || first_char == 'Y' || first_char == 'Z')) {
			//ES NIE
			var middle_string = dni.trim().substr(1, 7);
			var first_char_replaced = "0";//POR DEFECTO ESPERAMOS LA X
			if (first_char == "Y") {
				first_char_replaced = "1";
			} else {
				if (first_char == "Z") {
					first_char_replaced = "2";
				}
			}

			var final_string = first_char_replaced + middle_string;

			//SI NO ES NUMERICO NO ES VALIDO
			if (!isNaN(final_string)) {
				//DIVIDIR EL CODIGO ENTRE 23
				var number_to_divide = Number(final_string);
				var resto = number_to_divide % 23;

				for (i = 0; i < arrResto.length; i++) {
					if (arrResto[i] == resto && arrLetras[i] == last_char) {
						validated = true;
					}
				}
			}

		} else {

			//si la ultima no es numerica, es un NIF
			if (!isNaN(first_char) && isNaN(last_char)) {
				//ES NIE
				var middle_string = dni.trim().substr(0, 8);

				//SI NO ES NUMERICO NO ES VALIDO
				if (!isNaN(middle_string)) {
					//DIVIDIR EL CODIGO ENTRE 23
					var number_to_divide = Number(middle_string);
					var resto = number_to_divide % 23;

					for (i = 0; i < arrResto.length; i++) {
						if (arrResto[i] == resto && arrLetras[i] == last_char) {
							validated = true;
						}
					}
				}
			}
			//es un CIF de empresa
			else if (isNaN(first_char) && first_char != 'X' && first_char != 'Y' && first_char != 'Z') {
				validated = true;
			}
		}

	}

	if (validated) {
		//HACER LO QUE SEA PARA QUE SE NOTE QUE ES VALIDO
		//alert("VALIDO");
		$("#frmRegister-adv input#dni").parent().removeClass("has-error");
		$("#frmRegister-adv input#dni").parent().addClass("has-success");
	} else {
		//HACER LO QUE SEA PARA QUE SE NOTE QUE NO ES VALIDO
		//alert("NO VALIDO");
		if ($("#frmRegister-adv input#dni").parent().hasClass("has-error")) {
			$("#frmRegister-adv input#dni").parent().removeClass("has-error");
		}

		if ($("#frmRegister-adv input#dni").parent().hasClass("has-success")) {
			$("#frmRegister-adv input#dni").parent().removeClass("has-success");
		}


		$("#frmRegister-adv input#dni").parent().addClass("has-error");
	}

}


window.modalDeletAddress = function modalDeletAddress() {
	var token = $("#modalDeletAddress #_token").val();
	var cod = $("#modalDeletAddress #cod_delete").val();
	var lang = $("#modalDeletAddress #lang").val();

	$.ajax({
		type: "POST",
		url: '/delete_address_shipping',
		data: { _token: token, cod: cod },
		success: function (response) {
			if (response.status == 'success') {
				$("#modalMensaje #insert_msg").html('');
				$("#modalMensaje #insert_msg").html(messages.success.success_delete);
				$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				ajax_shipping('W1', lang);
			}
		}
	})
};

function fav_addres(thi) {
	var cod = $(thi).attr('cod');
	return $.ajax({
		type: "POST",
		url: '/api-ajax/add_favorite_address_shipping',
		data: { codd_clid: cod },
		success: function (response) {
			if (response.status == 'success') {
				$("#modalMensaje #insert_msg").html('');
				$("#modalMensaje #insert_msg").html(messages.success.success_saved);
				$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				ajax_shipping(response.codd_clid, cod);
				return response;
			}

		}
	}).then((response) => {
		return response;
	});
}

function delete_shipping_addres(thi) {
	var cod = $(thi).attr('cod');
	$("#cod_delete").val(cod);
	$.magnificPopup.open({ items: { src: '#modalDeletAddress' }, type: 'inline' }, 0);
}

function submit_shipping_addres(event, thi) {
	event.preventDefault();
	return $.ajax({
		type: "POST",
		context: thi,
		url: '/change_address_shipping',
		data: $(thi).serialize(),
		success: function (response) {
			if (response.status == 'success' || response.status == 'new') {

				$("#modalMensaje #insert_msg").html('');
				$("#modalMensaje #insert_msg").html(messages.success.success_saved);
				$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				ajax_shipping(response.codd_clid, $("#lang_dirreciones").val());
				return response;
			}
		}
	}).then((response) => {
		return response;
	});
}


function countdown_timer_ficha(countdown) {
	//SI ESTA PARADO NO DEBE HACER NADA
	if (typeof countdown.data('stop') != 'undefined' && countdown.data('stop') == 'stop') {
		return;
	}

	ToFinish = countdown.data('countdownficha') - Math.round(((new Date().getTime() - countdown.data('ini')) / 1000));

	if (ToFinish < 0) {
		ToFinish = 0;
		$.ajax({
			type: "GET",
			url: '/' + auction_info.lang_code.toLowerCase() + '/reload_lot',
			data: { cod_sub: auction_info.lote_actual.cod_sub, ref: auction_info.lote_actual.ref_asigl0 },
			success: function (response) {
				if (response != 'error') {
					$("#reload_inf_lot").html('');
					$("#reload_inf_lot").html(response);
					reloadPujasList_O();
					countdown.data('stop', 'stop');
					if (typeof countdown.data('txtend') != 'undefined') {
						countdown.html(countdown.data('txtend'));

					}
				}
			}
		});

	}


	var timeFormat = time_format(ToFinish, countdown.data('format'));
	countdown.html(timeFormat);
	setTimeout(function () {
		countdown_timer_ficha(countdown);
	}, 1000);

}

function countdown_timer(countdown) {


	//SI ESTA PARADO NO DEBE HACER NADA
	if (typeof countdown.data('stop') != 'undefined' && countdown.data('stop') == 'stop') {
		return;
	}

	ToFinish = countdown.data('countdown') - Math.round(((new Date().getTime() - countdown.data('ini')) / 1000));

	if (ToFinish < 0) {
		ToFinish = 0;
		//SI HAY TEXTO DE FIN, PONEMOS EL TEXTO Y PARAMOS EL BUCLE
		if (typeof countdown.data('txtend') != 'undefined') {
			countdown.html(countdown.data('txtend'));
			countdown.data('stop', 'stop');
			//paramos el contador
			return
		}
	}

	var timeFormat = time_format(ToFinish, countdown.data('format'));
	countdown.html(timeFormat);
	setTimeout(function () {
		countdown_timer(countdown);
	}, 1000);

}

function time_format(timer, format) {



	if (format.indexOf('%D') > -1) {
		format = format.replace('%D', calc_day(timer));
	}
	if (format.indexOf('%H') > -1) {
		format = format.replace('%H', calc_hour(timer));
	}

	if (format.indexOf('%M') > -1) {
		format = format.replace('%M', calc_minute(timer));
	}

	if (format.indexOf('%S') > -1) {
		format = format.replace('%S', calc_seconds(timer));
	}

	return format;
}

function calc_day(timer) {
	var days = parseInt(timer / 86400);
	days = days < 10 ? "0" + days : days;
	return days
}

function calc_hour(timer) {
	var hours = parseInt((timer % 86400) / 3600);
	hours = hours < 10 ? "0" + hours : hours;
	return hours
}


function calc_minute(timer) {
	var minutes = parseInt((timer % 3600) / 60);
	minutes = minutes < 10 ? "0" + minutes : minutes;
	return minutes
}


function calc_seconds(timer) {
	var seconds = parseInt((timer % 60));
	seconds = seconds < 10 ? "0" + seconds : seconds;
	return seconds

}


$(document).ready(function () {


	$('[name="cpostal"]').blur(function () {
		var country = $('[name="pais"]').val();
		var zip = $(this).val();
		if (country != '') {
			$.ajax({
				type: "POST",
				data: { zip: zip, country: country },
				url: '/api-ajax/cod-zip',
				success: function (msg) {
					if (msg.status == 'success') {
						$('[name="poblacion"]').val(msg.pob);
						$('[name="provincia"]').val(msg.des_prv);
					}
				}
			});
		}
	});

	$("#codigo_postal").blur(function () {
		var country = $("#country_envio").val();
		var zip = $(this).val();
		if (country != '') {
			$.ajax({
				type: "POST",
				data: { zip: zip, country: country },
				url: '/api-ajax/cod-zip',
				success: function (msg) {
					if (msg.status == 'success') {
						$("#clid_provincia").val(msg.des_prv);
						$("#clid_poblacion").val(msg.pob);
					}
				}
			});
		}
	});

	$(".change_job").click(function () {
		var job = $(this).val();
		if (job == 'J') {
			$('.rsoc_cli').removeClass('hidden');
			$('.rsoc_cli input').attr('data-checking', 'true');
			$('.gener-group').addClass('hidden');
			$('.fech_nac').addClass('hidden');
			$('.fech_nac input').attr('data-checking', 'false');
			$(".rsoc_cli input").prop("required", 'true');
			$(".rsoc_cli").attr('data-checking', 'true');
			$('.name_client').addClass('hidden');
			$('.name_client input').attr('data-checking', 'false');
			//$(".name_client input").removeAttr("required");
			$(".name_client input").attr("required", false);
			$('.cif_txt').removeClass('hidden');
			$('.cif_txt input').attr('data-checking', 'true');
			$('.dni_txt').addClass('hidden');
			$('.dni_txt input').attr('data-checking', 'false');
			$("#dni").attr("placeholder", $('.cif_txt').html());


		} else {
			$('.gener-group').removeClass('hidden');

			$('.fech_nac').removeClass('hidden');
			$('.fech_nac input').attr('data-checking', 'true');

			$('.rsoc_cli').addClass('hidden');
			$('.rsoc_cli').attr('data-checking', 'false');
			$(".rsoc_cli input").removeAttr("required");
			$('.name_client').removeClass('hidden');
			$(".name_client input").prop("required", true);
			$(".name_client input").attr('data-checking', 'true');
			$('.cif_txt').addClass('hidden');
			$('.cif_txt input').attr('data-checking', 'false');
			$('.dni_txt').removeClass('hidden');
			$('.dni_txt input').attr('data-checking', 'true');
			$("#dni").attr("placeholder", $('.dni_txt').html());
		}

	});

	$("#email").blur(function () {
		var email = $(this).val();
		var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if (regex.test(email) == true) {
			$.ajax({
				type: "POST",
				data: { email: email },
				url: '/api-ajax/exist-email',
				success: function (msg) {
					if (msg.status == 'error') {

						if (typeof specificModalEmailExist === "function") {
							specificModalEmailExist();
							$("#erroremail").addClass("hidden");
						} else {
							$("#erroremail").html(messages.error.email_exist);
							$("#erroremail").removeClass("hidden");
						}

					} else {
						//    $("#erroremail").html('');
						$("#erroremail").addClass("hidden");

					}
				}
			});
		} else {
			$("#erroremail").html(messages.error.email_invalid);
			$("#erroremail").removeClass("hidden");
		}
	});

	$('#passRecovered').validator().on('submit', function (e) {

		if (e.isDefaultPrevented()) {
			// formulario incorrecto
		} else {
			e.preventDefault();
			var $this = $(this);

			$('button', $this).attr('disabled', 'disabled');

			$.ajax({
				type: "POST",
				url: '/change-passw-user',
				data: $('#passRecovered').serialize(),
				success: function (response) {
					if (response.status == 'success') {

						$("#modalMensaje #insert_msg").html(messages.success[response.msg]);

						if ($("#login").val() == true) {

							$.ajax({
								type: "POST",
								url: '/login_post_ajax',
								data: $('#passRecovered').serialize(),
								success: function (response) {
									if (response.status == 'success') {
										location.href = '/';
									} else {
										$(".message-error-log").text('').append(messages.error[response.msg]);
									}

								}
							});

						} else {
							setTimeout(function () {
								location.href = '/';
							}, 2000);
						}

					} else {
						$('button', $this).removeAttr('disabled');
						$("#modalMensaje #insert_msg").html(messages.error[response.msg]);
					}
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);

				},
				error: function (response) {
					$('button', $this).removeAttr('disabled');

					const errors = response.responseJSON.message;
					const html = errors.map(error => `<p>${error}</p>`).join('');
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
					$("#modalMensaje #insert_msg").html(html);
				}
			});

		}
	});
});

/*/
 $('[data-countdown]').each(function() {
 var countdown = $(this).data('countdown');
 countdown = countdown.replace(/-/g, "/");

 var fechafin_original =new Date(countdown); //new Date();


 var fechafin_calculada =   fecha_js - fecha_server  + fechafin_original.getTime();

 var $this = $(this), finalDate = fechafin_calculada, closed = $(this).data('closed'), format = $(this).data('format') ;
 $this.countdown(finalDate, function(event) {
 var timing = event.strftime(format);

 var ffin = new Date(finalDate).getTime();
 var fnow = new Date().getTime();

 if(ffin < fnow || isNaN(ffin) || closed == 'S') {
 $(this).closest('.still_timer').hide();
 $(this).closest('.timeLeft').find('.stop_timer').show();
 }

 $this.html(timing);
 });
 });
 */


function cambiarDireccion(thi) {
	var idInput = $(thi).parents('.tab-pane').attr('id')
	var inputCambiar = $('.content-tabs').find('a[href="#' + idInput + '"]')
	var value = $(thi).val()

	$(inputCambiar).find('.change_address').html(value);


}

function ajax_shipping(cod_ship, lang) {
	$.ajax({
		type: "GET",
		url: '/' + lang + '/seeShippingAddress',
		data: { codd_clid: cod_ship },
		success: function (response) {
			$("#ajax_shipping_add").html('');
			$("#ajax_shipping_add").html(response);
		}
	});

}

function changeCurrency(price, exchange, object) {
	price = Math.round(price * currency[exchange].impd_div * 100) / 100;
	if(typeof sindecimales  != 'undefined' && sindecimales==true){
		newPrice = numeral(price).format('0,0');
	}else{
		newPrice = numeral(price).format('0,0.00');
	}
	if (currency[exchange].pos_div == 'R') {
		newPrice += " " + currency[exchange].symbolhtml_div;
	} else {
		newPrice = currency[exchange].symbolhtml_div + newPrice;
	}
	$("#" + object).html(newPrice);

}

function changeCurrencyNew(price, exchange, object) {
	price = Math.round(price * currency[exchange].impd_div * 100) / 100;

	newPrice = numeral(price).format('0,0.00');

	if (currency[exchange].pos_div == 'R') {
		newPrice += " " + currency[exchange].symbolhtml_div;
	} else {
		newPrice = currency[exchange].symbolhtml_div + newPrice;
	}
	$(object).html(newPrice);

}

function changeCurrencyWithElement(price, exchange, element) {
	price = Math.round(price * currency[exchange].impd_div * 100) / 100;

	let newPrice = numeral(price).format('0,0.00');
	if(typeof sindecimales  != 'undefined' && sindecimales == true){
		newPrice = numeral(price).format('0,0');
	}

	if (currency[exchange].pos_div == 'R') {
		newPrice += " " + currency[exchange].symbolhtml_div;
	} else {
		newPrice = currency[exchange].symbolhtml_div + newPrice;
	}

	element.innerHTML = newPrice;
}

$(document).ready(function () {

	$("#actual_currency").change(function () {

		$(".js-divisa").each(function () {
			a = $(this).attr('value');
			if (a != "undefined") {
				changeCurrencyNew(a, $('#actual_currency').val(), $(this));
			}
		});

		$.ajax({
			type: "POST",
			url: "/api-ajax/updateDivisa",
			data: { divisa: $("#actual_currency").val() },
			success: function (data) {
				console.log(data);
			}
		});


	});


});









































/***************************************************************************************/
/*************************** FUNCIONES SOBRE MODAL DE COOKIES **************************/
/***************************************************************************************/
function acceptAllCookies() {
	updateConsent();
	fetch('/accept-all-cookies', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	}).then(function (response) {
		if (response.ok) {
			$(".cookies").fadeOut(500);
			$(".modal-cookies").modal('hide');
		}
	});
}

function rejectAllCookies() {
	fetch('/reject-all-cookies', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	}).then(function (response) {
		if (response.ok) {
			$(".cookies").fadeOut(500);
			$(".modal-cookies").modal('hide');
		}
	});
}

function savePreferencesCookies() {

	const isAnalysisActive = $("[name='permission_analysis']").is(":checked");
	const isAdvertisingActive = $("[name='permission_advertising']").is(":checked");
	if(isAnalysisActive){
		updateConsent();
	}
	const data = {
		'analysis': isAnalysisActive ? 1 : 0,
		'advertising': isAdvertisingActive ? 1 : 0
	};

	fetch('/save-preferences-cookies', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		body: JSON.stringify({preferences: data})
	}).then(function (response) {
		if (response.ok) {
			$(".modal-cookies").modal('hide');
		}
	});
}

function saveConfigurationCookies(data) {
	fetch('/add-configurations-cookies', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		body: JSON.stringify({configurations: data})
	}).catch(function (error) {
		console.log(error);
	});
}

function updateConsent(){
	gtag('consent', 'update', {
		'ad_storage': 'granted',
		'ad_user_data': 'granted',
		'ad_personalization': 'granted',
		'analytics_storage': 'granted'
	  });
	 

}

//
//  Función creada para poder debugar mostrando objetos
//
//  @obj - Objeto a mostrar
//

function dump(obj) {
	var out = '';
	for (var i in obj) {
		out += i + ": " + obj[i] + "\n";
	}

	showMessage(out, "DUMP");

}


//
//  Función creada para agilizar los mensajes de modales. Es como un alert pero en modal y con la posibilidad de usar
//  los parametros de la libreria MessageLib
//
//  @data - Campo obligatorio donde recibimos un objeto de mensaje o un simple texto
//  @title - Titulo para el modal
//  @method - funcion a la que llamamos en caso de que se acepte.
//

function showMessage(data, title) {

	text = "";

	// TRATAMOS EL MENSAJE
	// Miramos si existe el mensaje traducido

	if (typeof (data) != "undefined" && typeof (data.status) != "undefined" && typeof (data.message) != "undefined") {
		text = eval("messages." + data.status + "." + data.message);
	}

	// Si no existe la traducción, miramos si podemos mostrar el mensaje tal cual
	if ((typeof (text) == "undefined" || text == "") && typeof (data) != "undefined" && typeof (data.message) != "undefined") {
		text = data.message;
	}

	// Si no existe el objeto data y es solo un texto lo mostramos
	if ((typeof (text) == "undefined" || text == "") && data != "") {
		text = data;
	}

	if (typeof (title) != "undefined") {
		$("#insert_msg_title").html(title);
	}

	setTimeout("$('.popover').fadeOut(1000)", 4000);

	$("#insert_msgweb").html(text);
	$.magnificPopup.open({ items: { src: '#modalMensajeWeb' }, type: 'inline' }, 0);


}








/****************************************************************************************/
/*************************** FUNCIONES SOBRE PANTALLA CONTACTO **************************/
/****************************************************************************************/

function sendContact() {

	$(".g-recaptcha").find("iframe").removeClass("has-error");

	response = $("#g-recaptcha-response").val();

	if (response) {
		$.ajax({
			type: "POST",
			url: "/contactSendmail",
			data: $(contactForm).serialize(),
			success: function (response) {
				if (response.status == "error") {
					showMessage(response.message);
				} else {
					showMessage(response, "");
					setTimeout("location.reload()", 4000);
				}
			},
			error: function (response) {
				showMessage("Error");
			}
		});
	} else {
		$(".g-recaptcha").find("iframe").addClass("has-error");
		showMessage(messages.error.hasErrors);
	}

}








/****************************************************************************************/
/******************************* FUNCIONES SOBRE PANTALLA FAQS **************************/
/****************************************************************************************/


function FaqshowContent(id) {
	$(".faq").hide(500);
	$('#' + id).toggle('Drop');

}

function muestraSub(id) {

	$(".subfamily").hide();
	$(".parent" + id).toggle('Drop');
	$(".parentFaq").hide();
}

function muestraFaq(id) {

	$(".parentFaq").hide();
	$(".parentFaq" + id).toggle('Blind');
}



$(document).ready(function () {
	$(".signIn").on("click", function () {

		$(".login_desktop").fadeIn();
	});
});


function trans(key = null, replace = {}, locale = null){

	const keys = key.split('.');
	const existTranslate = typeof translates != 'undefined' && typeof translates[keys[0]] != 'undefined' && typeof translates[keys[0]][keys[1]] != 'undefined';

	if(!existTranslate){
		return key;
	}

	let string = translates[keys[0]][keys[1]];
	Object.keys(replace).map(function(value) {

		let str1 = `:${value}`;
		let re = new RegExp(str1, "i");
		string = string.replace(re, replace[value]);

	});


	return string;
}

function openLogin(){
	//por si venimos de un moda, no afecta a nada mas
	$.magnificPopup.close();
	$('.login_desktop').fadeToggle("fast");
	$('.login_desktop [name=email]').focus();
}


function markCurrentPageHeader(exceptions){

	const urlArray = window.location.pathname.split('/');
    const section = urlArray[2];
    var menuItems = $('.menu-principal-content').find('li');

	for (const value of exceptions) {
		if(section == value){
			return;
		}
	}

    menuItems.each(function () {

        $(this).find('a').attr('href')
        var link = $(this).find('a').attr('href').includes(section)
        if (link) {
            $(this).find('a').addClass('color-brand')
        }
    });
}

function sharePage({ text, title, url }) {

	if (navigator.share) {
		navigator.share({ title, text, url })
			.then(() => console.log('Successful share'))
			.catch((error) => console.log('Error sharing', error));
	}
	else {
		const shareUrl = `mailto:?subject=${title}&body=${text} ${url}`;
		window.open(shareUrl, '_blank');
	}
}
