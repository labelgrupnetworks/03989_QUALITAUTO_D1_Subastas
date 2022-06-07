var port= 29345;
var hostname= 'www.tauleryfau.com';
//algunas rutas necesitan definir la url entero
var large_hostname= 'https://www.tauleryfau.com';



var fs = require( 'fs' );
var querystring = require('querystring');
var express = require('express');  
var app = express();  

//para evitar errore con autocertificado
process.env.NODE_TLS_REJECT_UNAUTHORIZED = "0";

var https   = require('https');
var server = https.createServer({
    key: fs.readFileSync('/etc/ssl/private/www_tauleryfau_com.key'),
    cert: fs.readFileSync('/etc/ssl/certs/www_tauleryfau_com.crt')
},app);
server.listen(port);

var io = require('socket.io').listen(server);


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
        
	/*
	socket.on('chat', function(params)
	{  
	    var data = [];
            
            var postData = JSON.stringify(params);                     
            const options = {
                hostname: hostname,
                port: 443,
                path: '/api/chat',
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'Content-Length': postData.length
                }
            };                
            
            var req = https.request(options, function(res) {                
                res.setEncoding('utf8');
                res.on('data', function (chunk) { 
                    console.log(chunk);
                  io.sockets.in(params.cod_sub).emit('chat_response',  JSON.parse(chunk));
                });
            });

                req.on('error', function(e) {
                  io.sockets.in(params.cod_sub).emit('chat_response', 'error');
                });                
                
                req.write(postData);
                req.end();     
            
	});
        */
        socket.on('chat', function(params)
            {  
                var data = [];

            request({
                        url: large_hostname + '/api/chat',
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
         var postData = JSON.stringify(params);                     
        const options = {
            hostname: hostname,
            port: 443,
            path: '/api/chat/delete',
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Content-Length': postData.length
            }
        };    
        
        var req = https.request(options, function(res) {        
                var datos = "";
                res.setEncoding('utf8');
                res.on('data', function (chunk) {  
                     datos += chunk;			 
                  
                });
                res.on('end', function () {                     		  
                    var datos_finales = JSON.parse(datos);			  
                    io.sockets.in(params.cod_sub).emit('delete_msg_response',  datos_finales);
              });
            });

                req.on('error', function(e) {
                  io.sockets.in(params.cod_sub).emit('delete_msg_response', 'error');
                });                
                
                req.write(postData);
                req.end();     
    	    
	});

	// Cancelar una puja
	socket.on('cancel_bid', function(params)
    {  
        var data = [];
        var postData = '{"params":' + JSON.stringify(params) + '}';                 
        const options = {
            hostname: hostname,
            port: 443,
            path: '/api/cancel_bid',
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Content-Length': postData.length
            }
        };    
        
        var req = https.request(options, function(res) {          
                var datos = "";
                res.setEncoding('utf8');
                res.on('data', function (chunk) {  
                    datos += chunk;	
                });
                res.on('end', function () {                     			  
                    var datos_finales = JSON.parse(datos);
                    io.sockets.in(params.cod_sub).emit('cancel_bid_response',  datos_finales);
                });
            });

                req.on('error', function(e) {
                  io.sockets.in(params.cod_sub).emit('cancel_bid_response', 'error');
                });                
                
                req.write(postData);
                req.end();  
	});
        
        // Cancelar una orden
	socket.on('cancel_order', function(params)
        {  
            var data = [];
            var postData = '{"params":' + JSON.stringify(params) + '}';            
            const options = {
                hostname: hostname,
                port: 443,
                path: '/api/cancel_order',
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'Content-Length': postData.length
                }
            };    
        
        var req = https.request(options, function(res) {   
                var datos = "";
                res.setEncoding('utf8');
                res.on('data', function (chunk) {  
                    datos += chunk;	
                });
                res.on('end', function () {                     		  
                   var datos_finales = JSON.parse(datos);
                   io.sockets.in(params.cod_sub).emit('cancel_order_response',  datos_finales);
                });
            });

                req.on('error', function(e) {
                  io.sockets.in(params.cod_sub).emit('cancel_order_response', 'error');
                });                
                
                req.write(postData);
                req.end();  
    	    
	});

	//Pujas y órdenes de licitación
    socket.on('action', function(params)
    {  
        
        var data = [];
        proceso_puja_finalizado[params.cod_sub] = false;
       /*PARARCUENTA ATRAS para la cuenta atrás de finalizar lote. 
        if (typeof params.can_do == 'undefined' || params.can_do != 'orders') {
                interrupt_cd_time[params.cod_sub] = true;
        }
        FIN PARARCUENTA ATRAS */
		var postData = '{"params":' + JSON.stringify(params) + '}'; 		
        const options = {
            hostname: hostname,
            port: 443,
            path: '/api/action/subasta'+ '-' + params.cod_sub ,
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Content-Length': postData.length
            }
        };    
        
        var req = https.request(options, function(res) {  
			var datos = "";
            res.setEncoding('utf8');
            res.on('data', function (chunk) {               
			   datos += chunk;			 
            });
			res.on('end', function () { 
					 var datos_finales = JSON.parse(datos);
					 
				 /* PARARCUENTA ATRAS nuevo código para no parar cuenta atras */
					if (typeof params.can_do == 'undefined' || params.can_do != 'orders') {
						if( typeof datos_finales.no_interrupt_cd_time != 'undefined' &&  datos_finales.no_interrupt_cd_time == 'true'){
						   ;//si ha habido un error no debemos parar la cuenta atras
						   
						}else{
							 interrupt_cd_time[params.cod_sub] = true;
							 
						}  
					}
				/* FIN PARARCUENTA ATRAS */


			
              proceso_puja_finalizado[params.cod_sub] = true;			  
			 			  
              io.sockets.in(params.cod_sub).emit('action_response',  datos_finales);
            });
			
        });

                req.on('error', function(e) {
                  io.sockets.in(params.cod_sub).emit('action_response', 'error');
                });                
                
                req.write(postData);
                req.end();     
       
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

		    io.sockets.in(params.cod_sub).emit('pausar_lote_response', $res);

		});
    	    
	});

    //Cambia el estado de la subasta. (se puede parar, reanudar, etc.)
	socket.on('auction_status', function(params)
	{
            console.log('auction status');
		if (typeof params.status != 'undefined' && params.status != '') {

	    	request({
			    url:  large_hostname + '/api/status/subasta',
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

});