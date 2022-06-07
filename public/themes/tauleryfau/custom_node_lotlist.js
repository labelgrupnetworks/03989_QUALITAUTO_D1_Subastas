
$(function() {
    var socket = io.connect(routing.node_url, { 'forceNew': true });

    /*
    |--------------------------------------------------------------------------
    | Conexión a nodejs
    |--------------------------------------------------------------------------
    */
	socket.on('connect', function() {

	   socket.emit('room', {cod_sub: cod_sub, id: socket.id});

	});

    /*
    |--------------------------------------------------------------------------
    | END Conexión a nodejs
    |--------------------------------------------------------------------------
    */

	/*Bloqueado hasta que tengamos las variables necesarias en el nuevo grid de lotes

   socket.on('action_response', function(data) {
       if( typeof data != 'undefined'  &&  typeof data.pujasAll != 'undefined' ){
           //data.pujasAll[0]
           var id_sub_ref = data.pujasAll[0].cod_sub+"-"+data.pujasAll[0].ref_asigl1;
           var imp_asigl1 = data.pujasAll[0].formatted_imp_asigl1;
           $('.'+id_sub_ref).html( imp_asigl1 + " €");
           $('.update-bid-tr-'+id_sub_ref).removeClass('hidden');
           $('.remove-bid-tr-'+id_sub_ref).addClass('hidden');
           if( cod_licit != null && data.pujasAll[0].cod_licit == cod_licit){
				$('.'+id_sub_ref).addClass('mine').removeClass('other');
				$('#actual_max_bid').addClass('mine').removeClass('other');
           }else if( cod_licit != null && data.pujasAll[0].cod_licit != cod_licit ){

                var not_push = false;
                $( data.pujasAll ).each(function( index ) {

                  var puja = data.pujasAll[index];

                  if(puja.cod_licit == auction_info.user.cod_licit){
                      not_push = true;
                       return false;
                  }
                });

                if(not_push){
					  $('.'+id_sub_ref).addClass('other').removeClass('mine').removeClass('gold');
					  $('#actual_max_bid').addClass('other').removeClass('mine');
                }else{
					  $('.'+id_sub_ref).addClass('gold').removeClass('mine').removeClass('other');
					  $('#actual_max_bid').addClass('other').removeClass('mine');
                }

           }else{
                $('.'+id_sub_ref).addClass('gold');
           }
       }

   });
   */
    socket.on('auction_status_response', function(data) {
        if(data.status == "in_progress"){
            $('#modal-current-auction_' + data.id_sub).modal('show',{
                keyboard: true
            })
        }
     });



});

