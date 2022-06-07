var port= 22345 ;// soler 2125, local-auctions 22345; demo 2345
var hostname = 'www.newsubastas.test/';  //'demoauction.label-grup.com';
//algunas rutas necesitan definir la url entero
var large_hostname= 'http://www.newsubastas.test/';

var io = require('socket.io')(port, {
	cors: {
		origin: 'http://www.newsubastas.test'
	}
});//29345
var request = require("request");
//var auction_status = []; //Se guarda el estado de cada subasta: posible values = 'stopped', 'in_progress'.
var interrupt_cd_time = []; //Se guarda el valor para interrumpir la cuenta atrás de cada subasta.
var cd_interval = []; //Se guarda el intervalo para saber si ya se ha iniciado la cuenta atrás en cada subasta.
var proceso_puja_finalizado = [];
var allClients = new Object();

io.on('connection', function(socket) {



        //DESCONTAR USUARIOS CONECTADOS
        socket.on('disconnect', function() {

            if(typeof socket.handshake!= 'undefined' && typeof socket.handshake.address != 'undefined'){
                for (var cod_sub_array in allClients){
                    i=allClients[cod_sub_array].indexOf(socket.handshake.address);
                    if(i !== -1){
                        allClients[cod_sub_array].splice(i,1);
                        var count = [];
                            Object.keys(allClients[cod_sub_array]).forEach(function(key) {
                            i=count.indexOf(allClients[cod_sub_array][key]);
                            if(i === -1){
                                count.push(allClients[cod_sub_array][key]);
                            }

                        });
                        //Actualizamos  restamos 2 para que nocuente al admin en tiempo real
                        io.sockets.in(cod_sub_array).emit('count_clients_response',  count.length-1);
                    }
                }
            }

         });

	//Crea las salas
	socket.on('room', function(params)
	{


    	socket.join(params.cod_sub);

            //CONTADOR DE CLIENTES
            if (typeof allClients[params.cod_sub] === 'undefined') {
                allClients[params.cod_sub] =  [];//el admin realiza dos conexiones
            }
            if(typeof socket.handshake!= 'undefined' && typeof socket.handshake.address != 'undefined'){
                allClients[params.cod_sub].push(socket.handshake.address);
                var count = [];
                Object.keys(allClients[params.cod_sub]).forEach(function(key) {
                    i=count.indexOf(allClients[params.cod_sub][key]);
                    if(i === -1){
                        count.push(allClients[params.cod_sub][key]);
                    }

                });

                //Actualizamos, restamos 2 para que nocuente al admin en tiempo real
                io.sockets.in(params.cod_sub).emit('count_clients_response', count.length-1);
            }
            //FIN CONTADOR CLIENTES



    	if (typeof interrupt_cd_time[params.cod_sub] == 'undefined'){
    		interrupt_cd_time[params.cod_sub] = false;
    	}

    	if (typeof cd_interval[params.cod_sub] == 'undefined'){
    		cd_interval[params.cod_sub] = false;
    	}

    	if (typeof proceso_puja_finalizado[params.cod_sub] == 'undefined'){
    		proceso_puja_finalizado[params.cod_sub] = true;
    	}

    	//Envia respuesta a 1 usuario.
        //io.sockets.connected['/#'+params.id].emit('auction_status_response', auction_status[params.cod_sub]);
    });

	socket.on('chat', function(params)
	{
	    var data = [];

    	request({
		    url: large_hostname +  '/api/chat',
		    json: true,
		    method: 'POST',
		    //parámetros
		    qs: params
		}, function (error, response, body) {

		    if (!error && response.statusCode === 200) {
		    	$res = body;

		    }else{
		    	$res = 'error';
		    }

		    io.sockets.in(params.cod_sub).emit('chat_response', $res);

		});

	});

	socket.on('delete_msg', function(params)
    {
	    var data = [];

    	request({
		    url: large_hostname + '/api/chat/delete',
		    json: true,
		    method: 'POST',
		    //parámetros
		    qs: params
		}, function (error, response, body) {

		    if (!error && response.statusCode === 200) {
		    	$res = body;
		    }else{
		    	$res = 'error';
		    }

		    io.sockets.in(params.cod_sub).emit('delete_msg_response', $res);

		});

	});

	// Cancelar una puja
	socket.on('cancel_bid', function(params)
    {
	    var data = [];

    	request({
		    url: large_hostname + '/api/cancel_bid',
		    json: true,
		    method: 'POST',
		    //parámetros
		    qs: {'params': params}
		}, function (error, response, body) {

		    if (!error && response.statusCode === 200) {
		    	$res = body;
		    }else{
		    	$res = 'error';
		    }

		    io.sockets.in(params.cod_sub).emit('cancel_bid_response', $res);

		});

	});

	// Usuario Cancela orden
	socket.on('cancel_order_user', function(params)
    {

	    var data = [];

    	request({
		    url: large_hostname + '/api/cancelar_orden_user',
		    json: true,
		    method: 'POST',
		    //parámetros
		    qs: {'params': params}
		}, function (error, response, body) {

		    if (!error && response.statusCode === 200) {
		    	$res = body;
		    }else{
		    	$res = 'error';
		    }

		    io.sockets.in(params.cod_sub).emit('cancel_order_response', $res);

		});

	});

        // Cancelar una orden
	socket.on('cancel_order', function(params)
        {
	    var data = [];

    	request({
		    url: large_hostname + '/api/cancel_order',
		    json: true,
		    method: 'POST',
		    //parámetros
		    qs: {'params': params}
		}, function (error, response, body) {

		    if (!error && response.statusCode === 200) {
		    	$res = body;
		    }else{
		    	$res = 'error';
		    }

		    io.sockets.in(params.cod_sub).emit('cancel_order_response', $res);

		});

	});

	//Pujas y órdenes de licitación
    socket.on('action', function(params)
    {

		var peticionDate = new Date();
		var dateFormat = `${peticionDate.getHours()}:${peticionDate.getMinutes()}:${peticionDate.getSeconds()}:${peticionDate.getMilliseconds()}`;
		console.log("action lot", params.ref, "licit:", params.cod_licit, "imp:", params.imp , "date:",  dateFormat);


	    var data = [];
    	proceso_puja_finalizado[params.cod_sub] = false;
	    /*PARARCUENTA ATRAS para la cuenta atrás de finalizar lote.

            if (typeof params.can_do == 'undefined' || params.can_do != 'orders') {
                interrupt_cd_time[params.cod_sub] = true;
            }
            FIN PARARCUENTA ATRAS */
    	request({
		    url: large_hostname +  '/api/action/subasta'+ '-' + params.cod_sub,
		    json: true,
		    method: 'POST',
		    //parámetros
		    qs: {'params': params}
		}, function (error, response, body) {
			//console.log(response);
		    if (!error && response.statusCode === 200) {
		    	$res = body;

                       /* PARARCUENTA ATRAS nuevo código para no parar cuenta atras */
                        if (typeof params.can_do == 'undefined' || params.can_do != 'orders') {
                            if( typeof $res.no_interrupt_cd_time != 'undefined' &&  $res.no_interrupt_cd_time == 'true'){
                               console.log("no interrumpir") ;//si ha habido un error no debemos parar la cuenta atras
                            }else{
                                 interrupt_cd_time[params.cod_sub] = true;
                                  console.log("Interrumpir cuenta atras") ;
                            }
                        }else{
                             console.log("Orden no interrumpir") ;
                        }
                        /* FIN PARARCUENTA ATRAS */
		    }else{
		    	$res = 'error';
		    }
			var respuestaDate = new Date();
			tiempoRespuesta = respuestaDate - peticionDate;
			console.log("response action lot", params.ref, "licit:", params.cod_licit, "imp:", params.imp , "time:", tiempoRespuesta, " ms");
		    proceso_puja_finalizado[params.cod_sub] = true;
		    io.sockets.in(params.cod_sub).emit('action_response', $res);

		});

	});

	// Pausar Lote
    socket.on('pausar_lote', function(params)
    {
	    var data = [];

    	request({
		    url: large_hostname + '/api/pause_lot',
		    json: true,
		    method: 'POST',
		    //parámetros
		    qs: {'params': params}
		}, function (error, response, body) {

		    if (!error && response.statusCode === 200) {
		    	$res = body;
		    }else{
		    	$res = 'error';
		    }


			console.log($res);

		    io.sockets.in(params.cod_sub).emit('pausar_lote_response', $res);

		});

	});

    //Cambia el estado de la subasta. (se puede parar, reanudar, etc.)
	socket.on('auction_status', function(params)
	{
		if (typeof params.status != 'undefined' && params.status != '') {

	    	request({
			    url: large_hostname + '/api/status/subasta',
			    json: true,
			    method: 'POST',
			    //parámetros
			    qs: {'params': params}
			}, function (error, response, body) {

			    if (!error && response.statusCode === 200) {
			    	$res = body;
			    }else{
			    	$res = 'error';
			    }

			    io.sockets.in(params.cod_sub).emit('auction_status_response', $res);

			});

			//auction_status[params.cod_sub] = params.status;
    		//io.sockets.in(params.cod_sub).emit('auction_status_response', auction_status[params.cod_sub]);
		}

    });

	//Inicia la cuenta atrás para pasar de lote.
    socket.on('start_count_down', function(params)
	{
		var cd_frequency = 1000; // miliseconds

		interrupt_cd_time[params.cod_sub] = false;

		if (typeof params.cd_time != 'undefined' && params.cd_time != ''){

			if(typeof cd_interval[params.cod_sub] == 'undefined' || !cd_interval[params.cod_sub]){
				cd_interval[params.cod_sub] = setInterval( function(){

					if(params.cd_time == -1 || interrupt_cd_time[params.cod_sub]){
					 	clearInterval(cd_interval[params.cod_sub]);
					 	cd_interval[params.cod_sub] = false;

					 	if (params.cd_time == -1 && !interrupt_cd_time[params.cod_sub] && proceso_puja_finalizado[params.cod_sub]){
                                                        io.sockets.in(params.cod_sub).emit('local_end_lot',{cod_licit:params.cod_licit});
					 	  /* PARARCUENTA si hemos llegado a -1 pero han interrumpido el cd time debemos volver a activar pujas, ya que se bloquearon en el segundo 0	*/
					 	}else if (params.cd_time == -1 && interrupt_cd_time[params.cod_sub] ){
							io.sockets.in(params.cod_sub).emit('openBids');
                                                  /* Si hay un proceso de puja que aun no ha finalizado volvemos a abrir las pujas y marcamos como si se interrumpiera para que no se bloquee la subasta*/
						}else if (!proceso_puja_finalizado[params.cod_sub]){
                                                    io.sockets.in(params.cod_sub).emit('openBids');
                                                    interrupt_cd_time[params.cod_sub]=true;
                                                }
                                                 /* FIN PARARCUENTA ATRAS */
					}else if(params.cd_time == 0){
                                              io.sockets.in(params.cod_sub).emit('closeBidsEndLot',{cod_licit:params.cod_licit});
                                        }

					io.sockets.in(params.cod_sub).emit('start_count_down_response', { cd_time: params.cd_time, interrupt_cd_time: interrupt_cd_time[params.cod_sub] } );

					params.cd_time--;
				}, cd_frequency );
			}

		}

    });
     socket.on('server_end_lot', function(params)
	{
		var peticionDate = new Date();
		var dateFormat = `${peticionDate.getHours()}:${peticionDate.getMinutes()}:${peticionDate.getSeconds()}:${peticionDate.getMilliseconds()}`;
		console.log("endlot lot", params.lot, "date:",  dateFormat);


            request({
                url: large_hostname + '/api/end_lot'+ '-' +params.cod_sub,
                json: true,
                method: 'POST',
                //parámetros
                qs: {'params': params}
            }, function (error, response, body) {

                if (!error && response.statusCode === 200) {
                    $res = body;
                }else{
                    $res = 'error';
                }


			var respuestaDate = new Date();
			tiempoRespuesta = respuestaDate - peticionDate;
			console.log("response endlot lot", params.lot, tiempoRespuesta, " ms");
            io.sockets.in(params.cod_sub).emit('end_lot_response', $res);
            return;

            });
        });


    //Para la cuenta atrás para pasar de lote.
    socket.on('stop_count_down', function(params)
	{
		interrupt_cd_time[params.cod_sub] = true;

	});
    socket.on('open_bids', function(params)
	{
            console.log("open_bids");
            io.sockets.in(params.cod_sub).emit('openBids');


	});



	socket.on('fairwarning', function(params)
	{
        io.sockets.in(params.cod_sub).emit('fairwarning_response');
	});


});
