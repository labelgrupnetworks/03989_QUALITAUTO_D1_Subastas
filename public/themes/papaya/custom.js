
function verifyLang(actualLang){

    //comprobamos si el idoma actual de label a cambiado
    if(actualLang !== sessionStorage.getItem('lang')){

        sessionStorage.setItem('lang', actualLang)
        var iframe = document.getElementsByClassName('goog-te-banner-frame')[0];

        if(!iframe) return;


        //seteamos el nuevo idoma
        //nativo de label
        var innerDoc = iframe.contentDocument || iframe.contentWindow.document;
        var restore_el = innerDoc.getElementsByTagName("button");

        for(var i = 0; i < restore_el.length; i++){
          if(restore_el[i].id.indexOf("restore") >= 0) {
            restore_el[i].click();
            var close_el = innerDoc.getElementsByClassName("goog-close-link");
            close_el[0].click();
            return;
          }
        }
    }
}

function loadVideo(video) {
	$('#video_main_wrapper').empty();
	$('.img-global-content').hide();
	$videoDom = $('<video width="100%" height="auto" autoplay="true" controls>').append($(`<source src="${video}">`));
	$('#video_main_wrapper').append($videoDom);
	$('#video_main_wrapper').show();
}

function reloadDate(){
	$('#this-moment').text(new Date().toLocaleDateString('es-ES', {timeZoneName:"short",weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour:'numeric', minute:'numeric', second: 'numeric'}));
}

function openMultipleBiddersModal() {

	//clean modal
	document.querySelectorAll('#biddersForm > div:not(:first-child)').forEach(element => element.remove());

	//add this puja
	const bidImport = $('#bid_amount_firm').val();
	$importInput = document.getElementById('multipleBidders').querySelector('[name="import"]');
	$importInput.value = bidImport;
	$importInput.setAttribute('max', bidImport);

	//open modal
	$.magnificPopup.open({items: {src: '#multipleBidders'}, type: 'inline'}, 0)
}


