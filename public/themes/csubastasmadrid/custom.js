$(document).ready(function () {

	if ($('.sales-panel').length) {
		initSalesDataTables();
	}

	$('.btn-credit').on('click', function () {
		$('.btn-credit').removeClass('btn-color');
		$(this).addClass('btn-color');
	});

	$('#add-credit').on('click', function () {

		//$('input[name="credit"]').val($('.btn-credit.btn-color').data("value"));
		//$('#creditValue').text($('.btn-credit.btn-color').text());
		//Como solo quieren un botón de credito, no cal comprobar que este marcado.
		$('input[name="credit"]').val($('.btn-credit').data("value"));
		$('#creditValue').text($('.btn-credit').text());
		$.magnificPopup.open({ items: { src: '#modalCredit' }, type: 'inline' }, 0);

	});



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

	$("#frmUpdateUserInfoADV input").on('change keyup paste', function () {
		$("#frmUpdateUserInfoADV  button").prop('disabled', false);
		$("#frmUpdateUserInfoADV .delete-hid span").removeClass("hint--top").removeClass("hint--medium");
	});

	//recargar toda la suma de los lotes seleccionados
	$('.add-carrito').change(function () {
		reload_carrito();
	});

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

	$("#owl-carousel").owlCarousel({
		items: 1,
		loop: true,
		autoplay: true,
		stagePadding: 80,
		margin: 80,
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
		nav: true,
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

		$("#accerder-user").attr("disabled", "disabled");

		$.ajax({
			type: "POST",
			url: '/login_post_ajax',
			data: $('#accerder-user-form').serialize(),
			success: function (response) {
				if (response.status == 'success') {
					location.reload();
				} else {
					$(".message-error-log").text('').append(messages.error[response.msg]);
				}

			},
			complete: function () {
				$("#accerder-user").removeAttr("disabled");
			}
		});
	});

	$('#frmRegister-adv').on('submit', async function (e) {

		if (e.isDefaultPrevented()) {
			// formulario incorrecto
			var text = $(".error-form-validation").html();
			$("#insert_msgweb").html('');
			$("#insert_msgweb").html(text);
			$.magnificPopup.open({ items: { src: '#modalMensajeWeb' }, type: 'inline' }, 0);


			if ($("#recibir-newletter").is(':checked')) {
				$(".recibir-newletter").css('color', '#333');
				$(".recibir-newletter a").css('color', '#333');
			} else {
				$(".recibir-newletter").css('color', '#843534');
				$(".recibir-newletter a").css('color', '#843534');
			}

		} else {
			e.preventDefault();
			var $this = $(this);
			$(this).validator('validate');
			has_errors = verifyFormLoginContent();

			if ($("#recibir-newletter").is(':checked')) {
				$(".recibir-newletter").css('color', '#333');
				$(".recibir-newletter a").css('color', '#333');
			}

			if (has_errors) {
				$("#insert_msgweb").html('');
				$("#insert_msgweb").html(messages.error.form_error);
				$.magnificPopup.open({ items: { src: '#modalMensajeWeb' }, type: 'inline' }, 0);
			}
			else if ($("#frmRegister-adv input#dni").parent().hasClass("has-error")) {
				$("#insert_msgweb").html('');
				$("#insert_msgweb").html(messages.error.dni_incorrect);
				$.magnificPopup.open({ items: { src: '#modalMensajeWeb' }, type: 'inline' }, 0);
			} else {
				$('button', $this).attr('disabled', 'disabled');
				const captcha = await isValidCaptcha();
				if(!captcha){
					showMessage(messages.error.recaptcha_incorrect);
					return;
				}

				// Datos correctos enviamos ajax
				const formDataRegister = new FormData(this);

				$.ajax({
					type: "POST",
					url: routing.registro,
					data: formDataRegister,
					enctype: 'multipart/form-data',
					processData: false,
					contentType: false,
					beforeSend: function () {
						$('#btnRegister').prepend(' <i class="fa fa-spinner fa-pulse fa-fw margin-bottom"></i> ');
					},
					success: function (response) {
						res = $.parseJSON(response);
						if (res.err == 1) {
							$("#insert_msgweb").html('');
							$("#insert_msgweb").html(messages.error[res.msg]);
							$.magnificPopup.open({ items: { src: '#modalMensajeWeb' }, type: 'inline' }, 0);
						} else {
							window.location.href = res.msg;
						}

					},
					complete: function () {
						$('button', $this).attr('disabled', false);
					}
				});
			}


		}

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

	$('#frmUpdateUserInfoADV').on('submit', function (e) {

		e.preventDefault();
		var $this = $(this);

		const form = document.getElementById('frmUpdateUserInfoADV');
		let err = 0;

		//Capturamos solo los inputs validos
		const isValidInput = (element) => !["button", "hidden", "submit"].includes(element.type);
		const validFormElements = [...form.elements].filter(isValidInput);

		validFormElements.forEach(element => {
			if (!Boolean(element.value)) {
				err++;
				element.parentElement.classList.add('has-error', 'has-danger');
			}
			else {
				element.parentElement.classList.remove('has-error', 'has-danger');
			}
		});

		$('button', $this).attr('disabled', 'disabled');

		if (!err) {

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
						$("#insert_msgweb").html(messages.success.update_info);
						$.magnificPopup.open({ items: { src: '#modalMensajeWeb' }, type: 'inline' }, 0);
					}
				}
			});
		}
		else {

			$("#insert_msgweb").html(messages.error.generic);
			$.magnificPopup.open({ items: { src: '#modalMensajeWeb' }, type: 'inline' }, 0);

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
					$("#actual_max_bid").attr("data-winner", data.winner);
					$("#bid_modal_pujar").val(data.imp_actual);
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

        $( "#confirm_orden_lotlist" ).click(function() {
            imp = $(".precio_orden").html();
            ref = $(".ref_orden").html();
            $.ajax({
                type: "POST",
                url:  routing.ol + '-' + cod_sub,
                data: { cod_sub: cod_sub, ref: ref, imp: imp },
                success: function( data ) {
                    if (data.status == 'error'){

                        $("#insert_msg_title").html("");
                        $("#insert_msg").html(data.msg_1);
                        $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
                    }else if(data.status == 'success'){
                         $("#insert_msg_title").html("");
                        $("#insert_msg").html(data.msg);
                        $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
                    }

                }
            });


        });

        $( "#accerder-user-responsive" ).click(function(event) {
            event.preventDefault();
            $.ajax({
                type: "POST",
                url: '/login_post_ajax',
                data: $('#accerder-user-form-responsive').serialize(),
                success: function( response )
                {
                    if(response.status == 'success'){
                          location.reload();
                    }else{
                        $( ".message-error-log" ).text('').append(messages.error[response.msg]);
                    }

                }
            });
        });

        $('.lot-action_comprar_lot').on('click', function(e) {
                e.stopPropagation();
                $.magnificPopup.close();
                if (typeof cod_licit == 'undefined' || cod_licit == null )
                {
                    $("#insert_msg").html("");
                    $("#insert_msg").html(messages.error.mustLogin);
                    $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
                }else{
                    $.magnificPopup.open({items: {src: '#modalComprarFicha'}, type: 'inline'}, 0);
                }

       });

	   $( window ).resize(function() {
		if($(window).width() < 1200){
			 $('.small_square .item_lot').removeClass('col');
		}
	});
$( ".submit_carrito" ).click(function() {
          var cod_sub = $(this).attr('cod_sub');
          $( ".submit_carrito" ).html("<div class='loader mini' style='width: 20px;height: 20px;margin-top: 0px;margin-bottom: 0;'></div>");
          $( ".submit_carrito" ).attr("disabled", "disabled");

           var pay_lote = $('#pagar_lotes_'+cod_sub).serialize();
             $.ajax({
                type: "POST",
                url:  '/gateway/pagarLotesWeb',
                data: pay_lote,
                success: function(data) {
                    if(data.status == 'success'){
                        window.location.href = data.msg;
                    }else if(data.status == 'error'){
                        $("#modalMensaje #insert_msg").html('');
                        $("#modalMensaje #insert_msg").html(messages.error.generic);
                        $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
                        $( ".submit_carrito" ).html(" ");
                        ( ".submit_carrito" ).prop("disabled", false);
                    }
                },
                error: function (response){
                    $("#modalMensaje #insert_msg").html('');
                    $("#modalMensaje #insert_msg").html(messages.error.generic);
                    $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
                }
            });

        });



        $( "#save_change_orden" ).click(function() {

            var cod_sub = $(this).attr('cod_sub');
            var ref = $(this).attr('ref');
            var order = $(this).attr('order');
             $.ajax({
                    type: "POST",
                    url:  url_orden+'-'+cod_sub,
                    data: {cod_sub:cod_sub,ref:ref,imp:order},
                    success: function( res )
                    {
                        if(res.status == 'success'){
                            $("#modalMensaje #insert_msg").html('');
                            $("#modalMensaje #insert_msg").html(res.msg);
                            $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
                            change_price_saved_offers();
                        }else{
                            $("#modalMensaje #insert_msg").html('');
                            $("#modalMensaje #insert_msg").html(res.msg_1);
                            $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
                        }
                    }

                });
        });

        $('.save_orders').validator().on('submit', function (e) {
            var order;
            if (e.isDefaultPrevented()) {

            }else{
                e.preventDefault()
                var save_orders = $(this).serializeArray();
                $.each(save_orders, function(i, field){
                    $("#save_change_orden").attr(field.name,field.value);
                    if(field.name == 'order'){
                        order = field.value
                    }
                });
                $( ".precio_orden" ).html('');
                $( ".precio_orden" ).html(order);

                $.magnificPopup.open({items: {src: '#changeOrden'}, type: 'inline'}, 0);
            }
        });

        $( ".confirm_delete" ).click(function() {
            var ref = $(this).attr("ref");
            var sub = $(this).attr("sub");
            $.magnificPopup.close();
            $.ajax({
                type: "POST",
                url:  '/api-ajax/delete_order',
                data: {ref:ref,sub:sub},
                success: function( response )
                {
                    res = jQuery.parseJSON(response);
                    if(res.status == 'success'){
                        $( "#"+res.respuesta ).remove();
                        $("#insert_msg").html(messages.success[res.msg]);
                        $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
                        change_price_saved_offers();
                    }else{
                        if($( res.respuesta ).empty()){
                            $( "#"+res.respuesta +" .form-group-custom input" ).addClass("has-error-custom");
                        }
                        $("#insert_msg").html(messages.error[res.msg]);
                        $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
                    }

                }

            });
        });

        $( ".delete_order" ).click(function() {
            var ref = $(this).attr("ref");
            var sub = $(this).attr("sub");
            $(".confirm_delete").attr( "price", $("#"+sub+"-"+ref+" input").val() );
            $(".confirm_delete").attr( "ref", ref );
            $(".confirm_delete").attr( "sub", sub );
            $("#insert_msg_delete").html(messages.neutral.confirm_delete);
            $.magnificPopup.open({items: {src: '#modalMensajeDelete'}, type: 'inline'}, 0);
        });

        $( "#form-valoracion-adv" ).submit(async function(event) {


            if (event.isDefaultPrevented()) {
                if($("#condiciones").is(':checked')){
                   $(".condiciones").css('color', '#333');
                   $(".condiciones a").css('color', '#333');
                }else{
                   $(".condiciones").css('color', '#843534');
                   $(".condiciones a").css('color', '#843534');
                }

            } else {
                event.preventDefault();

                const captcha = await isValidCaptcha();
				if(!captcha){
					showMessage(messages.error.recaptcha_incorrect);
					return;
				}

                if($("#condiciones").is(':checked')){
                   $(".condiciones").css('color', '#333');
                   $(".condiciones a").css('color', '#333');
                }

                formData = new FormData(this);
                var max_size = 20;
                var size = 0;
                $( event.target.files.files ).each(function( index, element ) {

                   size = parseInt((element.size/1024/1024).toFixed(2)) + parseInt(size);
                   //console.log(size);
               });
               $(".loader").removeClass('hidden');
                if($("#condiciones").is(':checked')){
                   $(".condiciones").css('color', '#333');
                   $(".condiciones a").css('color', '#333');
                }
               if(size < max_size){
                   $.ajax({
                    type: "POST",
                    url:  "valoracion-articulos-adv",
                    data: formData ,
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    success: function(result) {
                        if(result.status == 'correct'){
                            window.location.href =  result.url;
                        }else if(result.status == 'error_size'){
                            $(".loader").addClass('hidden');
                            $("#modalMensaje #insert_msg").html('');
                            $("#modalMensaje #insert_msg").html(messages.error[result.msg]);
                            $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
                        }else{
                            $(".loader").addClass('hidden');
                            $(".msg_valoracion").removeClass('hidden');
                        }
                    },
                    error: function(result) {
                       $(".loader").addClass('hidden');
                       $(".msg_valoracion").removeClass('hidden');
                    }
                });
               }else{
                   $(".loader").addClass('hidden');
                   $("#insert_msg").html(messages.error.max_size_img);
                   $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
               }
            }
        });

        $('.content_item_mini').hover(function(e){

            var el, newPos,capaOculta, vwCapaOculta, vwWindow ;
            el = $(this)
            posEl = el.offset()
            capaOculta = $(this).siblings($('.capaOculta'))
            capaOculta.show()
            posLeft = posEl.left
            vwWindow = $(window).width() / 2

          if (posLeft > vwWindow ){
            vwCapaOculta = ($('.capaOculta').width() / 2);
            newPos = posLeft - vwCapaOculta;
            newpos2 = ($('.capaOculta').offset().left - vwCapaOculta)  - 90
            capaOculta.css("left", newpos2 + 'px' );


          }else {

             newpos2 = 0
          }
              capaOculta.css("left", newpos2 + 'px' );

          var posElTop = el.offset().top
              vhWindow = $(window).height() / 2

            if(posElTop > vhWindow){
                    console.log(vhWindow)
                        if($(document).scrollTop()>200){

                    }

                    var newPosTop = -400 + ($(document).scrollTop());

                    capaOculta.css("top", '-400px' );

            }


          }, function(){
              var capaOculta = $(this).siblings($('.capaOculta'))
              capaOculta.hide()
        })

        $(".formsearch").submit(function(){
            $(".fa-search").addClass("hidden");
            $('.search-loader').show();
        });

         $('#newsletter-btn-panel').on('click',function() {
            var email = $('.newsletter-input').val();
            var lang = $('#lang-newsletter').val();
            var families = [];
             //coge los ocultos
            $( "input.newsletter" ).each(function( index ) {
                if($(this).is(':checked')) {
                     families.push($( this ).val());
                }
            });

            $.ajax({
                type: "POST",
                data: {email : email, families: families, lang:lang, no_validate:true, condiciones: true},
                url: '/api-ajax/newsletter/add',
                beforeSend: function () {
                },
                success: function( msg ) {
                        if(msg.status == 'success'){
                            $('.insert_msg').html(messages.success[msg.msg]);
                        }else{
                            $('.insert_msg').html(messages.error[msg.msg]);
                        }
                        $.magnificPopup.open({items: {src: '#newsletterModal'}, type: 'inline'}, 0);
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


		if (event.isDefaultPrevented()) {
			if ($("#condiciones").is(':checked')) {
				$(".condiciones").css('color', '#333');
				$(".condiciones a").css('color', '#333');
			} else {
				$(".condiciones").css('color', '#843534');
				$(".condiciones a").css('color', '#843534');
			}

		} else {
			event.preventDefault();

			var $captcha = $('#recaptcha'),
				response = grecaptcha.getResponse();

			if (response.length === 0) {
				if (!$captcha.hasClass("error")) {
					$captcha.addClass("error");
				}
				return;
			} else {
				$captcha.removeClass("error");
			}

			if ($("#condiciones").is(':checked')) {
				$(".condiciones").css('color', '#333');
				$(".condiciones a").css('color', '#333');
			}

			formData = new FormData(this);
			var max_size = 20;
			var size = 0;
			$(event.target.files.files).each(function (index, element) {

				size = parseInt((element.size / 1024 / 1024).toFixed(2)) + parseInt(size);
				//console.log(size);
			});
			$(".loader").removeClass('hidden');
			if ($("#condiciones").is(':checked')) {
				$(".condiciones").css('color', '#333');
				$(".condiciones a").css('color', '#333');
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
							$(".loader").addClass('hidden');
							$("#modalMensaje #insert_msg").html('');
							$("#modalMensaje #insert_msg").html(messages.error[result.msg]);
							$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
						} else {
							$(".loader").addClass('hidden');
							$(".msg_valoracion").removeClass('hidden');
						}
					},
					error: function (result) {
						$(".loader").addClass('hidden');
						$(".msg_valoracion").removeClass('hidden');
					}
				});
			} else {
				$(".loader").addClass('hidden');
				$("#insert_msg").html(messages.error.max_size_img);
				$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
			}
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

	$(".formsearch").submit(function () {
		$(".fa-search").addClass("hidden");
		$('.search-loader').show();
	});

	$('#newsletter-btn-panel').on('click', function () {
		var email = $('.newsletter-input').val();
		var lang = $('#lang-newsletter').val();
		var families = [];
		//coge los ocultos
		$("input.newsletter").each(function (index) {
			if ($(this).is(':checked')) {
				families.push($(this).val());
			}
		});

		$.ajax({
			type: "POST",
			data: { email: email, families: families, lang: lang, no_validate: true, condiciones: true },
			url: '/api-ajax/newsletter/add',
			beforeSend: function () {
			},
			success: function (msg) {
				if (msg.status == 'success') {
					$('.insert_msg').html(messages.success[msg.msg]);
				} else {
					$('.insert_msg').html(messages.error[msg.msg]);
				}
				$.magnificPopup.open({ items: { src: '#newsletterModal' }, type: 'inline' }, 0);
			}
		});
	});

	$('.js-btn-fav-grid').length && $('.js-btn-fav-grid').on('click', favLotFromGrid);
});


function cerrarLogin(){
  $('.login_desktop').fadeToggle("fast");
}

function ajax_carousel(key, replace) {
	$("#" + key).siblings().removeClass('hidden');
	$.ajax({
		type: "POST",
		url: "/api-ajax/carousel",
		data: { key: key, replace: replace },
		success: function (result) {

			if(!result){
				$(`.${key}`).hide();
				return;
			}

			$("#" + key).html(result);

			carrousel_molon($("#" + key));

			$('[data-countdown]').each(function () {
				$(this).data('ini', new Date().getTime());
				countdown_timer($(this));
			});
		},
		complete: function () {
			$("#" + key).siblings('.loader').addClass('hidden');
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


function carrousel_molon(carrousel) {
	carrousel.owlCarousel({
		items: 3,
		autoplay: true,
		autoplayHoverPause: true,
		margin: 20,
		loop: true,
		dots: false,
		nav: true,
		navText: ['<i class="fa fa-angle-left visible-lg">', '<i class="fa fa-angle-right visible-lg">'],
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

function password_recovery(lang) {
	var pass_recov = $("#password_recovery").serializeArray();
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

function favLotFromGrid(event) {

	event.preventDefault();
	const buttonElement = event.currentTarget;
	const iconElement = buttonElement.querySelector('i');
	const {isFavorite, refAsigl0, codSub} = buttonElement.dataset;
	const action = (isFavorite === "true") ? "remove" : "add";
	buttonElement.disabled = true;

	$.ajax({
		type: "GET",
		url: `/api-ajax/favorites/${action}`,
		data: { cod_sub: codSub, ref: refAsigl0 },
		success: function( data ) {
			if (data.status == 'error'){

				$("#insert_msg").html("");
				$("#insert_msg").html(messages.error[data.msg]);
				$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);

			}else if(data.status == 'success'){
				buttonElement.dataset.isFavorite = (isFavorite === "true") ? "false" : "true";
				iconElement.classList.toggle('fa-star-o');

				$("#insert_msg").html("");
                $("#insert_msg").html(messages.success[data.msg] );
				$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
			}
		},
		complete: function() {
			buttonElement.disabled = false;
		}
	});
}

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
				$( '.'+ref+'-'+cod_sub).remove();
                $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
				if (action == 'remove' && $('#'+cod_sub+' .panel-body .table tbody tr').length <= 0) {
					$('#'+cod_sub).remove();
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
	});

	/* $('.add_factura').change(function() {
		reload_facturas();
	}); */

	/* $("#submit_fact").click(function () {
		$("#submit_fact").addClass('hidden');
		$("#submit_fact").siblings().removeClass('hidden');
		var pay_fact = $('#pagar_fact').serializeArray();
		var total = 0;
		$.each(info_fact, function (index_anum, value_anum) {
			$.each(value_anum, function (index_num, value_num) {
				$.each(value_num, function (efect_num, value_efect) {
					if ($("#checkFactura-" + index_anum + "-" + index_num + "-" + efect_num + "").is(":checked")) {
						total = total + info_fact[index_anum][index_num][efect_num];
					}
				});
			});
		});
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
							$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
							$("#submit_carrito").removeClass('hidden');
							$("#submit_carrito").siblings().addClass('hidden');
						}

				},
				error: function (response) {
					$("#modalMensaje #insert_msg").html('');
					$("#modalMensaje #insert_msg").html(messages.error.generic);
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
					$("#submit_fact").removeClass('hidden');
					$("#submit_fact").siblings().addClass('hidden');
				}
			});
		}

	}); */

});

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

/* function reload_facturas(){
	var total = 0;
   $.each(info_fact, function (index_anum, value_anum) {
		$.each(value_anum, function (index_num, value_num) {
			$.each(value_num, function (efect_num, value_efect) {
				if ($("#checkFactura-" + index_anum + "-" + index_num + "-" + efect_num + "").is(":checked")) {
					total = total + info_fact[index_anum][index_num][efect_num];
				}
			});
		});
	});
	if(total>0){
		$("#submit_fact").removeClass('hidden');
	}else{
		$("#submit_fact").addClass('hidden');
	}
	$("#total_bills").html(change_currency(total));
} */

function change_currency(price) {

	var price = numeral(price).format('0,0.00');
	return price;
}

function recaptcha_callback() {
	$('#recaptcha').removeClass("error");
};



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

function initSalesDataTables() {

	$('.table').DataTable({
		searching: false,
		paging: false,
		info: false,
		//responsive: true,
		columnDefs: [
			{ orderable: false, targets: 0 }
		],
		order: [[1, 'asc']],
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

	var $captcha = $('#recaptcha');

	const recatchaIsVerified = Boolean(grecaptcha.getResponse());
	$captcha[0].classList.toggle("error", !recatchaIsVerified);

	if (!recatchaIsVerified) {
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
	$("#total_bills").html(change_currency(total));
	// Hacer la línea $("#total_bills").html(change_currency(total)); sin jquery

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

$('.panel-collapse').on('show.bs.collapse', function () {
	var id = $(this).attr('id')
	$(this).siblings('.user-accounte-titles-link[data-id="' + id + '"]').find('.label-close').show()
	$(this).siblings('.user-accounte-titles-link[data-id="' + id + '"]').find('.label-open').hide()
})
$('.panel-collapse').on('hide.bs.collapse', function () {
	var id = $(this).attr('id')
	$(this).siblings('.user-accounte-titles-link[data-id="' + id + '"]').find('.label-close').hide()
	$(this).siblings('.user-accounte-titles-link[data-id="' + id + '"]').find('.label-open').show()
})

$(document).ready(function () {

	$("#square").on('click', function(){
		seeLot('img');
	});
	$("#square_mobile").on('click', function(){
		seeLot('img');
	});
	$("#small_square").on('click', function(){
		seeLot('small_img');
	});
	$("#large_square").on('click', function(){
		seeLot('desc');
	});
	$("#large_square_mobile").on('click', function(){
		seeLot('desc');
	});

	if($('[name=lot_see_configuration]').length) {
		seeLot($('[name=lot_see_configuration]').val(), false);
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

function readMoreDescription($element, lineNum) {
	const $readMore = $('.see-more');
	const $hideMore = $('.hide-more');

	const lines = lineNum || 3;
	const textMinHeight = "" + (parseInt($element.css("line-height"), 19) * lines) + "px";
	const textMaxHeight = "" + $element.css("height");

	if (parseInt($element.css("height")) > 52 && $(document).width() > 768) {
		$element.css("height", "" + textMaxHeight);
		$element.css("transition", "height .5s");
		$element.css("height", "" + textMinHeight);

		$readMore.removeClass('hidden');

		$readMore.on('click', function () {
			$element.css("height", "" + textMaxHeight);
			$readMore.addClass('hidden');
			$hideMore.removeClass('hidden');
		});

		$hideMore.on('click', function () {
			$element.css("height", "" + textMinHeight);
			$readMore.removeClass('hidden');
			$hideMore.addClass('hidden');
		});
	}
}
