  $(document).ready(function(){

      $("#country").change(function(){
        var selected_country = $("#frmRegister-adv #country").val();
        if(selected_country=="ES"){
            $('#dni').prop("required", true);
            $('#cpostal').prop("required", true);
        }else{
            $('#dni').removeAttr("required");
            $('#cpostal').removeAttr("required");
        }
      });

      $('.lazy').Lazy({
	// your configuration goes here
	scrollDirection: 'vertical',
	effect: 'fadeIn',
	visibleOnly: true,
	onError: function(element) {
		console.log('error loading ' + element.data('src'));
	}
});

      $('.switcher').click(function() {
        $(this).toggleClass('switcher-active');

    });

    jQuery( "#confirm_orden" ).on( "tap", function( event ) { alert() } )

    $('.tabs-custom ul li').click(function(e){

        var elFather =  $(this).parents('.tabs-custom');
        var elBro =  $(elFather).siblings('.tab-content');


        $(elBro).fadeOut(200);
        $('.loading-page').show(500);
        if($('.adj').length){
            $('.adj').hide();
        }

    });
            $(document).scroll(function(e){
                if ($(document).scrollTop() > 100 ){
                    $('.button-up').show(500)
                }
                if ($(document).scrollTop() <= 100 ){
                    $('.button-up').hide(500)
                }
            })


            $('.button-up').click(function(){

                $('html,body').animate({scrollTop: 0}, 500);
            });

	  $("#owl-carousel").owlCarousel({
	      items:1,
	      loop: true,
	      autoplay:true,
	      dots:true,
	      nav: true,
	      navText: ['<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>', '<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>']
	  });
	  $(".owl-carousel-home").owlCarousel({
	      items:4,
	      loop: true,
	      autoplay:true,
	      margin: 20,
	      dots:false,
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
	      items:4,
	      loop: true,
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
	          },
	      }
	  });

        $("#owl-carousel-responsive").owlCarousel({
            items:1,
            autoplay:true,
            margin: 20,
            dots:true,
            nav: false,
	    responsiveClass: true,
        });


	$('.login').on('click', function(){
		$('#loginResponsive').removeClass('fadeOutDown');
		$('#loginResponsive').show().addClass('animated fadeInDown');
	});
	$('#closeResponsive').on('click', function(){
		$('#loginResponsive').addClass('animated fadeOutDown').removeClass('fadeInDown');
	})
	$('#btnResponsive').on('click', function(){
	  $('#menuResponsive').show().addClass('animated fadeInRight').removeClass('fadeOutRight');
	});
	$('#btnResponsiveClose').on('click', function(){
	  $('#menuResponsive').addClass('animated fadeOutRight').removeClass('fadeInRight');
	});
	$('.btn_login_desktop').on('click', function(){
	  $('.login_desktop').fadeToggle("fast");
	});
	$('.closedd').on('click', function(){
	  $('.login_desktop').fadeToggle("fast");
	});

        $( "#accerder-user" ).click(function() {
         $.ajax({
                type: "POST",
                url: '/login_post_ajax',
                data: $('#accerder-user-form').serialize(),
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

        $("#accerder-user-form input[name='password']").on('keyup', function (e) {
            if (e.keyCode == 13) {
                $( "#accerder-user" ).click()
            }
        });

        $("#accerder-user-form-responsive input[name='password']").on('keyup', function (e) {
            if (e.keyCode == 13) {
                $( "#accerder-user-responsive" ).click()
            }
        });

        $('#frmRegister-adv').validator().on('submit', function (e) {
            if (e.isDefaultPrevented()) {
                // formulario incorrecto
                var text = $(".error-form-validation").html();
                $("#insert_msgweb").html('');
                $("#insert_msgweb").html(text);
                $.magnificPopup.open({items: {src: '#modalMensajeWeb'}, type: 'inline'}, 0);
            } else {
                e.preventDefault();
                var $this = $(this);
                verifyFormLoginContent();
                if($("#frmRegister-adv input#dni").parent().hasClass( "has-error" )){
                    $("#insert_msgweb").html('');
                    $("#insert_msgweb").html(messages.error.dni_incorrect);
                    $.magnificPopup.open({items: {src: '#modalMensajeWeb'}, type: 'inline'}, 0);
                }else{
                    $('button', $this).attr('disabled', 'disabled');
                  // Datos correctos enviamos ajax
                  $.ajax({
                          type: "POST",
                          url: routing.registro,
                          data: $('#frmRegister-adv').serialize(),
                          beforeSend: function () {
                                  $('#btnRegister').prepend(' <i class="fa fa-spinner fa-pulse fa-fw margin-bottom"></i> ');
                          },
                          success: function( response ) {
                                  $('button', $this).attr('disabled', false);
                                  res = jQuery.parseJSON(response);
                                  if(res.err == 1) {
                                        $("#insert_msgweb").html('');
                                        $("#insert_msgweb").html(messages.error[res.msg]);
                                        $.magnificPopup.open({items: {src: '#modalMensajeWeb'}, type: 'inline'}, 0);
                                  } else {
                                          if (res.redirect != undefined) {
                                            $("#info_sent").val(JSON.stringify(res.info));
                                            $("#cod_auchouse_sent").val(res.cod_auchouse);
                                            $("#redirect_sent").val(res.redirect);

                                            document.getElementById("formToSubalia").submit();
                                        } else {
                                            window.location.href = res.msg;
                                        }
                                  }

                          }
                      });
                }


            }

        });

        $('#newsletter-btn').on('click',function() {
            var email = $('.newsletter-input').val();
            var lang = $('#lang-newsletter').val();
            var families = [];
             //coge los ocultos
            $( ".newsletter" ).each(function( index ) {
                     families.push($( this ).val());
            });

            var entrar = true;

            if($('#condiciones').prop( "checked" )){
                    entrar = true;
            }

            if(entrar){
                    $.ajax({
                    type: "POST",
                    data: {email : email, newsletter: families,lang:lang, condiciones:true},
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
            } else {
                $("#insert_msgweb").html('');
                $("#insert_msgweb").html(messages.neutral.accept_condiciones);
                $.magnificPopup.open({items: {src: '#modalMensajeWeb'}, type: 'inline'}, 0);
            }
	});

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
		        success: function( response ) {

		        	$('button', $this).attr('disabled', false);

		        	res = jQuery.parseJSON(response);

		        	if(res.err == 1) {
                                        $('.insert_msg').html('<div class="alert alert-danger">'+messages.error[res.msg]+'</div>');
		        	} else {
		        		$('.insert_msg').html('<div class="alert alert-success">'+messages.success[res.msg]+'</div>');
		        	}

		        }
		    });

	    }
	});

        $('#frmUpdateUserInfoADV').validator().on('submit', function (e) {

		    if (e.isDefaultPrevented()) {
		        var text = $(".error-form-validation").html();
                        $("#insert_msgweb").html('');
                        $("#insert_msgweb").html(text);
                        $.magnificPopup.open({items: {src: '#modalMensajeWeb'}, type: 'inline'}, 0);
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
			        success: function( response ) {

			        	$('button', $this).attr('disabled', false);

			        	res = jQuery.parseJSON(response);

                                        if(res.err == 1) {
                                            $('.col_reg_form').html('<div class="alert alert-danger">'+messages.error[res.msg]+'</div>');
                                        } else {
                                            $('.col_reg_form').html('<div class="alert alert-success">'+messages.success[res.msg]+'</div>');
                                        }
                                    }

			    });

		    }
	});



        $( "#confirm_orden" ).click(function() {

            imp = $("#bid_modal_pujar").val();
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
                        $("#tuorden").html(data.imp);
                        $("#text_actual_no_bid").addClass("hidden");
                        $("#text_actual_max_bid").removeClass("hidden");
                        $("#actual_max_bid").html(data.open_price);
                        $("#insert_msg_title").html("");
                        $("#insert_msg").html(data.msg);
                        $(".hist_new").removeClass("hidden");
                        $(".custom").removeClass("hidden");
                        $("#bid_modal_pujar").val(data.imp_actual);
                        if(data.winner){
                            $(".no_winner").addClass("hidden");
                            $(".winner").removeClass("hidden");
                        }else{
                            $(".no_winner").removeClass("hidden");
                            $(".winner").addClass("hidden");
                        }
                        $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
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

       $( "#large_square" ).click(function() {
            see_desc();
        });

        $( "#square" ).click(function() {
              see_img();
        });

        $( "#small_square" ).click(function() {
              see_img_samll();
        });

        $( "#square_mobile" ).click(function() {
              see_img();
        });

        $( "#large_square_mobile" ).click(function() {
              see_desc();
        });

        if($.cookie('lot') == 'desc'){
            see_desc();
        }else if($.cookie('lot') == 'img'){
            see_img();
        }else if($.cookie('lot') == 'small_img'){
            see_img_samll();
        }else{
            see_img();
        }

        $( window ).resize(function() {
            if($(window).width() < 1200){
                 $('.small_square .item_lot').removeClass('col');
            }
        });


        var precio_final = 0;
        var send_pay = [];


        $('.add-carrito').change(function() {
            var id = $(this).attr("id");
            if($("input:checkbox[name='carrito["+id+"]']").is(':checked')){
                $( ".toggle-"+id+"" ).fadeIn( "slow" );
            }else{
                $( ".toggle-"+id+"" ).fadeOut( "slow" );
            }
            reload_carrito();
        });

        $('.envios').on('change', function() {
            reload_carrito();
          });

           $('.seguro').on('change', function() {
            reload_carrito();
          });

          function reload_carrito(){
              var precio_final = 0;
               var id_seguro=0
            $('input.add-carrito[type=checkbox]').each(function(){
                var id = $(this).attr("id");
                var id_envio = $("input:radio[name='envios["+id+"]']:checked").val();
                if(id_envio > 0){
                    id_seguro = $("input:radio[name='seguro["+id+"]']:checked").val();
                    $('.seguros').removeClass('hidden');
                }else{
                    id_seguro = 0;
                    $('.seguros').addClass('hidden');
                }
                var precio = $("#"+id).attr( "price" );
                 if($("#"+id).is(':checked')) {
                      precio_final = parseFloat(precio) + parseFloat(precio_final) + parseFloat(id_envio) + parseFloat(id_seguro);
                 }
                 $(".precio_final").text(precio_final.toFixed(2));
            })

            if(precio_final <= 0){
                $( "#submit_carrito" ).attr("disabled", "disabled");
            }else{
                 $( "#submit_carrito" ).removeAttr("disabled");
            }
          }



        $( "#submit_carrito" ).click(function() {
            $( "#submit_carrito" ).html("<div class='loader'></div>");
              $( "#submit_carrito" ).attr("disabled", "disabled");

           var pay_lote = $('#pagar_lotes').serialize();
             $.ajax({
                type: "POST",
                url:  '/gateway/pagarLotesWeb',
                data: pay_lote,
                success: function(data) {
                  window.location.href = data;
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

        $( "#form-valoracion-adv" ).submit(function(event) {

            event.preventDefault();
            formData = new FormData(this);
            var max_size = 20;
            var size = 0;
            $( event.target.files.files ).each(function( index, element ) {

               size = parseInt((element.size/1024/1024).toFixed(2)) + parseInt(size);
               //console.log(size);
           });
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
                        $("#modalMensaje #insert_msg").html('');
                        $("#modalMensaje #insert_msg").html(messages.error[result.msg]);
                        $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
                    }else{
                        $(".msg_valoracion").removeClass('hidden');
                    }
                },
                error: function(result) {
                   $(".msg_valoracion").removeClass('hidden');
                }
            });
           }else{
               $("#insert_msg").html(messages.error.max_size_img);
               $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
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
        });

        $("#submit_fact").click(function () {
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

            });

        $('.add_factura').change(function() {
                        reload_facturas();
        });


  });





function see_desc(){
    $.removeCookie('lot');
    $.cookie('lot','desc',{expires: 7,path: '/'});
    $( ".square" ).addClass( "hidden" );
    $( ".small_square" ).addClass( "hidden" );
    $( ".large_square" ).removeClass( "hidden" );
    $('.bar-lot-large').removeClass( "hidden" );
}

function see_img(){
    $.removeCookie('lot');
    $.cookie('lot','img',{expires: 7,path: '/'});
    $( ".large_square" ).addClass( "hidden" );
    $('.bar-lot-large').addClass( "hidden" );
    $( ".small_square" ).addClass( "hidden" );
    $( ".square" ).removeClass( "hidden" );
}

function see_img_samll(){
    $.removeCookie('lot');
    $.cookie('lot','small_img',{expires: 7,path: '/'});
    $( ".large_square" ).addClass( "hidden" );
    $('.bar-lot-large').addClass( "hidden" );
    $( ".square" ).addClass( "hidden" );
    $( ".small_square" ).removeClass( "hidden" );
}

function cerrarLogin(){
  $('.login_desktop').fadeToggle("fast");
}

function ajax_carousel(key,replace){
        $( "#"+key ).siblings().removeClass('hidden');
        $.ajax({
                type: "POST",
                url:  "/api-ajax/carousel",
                data: {key: key, replace: replace},
                success: function(result) {
                    $( "#"+key ).siblings('.loader').addClass('hidden');
                    $("#"+key).html(result);
                    carrousel_molon($("#"+key));
                    $('[data-countdown]').each(function() {
                        $(this).data('ini', new Date().getTime());
                        countdown_timer($(this))
                    });
            }

        });

};

 function format_date(fecha){

        var horas = fecha.getHours() ;
        var minutos = fecha.getMinutes();
        var mes;
        if (horas < 10){
            horas = '0' + horas
        }
        if (minutos < 10){
            minutos = '0' + minutos
        }

         $.each( traductions, function( key, value ) {
            if(key == $.datepicker.formatDate("M",fecha)){
                mes = value;
            }
      });

        var formatted = $.datepicker.formatDate("dd ",fecha)+ mes + " " + horas + ":" + minutos;
        return formatted;
}


function carrousel_molon(carrousel){
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
};

function password_recovery(lang){
    var pass_recov = $("#password_recovery").serialize();
    $.ajax({
        type: "POST",
        url:  '/'+lang+'/ajax-send-password-recovery',
        data: pass_recov,
        success: function( data ) {
           if(data.status == 'error'){
               $( ".error-recovery" ).html( data.msg );
           }else if(data.status == 'succes'){
                $( "#password_recovery" ).html( data.msg );
           }
        }
    });
};

function format_date_large(fecha,text){

    var horas = fecha.getHours() ;
    var minutos = fecha.getMinutes();
    var mes;
    if (horas < 10){
        horas = '0' + horas
    }
    if (minutos < 10){
        minutos = '0' + minutos
    }

    $.each( traduction_large, function( key, value ) {
        if(key == $.datepicker.formatDate("M",fecha)){
            mes = value;
        }
    });

    var formatted = $.datepicker.formatDate("dd ",fecha)+ mes + " " + text  + " " + horas + ":" + minutos + " h";
    return formatted;
}



function close_modal_session(){

    $("#closeResponsive").trigger("click");
}




function action_fav_modal (action){

     $('.button-follow').show();
     $('.button-follow-responsive').show();

    $.magnificPopup.close();
    if (typeof cod_licit == 'undefined' || cod_licit == null )
     {
         $("#insert_msg").html( messages.error.mustLogin );
         $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
         return;
     }else{
         $.ajax({
             type: "GET",
             url:  routing.favorites + "/" + action,
             data: { cod_sub: cod_sub, ref: ref, cod_licit: cod_licit },
             success: function( data ) {
                 $('.button-follow').hide();
                 $('.button-follow-responsive').hide();

                 if (data.status == 'error'){
                     $("#insert_msg").html("");
                    $("#insert_msg").html(messages.error[data.msg]);
                     $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
                 }else if(data.status == 'success'){
                     $("#insert_msg").html("");
                    $("#insert_msg").html( messages.success[data.msg] );
                     $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
                     if(action == 'add'){
                         $("#add_fav").addClass('hidden');
                         $("#del_fav").removeClass('hidden');
                         $(".slider-thumnail-container #add_fav").addClass('hidden');
                         $(".slider-thumnail-container #del_fav").removeClass('hidden');


                     }else{
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

function action_fav_lote(action, ref, cod_sub, cod_licit){
    routing.favorites	 = '/api-ajax/favorites';
    //$.magnificPopup.close();

    $.ajax({
        type: "GET",
        url:  routing.favorites + "/" + action,
        data: { cod_sub: cod_sub, ref: ref, cod_licit: cod_licit },
        success: function( data ) {

            if (data.status == 'error'){

                $("#insert_msg").html("");
                $("#insert_msg").html(messages.error[data.msg]);
                $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
            }else if(data.status == 'success'){
                $("#insert_msg").html("");
                $("#insert_msg").html( messages.success[data.msg] );
                $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
                $( '.'+ref+'-'+cod_sub).remove();

            }

        }
    });

};

function change_price_saved_offers(){
    var precio = 0;
    $('input[name=order]').each(function(){
        precio =  parseInt($(this).val()) +  parseInt(precio);
    })
    $("#change_price").html('');
    $("#change_price").html(parseFloat(precio, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString());
}

function reload_facturas(){
    var total = 0;
   $.each(info_fact, function( index_anum, value_anum ){
        $.each(value_anum, function( index_num, value_num ){
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
}

function change_currency(price) {

    var price = numeral(price).format('0,0.00');

    return price;
}