$(function () {

	setInterval(reloadDate, 1000);

	$('#multipleBiddersLink').on('click', (event) => {
		$.magnificPopup.close();
		setTimeout(openMultipleBiddersModal, 200);
	});

    $('.panel-collapse').on('show.bs.collapse', function () {
        var id = $(this).attr('id')
        console.log($(this))
        $(this).siblings('.user-accounte-titles-link[data-id="'+id+'"]').find('.label-close').show()
        $(this).siblings('.user-accounte-titles-link[data-id="'+id+'"]').find('.label-open').hide()
      })
      $('.panel-collapse').on('hide.bs.collapse', function () {
        var id = $(this).attr('id')
        $(this).siblings('.user-accounte-titles-link[data-id="'+id+'"]').find('.label-close').hide()
        $(this).siblings('.user-accounte-titles-link[data-id="'+id+'"]').find('.label-open').show()
      })


    //Reestylin google tranlate
    $('#google_translate_element').on("click", function () {

        // Change font family and color
        $("iframe").contents().find(".goog-te-menu2-item div, .goog-te-menu2-item:link div, .goog-te-menu2-item:visited div, .goog-te-menu2-item:active div, .goog-te-menu2 *")
            .css({
                'color': '#544F4B',
                'font-family': 'Roboto',
								'width':'100%'
            });
        // Change menu's padding
        $("iframe").contents().find('.goog-te-menu2-item-selected').css ('display', 'none');

				// Change menu's padding
        $("iframe").contents().find('.goog-te-menu2').css ('padding', '0px');

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




$('#button-open-user-menu').click(function() {
    $('#user-account-ul').toggle()
})
    $('.newsletter-input').focus(function(){
        $('.newsletter-placeholder').fadeOut()
    })
    $('.newsletter-input').focusout(function(){
        if($(this).val().length > 0) {
            $('.newsletter-placeholder').hide()
        }else{
            $('.newsletter-placeholder').show()
        }
    })

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
        $(this).fadeOut(500)
        $('.search-header-close').css('left', '0')
        $('.search-header-close').addClass('bounceInLeft')


    })

    $('.search-header-close').click(function () {
        $('.menu-principal-search').removeClass('active')
        $('.search-header-close').css('left', '-90px')
        $('.search-header-close').removeClass('bounceInLeft')
        $('.search-header').fadeIn()
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
    $(document).scroll(function (e) {
        if ($(document).scrollTop() > 33) {
            $('header').addClass('fixed w-100 top-0 left-0')
        }
        if ($(document).scrollTop() <= 33) {
            $('header').removeClass('fixed w-100 top-0 left-0')
        }
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
        stagePadding: 0,
        margin: 0,
        dots: true,
        nav: false,
        animateOut: 'fadeOut',
        slideTransition: 'ease',

        navText: ['<i class="fas fa-chevron-left visible-lg">', '<i class="fas fa-chevron-right visible-lg">']
    });

    $(".owl-carousel-home").owlCarousel({
        items: 4,
        loop: true,
        autoplay: true,
        margin: 20,
        padding: 20,
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
    $('.btn_login').on('click', function () {
        $('.login_desktop').fadeToggle("fast");
        $('.login_desktop [name=email]').focus();
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
                    location.reload();
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

    $('#condiciones').change(function () {
        $('#condiciones').siblings('span').removeClass('text-danger')
        $('#condiciones').siblings('span').find('a').removeClass('text-danger')
    })

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


	$('.lot-action_info_lot').on('click', function (e) {
        e.stopPropagation();
        $.magnificPopup.close();
        if (typeof cod_licit == 'undefined' || cod_licit == null) {
            $("#modalMensaje #insert_msg").html("");
            $("#modalMensaje #insert_msg").html(messages.error.need_login);
			$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
			return;
		}

		$.ajax({
			type: "POST",
			url: '/api-ajax/info-lot-email',
			data: {cod_licit: cod_licit, cod_sub: cod_sub, ref: ref},
			success: function( data ) {

				$("#insert_msg").html("");

				if (data.status == 'error'){
						$("#modalMensaje #insert_msg").html(data.msg);
						$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
				}
				else if(data.status == 'success'){
					$("#modalMensaje #insert_msg").html(data.msg);
					$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
				}


			}
		});

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
        $('#images').remove()
        $(".loader").removeClass("hidden");
        $("#valoracion-adv").addClass("hidden");
        event.preventDefault();
        formData = new FormData(this);


        var max_size = 2000;
        var size = 0;

        $("#form-valoracion-adv").find('input[type="file"]').each(function (index, element) {

            $(element.files).each(function(index, el){

                size = size + ((el.size / 1024))
            })
        });

        if (Math.floor(size) < max_size) {
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
                    } else if (result.status == 'error_size'  || result.status == 'error_no_image' ) {
                        $("#modalMensaje #insert_msg").html('');
                        $("#modalMensaje #insert_msg").html(messages.error[result.msg]);
                        $.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
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
        } else {
            $(".loader").addClass("hidden");
            $("#valoracion-adv").removeClass("hidden");
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
            if ($(document).scrollTop() > 200) {

            }

            var newPosTop = -400 + ($(document).scrollTop());

            capaOculta.css("top", '-400px');

        }


    }, function () {
        var capaOculta = $(this).siblings($('.capaOculta'))
        capaOculta.hide()
    })

});

function cerrarLogin() {
    $('.login_desktop').fadeToggle("fast");
}

function ajax_carousel(key, replace) {
	//$( "#"+key ).siblings().removeClass('hidden');
	$("#navs-arrows").addClass('hidden');
    $.ajax({
        type: "POST",
        url: "/api-ajax/carousel",
        data: { key: key, replace: replace, size: true },
        success: function (result) {

            if(result.size > 8){
                $("#navs-arrows").removeClass('hidden');
            }

            if(result.contents === ''){
                $("#" + key + '-content').hide()
            }
            $("#" + key).siblings('.loader').addClass('hidden');
            $("#" + key).html(result.contents);
            if(key === 'lotes_recomendados'){
                carrousel_molon_new($("#" + key));
            }else{

                setTimeout(function(){
                    carrousel_molon($("#" + key));
                }, 100);

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

countdown_timer = function(countdown) {

	//SI ESTA PARADO NO DEBE HACER NADA
	if (typeof countdown.data('stop') != 'undefined' && countdown.data('stop') == 'stop') {
		return;
	}

	if(typeof countdown.data('ini') == 'undefined'){
		$('[data-countdown]').each(function () {
			$(this).data('ini', new Date().getTime());
			countdown_timer($(this));
		});
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
    if(carrousel.data('hasSlick')){
        carrousel.slick('unslick');
    }
    carrousel.slick({
        "slidesToScroll": 1, "rows": 2, "slidesPerRow": 4,
        arrows: true,
        prevArrow: $('.fa-chevron-left'),
        nextArrow: $('.fa-chevron-right'),
        responsive: [
            {
              breakpoint: 1024,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
                infinite: true,
                dots: true,
                rows: 1,
                "slidesPerRow": 3,
              }
            },
            {
              breakpoint: 600,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 2,
                rows: 1,
                "slidesPerRow": 2,
              }
            },
            {
              breakpoint: 599,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                rows: 1,"slidesPerRow":1,
              }
            }

          ]
    });

    carrousel.data('hasSlick', true);

};

function carrousel_molon_new(carrousel) {

	$("#navs-arrows").removeClass('hidden');
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
		slidesToShow: 4,
		arrows: true,
		prevArrow: $('.owl-prev'),
		nextArrow: $('.owl-next'),
		responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 3,
					infinite: true,
					dots: true,
					rows: 1,
				}
			},
			{
				breakpoint: 600,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2,
					rows: 1,
				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					rows: 1,
				}
			}

		]
	});

	carrousel.data('hasSlick', true);
}


/*
function carrousel_molon_new(carrousel) {
    carrousel.owlCarousel({
        items: 2,
        autoplay: true,
        margin: 0,
        dots: false,
        nav: true,
        navText: ['<i class="fas fa-chevron-left">', '<i class="fas fa-chevron-right">'],
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
                items: 4
            }
        }
	});};
	*/

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

    var formatted = $.datepicker.formatDate("dd ", fecha) + mes + " "  + $.datepicker.formatDate("yy", fecha) + " " + text + " " + horas + ":" + minutos + " h";
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
        $.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
        return;
    } else {
        $.ajax({
            type: "GET",
            url: routing.favorites + "/" + action,
            data: { cod_sub: cod_sub, ref: ref, cod_licit: cod_licit },
            success: function (data) {
                $('.lds-ellipsis').hide()


                if (data.status == 'error') {
                    $("#insert_msg").html("");
                    $("#insert_msg").html(messages.error[data.msg]);
                    $.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
                } else if (data.status == 'success') {
                    $("#insert_msg").html("");
                    $("#insert_msg").html(messages.success[data.msg]);
                    $.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
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

    $('.lazy').Lazy({
        scrollDirection: 'vertical',
        effect: 'fadeIn',
        visibleOnly: true,
    });

    $('.add_factura').change(function () {
        reload_facturas();
    });

    $("#submit_fact").click(function () {
        $("#submit_fact").addClass('hidden');
        $("#submit_fact").siblings().removeClass('hidden');
        var pay_fact = $('#pagar_fact').serializeArray();
        var total = 0;

        for (const factura of pendientes) {
            if($(`#checkFactura-${factura.anum_pcob}-${factura.num_pcob}-${factura.efec_pcob}`).is(":checked")){
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

    });

    $('#full-screen').click(function() {
        if($('.filters-auction-content').css('display') === 'none'){
            $('.filters-auction-content').parent().show()
        $('.filters-auction-content').show("slide", { direction: "left" }, 500);

        $('.list_lot_content').removeClass('col-md-12').addClass('col-md-9')
        $('.square').removeClass('col-lg-3').addClass('col-lg-4')
        $(this).removeClass('active')

        }else{
        $('.filters-auction-content').hide("slide", { direction: "left" }, 500, function() {
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

function reload_facturas() {
    var total = 0;

    for (const factura of pendientes) {
        if($(`#checkFactura-${factura.anum_pcob}-${factura.num_pcob}-${factura.efec_pcob}`).is(":checked")){
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
    if (nuevaVentana ) {
        nuevaVentana .focus();
    }
}


(function($) {



  $.fn.thumbPopup = function(options) {

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
      }, function(event) {
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

      /*	if (windowSize.width + windowSize.scrollLeft < event.pageX + popupSize.width + settings.cursorLeftOffset){
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

})(jQuery);

function newsletterSuscription (event) {
	const email = $('.newsletter-input').val();
	const lang = $('#lang-newsletter').val();
	$("#insert_msgweb").html('');

	if (!$('#condiciones').prop("checked") || !$('#bool__0__comercial').prop("checked")) {
		$("#insert_msgweb").html(messages.neutral.accept_condiciones);
		$.magnificPopup.open({ items: { src: '#modalMensajeWeb' }, type: 'inline' }, 0);
		return;
	}

	if (!comprueba_email(email, 1)) {
		$("#insert_msgweb").html(messages.error.email_invalid);
		$.magnificPopup.open({ items: { src: '#modalMensajeWeb' }, type: 'inline' }, 0);
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
		error: function(error) {
			$('.insert_msg').html(messages.error.message_500);
			$.magnificPopup.open({ items: { src: '#newsletterModal' }, type: 'inline' }, 0);
		}
	});
}

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

function askConfirmModal(message, title, callback) {
	$("#modalMensajeWebConfirm .modal-message").html(message);
	$("#modalMensajeWebConfirm .modal-title").html(title);
	$("#modalMensajeWebConfirm .modal-submit").off('click').on('click', callback);
	$.magnificPopup.open({ items: { src: '#modalMensajeWebConfirm' }, type: 'inline' }, 0);
}
