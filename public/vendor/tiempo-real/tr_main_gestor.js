//funcion que muestra un mensaje al admin si el lote
function showInfoTrLot(){

	if(typeof auction_info.lote_actual.infotr_hces1 != 'undefined' && auction_info.lote_actual.infotr_hces1 !=''){
		displayAlert(2, auction_info.lote_actual.infotr_hces1);
	}

}


$(function() {

    $(".inputmask").inputmask();
var socket = io.connect(routing.node_url, { 'forceNew': true });

    socket.on('connect', function() {
	   socket.emit('room', {cod_sub: auction_info.subasta.cod_sub, id: socket.id});
	});
    socket.on('local_end_lot', function(data) {

        if(data.cod_licit == auction_info.user.cod_licit){

			var showModal = (typeof showEverModal != 'undefined' && showEverModal);

            if (!automatic_auction && (typeof auction_info.lote_actual.max_puja != 'undefined' && auction_info.lote_actual.max_puja != 0 && auction_info.lote_actual.max_puja.cod_licit == auction_info.subasta.dummy_bidder || showModal) ) {

				$.magnificPopup.close();

		  		//copiar y pegar en navegador
                $.magnificPopup.open({items: {src: '#modalEndLot'}, type: 'inline',showCloseBtn: false,enableEscapeKey: false,  closeOnBgClick: false, focus: '#w_undefined'}, 0);


            }
            else{
                send_end_lot();
            }
        }

    });

    $('#w_undefined').keypress(function( event ) {
         if ( event.which == 13 ) {
           asign_licit();
        }


    });
/*
    |--------------------------------------------------------------------------
    | Cancelar una puja siempre la última
    |--------------------------------------------------------------------------
    */

 	$('#cancelarPuja').click(function() {
 		$.magnificPopup.open({items: {src: '#modalCancelarPuja'}, type: 'inline'}, 0);
 	});

	window.cancelar_puja = function ()
	{

            var cod_sub =  auction_info.subasta.cod_sub;
            var ref = auction_info.lote_actual.ref_asigl0;


			$.ajax({
				type: "POST",
				url: '/phpsock/cancelarbid',
				data:{  cod_sub: cod_sub,  ref: ref },
				beforeSend: function () {

				},
				success: function( response ) {
					if(response.status == 'error'){
						displayAlert(1, messages.error[response.msg]);
					}
				}
			});

	};

        $('#cancelarOrden').click(function() {
 		$.magnificPopup.open({items: {src: '#modalCancelarOrden'}, type: 'inline'}, 0);
 	});

	window.cancelar_orden = function ()
	{


            var cod_sub =  auction_info.subasta.cod_sub;
            var ref = auction_info.lote_actual.ref_asigl0;

			$.ajax({
				type: "POST",
				url: '/phpsock/cancelarorden',
				data:{  cod_sub: cod_sub,  ref: ref },
				beforeSend: function () {

				},
				success: function( response ) {
					if(response.status == 'error'){
						displayAlert(1, messages.error[response.msg]);
					}
				}
			});


	};


    /*
   |--------------------------------------------------------------------------
    | Cambia el estado de la subasta
    |--------------------------------------------------------------------------
    */
    $('.change_auction_status').click(function(){

    	if (typeof auction_info.user != 'undefined' && auction_info.user.is_gestor){
	    	var status = $(this).data().status;

	    	if (typeof status != 'undefined' && status != '' && status == 'stopped') {
	    		/* Abrimos popup de config de pausa y pausamos en caso de confirm*/
                        setInterval(show_restart_date,1000);
	    		$.magnificPopup.open({items: {src: '#modalConfigPausada'}, type: 'inline'}, 0);
	    	}else if (typeof status != 'undefined' && status != '' && status == 'stopped-time') {

                    $.magnificPopup.open({items: {src: '#modalPausarTime'}, type: 'inline',focus: '#hour_pause'}, 0);


                }else {
	    		/* Quitamos la pausa de la subasta*/
                        var status = 'in_progress';
                        var cod_sub = auction_info.subasta.cod_sub;
                        var cod_licit = auction_info.user.cod_licit;

						$.ajax({
							type: "POST",
							url: '/phpsock/set_status_auction',
							data:{  cod_sub: cod_sub, status:status,  id_auc_sessions: auction_info.subasta.id_auc_sessions },
							beforeSend: function () {

							},
							success: function( response ) {
								if(response.status == 'error'){
									displayAlert(1, messages.error[response.msg]);
								}
							}
						});


	    		 	}
	    }

    });

    window.pausarSubastaMinutos = function ()
    {

    	var minutesPause = $("#total_minutes_pause").val();

    	if(minutesPause > 0) {

			var status = 'stopped';
            if(typeof $("#new_status_auction").val() != 'undefined' &&  $("#new_status_auction").val() !="" && $("#new_status_auction").prop("checked") == true)
            {
				var status = $("#new_status_auction").val();
            }

            var cod_sub = auction_info.subasta.cod_sub;

			$.ajax({
				type: "POST",
				url: '/phpsock/set_status_auction',
				data:{  cod_sub: cod_sub, status:status,  minutesPause: minutesPause, id_auc_sessions: auction_info.subasta.id_auc_sessions },
				beforeSend: function () {

				},
				success: function( response ) {
					if(response.status == 'error'){
						displayAlert(1, messages.error[response.msg]);
					}else{
						if(automatic_auction){
							stop_automatic_auction();
						}
					}
				}
			});


		}

    }

    window.pausarSubasta = function ()
    {

    	var fechaPause_tmp = $('#date_pause').val() ;
        var dia = fechaPause_tmp.substr(0,2);
        var mes = fechaPause_tmp.substr(3,2);
        var anyo = fechaPause_tmp.substr(6,4);


        fechaPause= anyo + "-" + mes + "-" + dia + " " +  $('#hour_pause').val() ;


    	if(fechaPause.length > 0 && dia <= 31 && mes <=12 ) {

            var status = 'stopped';
            var cod_sub = auction_info.subasta.cod_sub;
			var id_auc_sessions= auction_info.subasta.id_auc_sessions;

			$.ajax({
				type: "POST",
				url: '/phpsock/set_status_auction',
				data:{  cod_sub: cod_sub, status:status,  reanudacion: fechaPause, id_auc_sessions: id_auc_sessions },
				beforeSend: function () {

				},
				success: function( response ) {
					if(response.status == 'error'){
						displayAlert(1, messages.error[response.msg]);
					}else{
						if(automatic_auction){
							stop_automatic_auction();
						}
					}
				}
			});



		}else{
            displayAlert(0, messages.error['wrong_date']);

        }

    }


    /*
	|--------------------------------------------------------------------------
	| Iniciar subasta antes de tiempo
	|--------------------------------------------------------------------------
	*/
	$('.start').on('click', function() {
		$.magnificPopup.open({items: {src: '#modalStart'}, type: 'inline'}, 0);
	});

	/* Iniciamos la subasta*/
	window.iniciar_subasta = function()
	{
		if (typeof auction_info.user != 'undefined' && auction_info.user.is_gestor){
                        var status = 'in_progress';
                        var cod_sub = auction_info.subasta.cod_sub;
						$.ajax({
							type: "POST",
							url: '/phpsock/set_status_auction',
							data:{  cod_sub: cod_sub, status:status,   id_auc_sessions: auction_info.subasta.id_auc_sessions },
							beforeSend: function () {

							},
							success: function( response ) {
								if(response.status == 'error'){
									displayAlert(1, messages.error[response.msg]);
								}
							}
						});


				}

	}
	/*
	|--------------------------------------------------------------------------
	| fin iniciar subasta
	|--------------------------------------------------------------------------
	*/

         /*
        |--------------------------------------------------------------------------
        | Eliminar un mensaje de chat, solo para gestor
        |--------------------------------------------------------------------------
        */
        $('body').on('click', '.btn-eliminar', function() {



    	/* si es un mensaje predefinido pasaremos un parametro para identificar el socket emit*/
    	var predefinido = $(this).attr('predefinido');
        var cod_licit = auction_info.user.cod_licit;
        var cod_sub = auction_info.subasta.cod_sub;


		$.ajax({
			type: "POST",
			url: '/phpsock/delete_message_chat',
			data:{  cod_sub: cod_sub, id_mensaje:$(this).attr('id_mensaje'), predefinido: $(this).attr('predefinido') },
			beforeSend: function () {

			},
			success: function( response ) {
				if(response.status == 'error'){
					displayAlert(1, messages.error[response.msg]);
				}
			}
		});


        });
         /*
        |--------------------------------------------------------------------------
        | Eliminar un mensaje normal solo para gestor
        |--------------------------------------------------------------------------
        */

       /*
    |--------------------------------------------------------------------------
    | Chat de mensajes en sala solo para gestor
    |--------------------------------------------------------------------------
    */
    $('body').on('click', '#btn-chat', function() {

    	mensajes 				= {};
    	mensajes_sin_procesar 	= {};

    	/*Comprobamos que esten los campos llenos o almenos uno de ellos*/
    	if ( $('input[name^="mens"]').filter(
  			function () {
		   		return $.trim( $(this).val() ).length > 0
		}).length == 0 ){
  			return;
		}

    	/* Buscamos si el mensaje es predefinido*/
    	if($('#predefinido').prop('checked')) {
    		predefinido = 1;
    	} else {
    		predefinido = 0;
    	}

    	/* Añadimos el array del formulario a un objeto en js con el contenido de los mensajes*/
		$('input[name^="mens"]').each(function() {
			mensajes[$(this).attr('clave')] = {'msg': $(this).val(), 'predefinido': predefinido};

			/* Insertamos los valores dentro del array javascript*/
			mensajes_sin_procesar[$(this).attr('contador')] = { 'msg': $(this).val(), 'predefinido': predefinido, 'lang_code': $(this).attr('clave')};
		});

                var cod_sub = auction_info.subasta.cod_sub;


		/* solo puede enviar chat el gestor*/
		if(typeof auction_info.user != 'undefined' && auction_info.user.is_gestor) {


			$.ajax({
				type: "POST",
				url: '/phpsock/set_message_chat',
				data:{  cod_sub: cod_sub, mensaje:mensajes },
				beforeSend: function () {

				},
				success: function( response ) {
					if(response.status == 'error'){
						displayAlert(1, messages.error[response.msg]);
					}
				}
			});

		}

		/* Reseteamos el valor de los campos input de mensaje*/
		$('#chat-frm')[0].reset();
	});
        /*
    |--------------------------------------------------------------------------
    | Fin Chat mensajes en sala
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Mensajes predefinidos solo para gestor
    |--------------------------------------------------------------------------
    */
    $('#mensajes_predefinidos').on('click', '.btn-chat-pre', function() {

    	var id_mensaje = $(this).attr('id_mensaje');

    	mensajes 				= {};
    	mensajes_sin_procesar 	= {};

    	contador = 1;

    	$.each(auction_info.chat.mensajes[id_mensaje], function(index, value) {
    		auction_info.chat.mensajes[id_mensaje][index].predefinido = 0;
    	});

		var cod_sub = auction_info.subasta.cod_sub;
		if(typeof auction_info.user != 'undefined' && auction_info.user.is_gestor) {
			$.ajax({
				type: "POST",
				url: '/phpsock/set_message_chat',
				data:{  cod_sub: cod_sub, mensaje:auction_info.chat.mensajes[id_mensaje] },
				beforeSend: function () {

				},
				success: function( response ) {
					if(response.status == 'error'){
						displayAlert(1, messages.error[response.msg]);
					}
				}
			});
		}

	});

    /*
    |--------------------------------------------------------------------------
    | Lanzar aviso de Fair Warning
    |--------------------------------------------------------------------------
    */

   $('.fairwarning_js').click(function(){
	if (typeof auction_info.user != 'undefined' && auction_info.user.is_gestor){

		$.ajax({
			type: "POST",
			url: '/phpsock/fair_warning',
			data:{  cod_sub: auction_info.subasta.cod_sub},
			beforeSend: function () {

			},
			success: function( response ) {
				if(response.status == 'error'){
					displayAlert(1, messages.error[response.msg]);
				}
			}
		});



	}
});

      /*
    |--------------------------------------------------------------------------
    | Finalizar lote
    |--------------------------------------------------------------------------
    */

    $('.change_end_lot').click(function(){
    	if (typeof auction_info.user != 'undefined' && auction_info.user.is_gestor){

			//endLotWithAjax($(this).data().status);
			endLotWithSocket($(this).data().status);
    	}
    });

	function endLotWithAjax(status) {
		if (status == 'end'){
			var url = '/phpsock/start_count_down';
		}else{
			var url = '/phpsock/stop_count_down';
		}

		$.ajax({
			type: "POST",
			url: url,
			data:{  cod_sub: auction_info.subasta.cod_sub,  cd_time: auction_info.subasta.cd_time,  lot: auction_info.lote_actual.ref_asigl0},
			beforeSend: function () {

			},
			success: function( response ) {
				if(response.status == 'error'){
					displayAlert(1, messages.error[response.msg]);
				}
			}
		});
	}

	function endLotWithSocket(status) {

		status == 'end'
			? count_down_lot()
			: socket.emit('stop_count_down', { cod_sub: auction_info.subasta.cod_sub,  lot: auction_info.lote_actual.ref_asigl0 });
	}

	function count_down_lot() {

		if (typeof auction_info.user != 'undefined' && auction_info.user.is_gestor) {
			var cod_sub = auction_info.subasta.cod_sub;
			var cod_licit = auction_info.user.cod_licit;
			var lot = auction_info.lote_actual.ref_asigl0;
			var string_hash = lot + " " + cod_sub + " " + cod_licit;
			var hash = CryptoJS.HmacSHA256(string_hash, auction_info.user.tk).toString(CryptoJS.enc.Hex);

			socket.emit('start_count_down', { cod_sub: cod_sub, cod_licit: cod_licit, hash: hash, cd_time: auction_info.subasta.cd_time, url: routing.end_lot, lot: lot });
		}
	}

    /*
	|--------------------------------------------------------------------------
	| Reabrir un lote en concreto
	|--------------------------------------------------------------------------
	*/
	$('#abrirLote').on('click', function(e) {


		e.stopPropagation();
		$.magnificPopup.close();
		$.magnificPopup.open({items: {src: '#modalLotAbrir'}, type: 'inline'}, 0);
	});

        window.abrir_lote = function()
	{
                var value_delete = 0;
                if ($('input.deleteBids').prop('checked')) {
                   value_delete = 1;
                }
		if (typeof auction_info.user != 'undefined' && auction_info.user.is_gestor) {

                      $.ajax({
		        type: "POST",
		        url: '/api-ajax/open_lot',
		        data: 'cod_sub='+auction_info.subasta.cod_sub+ '&orden=' + auction_info.lote_actual.orden_hces1+'&ref='+auction_info.buscador.ref_asigl0+'&deletBids='+value_delete,
		        beforeSend: function () {

		        },
		        success: function( response ) {
                                if(response.status == 'success'){
                                    displayAlert(1, messages.success[response.msg]);
                                    $('#abrirLote').addClass('hidden');
                                }else{
                                    displayAlert(0, messages.error[response.msg]);

                                }
		        }
		    });
	    }
	}
	/*
	|--------------------------------------------------------------------------
	| fin de abrir de lote único
	|--------------------------------------------------------------------------
	*/

	/*







	/*
	|--------------------------------------------------------------------------
	| Pausar un lote en concreto y cambiar el orden de aparición del mismo
	|--------------------------------------------------------------------------
	*/
	$('.pausarLote').on('click', function(e) {

		/*console.log('pausar lote deb: '+$(this).attr('ref'));*/
		$('#lotPause').attr('ref',$(this).attr('ref'));
		e.stopPropagation();
		$.magnificPopup.close();
		$.magnificPopup.open({items: {src: '#modalLotPause'}, type: 'inline'}, 0);
	});
	/*
	|--------------------------------------------------------------------------
	| fin de pausa de lote único
	|--------------------------------------------------------------------------
	*/

	/*
	|--------------------------------------------------------------------------
	| Reanudar lote en concreto
	|--------------------------------------------------------------------------
	*/
	$('body').on('click', '.reanudarLote', function(e) {
		var refclick 	= $(this).attr('data-id');
		var orden 		= $(this).attr('data-orden');

		/*console.log("Click actual:"+refclick+" Orden: "+orden);*/

		$('#lotOrden').removeAttr('data-ref');
		$('#lotOrden').val(refclick);
		$('#lotOrden').attr('data-ref',refclick);
		$('#lotOrden').attr('data-orden',orden);

		e.stopPropagation();
		$.magnificPopup.close();
		$.magnificPopup.open({items: {src: '#modalLotPauseReanudar'}, type: 'inline'}, 0);
	});
	/*
	|--------------------------------------------------------------------------
	| fin de pausa de lote único
	|--------------------------------------------------------------------------
	*/


       /*
	|--------------------------------------------------------------------------
	| Pausar un lote en concreto
	|--------------------------------------------------------------------------
	*/
	window.pausar_lote = function()
	{
		var status  = $('.lotPause').data().status;

		if (typeof auction_info.user != 'undefined' && auction_info.user.is_gestor) {

			var cod_sub = auction_info.subasta.cod_sub;
			var ref = auction_info.buscador.ref_asigl0;

			$.ajax({
				type: "POST",
				url: '/phpsock/lot_pause',
				data:{  cod_sub: cod_sub, ref: ref, status: status },
				beforeSend: function () {

				},
				success: function( response ) {
					if(response.status == 'error'){
						displayAlert(1, messages.error[response.msg]);
					}
				}
			});

	    }
	}





	/*
	|--------------------------------------------------------------------------
	| fin pausar lote
	|--------------------------------------------------------------------------
	*/



	/* Lista de lotes pausados*/
	$('#show_stopped_lots').on('click', function() {
		$.magnificPopup.open({items: {src: '#modalLotReanudarList'}, type: 'inline'}, 0);
	});


	/*
	|--------------------------------------------------------------------------
	| Reanudar un lote en concreto en X posición
	|--------------------------------------------------------------------------
	*/
	window.reanudar_lote = function () {
		var status = $('.lotResume').data().status;
		var orden_actual = $('#lotOrden').attr('data-orden');

		ref = $('#lotOrden').attr('data-ref');

		if (!ref) {
			ref = auction_info.buscador.ref_asigl0;
		}

		var ref_lot = $('#lotOrden').val();

		if (typeof auction_info.user != 'undefined' && auction_info.user.is_gestor) {

			var cod_sub = auction_info.subasta.cod_sub;
			reanudarLoteWithSocket(status, orden_actual, ref, ref_lot, cod_sub);
		}
		//displayAlert(0, messages.error.not_allowed_movement);
	}

	function reanudarLoteWithSocket(status, orden_actual, ref, ref_lot, cod_sub) {
		var cod_licit = auction_info.user.cod_licit;
		var string_hash = ref + " " + cod_sub + " " + cod_licit ;
		var hash = CryptoJS.HmacSHA256(string_hash, auction_info.user.tk).toString(CryptoJS.enc.Hex);
		socket.emit('pausar_lote', {ref: ref, cod_licit: cod_licit, cod_sub: cod_sub, status: status, ref_lote_actual: auction_info.lote_actual.ref_asigl0, orden_actual: orden_actual, ref_lot: ref_lot, hash: hash });
	}

	function reanudarLoteWithAjax(status, orden_actual, ref, ref_lot, cod_sub) {
		$.ajax({
			type: "POST",
			url: '/phpsock/jump_lot',
			data: { cod_sub: cod_sub, ref: ref, status: status, ref_lote_actual: auction_info.lote_actual.ref_asigl0, orden_actual: orden_actual, ref_lot: ref_lot },
			beforeSend: function () {

			},
			success: function (response) {
				if (response.status == 'error') {
					displayAlert(1, messages.error[response.msg]);
				}
			}
		});
	}


	/*
	|--------------------------------------------------------------------------
	| fin reanudar lote
	|--------------------------------------------------------------------------
	*/

	function send_end_lot(jump_lot = 0){
		if (typeof auction_info.user != 'undefined' && auction_info.user.is_gestor) {
			sendEndLotWithSocket(jump_lot);
		}
	}

	function sendEndLotWithSocket(jump_lot) {
		var cod_sub = auction_info.subasta.cod_sub;
		var cod_licit = auction_info.user.cod_licit;
		var lot = auction_info.lote_actual.ref_asigl0;
		var string_hash = lot + " " + cod_sub + " " + cod_licit ;
		var hash = CryptoJS.HmacSHA256(string_hash, auction_info.user.tk).toString(CryptoJS.enc.Hex);

		socket.emit('server_end_lot', {cod_sub: cod_sub, cod_licit: cod_licit, hash: hash, cd_time: auction_info.subasta.cd_time, url: routing.end_lot, lot: lot,jump_lot:jump_lot});
	}

	function sendEndLotWithAjax(jump_lot) {
		var cod_sub = auction_info.subasta.cod_sub;
		var lot = auction_info.lote_actual.ref_asigl0;

		$.ajax({
			type: "POST",
			url: '/phpsock/endlot',
			data: { cod_sub: cod_sub, lot: lot, jump_lot: jump_lot },
			beforeSend: function () {

			},
			success: function (response) {
				if (response.status == 'error') {
					displayAlert(1, messages.error[response.msg]);
				}
			}
		});
	}

	window.asign_licit = function asign_licit(forceAsign = false) {

		//si viene a true el forzar la asignación entramos, puede ser por ejemplo por una asignación del ministerio
		if ( forceAsign || (!$('#modalEndLot .winner_undefined').hasClass('hidden') && $.isNumeric($('#modalEndLot .winner_undefined input').val()) && $('#modalEndLot .winner_undefined input').val() > 0)) {

			//si ha entrado una puja nueva mientras asignabamos el licitador debemos cancelar la asignacion
			/*Fer un update del licitador introduit a la última puja del licitiador = auction_info.subasta.dummy_bidder*/
			winner = $('#w_undefined').val();



			/**
			 * Si tenemos la opcion de mostrar siempre el modal de asignar licitador
			 * Y el licitador introducido ya era el ganador, cerramos el lote directamente
			 */
			if (typeof showEverModal != 'undefined' && showEverModal && winner == auction_info.lote_actual.max_puja.cod_licit) {
				$("#modalEndLot_msg_error").addClass('hidden');
				send_end_lot();
				return;
			}


			$.ajax({
				type: "POST",
				url: '/api-ajax/set_licit_lot',
				data: 'licit=' + winner + '&cod_sub=' + auction_info.lote_actual.cod_sub + '&ref=' + auction_info.lote_actual.ref_asigl0,
				beforeSend: function () {

				},
				success: function (msg) {

					if (msg == 'error') {
						$.magnificPopup.close();
						$("#modalEndLot_msg_error").removeClass('hidden');
						$("#modalEndLot_msg_error").html(messages.error['no_licit']);
						$.magnificPopup.open({ items: { src: '#modalEndLot' }, type: 'inline', showCloseBtn: false, enableEscapeKey: false, closeOnBgClick: false }, 0);

					}else if (msg == 'error-notbid') {
						$.magnificPopup.close();
						$("#modalEndLot_msg_error").removeClass('hidden');
						$("#modalEndLot_msg_error").html(messages.error['not_bid']);
						$.magnificPopup.open({ items: { src: '#modalEndLot' }, type: 'inline', showCloseBtn: false, enableEscapeKey: false, closeOnBgClick: false }, 0);

					} else if (msg == 'ministery') {
						$("#modalEndLot_msg_error").addClass('hidden');
						//aign_ministery_html();
						send_end_lot();


					} else {
						$("#modalEndLot_msg_error").addClass('hidden');
						//si ha entrado una puja en el ultimo momento no se puede cerrar el lote

						if (auction_info.lote_actual.max_puja.cod_licit != auction_info.subasta.dummy_bidder) {
							//pintamos el licitador introducido
							asign_licit_html(winner);
							$.magnificPopup.close();
							$.magnificPopup.open({ items: { src: '#modal_cancelasignlicit' }, type: 'inline', showCloseBtn: false, enableEscapeKey: false, closeOnBgClick: false }, 0);
						} else {
							send_end_lot();
						}
					}
				}, error: function (msg) {
					$("#modalEndLot_msg_error").removeClass('hidden');
					$("#modalEndLot_msg_error").html("Error al modificar el licitador, por favor intentelo de nuevo");
					$.magnificPopup.open({ items: { src: '#modalEndLot' }, type: 'inline', showCloseBtn: false, enableEscapeKey: false, closeOnBgClick: false }, 0);
				}
			});



		} else {
			/* debe estar visible el campo del licitador y ademas si la casa de subastas no permite poner el licitador vacio */
			if (!$('#modalEndLot .winner_undefined').hasClass('hidden') && typeof notEmptyLicit != 'undefined' && notEmptyLicit){
				/* debemos esperar ya que hay procesos en paralelo que nos cerraran la ventana  */
				setTimeout(function(){
					$.magnificPopup.close();
					$("#modalEndLot_msg_error").removeClass('hidden');
					$("#modalEndLot_msg_error").html(messages.error['no_licit']);
					$.magnificPopup.open({ items: { src: '#modalEndLot' }, type: 'inline', showCloseBtn: false, enableEscapeKey: false, closeOnBgClick: false }, 0);

				},500);
			}else{
				$("#modalEndLot_msg_error").addClass('hidden');
				send_end_lot();

			}

		}
	}

        $(".cancelasignlicit").click(function() {
            $("#modalEndLot_msg_error").addClass('hidden');
            var cod_sub = auction_info.subasta.cod_sub;

			$.ajax({
				type: "POST",
				url: '/phpsock/open_bids',
				data:{  cod_sub: cod_sub },
				beforeSend: function () {

				},
				success: function( response ) {
					if(response.status == 'error'){
						displayAlert(1, messages.error[response.msg]);
					}
				}
			});


            //si no ha asignado ganador y  está activa la subasta automática
            if(automatic_auction){
                active_call();
            }

        });


        ///Activar como siguiente lote

        $("#jump_to_lots").click(function() {
            $.magnificPopup.close();
            $.magnificPopup.open({items: {src: '#modalJumpLot'}, type: 'inline',showCloseBtn: true,enableEscapeKey: false,  closeOnBgClick: true}, 0);
        });

	window.jump_lot = function () {
		var lot_go = $("#modalJumpLot #jumpLot").val();
		var actual_lot = auction_info.lote_actual.ref_asigl0;
		var jump_lot = 1;
		const open_lot = $('#openLot').is(':checked') ? 1 : 0;

		if (lot_go != '' && lot_go != actual_lot) {
			$.ajax({
				type: "POST",
				url: '/api-ajax/jump_lots',
				data: {
					ref: lot_go,
					codsub: auction_info.lote_actual.sub_hces1,
					ref_actual: actual_lot,
					open_lot: open_lot
				},
				success: function (data) {
					if (data.status == 'error') {
						displayAlert(0, messages.error[data.msg]);
					} else {
						send_end_lot(jump_lot);
					}
					$("#modalJumpLot #jumpLot").val('');
				}
			})
		}
	}

        $("#baja_client").click(function() {
            var direccion=' ';
            $.ajax({
                type: "POST",
                url: '/api-ajax/get_baja_cli_sub',
                data: {cod_sub:auction_info.lote_actual.sub_hces1},
                success: function( data ) {
                    $(data ).each(function( index ) {
                        $("#BajaClient .search-loader").hide();
                         direccion = direccion + "<tr><td>"+this.cod_licit+"</td><td>"+this.nom_cli+"</td><td>"+this.cod_cli+"</td><td style='cursor:pointer' class='alta-user' value='"+this.cod_licit+"' ><span class='glyphicon glyphicon-trash' aria-hidden='true'></span></td></tr>";
                    });
                    $(".clientes_baja").html(direccion);
                    $("#BajaClient .search-loader").hide();
                    $(".alta-user").bind("click", function(){
                        $("#licit_baja").val($(this).attr("value"));
                        $("#BajaClient .modal-confirm").click();
                    });

                }
            })
           $.magnificPopup.close();
           $.magnificPopup.open({items: {src: '#BajaClient'}, type: 'inline',showCloseBtn: true,enableEscapeKey: false,  closeOnBgClick: true}, 0);
        });

		$('#modified_paddles').on('click', function() {
			$.ajax({
                type: "POST",
                url: '/api-ajax/get_modified_paddles',
                data: {cod_sub:auction_info.lote_actual.sub_hces1},
                success: function(data) {
					$html = modifiedPaddlesRender(data);
                    $("#modified_paddles_tablebody").html($html);
                    $("#ModifiedPaddles .search-loader").hide();
                }
            })
			$.magnificPopup.close();
			$.magnificPopup.open({items: {src: '#ModifiedPaddles'}, type: 'inline', showCloseBtn: true, enableEscapeKey: false,  closeOnBgClick: true}, 0);
		});

		function modifiedPaddlesRender(results) {
			return results.map(function (result) {
				return `<tr>
							<td>${result.rsoc_licit ?? ''}</td>
							<td>${result.cod_licit_new}</td>
							<td>${result.cod_licit_old}</td>
							<td>${result.cod_licit}</td>
							<td>${result.cli_licit ?? ''}</td>
						</tr>`;
			});
		}

        $(".pause_auction").change(function(){
            show_restart_date();
        })


        function show_restart_date(){
            var minutes = 0;
            minutes  = parseInt($("#days_pause").val()) * 24 * 60;
            minutes += parseInt($("#hours_pause").val())  * 60;
            minutes += parseInt($("#minutes_pause").val());
            $("#total_minutes_pause").val(minutes);

            var miliseconds = new Date().getTime() + (minutes * 60  * 1000);
            var restart_date = new Date(miliseconds);
            options = {};

         $("#restart_auc_date").html(restart_date.toLocaleDateString('es-ES', options) + " " + restart_date.toLocaleTimeString('es-ES', options));
        }
        //hacemos una llamada para cargar datos
        show_restart_date();


        window.baja_client = function()
	{
            var licit = $("#BajaClient #licit_baja").val();
            if(licit != ''){
                $.ajax({
                    type: "POST",
                    url: '/api-ajax/baja_cli',
                    data: {cli_licit:licit,cod_sub:auction_info.lote_actual.sub_hces1},
                    success: function( data ) {
                        if(data.status == 'error'){
                            displayAlert(0, messages.error[data.msg]);
                        }else if(data.status == 'success_cli_baja'){
                            $("#alta_client").attr('cli_licit', data.value);
                            $.magnificPopup.close();
                            $.magnificPopup.open({items: {src: '#AltaClient'}, type: 'inline',showCloseBtn: false,enableEscapeKey: false,  closeOnBgClick: false}, 0);
                        }else{
                             displayAlert(1, messages.success[data.msg]);
                        }
                        $("#BajaClient #licit_baja").val('');
                    }
                })
            }
        }
        window.alta_client = function()
	{
            $.ajax({
                type: "POST",
                url: '/api-ajax/baja_cli',
                data: {alta_cli:true,cli_licit: $("#alta_client").attr('cli_licit'),cod_sub:auction_info.lote_actual.sub_hces1},
                success: function( data ) {
                    if(data.status == 'error'){
                         displayAlert(0, messages.error[data.msg]);
                    }else{
                         displayAlert(1, messages.success[data.msg]);
                    }
                }
            })
        }

        /* SUBASTA AUTOMATICA */

        //definimos a false por defecto
         var automatic_auction = false;
         //saber si actualmente está activa la funcion cargada en functimeout
         var automatic_active = false;
         var functimeout;
         //numero de segundos que faltan para lanzar finalizar lote
         var num_contador_automatico=0;
        var id_timeout_contador_automatico;
         //var id_contador_automatico
         var seconds_automatic = 5000;


		//implementamos que pida confirmación antes de activar la subasta automática, creo un nuevo id para esto y asi que lso que no lo tengan subido seguiran funcionando con el boton antiguo
		$('#confirm_automatic_auction').click(function(){
			//para axctivar pide confirmación para parar no
			if(typeof automatic_auction == 'undefined' || automatic_auction == false){
				$.magnificPopup.close();
				$.magnificPopup.open({items: {src: '#modalActivarSubastaAutomatica'}, type: 'inline',showCloseBtn: false,enableEscapeKey: false,  closeOnBgClick: false}, 0);
			}else{
                stop_automatic_auction();
            }
		});
		window.activate_automatic_auction = function()
		{
			activate_automatic_auction();
		}

         //evitar que se pare el key press a hacer finalizar subasta
        $('#automatic_auction').keypress(function(e) {
            if(e.which == 13) { // Checks for the enter key
                e.preventDefault(); // Stops IE from triggering the button to be clicked
            }
        });
        $('#automatic_auction').click(function(){

            if(typeof automatic_auction == 'undefined' || automatic_auction == false){
				activate_automatic_auction();
            }else{
				stop_automatic_auction();
            }



    });
	function activate_automatic_auction(){
		var sec = parseInt($("#seconds_automatic_auctions").val());

		if(sec > 0){
			seconds_automatic = sec * 1000;
			automatic_auction = true;
			$("#seconds_automatic_auctions").addClass('hidden');
			//si hay subasta automática no se debe poder saltar de lote
			$("#jump_to_lots").addClass('hidden');
			$("#jump_to_lots_disabled").removeClass('hidden');
			$("#baja_client").addClass('hidden');
			$("#baja_client_disabled").removeClass('hidden');
			$("#show_stopped_lots").addClass('hidden');
			$("#show_stopped_lots_disabled").removeClass('hidden');
			$(this).css('background','green');
			active_call();
		}else{
			 displayAlert(0, "debe indicar un valor de segundos validos");
		}
	}

	function stop_automatic_auction(){
		automatic_auction = false;
		cancel_call();
		$("#seconds_automatic_auctions").removeClass('hidden');
		$("#jump_to_lots").removeClass('hidden');
		$("#jump_to_lots_disabled").addClass('hidden');
		$("#baja_client").removeClass('hidden');
		$("#baja_client_disabled").addClass('hidden');
		$("#show_stopped_lots").removeClass('hidden');
		$("#show_stopped_lots_disabled").addClass('hidden');
		$(this).css('background','#337ab7');
	}


    function contador_automatico() {
        if(num_contador_automatico > 0){
            num_contador_automatico--;
            $('#msg_contador_automatico').html('Finalizar lote en ' + num_contador_automatico);
             id_timeout_contador_automatico=setTimeout(contador_automatico, 1000);
        }
    }
    //action_response
    //centralizamos todas las llamadas desde aqui
   function active_call(){

       automatic_active=true;

       num_contador_automatico = (seconds_automatic/1000)+1;
       contador_automatico();
       $('#msg_contador_automatico').html('Finalizar lote en ' + num_contador_automatico);
      //  functimeout= setTimeout('call_count_down_automatic()',seconds_automatic);

        functimeout= setTimeout(function(){


               //comprobamos que siga siendo automático
               if(automatic_auction){

                $('.change_end_lot[data-status="end"]').click();
                 //despues de hacer la llamada definimos que la función actualmente no esta ejecutandose
                automatic_active=false
                num_contador_automatico=0;
                $('#msg_contador_automatico').html('');

               }
           },seconds_automatic);
   }
    function cancel_call(){
        num_contador_automatico=0;
        //Finalizamos la cuenta atras de la subasta automatica
        clearTimeout(id_timeout_contador_automatico)
        $('#msg_contador_automatico').html('');
        automatic_active=false
        clearTimeout(functimeout);
    }
   //debemos recoger el evento de respuesta para detectar si se ha parado el contador
   socket.on('start_count_down_response', function(params) {
       //si se ha interrumpido
        if (params.interrupt_cd_time &&  (params.cd_time >= -1))
        {
            //si está activa la subasta automática
             if(automatic_auction){
                active_call();
            }
        }
    });
    //recogemos evento de end lot
     socket.on('end_lot_response', function(data) {
        if( auction_info.subasta.sub_tiempo_real != 'S'){
            return
        }
        if(typeof data.subasta_finalizada != 'undefined' && data.subasta_finalizada == 1)
    	{
            return
        }
         //si está activa la subasta automática
             if(automatic_auction && automatic_active == false){

                active_call();
			}


     });

     socket.on('action_response', function(data) {
         //  console.log(data);
           if( typeof data != 'undefined'  &&  typeof data.pujasAll != 'undefined' ){
                var   primera_puja =  data.pujasAll[0]
                     if (primera_puja.ref_asigl1 != auction_info.lote_actual.ref_asigl0){
                         return ;
                     }
                    // console.log(data.pujasAll);
                if( typeof auction_info.lote_actual != 'undefined' && typeof auction_info.lote_actual.actual_bid != 'undefined' && typeof data.actual_bid != 'undefined' ){
                    //if(auction_info.lote_actual.actual_bid != data.actual_bid || (auction_info.lote_actual.actual_bid == data.actual_bid && data.pujasAll.length == 1 ) ){
                        //si la subasta es automatica y actualmente estacorriend ola función la resetemaos para que empiece de nuevo
                        if(automatic_auction && automatic_active){

                             cancel_call()
                            active_call();
                        }
                   // }
                }
           }

       });


   /* fin subasta automática */

   //pintamos en el listado de pujas el licitador que han indicado para que sustituya al dummy
    function asign_licit_html(licit){
         var first = false;
            $('#pujas_list .licitadorPuja').each(function() {

                if($(this).html() == "("+ auction_info.subasta.dummy_bidder +")" && first == false){
                    first = true;
                     $(this).html("(" + licit + ")") ;


                }

            });
       }

       socket.on('count_clients_response', function(data) {

           $("#users_conectet").html(data);
       });


	/*
	|--------------------------------------------------------------------------
	| Asignar el lote al licitador del ministerio
	|--------------------------------------------------------------------------
	*/
	$('.assignToMinistry').on('click', function() {
		//añadimos el numero de licitador y asignamos
		$('#w_undefined').val(ministeryLicit);
		//forzamos la modificación del licitador
		asign_licit(true);
	});
	/*
	|--------------------------------------------------------------------------
	| Fin Asignar el lote al licitador del ministerio
	|--------------------------------------------------------------------------
	*/


});



