
$(function () {
    var socket = io.connect(routing.node_url, {'forceNew': true});

    closeBids();
    var pruebas = true;


    /*
     |--------------------------------------------------------------------------
     | Variables
     |--------------------------------------------------------------------------
     */



    if (typeof auction_info.lote_siguiente != 'undefined' && typeof auction_info.lote_siguiente == 'object' && !$.isEmptyObject(auction_info.lote_siguiente))
    {
        auction_info.buscador = auction_info.lote_siguiente;
    } else
    {
        auction_info.buscador = auction_info.lote_actual;
    }

    auction_info.modal_item = auction_info.buscador;
    auction_info.subasta.click_from = false;

    reloadBuscadorFavs();
    playAlert(['favs']);






    /*console.log(auction_info);*/

    /*
     |--------------------------------------------------------------------------
     | END Variables
     |--------------------------------------------------------------------------
     */


    /*
     |--------------------------------------------------------------------------
     | Conexión a nodejs
     |--------------------------------------------------------------------------
     */
    socket.on('connect', function () {
        socket.emit('room', {cod_sub: auction_info.subasta.cod_sub, id: socket.id});
        openBids();

    });

    socket.on('disconnect', function () {
        $.magnificPopup.open({items: {src: '#modalDisconnected'}, type: 'inline', showCloseBtn: false, enableEscapeKey: false, modal: true, closeOnBgClick: false}, 0);
    });
    /*
     |--------------------------------------------------------------------------
     | END Conexión a nodejs
     |--------------------------------------------------------------------------
     */

    function addBidLogic(amount, ref, imp_sal, $this, can_do)
    {

        var type_bid = 'W';

        /*Si no es un importe válido*/
        if (!$.isNumeric(amount)) {
            displayAlert(0, messages.error.not_number);
            return;
        }

        if (auction_info.subasta.status == 'stopped') {
            $.magnificPopup.open({items: {src: '#modalPausada'}, type: 'inline'}, 0);
            return;
        } else if (auction_info.subasta.status == 'pendiente') {
            $.magnificPopup.open({items: {src: '#modalPendiente'}, type: 'inline'}, 0);
            return;
        }

        if (typeof auction_info.user != 'undefined' && !auction_info.user.is_gestor) {

            if (typeof auction_info.user == 'undefined' ||
                    typeof auction_info.lote_actual.max_puja.cod_licit != 'undefined' && $($this).hasClass('loading') ||
                    (auction_info.lote_actual.ref_asigl0 == ref && auction_info.lote_actual.max_puja.cod_licit == auction_info.user.cod_licit && parseInt(amount) < parseInt(auction_info.lote_actual.importe_escalado_siguiente)) ||
                    (auction_info.lote_actual.ref_asigl0 == ref && parseInt(amount) < parseInt(auction_info.lote_actual.actual_bid)) ||
                    (auction_info.lote_actual.ref_asigl0 != ref && parseInt(amount) < parseInt(imp_sal))
                    ) {

                /* Puja inferior a la actual*/
                displayAlert(0, messages.error.small_bid);

                return;
            }
        }

        /* Comprobamos que exista el licitador*/
        if (typeof auction_info.user == "undefined") {
            displayAlert(0, messages.error.not_licit);
            return;
        }
        var params = {'cod_licit': auction_info.user.cod_licit, 'cod_sub': auction_info.subasta.cod_sub, 'ref': ref, 'url': routing.action_url, 'imp': amount, 'type_bid': type_bid, 'impsal': imp_sal, 'can_do': can_do, 'cod_original_licit': auction_info.user.cod_licit};




        if (typeof auction_info.user != 'undefined' && auction_info.user.is_gestor && $('#ges_cod_licit').length > 0) {
            params.cod_licit = $('#ges_cod_licit').val();
            params.type_bid = $($this).data().type;
            if (params.type_bid === undefined) {
                params.type_bid = 'W';
            }
            params.is_gestor = true;

            if (params.cod_licit == null || typeof params.cod_licit == 'undefined' || !params.cod_licit) {
                params.cod_licit = auction_info.subasta.dummy_bidder;
            }

            params.tipo_puja_gestor = $('.gestor_radios input:checked').val();
            $("input[name=puja_opts][value='normal']").prop("checked", true);
            $('#ges_cod_licit').val('');
        }
        var string_hash = params.cod_licit + " " + params.cod_sub + " " + params.ref + " " + params.imp;
        params.hash = CryptoJS.HmacSHA256(string_hash, auction_info.user.tk).toString(CryptoJS.enc.Hex);

        /*Necesita licitador para pujar / que esté registrado*/
        if (params.cod_licit == null || typeof params.cod_licit == 'undefined' || !params.cod_licit) {
            displayAlert(0, messages.error.not_licit);
            return;
        }

        $('.add_bid').addClass('loading');
        socket.emit('action', params);
    }

    /*
     |--------------------------------------------------------------------------
     | Órdenes de licitación y pujas
     |--------------------------------------------------------------------------
     */
	//pujar desde botón de puja directa
	$('#ficha').on('click', '.add_next-bid', function (event) {
		/* Terminos y Condiciones de las pujas*/
		/* Hay que aceptarlas para poder continuar*/
		var tos = $('#tos');
		if (typeof tos != 'undefined' && !tos.prop('checked') && tos.prop('checked') == false) {
			return $.magnificPopup.open({items: {src: '#modalAcceptTos'}, type: 'inline', showCloseBtn: false, enableEscapeKey: false, modal: true, closeOnBgClick: false}, 0);
		}

		var amount = auction_info.lote_actual.importe_escalado_siguiente;
		var ref = auction_info.lote_actual.ref_asigl0;
		var imp_sal = auction_info.lote_actual.impsalhces_asigl0;

		/* Solo Ordenes de licitación*/
		var solo_ol = $(this).hasClass('solo_ol');
		if (solo_ol) {
			var can_do_bid = 'orders';
		} else {
			var can_do_bid = 'all';
		}

		addBidLogic(amount, ref, imp_sal, this, can_do_bid);
	});

    /* Añadir puja desde el boton de pujar normal*/
    $('#ficha').on('click', '.add_bid', function () {
        /* Terminos y Condiciones de las pujas*/
        /* Hay que aceptarlas para poder continuar*/
        var tos = $('#tos');
        if (typeof tos != 'undefined' && !tos.prop('checked') && tos.prop('checked') == false) {
            return $.magnificPopup.open({items: {src: '#modalAcceptTos'}, type: 'inline', showCloseBtn: false, enableEscapeKey: false, modal: true, closeOnBgClick: false}, 0);
        }

        var amount = $('#bid_amount').val();
        var ref = auction_info.lote_actual.ref_asigl0;
        var imp_sal = auction_info.lote_actual.impsalhces_asigl0;

        /* Solo Ordenes de licitación*/
        var solo_ol = $('.add_bid').hasClass('solo_ol');
        if (solo_ol) {
            var can_do_bid = 'orders';
        } else {
            var can_do_bid = 'all';
        }

        addBidLogic(amount, ref, imp_sal, this, can_do_bid);

    });

    /* Añadir una orden por importe desde el buscador de lotes*/
    window.orderAmount = function orderAmount()
    {

        /* Si es una orden por importe del buscador de lotes...*/
        var amount = $('#order_amount').val();
        if (auction_info.subasta.click_from == 'modal') {
            var ref = auction_info.modal_item.ref_asigl0;
            var imp_sal = auction_info.modal_item.impsalhces_asigl0;

        } else {
            var ref = auction_info.buscador.ref_asigl0;
            var imp_sal = auction_info.buscador.impsalhces_asigl0;
        }
        playAlert(['new_ol']);
        addBidLogic(amount, ref, imp_sal, this, 'orders');
    };


    socket.on('action_response', function (data) {

        if (typeof data != 'undefined' && typeof data.pujasAll != 'undefined') {
            var primera_puja = data.pujasAll[0]
            if (primera_puja.ref_asigl1 != auction_info.lote_actual.ref_asigl0) {
                return;
            }
        }
        if (typeof auction_info.user != 'undefined') {

            if (data.status == 'error') {

                if (data.msg_1) {
                    if (parseInt(auction_info.user.cod_licit) == parseInt(data.cod_licit_actual) || (auction_info.user.is_gestor && data.is_gestor)) {
                        displayAlert(0, messages.error[data.msg_1]);
                        //si la puja es de tipo sala y el usuario es el gestor y el ganador no es el licitador se le debe notificar que ha sido sobrepujado con un sonido
                        if ((data.type_bid == 'S' || data.type_bid == 'T') && auction_info.user.is_gestor && data.cod_licit_actual != data.winner) {
                            playAlert(['new_bid']);
                        }
                        /* El usuario esta online con lo que no es necesario seguramente*/
                        if ($('#tiempo_real').val() == 0 && data.sobrepuja)
                        {
                            /*emailSobrePuja(auction_info.subasta.cod_sub, data.cod_licit_actual);*/
                        }
                        //notificar a los uaurios la puja si así lo define el config
                    } else if (data.msg_1 == 'higher_bid' && typeof alert_bid != 'undefined' && alert_bid) {
                        playAlert(['new_bid']);
                    }

                }

                if (data.msg_2) {
                    if (parseInt(auction_info.user.cod_licit) == parseInt(data.cod_licit_actual)) {
                        displayAlert(0, messages.error[data.msg_2]);
                    }
                }

            } else if (data.status == 'success') {

                /* Mensajes nuevos*/
                /* Mensaje de sobrepuja a usuario local*/

                if (data.msg_2) {
                    if (parseInt(auction_info.user.cod_licit) == parseInt(data.cod_licit_actual) || (auction_info.user.is_gestor && data.is_gestor)) {
                        if (!auction_info.user.is_gestor || auction_info.lote_actual.tipo_sub != "W") {

							//mostrar precio de reserva no alcanzado, a quien tenga la funcion
							if(typeof reservePriceNotReached === 'function' && data.actual_bid < auction_info.lote_actual.impres_asigl0){
								reservePriceNotReached();
							}

                            displayAlert(1, messages.success[data.msg_2]);

                        }
                        //si la puja es de tipo sala y el usuario es el gestor y el ganador no es el licitador se le debe notificar que ha sido sobrepujado con un sonido
                        if (data.type_bid == 'S' && auction_info.user.is_gestor && data.cod_licit_actual != data.winner) {
                            playAlert(['new_bid']);
                        }
                        //notificar a los uaurios la puja si así lo define el config
                    } else if (typeof alert_bid != 'undefined' && alert_bid) {

                        //comprobación para sonido de notificación en sobrepuas
                        if (parseInt(auction_info.user.cod_licit) == parseInt(data.cod_licit_db)) {
                            if (!auction_info.user.is_gestor || auction_info.lote_actual.tipo_sub != "W") {
                            	playAlert(['new_bid']);
                            }
                        }
                        else{
                            playAlert(['new_bid']);
                        }
                    }
                }

                /* Mensaje a usuario de db*/
                if (data.msg_1) {
                    if (parseInt(auction_info.user.cod_licit) == parseInt(data.cod_licit_db)) {
                        if (!auction_info.user.is_gestor || auction_info.lote_actual.tipo_sub != "W") {
                            //si config higher_bid_tr es true no enviar notificación de sobrepuja
                            if (typeof higher_bid_notshow == 'undefined' || !higher_bid_notshow) {
                                displayAlert(3, messages.error[data.msg_1]);
                            }
                        }
                    }
                }

                /*
                 if(parseInt(auction_info.user.cod_licit) != parseInt(data.cod_licit_actual))
                 {
                 //email de sobrepuja
                 if($('#tiempo_real').val() == 0 && data.sobrepuja) {
                 emailSobrePuja (auction_info.subasta.cod_sub, data.cod_licit_actual, auction_info.lote_actual);
                 }
                 // Email de sobreorden a usuario con orden inferior
                 if(data.sobreorden && auction_info.lote_actual.tipo_sub == 'W')
                 {
                 emailSobrePuja (auction_info.subasta.cod_sub, data.cod_licit_actual, auction_info.lote_actual);
                 }
                 }

                 */

            }

        }


        /*Si es una orden, como es una orden para un lote que no es el actual, no actualiza la interfaz.*/
        /*if (typeof data.can_do != 'undefined' && data.can_do == 'orders') {*/
        if (typeof data.can_do == 'undefined' || data.can_do != 'orders') {

            /* Actualizamos la interfaz de la ficha de subasta normal*/
            if (typeof auction_info.user != 'undefined' && auction_info.user.cod_licit == data.cod_licit_actual) {
                $(".hist_new").removeClass("hidden");
                //muestra la orden o puja al usuario que ha pujado
				$('#tuorden').html(data.imp_original_formatted);
				//solo mostramso el boton de eliminar orden cuando sea una orden
				if(typeof data.actual_bid != 'undefined' && typeof data.imp_original != 'undefined' && parseInt(data.imp_original) > parseInt(data.actual_bid)){
					$("#cancelarOrdenUser").removeClass("hidden");
				}

                /*console.log(data.himp_formatted);
                 console.log(data);
                 console.log('return de can do orders');*/
            }
        }

        if (typeof data.actual_bid != 'undefined') {
            //
            actionResponseDesign(data);
            /* realizamos acciones cuando una accion ha tenido exito*/
            action_success(data);

            /*
             |--------------------------------------------------------------------------
             | Inicio actualización Ordenes de licitación
             |--------------------------------------------------------------------------
             */
            if ((data.imp > auction_info.lote_actual.importe_escalado_siguiente && data.cod_licit_actual == data.winner) || (data.imp_original > auction_info.lote_actual.importe_escalado_siguiente))
            {
                var obj_ol = {cod_licit: data.cod_licit_actual, imp_asigl1: data.actual_bid, formatted_imp_asigl1: data.formatted_actual_bid, himp_orlic: data.imp_original, himp_orlic_formatted: data.imp_original_formatted, tipop_orlic: data.type_bid};
				//se esta poniendo como tipop la puja ganadora, esto provoca error si hay ya una orden que gana a la actual ya que se pone el tipo de orden de la puja ganadora
				//para evitar que se vean ordenes de reserva si no lo son pongo esto
				if(obj_ol.tipop_orlic == 'R'){
					obj_ol.tipop_orlic = 'S';
				}
                /* Ordenes anteriores antes de actualizarlas para poder eliminarlas*/
                auction_info.lote_actual.ordenes_old = auction_info.lote_actual.ordenes;


                /* Antes de asignar el nuevo item eliminamos el antiguo*/
                /*NGAMEZ,SOLO SE MUESTRA UNA ORDEN POR LICITADOR, ELIMINA REGISTROS DUPLICADOS DEL LICITADOR*/

                $.each(auction_info.lote_actual.ordenes, function (key, value) {

                    if (typeof value != 'undefined' && value.cod_licit == data.cod_licit_actual)
                    {
                        // si el pujador es el dummy, si la orden es inferior a la anterior, nos quedaremso con la anterior ntes de borrarla.
                        if (data.cod_licit_actual == auction_info.subasta.dummy_bidder && parseInt(data.imp_original) < parseInt(auction_info.lote_actual.ordenes[key].himp_orlic)) {
                            obj_ol = auction_info.lote_actual.ordenes[key];
                        }
                        /*console.log('Key1: '+ key + " importe1: "+ value.himp_orlic);	*/
                        auction_info.lote_actual.ordenes.splice(key, 1);
                    }

                });

                /* Actualizamos las ordenes de licitacion del js*/
                auction_info.lote_actual.ordenes.unshift(obj_ol);     /*ESTO ESTABA FUERA DEL IF, NO SE PORQUE*/
            }
            /*
             |--------------------------------------------------------------------------
             | Fin actualización Ordenes de licitación
             |--------------------------------------------------------------------------
             */

            /*Actualiza el objeto con los datos de la subasta.
             si la puja es diferente al actual o es la primera puja
             if (typeof auction_info.lote_actual.pujas[0] != 'undefined' && auction_info.lote_actual.pujas[0].imp_asigl1 != data.actual_bid){
             si la puja es diferente a la actual o si es igual al importe de salida y no hay ninguna puja ya */
            if (typeof data != 'undefined' && (auction_info.lote_actual.actual_bid != data.actual_bid || (auction_info.lote_actual.impsalhces_asigl0 == data.actual_bid && auction_info.lote_actual.pujas.length == 0))) {

                var obj = {cod_licit: data.winner, imp_asigl1: data.actual_bid, formatted_imp_asigl1: data.formatted_actual_bid, pujrep_asigl1: data.type_bid, bid_date: new Date(), type_asigl1: 'N'};



                if (typeof data.pujasAll != 'undefined') {
                    /* Actualizamos la lista de pujas con todo el array de pujas caso de batalla de Ordenes de licitacion*/
                    auction_info.lote_actual.pujas = data.pujasAll;
                } else {
                    // Actualizamos la lista de pujas con la nueva puja
                    auction_info.lote_actual.pujas.unshift(obj);
                }

                /*hacer sonido si es diferente de sala o telefono y el usuario es administrador o el usuario es normal y es su puja */
                if (data.type_bid != 'S' && data.type_bid != 'T' && typeof auction_info.user != 'undefined' && (auction_info.user.is_gestor || (!auction_info.user.is_gestor && auction_info.user.cod_licit == data.cod_licit_actual))) {
                    playAlert(['new_bid']);
                }
                auction_info.lote_actual.max_puja = obj;
                auction_info.lote_actual.importe_escalado_siguiente = data.siguiente;

                auction_info.lote_actual.actual_bid = data.actual_bid;
            }


        }

        if (typeof data.siguiente != 'undefined') {
            if (!$('#bid_amount').is(':focus')) {
				$('#bid_amount').val(data.siguiente);
				$('#next_bid_JS').html(format_thousand(data.siguiente));
				//para subastas con input de subasta en firme
				$('#bid_amount_firm').val(data.siguiente);
            }
        }

        $('.add_bid').removeClass('loading');

        reloadPujasList();
        /* Solo actualizamos la lista de ordenes cuando realmente sea una orden de licitación*/
        /*if((data.imp >= auction_info.lote_actual.importe_escalado_siguiente) || (data.imp_original > auction_info.lote_actual.importe_escalado_siguiente)) {*/
        reloadOrderList();
        /*console.log(data);*/

    });
    /*
     |--------------------------------------------------------------------------
     | END Órdenes de licitación y pujas
     |--------------------------------------------------------------------------
     */


    /*
     |--------------------------------------------------------------------------
     | Bloquea las pujas al pasar de lote, mientras se devuelven los datos.
     |--------------------------------------------------------------------------
     */
    socket.on('closeBidsEndLot', function (data) {

		if (auction_info.subasta.sub_tiempo_real != 'S') {
            return;
        }

        if (typeof auction_info.user != 'undefined' && data.cod_licit != auction_info.user.cod_licit) {
            $('.add_bid').addClass('loading');
            $('.txt_esperando_sala').removeClass('hidden');
            $('.txt_loading').addClass('hidden');
            $.magnificPopup.open({items: {src: '#modalCloseBids'}, type: 'inline', showCloseBtn: false, enableEscapeKey: false, modal: true, closeOnBgClick: false}, 0);

        }
    });
    socket.on('closeBids', function (data) {
        closeBids();
    });
    socket.on('openBids', function (data) {
        openBids();
    });




    /*
     |--------------------------------------------------------------------------
     | Comprar lote (a través del popup modal)
     |--------------------------------------------------------------------------
     */

    $('.lot-action_comprar').on('click', function (e) {
        e.stopPropagation();
        $.magnificPopup.close();

        auction_info.subasta.click_from = $(this).data().from;


        $.magnificPopup.open({items: {src: '#modalComprar'}, type: 'inline'}, 0);
    });


    window.comprarLote = function comprarLote()
    {
        /* Si no hay sesión de usuario le informaremos*/
        if (typeof auction_info.user == 'undefined') {
            var mfp = $.magnificPopup.instance;
            setTimeout(function () {
                if (!mfp.isOpen) {
                    $.magnificPopup.open({
                        items: {
                            src: '#closedSession'
                        },
                        type: 'inline'
                    });
                }
            }, 500);

            return;
        }


        if (!$('#infoLot').hasClass('mfp-hide')) {
            var ref = auction_info.modal_item.ref_asigl0;

        } else {
            var ref = auction_info.buscador.ref_asigl0;

        }
        var cod_sub = auction_info.subasta.cod_sub;
        if (typeof auction_info.user != 'undefined' && auction_info.user.is_gestor) {
            var cod_licit = $('#ges_cod_licit').val();
        } else {
            var cod_licit = auction_info.user.cod_licit;
        }


        $.ajax({
            type: "POST",
            url: routing.comprar + '-' + cod_sub,
            data: {cod_sub: cod_sub, ref: ref, cod_licit: cod_licit},
            success: function (data) {

                if (data.status == 'error') {
                    displayAlert(0, data.msg_1);
                    $(".lot-action_comprar").addClass('hidden');
                } else if (data.status == 'success') {
                    displayAlert(1, data.msg);
                    //añadimos el lote a adjudicaciones.

                    var lote = {'ref_asigl1': data.ref, 'imp_asigl1': data.imp}
                    auction_info.user.adjudicaciones.splice(0, 0, lote);
                    reloadAdjudicaciones();
                    $(".lot-action_comprar").addClass('hidden');
                }


            }
        });


    }


    $('.lot-action_pujar').on('click', function (e) {
        e.stopPropagation();
        $.magnificPopup.close();
        /* Si no hay sesión de usuario le informaremos*/
        if (typeof auction_info.user == 'undefined')
        {
            var mfp = $.magnificPopup.instance;
            setTimeout(function () {
                if (!mfp.isOpen)
                {
                    $.magnificPopup.open({
                        items: {
                            src: '#closedSession'
                        },
                        type: 'inline'
                    });
                }
            }, 500);

            return;
        }
        auction_info.subasta.click_from = $(this).data().from;


        var val_nodecimales = $("#actual_max_bid2").html();
        /*val_nodecimales = val_nodecimales.substr(0, val_nodecimales.indexOf(","));*/
        val_nodecimales = val_nodecimales.replace(",", ".");
        val_nodecimales = parseInt(val_nodecimales);
        $("#bid_modal_pujar").val(val_nodecimales);
        $.magnificPopup.open({items: {src: '#modalPujar'}, type: 'inline'}, 0);
    });


    window.do_bid = function do_bid()
    {
        /* Si no hay sesión de usuario le informaremos*/
        if (typeof auction_info.user == 'undefined')
        {
            var mfp = $.magnificPopup.instance;
            setTimeout(function () {
                if (!mfp.isOpen)
                {
                    $.magnificPopup.open({
                        items: {
                            src: '#closedSession'
                        },
                        type: 'inline'
                    });
                }
            }, 500);

            return;
        }

        var txtBid = $("#bid_modal_pujar").val();
        txtBid = txtBid.replace(".", "");
        txtBid = txtBid.replace(",", ".");
        var amount = parseFloat(txtBid);
        var ref = auction_info.modal_item.ref_asigl0;
        var imp_sal = auction_info.modal_item.impsalhces_asigl0;
        addBidLogic(amount, ref, imp_sal, this, 'orders');


        /*
         if ( !$('#infoLot').hasClass('mfp-hide') )
         {
         var ref = auction_info.modal_item.ref_asigl0;
         var order = auction_info.modal_item.orden_hces1;
         }
         else
         {
         var ref = auction_info.buscador.ref_asigl0;
         var order = auction_info.buscador.orden_hces1;
         }

         var params = {'cod_licit': auction_info.user.cod_licit, 'cod_sub': auction_info.subasta.cod_sub, 'ref': ref, 'url': routing.comprar, 'orden_lote_actual': auction_info.lote_actual.orden_hces1, 'orden_lote': order};

         //Si es gestor.
         if(typeof auction_info.user != 'undefined' && auction_info.user.is_gestor)
         {
         params.cod_licit 	= $('#ges_cod_licit').val();
         params.is_gestor	= true;
         }

         //Necesita licitador para pujar / que esté registrado
         if (params.cod_licit == null || typeof params.cod_licit == 'undefined' || !params.cod_licit)
         {
         displayAlert(0, messages.error.not_licit);
         return;
         }

         */
        //socket.emit('comprar', params);

    }






    /*
     |--------------------------------------------------------------------------
     | END Comprar lote
     |--------------------------------------------------------------------------
     */




    /*Respuesta de eliminar un mensaje*/
    socket.on('delete_msg_response', function (data) {

        if (data.status == 'error' && typeof auction_info.user != 'undefined' && auction_info.user.is_gestor) {
            displayAlert(0, data.msg);
            return;
        }

        var id_mensaje = data.id_mensaje;
        var mensajes = auction_info.chat.mensajes;

        /* Si es gestor y esta en la misma subasta le enviamos el delete de linea de mensaje (predeterminados)*/
        if (typeof auction_info.user != 'undefined' && auction_info.user.is_gestor && data.cod_sub == auction_info.subasta.cod_sub && data.predefinido == 1) {

            /* borramos el item del objeto js*/
            delete mensajes[id_mensaje];

            $('#predefinido-model-' + id_mensaje).remove();

        }

        if (data.cod_sub == auction_info.subasta.cod_sub && data.predefinido == 0) {
            /* borramos el item del objeto js*/
            delete mensajes[id_mensaje];

            $('#chatline_model_' + id_mensaje).remove();
        }

    });


    /*
     $('#ficha').on('click', '.delete-msg', function() {
     var id_mensaje = $(this).attr('id_mensaje');



     // solo puede enviar chat el gestor
     if(typeof auction_info.user != 'undefined' && auction_info.user.is_gestor) {
     socket.emit('delete_msg', params);
     }

     });*/





    socket.on('chat_response', function (data) {
        if (data.status == 'error' && typeof auction_info.user != 'undefined' && auction_info.user.is_gestor) {
            displayAlert(0, data.msg);
            return;
        } else if (!auction_info.user.is_gestor) {
            displayAlert(1, messages.success.new_chat_message);
            //si tab-messages existe hacemos la animacion del tab messages
            if ($('#tab-messages').length > 0) {
                if (!$('#tab-messages').hasClass('active')) {
                    $('#tab-messages').addClass('animation');
                    $('#tab-messages').one('webkitAnimationEnd oanimationend msAnimationEnd animationend',
                            function (e) {
                                // code to execute after animation ends
                                $('#tab-messages').removeClass('animation');
                                $($('.alert-messages').addClass('pending'))
                            });
                }
            }

        }
        auction_info.chat.mensajes = data.mensaje;

        var container = $('.chat');
        var model = $('#chatline_model').clone();

        contador1 = 1;
        contador2 = 1;
        fail = false;

        $.each(data.mensaje, function (key, item) {

            if (typeof item[auction_info.lang_code] == 'undefined') {

                fail = true;

                Object.keys(data.mensaje[key]).forEach(function (index) {

                    if (typeof data.mensaje[key][index] != 'undefined') {
                        item = data.mensaje[key][index];
                    }

                });

            } else {
                original = item;
                item = item[auction_info.lang_code];
            }

            if ((item.lang_code == auction_info.lang_code && item.predefinido == 0) || ((fail == true && item.predefinido == 0))) {

                if (contador1 == 1) {
                    container.html("");
                }

                var $this = model.clone().removeClass('hidden').removeAttr('id');
                var fecha = item.fecha.replace(/\//g, "-");
                var parte = fecha.split(" ");
                var fecha_final = parte[0] + 'T' + parte[1];

                $('.texto', $this).append(item.msg);

                /* Si es gestor mostramos el boton eliminar*/
                if (typeof auction_info.user != 'undefined' && auction_info.user.is_gestor) {
                    $('.btn-eliminar', $this).attr('id_mensaje', item.id_web_chat);
                } else {
                    $('.btn-eliminar', $this).remove();
                }

                $('.timeago', $this).attr('datetime', fecha_final);
                $($this).attr('id', 'chatline_model_' + item.id_web_chat);

                container.prepend($this);
                contador1++;

            } else if (item.predefinido == 1) {

                /* Si son mensajes predefinidos los añadimos en el panel del gestor*/
                /* inicio clonacion para añadir mensajes predefinidos */

                var container2 = $('.chat-predefinidos');
                var model2 = $('#predefinido-model').clone();
                var $this = model2.clone().removeClass('hidden').removeAttr('id');

                if (contador2 == 1) {
                    $('.chat-predefinidos').html("");
                }

                mensaje = item.msg;

                if (mensaje) {
                    $('.texto', $this).html(mensaje);
                    $('.btn-enviar', $this).attr('id_mensaje', item.id_web_chat);
                    $('.btn-eliminar', $this).attr('id_mensaje', item.id_web_chat);
                    $($this).attr('id', 'predefinido-model-' + item.id_web_chat);
                    container2.prepend($this);
                }

                contador2++;
                /* fin clonacion */

            }

            fail = false;


        });

        $("time.timeago").timeago();
    });






    /*
     |--------------------------------------------------------------------------
     | Ventana modal de confirm
     | En el html hay que indicar el attr data-to ya que llamará a esa función al aceptar
     |--------------------------------------------------------------------------
     */

    /* Contenido movido a common.js*/

    $.magnificPopup.instance.close = function () {
        reloadBuscadorFavs();
        $.magnificPopup.proto.close.call(this);
    };

	//Evento al abrir el modal de asignr lote en el tiempo real
    $.magnificPopup.instance.open = function (data) {

		var showModal = (typeof showEverModal != 'undefined' && showEverModal);

		if (typeof data.items != 'undefined' && data.items.src == "#modalEndLot") {

			if (typeof auction_info.lote_actual.max_puja != 'undefined' && auction_info.lote_actual.max_puja != 0 && auction_info.lote_actual.max_puja.cod_licit == auction_info.subasta.dummy_bidder ) {
				$('#w_undefined').val('');

                $('#modalEndLot .winner_undefined').removeClass('hidden');


            }
			else if (showModal  &&  auction_info.lote_actual.max_puja.cod_licit != auction_info.subasta.dummy_bidder){
				$('#modalEndLot .winner_undefined').addClass('hidden');
			}
			else {

				$('#modalEndLot .winner_undefined').addClass('hidden');

            }
        }

        $.magnificPopup.proto.open.call(this, data);

    };



    /*
     |--------------------------------------------------------------------------
     | END Ventana modal de confirm
     |--------------------------------------------------------------------------
     */


    /*
     |--------------------------------------------------------------------------
     | Buscar Lote
     |--------------------------------------------------------------------------
     */
    $('#ficha #search_item_field').keypress(function (e) {
        var key = e.which;
        if (key == 13)  /* the enter key code*/
        {
            var ref = parseInt($(this).val());
            if (!isNaN(ref)) {
                SearchLoteAjax(ref, 0, 'CURRENT');
            } else {
                displayAlert(0, messages.error.lot_not_found);
            }

        }
    });

    $('#ficha').find('#search_item').click(function () {
        var ref = parseInt($('#ficha #search_item_field').val());
        /* Valores actuales*/
        if (!isNaN(ref)) {
            SearchLoteAjax(ref, 0, 'CURRENT');
        } else {
            displayAlert(0, messages.error.lot_not_found);
        }
    });

    /*Ficha de lote a tiempo real. Buscar lote por las flechas.*/
    $('#ficha #loteAjax').find('.controles').click(function () {

        if ($(this).attr('id') == 'right')
        {
            if (Number(auction_info.buscador.ref_asigl0) < Number(auction_info.subasta.last_item))
            {

                SearchLoteAjax(auction_info.buscador.ref_asigl0, auction_info.buscador.orden_hces1, 'NEXT');
            }
        } else
        {
            if ($(this).attr('id') == 'left')
            {
                if (Number(auction_info.buscador.ref_asigl0) > Number(auction_info.subasta.first_item))
                {

                    SearchLoteAjax(auction_info.buscador.ref_asigl0, auction_info.buscador.orden_hces1, 'PREVIOUS');
                }
            }
        }
    });


    /*Recarga los botones del buscador o del modal de información de un lote.*/
    function reloadBuscador(info_lot, from) {

        var selector = {};

        if (from == 'buscador') {
            selector = $('#loteAjax');
        } else if (from == 'modal') {
            selector = $('#infoLot');
        }

        if (parseInt(info_lot.orden_hces1) < parseInt(auction_info.lote_actual.orden_hces1)) {

            if (info_lot.cerrado_asigl0 != 'N' && (typeof info_lot.max_puja == 'undefined' || info_lot.max_puja == 0)) {

                if (typeof auction_info.user != 'undefined') {
                    if (info_lot.cerrado_asigl0 == 'P' || info_lot.cerrado_asigl0 == 'J' || info_lot.compra_asigl0 == 'N') {
                        $('.lot-action_comprar', selector).addClass('hidden');
                    } else {
                        $('.lot-action_comprar', selector).removeClass('hidden');
                    }
                }

                $('.lot-msg_adjudicado', selector).addClass('hidden');
                $('.lot-msg_ensubasta', selector).addClass('hidden');
                $('.lot-order_importe', selector).addClass('hidden');

            } else {
                if (typeof auction_info.user != 'undefined' && info_lot.cerrado_asigl0 == 'S') {
                    $('.lot-msg_adjudicado').removeClass('hidden');
                }
                $('.lot-action_comprar', selector).addClass('hidden');
                $('.lot-msg_ensubasta', selector).addClass('hidden');
                $('.lot-order_importe', selector).addClass('hidden');
            }

            /*Lote actual*/
        } else if (parseInt(info_lot.orden_hces1) == parseInt(auction_info.lote_actual.orden_hces1)) {
            if (typeof auction_info.user != 'undefined') {
                $('.lot-msg_ensubasta', selector).removeClass('hidden');
            }

            $('.lot-action_comprar', selector).addClass('hidden');
            $('.lot-msg_adjudicado', selector).addClass('hidden');
            $('.lot-order_importe', selector).addClass('hidden');

            /*Lote disponible*/
        } else {
            if (info_lot.cerrado_asigl0 != 'N' && (typeof info_lot.max_puja == 'undefined' || info_lot.max_puja == 0)) {

                if (typeof auction_info.user != 'undefined') {

                    if (info_lot.cerrado_asigl0 == 'P' || info_lot.cerrado_asigl0 == 'J' || info_lot.compra_asigl0 == 'N') {
                        $('.lot-action_comprar', selector).addClass('hidden');
                    } else {
                        $('.lot-action_comprar', selector).removeClass('hidden');
                    }
                }

                $('.lot-msg_adjudicado', selector).addClass('hidden');
                $('.lot-msg_ensubasta', selector).addClass('hidden');
                $('.lot-order_importe', selector).addClass('hidden');

            } else {

                if (typeof auction_info.user != 'undefined' && info_lot.cerrado_asigl0 == 'N') {
                    $('.lot-order_importe', selector).removeClass('hidden');
                } else {
                    $('.lot-order_importe', selector).addClass('hidden');
                }

                $('.lot-action_comprar', selector).addClass('hidden');
                $('.lot-msg_adjudicado', selector).addClass('hidden');
                $('.lot-msg_ensubasta', selector).addClass('hidden');

            }
        }


        if (from == 'buscador') {
            /* Seteamos los nuevos valores del lote seleccionado*/
            if (typeof auction_info.buscador.imagen_base64 != 'undefined') {
                $('.img-responsive', selector).prop('src', 'data:image/jpg;base64,' + auction_info.buscador.imagen_base64);
            } else {
                $('.img-responsive', selector).attr('src', '/img/load/lote_small/' + auction_info.buscador.imagen);
            }
            //cargamos la imagen en infolot, para que este cargado si lo abren
            $('.img-responsive', '#infoLot').attr('src', '/img/load/lote_medium/' + auction_info.buscador.imagen);
			var refLotBuscador = auction_info.buscador.ref_asigl0;

			refLotBuscador=refLotBuscador.replace(".1","-A").replace(".2","-B").replace(".3","-C").replace(".4","-D").replace(".5","-E");


            $('#slote_title', selector).html(refLotBuscador);
			if (typeof auction_info.buscador.text_lang == 'undefined'){
            	$('h4', selector).html(auction_info.buscador.titulo_hces1);
            	$('#desc_web', selector).html(auction_info.buscador.descweb_hces1);
			}else{
				$('h4', selector).html(auction_info.buscador.text_lang[auction_info.lang_code].titulo_hces1);
            	$('#desc_web', selector).html(auction_info.buscador.text_lang[auction_info.lang_code].descweb_hces1);
			}
            $('.precio', selector).html(auction_info.buscador.formatted_impsalhces_asigl0);
            if (typeof auction_info.buscador.himp_csub != 'undefined' && auction_info.buscador.himp_csub != '0') {
				if(auction_info.subasta.currency.symbol == '$' || auction_info.subasta.currency.symbol == 'US$'){
					$('.imp_adj', selector).html( auction_info.subasta.currency.symbol + auction_info.buscador.himp_csub);
				}
				else{
					$('.imp_adj', selector).html(auction_info.buscador.himp_csub + auction_info.subasta.currency.symbol);
				}
            } else {
                $('.imp_adj', selector).html('');
            }

            $('.pausarLote').attr('ref', auction_info.buscador.ref_asigl0);

            $('.pausarLote').removeClass("reanudarLote");
            /*$('.pausarLote').addClass("pausarLote"+auction_info.buscador.ref_asigl0);*/
            $("#url_lot").attr("href", auction_info.buscador.url_lot);
            /*console.log("ESTADO: "+auction_info.buscador.cerrado_asigl0);*/
            $('#abrirLote').addClass('hidden');
            if (auction_info.buscador.cerrado_asigl0 == 'P') {
                $('.pausarLote').html(messages.neutral.resume);
                $('.pausarLote').attr('onclick', "$('.reanudarLote" + auction_info.buscador.ref_asigl0 + "').click();");
                $('.pausarLote').removeClass('hidden');
                $('#activate_next').addClass('hidden');
                /*$('.pausarLote').removeClass('pausarLote');
                 $('.pausarLote').removeClass('pausarLote'+auction_info.buscador.ref_asigl0);*/

            } else if (auction_info.buscador.cerrado_asigl0 == 'N') {
                $('.pausarLote').html(messages.neutral.pause_lot);
                $('.pausarLote').removeAttr('onclick');
                $('.pausarLote').removeClass('hidden');
                $('#activate_next').removeClass('hidden');
            } else if (auction_info.buscador.cerrado_asigl0 == 'J') {
                $('.pausarLote').addClass('hidden');
                $('#abrirLote').addClass('hidden');
                $('#activate_next').addClass('hidden');
            } else {
                $('.pausarLote').addClass('hidden');
                $('#abrirLote').removeClass('hidden');
                $('#activate_next').addClass('hidden');
                $('#url_lot').addClass('hidden');
            }


            if (auction_info.buscador.ref_asigl0 == auction_info.lote_actual.ref_asigl0) {
                $('.pausarLote').addClass('hidden');
            }

        }
    }


    function SearchLoteAjax(ref, order, search)
    {
        /*console.log(auction_info.buscador);*/

        $.ajax({
            type: "GET",
            url: '/api-ajax/get_lote/' + auction_info.lang_code + '/' + auction_info.subasta.cod_sub + "/" + auction_info.subasta.id_auc_sessions + "/" + ref + '/' + order + '/' + search,
            beforeSend: function () {
                $("#loteAjax").css('opacity', '0.5');
            },
            success: function (msg) {

                var parsed_msg = $.parseJSON(msg);

                if (typeof parsed_msg != 'undefined' && typeof parsed_msg == 'object' && !$.isEmptyObject(parsed_msg) && parsed_msg.status == 'success') {

                    auction_info.buscador = parsed_msg.lote;
                    reloadBuscador(auction_info.buscador, 'buscador');
                    reloadBuscadorFavs();
                } else {
                    displayAlert(0, messages.error.lot_not_found);
                }

                /* El lote ha sido adjudicado/cerrado*/


                $("#loteAjax").css('opacity', '1');


            }
        });
    }
    /*
     |--------------------------------------------------------------------------
     | END Buscar Lote
     |--------------------------------------------------------------------------
     */


    /*
     |--------------------------------------------------------------------------
     | Repinta la caja de pujas
     |--------------------------------------------------------------------------
     */
    /* HE PASADO LA FUNCION A PERSONALIZA_TR_MAIN
     function reloadPujasList()
     {

     var model = $('#type_bid_model').clone();
     var container = $('.aside.pujas #pujas_list');

     if (typeof auction_info.lote_actual != 'undefined' && typeof auction_info.lote_actual.pujas != 'undefined' && typeof container != 'undefined' && container.length > 0){

     $('.aside.pujas .pujas_model:not(#type_bid_model)').remove();

     $.each( auction_info.lote_actual.pujas, function( key, value ) {

     // limite de pujas a mostrar
     if (key >= auction_info.subasta.max_bids_shown && auction_info.subasta.max_bids_shown != -1){
     return false;
     }

     var $this = model.clone().removeClass('hidden').removeAttr('id');

     $('.importePuja .puj_imp', $this).html(value.formatted_imp_asigl1);
     $('.importePuja .licitadorPuja', $this).html('('+value.cod_licit+')');

     $('.tipoPuja p:not(.hidden)', $this).addClass('hidden');
     $('.tipoPuja p[data-type="'+ value.pujrep_asigl1 +'"]', $this).removeClass('hidden');

     container.append($this);
     });
     }
     }
     */
    /*
     |--------------------------------------------------------------------------
     | END Repinta la caja de pujas
     |--------------------------------------------------------------------------
     */


	function removeDuplicates(arr, prop) {
		var new_arr = [];
		var lookup = {};

		for (var i in arr) {
			lookup[arr[i][prop]] = arr[i];
		}

		for (i in lookup) {
			new_arr.push(lookup[i]);
		}

		return new_arr;
	}

    /*
     |--------------------------------------------------------------------------
     | Repinta la caja de ordenes de licitacion
     |--------------------------------------------------------------------------
     */
    function reloadOrderList()
    {
        var model = $('#type_bid_model_order').clone();
        var container = $('.aside.ol #ol_list');
        var clave = false;
        $('.aside.ol .ol_model:not(#type_bid_model_order)').remove();
        if (typeof auction_info.lote_actual != 'undefined' && typeof auction_info.lote_actual.ordenes != 'undefined' && typeof container != 'undefined' && container.length > 0) {



            $.each(auction_info.lote_actual.ordenes, function (key, value) {
                /* limite de pujas a mostrar*/
                if (key >= auction_info.subasta.max_bids_shown && auction_info.subasta.max_bids_shown != -1) {
                    return false;
                }

                var $this = model.clone().removeClass('hidden').removeAttr('id');

                $('.importeOrden .puj_imp_order', $this).html(value.himp_orlic_formatted);
                /*Nombre de los licitadores*/
                var name_licit = messages.neutral.new_licit;
                if (typeof licitadores == 'undefined') {
                    var name_licit = "";
                } else if (typeof licitadores != 'undefined' && typeof licitadores[value.cod_licit] != 'undefined') {
                    name_licit = licitadores[value.cod_licit];
                } else if (value.cod_licit == auction_info.subasta.dummy_bidder) {
                    name_licit = '-';
                }
                /*Fin de nombre de los licitadores*/
                $('.importeOrden .licitadorOrden', $this).html('(' + value.cod_licit + ') <span style="font-size: 12px;"> ' + name_licit + '</span>');

                $('.tipoOrden p:not(.hidden)', $this).addClass('hidden');
                $('.tipoOrden p[data-type="' + value.tipop_orlic + '"]', $this).removeClass('hidden');

                container.append($this);
            });
        }
    }
    /*
     |--------------------------------------------------------------------------
     | END Repinta la caja de ordenes de licitacion
     |--------------------------------------------------------------------------
     */

    /*
     |--------------------------------------------------------------------------
     | Muestra la caja del gestor
     |--------------------------------------------------------------------------
     */
    $('#controles_gestor_box').on('click', '.desplegable', function () {
        if ($('#controles_gestor_box').hasClass('opened_box')) {
            $('#controles_gestor_box').removeClass('opened_box');
            $('i', this).removeClass('fa-angle-left');
            $('i', this).addClass('fa-angle-right');
        } else {
            $('#controles_gestor_box').addClass('opened_box');
            $('i', this).removeClass('fa-angle-right');
            $('i', this).addClass('fa-angle-left');

        }
    });
    /*
     |--------------------------------------------------------------------------
     | END Muestra la caja del gestor
     |--------------------------------------------------------------------------
     */



    socket.on('cancel_order_response', function (data) {

        if (data['status'] == "error") {
            if (typeof auction_info.user != 'undefined' && data['cod_licit_actual'] == auction_info.user.cod_licit) {
                displayAlert(0, messages.error[data['msg_1']]);
            }
        } else {

            auction_info.lote_actual.ordenes = data['ordenes'];

            reloadOrderList();
            if (typeof auction_info.user != 'undefined' && data['licit_delete'] == auction_info.user.cod_licit) {
				displayAlert(3, messages.error[data['msg_delete']]);
				tuorden =0;
				//cogemso la puja máxima que tenga el usuario para sustituir a la orden borrada
				$.each(auction_info.lote_actual.pujas, function (key, value) {

					if (typeof value != 'undefined' && value.cod_licit == auction_info.user.cod_licit)
					{
						if (tuorden == "0") {

							tuorden = value.formatted_imp_asigl1;
						}
					}
				});
				$('#tuorden').html(tuorden);
				$("#cancelarOrdenUser").addClass("hidden");
            }
            if (typeof auction_info.user != 'undefined' && data['licit'] == auction_info.user.cod_licit) {
                displayAlert(1, messages.success[data['msg_response']]);
            }
        }

    });

    socket.on('cancel_bid_response', function (data) {


        if (data['status'] == "error") {
            if (typeof auction_info.user != 'undefined' && data['cod_licit_actual'] == auction_info.user.cod_licit) {
                displayAlert(0, messages.error[data['msg_1']]);
            }
        } else {
            auction_info.lote_actual.actual_bid = data['actual_bid'];
            auction_info.lote_actual.max_puja.cod_licit = data['actual_licit'];
            auction_info.lote_actual.formatted_actual_bid = data['formatted_actual_bid'];
            auction_info.lote_actual.importe_escalado_siguiente = data['importe_escalado_siguiente'];
            if (data['pujas'].length > 0) {
				if(auction_info.subasta.currency.symbol == '$' || auction_info.subasta.currency.symbol == 'US$'){
					$('#actual_max_bid').html( auction_info.subasta.currency.symbol + auction_info.lote_actual.formatted_actual_bid );
				}else{
					$('#actual_max_bid').html(auction_info.lote_actual.formatted_actual_bid + auction_info.subasta.currency.symbol);
				}

                $('#text_actual_no_bid').addClass('hidden');
                $('#text_actual_max_bid').removeClass('hidden');
            } else {
                $('#actual_max_bid').html('');
                $('#text_actual_max_bid').addClass('hidden');
                $('#text_actual_no_bid').removeClass('hidden');
            }

			$('#bid_amount').val(auction_info.lote_actual.importe_escalado_siguiente);
			$('#next_bid_JS').html(format_thousand(auction_info.lote_actual.importe_escalado_siguiente));

            //funcion customizada
            if (typeof actualize_currency === 'function') {
                actualize_currency(data);
            }
            auction_info.lote_actual.pujas = data['pujas'];

            reloadPujasList();
            if (typeof data['ordenes'] != 'undefined') {
                auction_info.lote_actual.ordenes = data['ordenes'];
                reloadOrderList();

            }
            if (typeof auction_info.user != 'undefined' && data['licit_delete'] == auction_info.user.cod_licit) {
                $('#cancelarPujaUser').addClass('hidden');
                displayAlert(3, messages.error[data['msg_delete']]);
                $('#actual_max_bid').addClass('other');
                $('#tupuja').html('');
				$('#tuorden').html('0');

                $.each(auction_info.lote_actual.pujas, function (key, value) {
                    if (value.cod_licit == auction_info.user.cod_licit) {
                        $('#tupuja').html(value.formatted_imp_asigl1);
                        //se pone la ultima puja mayors
						$('#tuorden').html(value.formatted_imp_asigl1);

                        return false
                    }
                });

            }
            if (typeof auction_info.user != 'undefined' && data['actual_licit'] == auction_info.user.cod_licit) {

                $('#cancelarPujaUser').removeClass('hidden');
                $('#actual_max_bid').removeClass('other');
                $('#actual_max_bid').addClass('mine');
                displayAlert(1, messages.error[data['msg_response']]);
            }
        }

    });
    /*
     |--------------------------------------------------------------------------
     | Fin Cancelar una puja
     |--------------------------------------------------------------------------
     */




    socket.on('auction_status_response', function (params) {

        if (typeof auction_info.user != 'undefined' && auction_info.user.is_gestor) {

            if (params.status == 'stopped') {
                $('.change_auction_status[data-status="stopped"]').addClass('hidden');
                $('.change_auction_status[data-status="in_progress"]').removeClass('hidden');
            }else{
                $('.change_auction_status[data-status="in_progress"]').addClass('hidden');
                $('.change_auction_status[data-status="stopped"]').removeClass('hidden');
            }

		}


        if ((params.status == 'start' || params.status == 'in_progress')) {


            $('#clock, button.start').hide();
            $('.started').removeClass('hidden');
			$('body').addClass('tr_progress').removeClass('tr_stop').removeClass('tr_finished');

            params.status = 'in_progress';
            $('.tiempo_real')[0].style.position = "initial";

            if (typeof params.pujas != 'undefined' && params.pujas.length > 0) {

                pujamaxima= params.pujas[0];
				if(auction_info.subasta.currency.symbol == '$' || auction_info.subasta.currency.symbol == 'US$'){
					$('#actual_max_bid').html(auction_info.subasta.currency.symbol + pujamaxima.formatted_imp_asigl1 );
				}else{
					$('#actual_max_bid').html(pujamaxima.formatted_imp_asigl1 + auction_info.subasta.currency.symbol);
				}

                $('#text_actual_no_bid').addClass('hidden');
                $('#text_actual_max_bid').removeClass('hidden');

				$('#bid_amount').val(params.importe_escalado_siguiente);
				$('#next_bid_JS').html(format_thousand(params.importe_escalado_siguiente));

                //colores de puja
                if (typeof auction_info.user != 'undefined' && pujamaxima.cod_licit == auction_info.user.cod_licit) {
					$('#tupuja').html(pujamaxima.formatted_imp_asigl1);
                    $('#cancelarPujaUser').removeClass('hidden');
                    $('#actual_max_bid').removeClass('other');
                    $('#actual_max_bid').addClass('mine');
                }else{
                    $('#cancelarPujaUser').addClass('hidden');
                    $('#actual_max_bid').addClass('other');
                }
                //recargar pujas
                auction_info.lote_actual.pujas = params.pujas;
                reloadPujasList();
            }



        } else {

            /* Si paramos la subasta volveremos a mostrar la cuenta atras hasta la siguiente reanudación*/
			if (params.status == 'stopped' || params.status == 'reload') {

				if(params.status == 'reload'){
					if (typeof auction_info.user == 'undefined' || !auction_info.user.is_gestor) {
						 location.reload(true);
					}
				}

                $(".tiempo").data('ini', new Date().getTime());
                $(".tiempo").data('countdown', params.reanudacion);
                if (typeof $(".tiempo").data('stop') != 'undefined' && $(".tiempo").data('stop') == 'stop') {
                    $(".tiempo").data('stop', "")
                    countdown_timer($(".tiempo"));
                }
                $('#clock, button.start').show();

                $('.started').addClass('hidden');
				$('body').addClass('tr_stop').removeClass('tr_progress').removeClass('tr_finished');

                if (typeof auction_info.user == "undefined" || (typeof auction_info.user != "undefined" && !auction_info.user.is_gestor)){
                    $('.tiempo_real')[0].style.position = "fixed";
                }

            }

            /* texto de estado de subasta*/
            $('#text_auc_status').html(messages.neutral[params.status]);
        }
        if (typeof params.status != 'undefined') {
            auction_info.subasta.status = params.status;
        }
    });
    /*
     |--------------------------------------------------------------------------
     | END Cambia el estado de la subasta
     |--------------------------------------------------------------------------
     */




    socket.on('start_count_down_response', function (params) {

        if (params.interrupt_cd_time)
        {
            if (typeof auction_info.user != 'undefined' && auction_info.user.is_gestor)
            {
                $('.change_end_lot[data-status="end"]').removeClass('hidden');
                $('.change_end_lot[data-status="cancel"]').addClass('hidden');
            }

            $('#count_down_msg').addClass('hidden');

        } else
        {
            if (params.cd_time <= 0)
            {

                if (typeof auction_info.user != 'undefined' && auction_info.user.is_gestor)
                {
                    $('.change_end_lot[data-status="end"]').removeClass('hidden');
                    $('.change_end_lot[data-status="cancel"]').addClass('hidden');
                }

                $('#count_down_msg').addClass('hidden');

            } else
            {

                if ($('#count_down_msg').hasClass('hidden'))
                {
                    $('#count_down_msg').removeClass('hidden');
                }

                $('.change_end_lot[data-status="end"]').addClass('hidden');
                $('.change_end_lot[data-status="cancel"]').removeClass('hidden');
                //quitamos todas las clases
                $('#count_down_msg p').removeClass();

                $('#count_down_msg p').addClass('count_' + params.cd_time);
                $('#count_down_msg p').html(params.cd_time);
                playAlert(['countdown']);
            }
        }

    });

    socket.on('end_lot_response', function (data) {
        if (auction_info.subasta.sub_tiempo_real != 'S') {
            return
        }

        /* playAlert(['end_lot']);*/
        /*borramso lso datos del usuario*/
        $('#tupuja').html('');
		$('#tuorden').html('');

        /*console.log("end_lot_response");*/
        auction_info.lote_anterior = data.lote_anterior;
        auction_info.lote_actual = data.lote_actual;
        auction_info.subasta.nextLotes = data.nextLotes;

        if (typeof data.lote_siguiente != 'undefined' || data.lote_siguiente == auction_info.lote_siguiente)
        {
            auction_info.lote_siguiente = data.lote_siguiente;
            /* TrSearchLote(parseInt(data.lote_siguiente.ref_asigl0), 'orden');*/
        } else
        {
            $('#lot_sig').hide();
        }

        if (data.jump_lot != 'undefined' && data.jump_lot == 1 && typeof auction_info.user != 'undefined') {
            displayAlert(1, messages.success.jump_lot);
        }

        //funcion customizada
        if (typeof actualize_currency === 'function') {
            actualize_currency(data.lote_actual);
        }
        /*console.log(data);*/
        /* Si la subasta ha finalizado..*/
        if (typeof data.subasta_finalizada != 'undefined' && data.subasta_finalizada == 1)
        {
            $('#clock').show();

			$('body').addClass('tr_finished').removeClass('tr_stop').removeClass('tr_progress');
            $('.tiempo').data('stop', "stop");
            $('.tiempo').html(messages.neutral.auction_end);
            $('.started').addClass('hidden');
            $('.img-responsive').css('width', 'auto');

            /* si aun no esta iniciada se verá la imagen en grande*/
            $('.colimagen').removeClass('col-lg-6');
            $('.colimagen').addClass('col-lg-12');

            $.magnificPopup.close();
            return;
        }
        //cojeremso como orden la puja maxima para que no esté a cero
		var tuorden = "0";
		//ocultamos el boton de borrar orden y solo lo mostramos si hay orden
		$("#cancelarOrdenUser").addClass("hidden");
        if (typeof auction_info.user != 'undefined' && typeof auction_info.user.cod_licit != 'undefined') {

            $.each(auction_info.lote_actual.pujas, function (key, value) {

                if (typeof value != 'undefined' && value.cod_licit == auction_info.user.cod_licit)
                {
                    if (tuorden == "0") {

                        tuorden = value.formatted_imp_asigl1;
                    }
                    $('#tupuja').html(value.formatted_imp_asigl1);
                }



            });
            $.each(auction_info.lote_actual.ordenes, function (key, value) {

                if (typeof value != 'undefined' && value.cod_licit == auction_info.user.cod_licit)
                {
					tuorden = value.himp_orlic_formatted;
					$("#cancelarOrdenUser").removeClass("hidden");
                }

            });

        }
		$('#tuorden').html(tuorden);


        if (typeof auction_info.lote_anterior != 'undefined') {
            /*Actualiza el buscador si influye con el cambio de lote.*/
            updateBuscador();

			/**Alert si el lote anterior se lo a quedado el ministerio**/
			if(typeof data.lote_anterior.max_puja != 'undefined' && typeof ministeryLicit != 'undefined' && data.lote_anterior.max_puja.cod_licit == ministeryLicit){
				displayAlert(2, messages.error.asign_to_ministery);
			}

            if (typeof auction_info.user != 'undefined' && typeof data.lote_anterior != undefined && data.lote_anterior.max_puja.cod_licit == auction_info.user.cod_licit && data.lote_anterior.cerrado_asigl0 == "S" && data.lote_anterior.max_puja.imp_asigl1 >= parseInt(data.lote_anterior.impres_asigl0) ) {
                /*añadimos la nueva adjudicacion al principio	*/
                auction_info.user.adjudicaciones.splice(0, 0, data.lote_anterior.max_puja);
                /*auction_info.user.adjudicaciones.push(data.lote_anterior.max_puja);*/
            } else if (typeof auction_info.user != 'undefined' && typeof data.lote_anterior != undefined && data.lote_anterior.max_puja.cod_licit != auction_info.user.cod_licit && data.lote_anterior.cerrado_asigl0 == "S") {
                var ref_adj_lot = data.lote_anterior.max_puja.ref_asigl1
                $.each(auction_info.user.adjudicaciones, function (key, value) {
                    if (typeof value != 'undefined' && value.ref_asigl1 == ref_adj_lot) {
                        auction_info.user.adjudicaciones.splice(key, 1);
                    }
                });
            }

            /*abre la opción a pujar.*/
            openBids();

            /*repinta la info de la pantalla.*/
            reloadMainLotInfo();
			playAlert(['favs']);
			/* funcion solo para admin por eso esta el if */
			if (typeof showInfoTrLot === 'function') {
				showInfoTrLot()
			}
        }
    });
    /*
     |--------------------------------------------------------------------------
     | END Finalizar lote
     |--------------------------------------------------------------------------
     */


    /*
     |--------------------------------------------------------------------------
     | Actualiza el objeto buscador
     |--------------------------------------------------------------------------
     */
    function updateBuscador()
    {
        /* Si tenemos lote anterior, de lo contrario la subasta ha terminado*/
        if (typeof auction_info.lote_anterior != 'undefined') {
            if (auction_info.buscador.ref_asigl0 == auction_info.lote_anterior.ref_asigl0) {
                auction_info.buscador = auction_info.lote_anterior;
                /*ponemos el siguiente para que avance*/
            } else if (auction_info.buscador.ref_asigl0 == auction_info.lote_actual.ref_asigl0) {
                auction_info.buscador = auction_info.lote_siguiente;
            } else if (auction_info.buscador.ref_asigl0 == auction_info.lote_siguiente.ref_asigl0) {
                auction_info.buscador = auction_info.lote_siguiente;
            }
        }
    }
    /*
     |--------------------------------------------------------------------------
     | END Actualiza el objeto buscador
     |--------------------------------------------------------------------------
     */


    /*
     |--------------------------------------------------------------------------
     | Repinta la main caja del lote
     |--------------------------------------------------------------------------
     */
    function reloadMainLotInfo() {

        var container = $('#main_lot_box');

        if (typeof auction_info.lote_actual != 'undefined' && typeof container != 'undefined' && container.length > 0) {
            $('.img-responsive', container).prop('src', 'data:image/jpg;base64,' + auction_info.lote_actual.imagen);

			$('#img-proyector').css('background-image', 'url(data:image/jpg;base64,' + auction_info.lote_actual.imagen);
			var actualref=auction_info.lote_actual.ref_asigl0;
			/* ponemos el bis, se ha eliminado que algunos lcientes empiecen por B por lo que todos empiezan por A */
			actualref=actualref.replace(".1","-A").replace(".2","-B").replace(".3","-C").replace(".4","-D").replace(".5","-E");

            $('#info_lot_actual').html(actualref);
            $('.info_lot_actual').html(actualref);

            $('#actual_titulo').html(auction_info.lote_actual.text_lang[auction_info.lang_code].titulo_hces1);
            $('#autor_JS').html(auction_info.lote_actual.autor);
            $('#actual_descripcion').html(auction_info.lote_actual.text_lang[auction_info.lang_code].desc_hces1);
            $('#actual_descripcion_mobile').html(auction_info.lote_actual.text_lang[auction_info.lang_code].desc_hces1);
            $('#actual_descweb').html(auction_info.lote_actual.text_lang[auction_info.lang_code].descweb_hces1);
            if (auction_info.lote_actual.pujas.length > 0) {
				if(auction_info.subasta.currency.symbol == '$' || auction_info.subasta.currency.symbol == 'US$'){
					$('#actual_max_bid').html(auction_info.subasta.currency.symbol + auction_info.lote_actual.formatted_actual_bid );
				}else{
					$('#actual_max_bid').html(auction_info.lote_actual.formatted_actual_bid + auction_info.subasta.currency.symbol);
				}

                $('#text_actual_no_bid').addClass('hidden');
                $('#text_actual_max_bid').removeClass('hidden');
            } else {
                $('#actual_max_bid').html('');
                $('#text_actual_max_bid').addClass('hidden');
                $('#text_actual_no_bid').removeClass('hidden');
            }
			$('#bid_amount').val(auction_info.lote_actual.importe_escalado_siguiente);
			$('#next_bid_JS').html(format_thousand(auction_info.lote_actual.importe_escalado_siguiente));
        }
        $('#imptas').html(auction_info.lote_actual.formatted_imptas_asigl0);
        $('#imptash').html(auction_info.lote_actual.formatted_imptash_asigl0);
        $('#precioSalida span').html(auction_info.lote_actual.formatted_impsalhces_asigl0);
		$('#precioReserva span').html(auction_info.lote_actual.impres_asigl0 ? auction_info.lote_actual.formatted_impres_asigl0 : '0');
		$('#precioCoste span').html(auction_info.lote_actual.pc_hces1);
		$('.add_next-bid #value-view').html(numeral(auction_info.lote_actual.importe_escalado_siguiente ?? 0).format('0,0' ) + " €");
        if (typeof cedente != 'undefined') {
            $('#inf_prop .inf_cod_prop').html(auction_info.lote_actual.prop_hces1);
            $('#inf_prop .inf_name_prop').html('');
            if (cedente[auction_info.lote_actual.prop_hces1] != 'undefined') {
                $('#inf_prop .inf_name_prop').html(cedente[auction_info.lote_actual.prop_hces1]);
            }
        }

        reloadPujasList();
        reloadOrderList();
        reloadSigAntLotInfo();
        reloadAdjudicaciones();
		reloadBuscador(auction_info.buscador, 'buscador');

		if (typeof reloadAdjudicacionesCustom === "function") {
			reloadAdjudicacionesCustom();
		}

		if (typeof reloadCarrousel === "function") {
			reloadCarrousel();
		}

        if (typeof auction_info.user != 'undefined' && typeof auction_info.lote_actual.max_puja != 'undefined' && typeof auction_info.lote_actual.max_puja.cod_licit != 'undefined' && auction_info.lote_actual.max_puja.cod_licit == auction_info.user.cod_licit) {


            $('#actual_max_bid').addClass('mine');
            $('#actual_max_bid').removeClass('other');

            $('#cancelarPujaUser').removeClass('hidden');

            /*$('#tupuja').html(auction_info.lote_actual.max_puja);*/

        } else {
            $('#actual_max_bid').addClass('other');
            $('#actual_max_bid').removeClass('mine');

            /* Si es gestor nunca se oculta el cancelar puja*/
            if (typeof auction_info.user != 'undefined' && auction_info.user.is_gestor) {
                $('#cancelarPujaUser').removeClass('hidden');
            } else {
                $('#cancelarPujaUser').addClass('hidden');
            }

        }

    }
    /*
     |--------------------------------------------------------------------------
     | END Repinta la main caja del lote
     |--------------------------------------------------------------------------
     */


    /*
     |--------------------------------------------------------------------------
     | Repinta la caja de adjudicaciones
     |--------------------------------------------------------------------------
     */

    function reloadAdjudicaciones()
    {
        if (typeof auction_info.user != 'undefined') {
            var model = $('#type_adj_model').clone();
            var container = $('.aside.adjudicaciones > div');

            if (typeof auction_info.user != 'undefined' && typeof auction_info.user.adjudicaciones != 'undefined' && typeof container != 'undefined' && container.length > 0) {

                $('.aside.adjudicaciones .adjudicaciones_model:not(#type_adj_model)').remove();
                reflots = []
				//solo mostrar una vez la adjudicación, en caso de que se haya duplicado
				$.each(auction_info.user.adjudicaciones, function (key, value) {
                    if (reflots.indexOf(value.ref_asigl1) == -1) {
                        reflots.push(value.ref_asigl1);

                        /*if (key >= auction_info.subasta.max_bids_shown){
                         return false;
                         }*/

                        var $this = model.clone().removeClass('hidden').removeAttr('id');
						var adjRef = value.ref_asigl1;
						adjRef=adjRef.replace(".1","-A").replace(".2","-B").replace(".3","-C").replace(".4","-D").replace(".5","-E");


                        $('.adj_ref span', $this).html(adjRef);
                        $('.adj_imp', $this).html(value.imp_asigl1);

						container.append($this);
                    }
				});
            }
        }
    }
    /*
     |--------------------------------------------------------------------------
     | END Repinta la caja de adjudicaciones
     |--------------------------------------------------------------------------
     */


    /*
     |--------------------------------------------------------------------------
     | Repinta las cajas de los lotes siguiente y anterior
     |--------------------------------------------------------------------------
     */
    function reloadSigAntLotInfo() {
        var container = $('#ant_sig_boxes');

        if (typeof container != 'undefined' && container.length > 0) {

            if (typeof auction_info.lote_anterior != 'undefined') {
                var lot_ant = $('#lot_ant', container);

                if (lot_ant.length > 0) {
                    $('h2 span', lot_ant).html(auction_info.lote_anterior.ref_asigl0);
                    $('.ant_title', lot_ant).html(auction_info.lote_anterior.titulo_hces1);
                    $('.ant_price span', lot_ant).html(auction_info.lote_anterior.formatted_impsalhces_asigl0);
                }
            }

            if (typeof auction_info.lote_siguiente != 'undefined') {
                var lot_sig = $('#lot_sig', container);

                if (lot_sig.length > 0) {
                    $('h2 span', lot_sig).html(auction_info.lote_siguiente.ref_asigl0);
                    $('.sig_title', lot_sig).html(auction_info.lote_siguiente.titulo_hces1);
                    $('.sig_price span', lot_sig).html(auction_info.lote_siguiente.formatted_impsalhces_asigl0);
                }
            }
        }
    }

    /* Funcion que actualiza los tiempos de los mensajes del chat*/
    setInterval(function () {
        $(".timeago").timeago();
    }, 60000);

    /* Alert de Mensajes predefinidos*/
    $('#msg_predef').on('click', function () {
        $.magnificPopup.open({items: {src: '#mensajes_predefinidos'}, type: 'inline'}, 0);
    });




    /* Autocompletar de Ordenes*/
    $('#order_amount').autoComplete({
        minChars: 1,
        cache: false,
        source: function (term, response) {
            try {
                xhr.abort();
            } catch (e) {
            }
            $.getJSON('/api-ajax/calculate_bids/' + auction_info.buscador.actual_bid + '/' + term, function (data) {
                var matches = [];
                for (i = 0; i < data.length; i++) {
                    matches.push(data[i].toString());
                }
                response(matches);
            });
        }
    });
    /*
     |--------------------------------------------------------------------------
     | END Autocompletar de pujas
     |--------------------------------------------------------------------------
     */







    /*
     |--------------------------------------------------------------------------
     | Ordenes por importe en el buscador de lotes
     |--------------------------------------------------------------------------
     */
    $('.lot-order_importe').on('click', function (e) {
        e.stopPropagation();
        $.magnificPopup.close();

        auction_info.subasta.click_from = $(this).data().from;

        var action = 'add';
        var dfrom = $(this).data().from;
        var ref = '';

        if (dfrom == 'buscador') {
            ref = auction_info.buscador.ref_asigl0;
        } else if (dfrom == 'modal') {
            ref = auction_info.modal_item.ref_asigl0;
        }

        updateFavs(action, ref);
        reloadBuscadorFavs();

        $.magnificPopup.open({items: {src: '#modalOrdenImporte'}, type: 'inline'}, 0);

    });
    /*
     |--------------------------------------------------------------------------
     | fin de ordenes por importe
     |--------------------------------------------------------------------------
     */




    /*
     |--------------------------------------------------------------------------
     | Modal información de lotes.
     |--------------------------------------------------------------------------
     */
    $('#lot_ant').on('click', function () {
        auction_info.modal_item = auction_info.lote_anterior;
        var lot = mountLot();
        modalInfoLots(lot);
    });

    $('#lot_sig').on('click', function () {
        auction_info.modal_item = auction_info.lote_siguiente;
        var lot = mountLot();
        modalInfoLots(lot);
    });

    $('#loteAjax').on('click', 'h4', function () {
        auction_info.modal_item = auction_info.buscador;
        var lot = mountLot();
        modalInfoLots(lot);
    });
    //por si no usa título si no descweb
    $('#loteAjax ').on('click', '#descweb', function () {
        auction_info.modal_item = auction_info.buscador;
        var lot = mountLot();
        modalInfoLots(lot);
    });

    $('#favs_box').on('click', '.bordered', function () {
        var key = $(this).data().key;

        if (typeof key == 'undefined' || typeof key == '') {
            return;
        }

        auction_info.modal_item = auction_info.user.favorites[key];
        var lot = mountLot();
        modalInfoLots(lot);
    });

    function mountLot() {
        var lot = {};


		if (typeof auction_info.modal_item.text_lang == 'undefined'){
			lot.title = auction_info.modal_item.titulo_hces1;
			lot.desc_hces1 = auction_info.modal_item.desc_hces1;
			lot.descweb_hces1 = auction_info.modal_item.descweb_hces1;
		}else{

			lot.title = auction_info.modal_item.text_lang[auction_info.lang_code].titulo_hces1;
			lot.desc_hces1 = auction_info.modal_item.text_lang[auction_info.lang_code].desc_hces1;
			lot.descweb_hces1 = auction_info.modal_item.text_lang[auction_info.lang_code].descweb_hces1;
		}
        lot.ref = auction_info.modal_item.ref_asigl0;

        lot.himp_csub = auction_info.modal_item.himp_csub;

        lot.imp = auction_info.modal_item.formatted_impsalhces_asigl0;
        lot.imagen = auction_info.modal_item.imagen;

        reloadBuscador(auction_info.buscador, 'modal');

        return lot;
    }

    function modalInfoLots(lot) {

        var model = $('#infoLot');

        if (model.length <= 0 || typeof lot == 'undefined' || !lot) {
            return;
        }

        /*Es pot saber el modal obert ja que se li afegeix la clase lot_ + ref*/
        /*Servirà per actualitzar en temps real si hi ha canvis al lot.*/
        model.attr('rel', lot.ref);

        $('.i_lot', model).html(lot.ref);
        $('.i_title', model).html(lot.title);
        $('.i_descweb', model).html(lot.descweb_hces1);
        $('.i_desc', model).html(lot.desc_hces1);
        $('.i_imp', model).html(lot.imp);
        $('.img-responsive', model).attr('src', '/img/load/lote_medium/' + lot.imagen);
        if (typeof lot.himp_csub != 'undefined' && lot.himp_csub != '0') {
			if(auction_info.subasta.currency.symbol == '$' || auction_info.subasta.currency.symbol == 'US$'){
				$('.imp_adj', model).html(auction_info.subasta.currency.symbol + lot.himp_csub );
			}else{
				$('.imp_adj', model).html(lot.himp_csub +  auction_info.subasta.currency.symbol);
			}

        } else {
            $('.imp_adj', model).html('');
        }

        reloadModalFavs();
        $.magnificPopup.open({items: {src: '#infoLot'}, type: 'inline'}, 0);
    }
    /*
     |--------------------------------------------------------------------------
     | END Modal información de lotes.
     |--------------------------------------------------------------------------
     */


    /*
     |--------------------------------------------------------------------------
     | Favoritos
     |--------------------------------------------------------------------------
     */
    /*añadir/borrar lotes*/
    $('body').on('change', '.add_to_fav', function () {

        if (typeof auction_info.user == 'undefined') {
            return;
        }

        try {
            xhr.abort();
        } catch (e) {
        }

        var action = this.checked ? 'add' : 'remove';
        var dfrom = $(this).data().from;
        var ref = '';

        if (dfrom == 'buscador') {
            ref = auction_info.buscador.ref_asigl0;
        } else if (dfrom == 'modal') {
            ref = auction_info.modal_item.ref_asigl0;
        }

        updateFavs(action, ref);
    });

    function updateFavs(action, ref) {
        if (typeof auction_info.user == 'undefined') {
            return;
        }
        var already_did = false;

        $.each(auction_info.user.favorites, function (key, item) {
            if (typeof item == 'undefined') {
                return true;
            }

            if (action == 'add' && parseInt(item.ref_asigl0) == parseInt(ref)) {
                already_did = true;
                return false;
            }

        });

        if (already_did) {
            return;
        }

        /*mirar si no está añadido ya a favoritos antes de enviar el ajax.*/
        $.getJSON('/api-ajax/favorites/' + action, {cod_sub: auction_info.subasta.cod_sub, cod_licit: auction_info.user.cod_licit, ref: ref}, function (data) {

            /*console.log(data);*/
            if (data.status == 'error') {
                displayAlert(0, messages.error[data.msg]);
                return;
            }

            displayAlert(1, messages.success[data.msg]);

            if (action == 'add' && typeof data.data != 'undefined') {
                auction_info.user.favorites.push(data.data);
            } else {

                if (typeof auction_info.user.favorites == 'undefined' || ref == '') {
                    return;
                }

                $.each(auction_info.user.favorites, function (key, item) {
                    if (typeof item == 'undefined') {
                        return true;
                    }

                    if (parseInt(item.ref_asigl0) == parseInt(ref)) {
                        delete(auction_info.user.favorites[key]);
                    }
                });
            }

            reloadBoxFavs();

        });
    }

    /*Recalcular los favoritos.*/
    function reloadBuscadorFavs() {
        if (typeof auction_info.user == 'undefined') {
            return;
        }

        if (typeof auction_info.user.favorites == 'undefined' || auction_info.user.favorites == '') {
            $('#loteAjax .add_to_fav').prop("checked", false);
            return;
        }

        var found = false;
        $.each(auction_info.user.favorites, function (key, item) {

            if (typeof item == 'undefined') {
                return true;
            }

            if (parseInt(item.ref_asigl0) == parseInt(auction_info.buscador.ref_asigl0)) {
                $('#loteAjax .add_to_fav').prop("checked", true);
                found = true;
            }
        });

        if (!found) {
            $('#loteAjax .add_to_fav').prop("checked", false);
        }
    }

    function reloadModalFavs() {
        if (typeof auction_info.user == 'undefined') {
            return;
        }

        if (typeof auction_info.user.favorites == 'undefined' || auction_info.user.favorites == '') {
            $('#infoLot .add_to_fav').prop("checked", false);
            return;
        }

        var found = false;
        $.each(auction_info.user.favorites, function (key, item) {

            if (typeof item == 'undefined') {
                return true;
            }

            if (parseInt(item.ref_asigl0) == parseInt(auction_info.modal_item.ref_asigl0)) {
                $('#infoLot .add_to_fav').prop("checked", true);
                found = true;
            }
        });

        if (!found) {
            $('#infoLot .add_to_fav').prop("checked", false);
        }
    }

    function reloadBoxFavs() {
        if (typeof auction_info.user == 'undefined') {
            return;
        }

        var model = $('#model_fav_box').clone();
        var container = $('#favs_box .row');
        var found = false;

        container.html('');

        if (typeof auction_info.user.favorites == 'undefined' || auction_info.user.favorites == '') {
            return;
        }

        $.each(auction_info.user.favorites, function (key, item) {

            if (typeof item == 'undefined') {
                return true;
            }

            var $this = model.clone().removeClass('hidden').removeAttr('id');

            $('.bordered', $this).data('key', key);
            $('.lot', $this).html(item.ref_asigl0);
            /*$('.img', $this).attr('src', item.imagen);*/
            $('.img-responsive', $this).attr('src', item.imagen);
            /*console.log($this);*/
            container.append($this);
        });

    }
    /*
     |--------------------------------------------------------------------------
     | END Favoritos.
     |--------------------------------------------------------------------------
     */

    socket.on('pausar_lote_response', function (data) {

        if (data.status == 'error' && typeof auction_info.user != 'undefined' && auction_info.user.is_gestor) {
            displayAlert(0, messages.error[data.msg_1]);

        } else if (data.status == 'success') {

            if (typeof auction_info.user != 'undefined' && auction_info.user.is_gestor) {
                displayAlert(1, messages.success[data.msg]);

                /* Cambiamos el texto del boton y demas atributos para convertir el boton en reanudar*/
                var loteBuscador = $('.pausarLote' + data.data.ref);

                /* El nuevo orden coincide con el del siguiente, con lo que debemos actualizar el objeto y el html*/
                if (data.data.orden == auction_info.lote_siguiente.orden_hces1) {
                    /*console.log("El nuevo orden coincide con el siguiente");*/
                    auction_info.lote_siguiente = auction_info.buscador;
                    reloadSigAntLotInfo();
                }

                /* Añadimos el lote del buscador actual al array de lotes pausados*/
                if (data.data.cerrado_asigl0 == 'P') {
                    auction_info.lotes_pausados.push(auction_info.buscador);
                    /*loteBuscador.html(messages.neutral.resume);*/
                    $("#lot-pausar").html(messages.neutral.resume);

                    $('.pausarLote').attr('onclick', "$('.reanudarLote" + auction_info.buscador.ref_asigl0 + "').click();");
                }

                pausados = auction_info.lotes_pausados;

                if (data.data.cerrado_asigl0 == 'N') {
                    /*loteBuscador.html(messages.neutral.pause_lot);*/
                    $("#lot-pausar").html(messages.neutral.pause_lot);

                    $.each(auction_info.lotes_pausados, function (index, value) {

                        /*
                         console.log("INDEX: "+index);
                         console.log(value);
                         console.log(auction_info.lotes_pausados);
                         */

                        if (typeof value != 'undefined' && value.ref_asigl0 == data.data.ref) {
                            /*console.log('refbuscaaa: '+value.ref_asigl0+" index:"+index);*/

                            if (index == 0) {
                                pausados.shift();
                            } else {
                                pausados.splice(index, 1);
                            }
                        }
                    });
                }

                lotesPausadosRefresh();

            }
        }

    });


    socket.on('reanudar_lote_response', function (data) {
        /*console.log("response reanudar_lote_response");*/

        if (data.status == 'error' && typeof auction_info.user != 'undefined' && auction_info.user.is_gestor) {
            displayAlert(0, messages.error[data.msg_1]);

        } else if (data.status == 'success') {

            $("#lot-pausar").html(messages.neutral.pause_lot);

            if (typeof auction_info.user != 'undefined' && auction_info.user.is_gestor) {
                displayAlert(1, messages.success[data.msg]);
            }
        }

	});

	socket.on('fairwarning_response', function (data) {
		$("#fairwarning").addClass("parpadea");
		setTimeout(function() {
			$("#fairwarning").removeClass("parpadea");
		},3000);

    });

    /* Refrescamos la lista de lotes pausados*/
    function lotesPausadosRefresh()
    {
        /* vaciamos el contenido inicial*/
        $('#reanudarList').html("");

        if (typeof auction_info.lotes_pausados != 'undefined' && auction_info.lotes_pausados.length == 0) {
            $('#reanudarList').html(messages.neutral.no_stopped_lots);
        }

        /* Recorremos el array de lotes pausados y los insertamos en el div de lotes pausados*/
        $.each(auction_info.lotes_pausados, function (key, item) {

            var modeloReanudar = $('#reanudarListModel');
            var $this = modeloReanudar.clone().removeClass('hidden').removeAttr('id');

            $('.titulo', $this).append(item.ref_asigl0 + ' - ');
            $('.titulo', $this).append(item.titulo_hces1);
            $('.boton .reanudarLote', $this).addClass('reanudarLote' + item.ref_asigl0);
            $('.boton .reanudarLote', $this).attr('data-id', item.ref_asigl0);
            $('.boton .reanudarLote', $this).attr('data-orden', item.orden_hces1);
            $('.boton .reanudarLote', $this).html(messages.neutral.resume);

            $('#reanudarList').append($this);

        });
    }

    lotesPausadosRefresh();



    $("#ficha .content_box img").imageLens({lensSize: 100, borderSize: 2, borderColor: "#000"});


    $('#cancelarPujaUser').click(function () {
        $.magnificPopup.open({items: {src: '#modalCancelarPujaUser'}, type: 'inline'}, 0);
    });

    window.cancelar_puja_user = function ()
    {
        var cod_licit = auction_info.user.cod_licit;
        var cod_sub = auction_info.subasta.cod_sub;
        var ref = auction_info.lote_actual.ref_asigl0;
        var imp_salida = auction_info.lote_actual.impsalhces_asigl0;
        var string_hash = cod_licit + " " + cod_sub + " " + ref;
        var hash = CryptoJS.HmacSHA256(string_hash, auction_info.user.tk).toString(CryptoJS.enc.Hex);


        socket.emit('cancel_bid', {cod_licit: cod_licit, cod_sub: cod_sub, ref: ref, imp_salida: imp_salida, hash: hash});

	};

	$('#cancelarOrdenUser').click(function () {
        $.magnificPopup.open({items: {src: '#modalCancelarOrdenUser'}, type: 'inline'}, 0);
	});

	window.cancelar_orden_user = function ()
    {
        var cod_licit = auction_info.user.cod_licit;
        var cod_sub = auction_info.subasta.cod_sub;
        var ref = auction_info.lote_actual.ref_asigl0;
        var string_hash = cod_licit + " " + cod_sub + " " + ref;
        var hash = CryptoJS.HmacSHA256(string_hash, auction_info.user.tk).toString(CryptoJS.enc.Hex);


        socket.emit('cancel_order_user', {cod_licit: cod_licit, cod_sub: cod_sub, ref: ref, hash: hash});

	};

    $('.confirm_puja').on('click', function (e) {
        confirm_puja();
    });

	$('.confirm_puja_multiple').on('click', function (e) {
		if(!checkSumRatiosIsValid()){
			showMultipleBidderError();
			return false;
		};
        confirm_puja({hasMultipleBidders: true});
    });

    function confirm_puja(options = {}) {

        $.magnificPopup.close();

		const defaultOptions = {
			hasMultipleBidders: false,
			...options
		}

        var type_bid = 'W';
        var can_do = null;
        var imp_sal = auction_info.lote_actual.impsalhces_asigl0;
        var amount = $("#bid_amount").val();
        var ref = auction_info.lote_actual.ref_asigl0;
        var cod_licit = auction_info.user.cod_licit;

        if (typeof auction_info.user != 'undefined' && auction_info.user.is_gestor && $('#ges_cod_licit').length > 0 && $('#ges_cod_licit').val() != "") {
            cod_licit = $('#ges_cod_licit').val();
            $('#ges_cod_licit').val('');
        }

		const bidders = defaultOptions.hasMultipleBidders ? generateMultipleBiddersItem() : [];

        var params = {
			'cod_licit': cod_licit,
			'cod_sub': auction_info.subasta.cod_sub,
			'ref': ref,
			'url': routing.action_url,
			'imp': amount,
			'type_bid': type_bid,
			'impsal': imp_sal,
			'can_do': can_do,
			'cod_original_licit': auction_info.user.cod_licit,
			'tipo_puja_gestor': $("#tipo_puja_gestor").val(),
			hasMultipleBidders: defaultOptions.hasMultipleBidders,
			bidders,
		};

        var string_hash = params.cod_licit + " " + params.cod_sub + " " + params.ref + " " + params.imp;
        params.hash = CryptoJS.HmacSHA256(string_hash, auction_info.user.tk).toString(CryptoJS.enc.Hex);
        socket.emit('action', params);
    }


});

function format_thousand(number){
	num = number.toString();
	return num.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
}


function closeBids() {
    $('.add_bid').addClass('loading');
    $('.txt_esperando_sala').addClass('hidden');
    $('.txt_loading').removeClass('hidden');
    $.magnificPopup.open({items: {src: '#modalCloseBids'}, type: 'inline', showCloseBtn: false, enableEscapeKey: false, modal: true, closeOnBgClick: false}, 0);
}

function openBids() {
    $('.add_bid').removeClass('loading');
    $.magnificPopup.close();
}

function checkSumRatiosIsValid() {
	const sumRatios = [...document.querySelectorAll('.bidder-wrap')].reduce((previousValue, currentValue) => {
		return previousValue + parseInt(currentValue.querySelector('[name="ratio"]').value);
	}, 0);

	return sumRatios === 100;
}

function showMultipleBidderError() {
	document.getElementById('multipleBidderError').classList.remove('hidden');
}

function generateMultipleBiddersItem() {
	const bidders = [];
	document.querySelectorAll('.bidder-wrap').forEach((element) => {

		const value = element.querySelector('[name="ratio"]').value;
		if(isNaN(parseInt(value) || value == 0)){
			return;
		}

		const bidder = {
			name: element.querySelector('[name="name"]').value,
			surname: element.querySelector('[name="last-name"]').value,
			ratio: element.querySelector('[name="ratio"]').value
		};
		bidders.push(bidder);
	});
	return bidders;
}
