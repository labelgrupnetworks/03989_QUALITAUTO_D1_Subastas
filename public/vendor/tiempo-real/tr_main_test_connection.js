
$(function() {
    var socket = io.connect( url_node_test, { 'forceNew': true });

	socket.on('connect', function() {

	   socket.emit('room', {cod_sub: "19042018", id: socket.id});
	 

	});

        
       $('#test_conection').on('click', function(e) {
           // socket.emit('test_connection', {cod_sub: 'aa'});
           var mensaje ={'CA':{'msg':'test','predefinido':'0'},'ES':{'msg':'test','predefinido':'0'},'EN':{'msg':'test','predefinido':'0'}};
             var params = {'cod_sub':'19042018' , 'mensaje': mensaje,cod_licit: '1009', 'hash': '83595a3913cea7c7569f982da1232c1daefc5ef9d5b64704f6e14659eebb9d8e' };      
              
           socket.emit('chat', params);
       });          
     
       
	/*
    |--------------------------------------------------------------------------
    | END Conexión a nodejs
    |--------------------------------------------------------------------------
    */
   
    
   socket.on('chat_response', function(data) {
               
        console.log(data);
		
    })
   

    
});

  