$(document).ready(function () {
	//hacemos la llamada para el lote actual, el resto se llaman al pasar de lote
	//El if es necesario para clientes que tienen esta función desactivada
	if (typeof showInfoTrLot === 'function') {
		showInfoTrLot();
	}
})


/**
 * Funcion guardada por si es neceario mostar al ministerio en la lista.
 */
function aign_ministery_html() {
	const puja = `<div class="pujas_model col-xs-12">
                            <div class="col-lg-6 tipoPuja">
                                <p data-type="I" class="hidden"><i class="fa fa-globe" aria-hidden="true"></i> Internacional</p>
                                <p data-type="W" class="hidden"><i class="fa fa-wikipedia-w" aria-hidden="true"></i> Web</p>
							</div>
                            <div class="col-lg-6 importePuja">
                                <p>
                                	<span>${auction_info.lote_actual.max_puja.imp_asigl1}</span> <span>€</span>
									<span class="licitadorPuja">(50)<span style="font-size: 12px;"> Ministerio</span>(50)</span>
								</p>
                        	</div>
						</div>`;
	$('#pujas_list').prepend(puja);
}
/* ya no se usa
    function call_count_down_automatic(){

      console.log("cerrando subasta");
        //comprobamos que siga siendo automático
        if(automatic_auction){
          $('.change_end_lot[data-status="end"]').click();
          //despues de hacer la llamada definimos que la función actualmente no esta ejecutandose
          automatic_active=false
        }

    }
    */
