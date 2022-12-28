/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function() {


    $('.lot-action_comprar').on('click', function(e) {


                    e.stopPropagation();
                    $.magnificPopup.close();
                    if (typeof cod_licit == 'undefined' || cod_licit == null )
                    {
                        displayAlert(0, messages.error.mustLogin);
                        return;
                    }else{
                        $.magnificPopup.open({items: {src: '#modalComprarFicha'}, type: 'inline'}, 0);
                    }

           });



    $('.lot-action_pujar').on('click', function(e) {


                   e.stopPropagation();
                   $.magnificPopup.close();
                   if (typeof cod_licit == 'undefined' || cod_licit == null )
                    {
                        displayAlert(0, messages.error.mustLogin);
                        return;
                    }else{
                        $.magnificPopup.open({items: {src: '#modalPujarFicha'}, type: 'inline'}, 0);
                        $("#bid_modal_pujar").val(imp);
                    }
           });

    $('.add_favs').on('click', function(e) {

         if (!$("#add_fav").hasClass('hidden')){
            $.ajax({
                type: "GET",
                url:  routing.favorites + "/add",
                data: { cod_sub: cod_sub, ref: ref, cod_licit: cod_licit },
                success: function( data ) {
                     if(data.status == 'success'){
                        displayAlertNotice(1, messages.success[data.msg]);
                        $("#add_fav").addClass('hidden');
                        $("#del_fav").removeClass('hidden');
                    }

                }
            });
        }
    });




});



window.pujarLoteFicha = function pujarLoteFicha()
{
	imp = $("#bid_modal_pujar").val();

    $.ajax({
        type: "POST",
        url:  routing.ol + '-' + cod_sub,
        data: { cod_sub: cod_sub, ref: ref, imp: imp},
        success: function( data ) {

            if (data.status == 'error'){
                displayAlert(0, data.msg_1);
            }else if(data.status == 'success'){
                displayAlert(1, data.msg);
                $("#tuorden").html(data.imp.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1."));
            }

        }
    });


}

 function action_fav(action){


                   $.magnificPopup.close();
                   if (typeof cod_licit == 'undefined' || cod_licit == null )
                    {
                        displayAlert(0, messages.error.mustLogin);
                        return;
                    }else{
                        $.ajax({
                            type: "GET",
                            url:  routing.favorites + "/" + action,
                            data: { cod_sub: cod_sub, ref: ref, cod_licit: cod_licit },
                            success: function( data ) {

                                if (data.status == 'error'){
                                    displayAlert(0, data.msg);
                                }else if(data.status == 'success'){
                                    displayAlert(1, data.msg);
                                    if(action == 'add'){
                                        $("#add_fav").addClass('hidden');
                                        $("#del_fav").removeClass('hidden');
                                    }else{
                                        $("#del_fav").addClass('hidden');
                                        $("#add_fav").removeClass('hidden');
                                    }
                                }

                            }
                        });
                    }
           };



$( document ).ready(function() {


    $("#pujar_ordenes_w").click(function() {

		$("#orderphone").val("");
		$(".phonebid_js").addClass("hide");

		confirmar_orden()
   });

   $("#pujar_orden_telefonica").click(function() {
		$("#orderphone").val("S");
		$(".phonebid_js").removeClass("hide");
		$("#errorOrdenFicha").addClass("hidden");

         if(auction_info.user!== undefined){
            if(auction_info.user.phone1 !== null){
                $("#phone1Bid_JS").val(auction_info.user.phone1);
            }
            if(auction_info.user.phone2 !== null){
                $("#phone2Bid_JS").val( auction_info.user.phone2 );
            }
         }

            confirmar_orden()

	});

});

function confirmar_orden(){
	var imp = $("#bid_modal_pujar").val();
	num = imp.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
	$( ".precio_orden" ).text( num );

	//divisas, debe existir el selector
	if(typeof $("#currencyExchange").val() != 'undefined'){
		changeCurrency(imp, $("#currencyExchange").val(),"newOrderExchange_JS");
	}


	if (typeof cod_licit == 'undefined' || cod_licit == null )
	{
		/*
			//muestra un mensaje para hacer login o registro
			$("#insert_msg_title").html("");
			$("#insert_msg").html(messages.error.mustLogin);
			$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
		*/

		//este codigo abre directamente la ventana emergente de login
		$('.login_desktop').fadeToggle("fast");
		$('.login_desktop [name=email]').focus();

	}else{
		$.magnificPopup.open({items: {src: '#ordenFicha'}, type: 'inline'}, 0);
	}
}

window.comprarLoteFicha = function comprarLoteFicha()
{

    $.ajax({
        type: "POST",
        url:  routing.comprar + '-' + cod_sub,
        data: { cod_sub: cod_sub, ref: ref},
        success: function( data ) {

            $("#insert_msg").html("");
            $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline', callbacks: {
                    close: function() {
                        document.location = document.location;
                    }
                }
            });

            if (data.status == 'error'){
                    $("#insert_msg").html(data.msg_1);
                    $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline', callbacks: {
                            close: function() {
                                document.location = document.location;
                            }
                        }
                    });

            }else if(data.status == 'success'){
                $("#insert_msg").html(data.msg);
                $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
                $(".lot-action_comprar_lot").addClass('hidden');
            }


        }
    });


};

window.makeOfferFicha = function makeOfferFicha()
{

imp = $("#bid_make_offer").val();
    $.ajax({
        type: "POST",
        url: "/api-ajax/makeOffer" ,
        data: { cod_sub: cod_sub, ref: ref, imp: imp },
        success: function( data ) {


            if (data.status == 'error'){
                    $("#insert_msg").html(data.msg_1);
                    $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'});

            }else if(data.status == 'info'){
                $("#insert_msg").html(data.msg_1);
				$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'});

				auction_info.lote_actual.pujas = data.pujas
				reloadPujasList_O();
            }else if(data.status == 'success'){
                $("#insert_msg").html(data.msg);
                $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline',callbacks: {
					close: function() {
						document.location = data.location;
					}
				}
			});

            }


        }
    });


};

