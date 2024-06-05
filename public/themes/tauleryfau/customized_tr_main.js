/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 /*
    |--------------------------------------------------------------------------
    | Repinta la caja de pujas
    |--------------------------------------------------------------------------
    */
   $(document).ready(function(){

		if($('#modalActivarAudio').length){
			setTimeout(function() {
				$.magnificPopup.open({
					items: {
						src: '#modalActivarAudio'
					},
					type: 'inline',
					mainClass: 'mfp-top-center',
				}, 0);
			}, 500);
		}


		$('#modalActivarAudio').on('click', '.modal-dismiss', desactivar_audio);

		/**
		 * Observer
		 * Controla los cambios en el contenedor $("#actual_max_bid"),
		 * Cuando salta el observer comprobamos que sea por un cambio en los atributos y de estos en el class.
		 */
		var $element = $("#actual_max_bid");
		var observer = new MutationObserver(function(mutations) {
  			mutations.forEach(function(mutation) {
    			if (mutation.attributeName === "class") {
					var attributeValue = $(mutation.target).prop(mutation.attributeName);

					if(attributeValue.includes("mine") && !attributeValue.includes("other")){
						$('.title-bid-button').hide();
						$('.input-gestor-content').hide();
						$('.user-higher-bidder').show();
					}
					else{
						$('.title-bid-button').show();
						$('.input-gestor-content').show();
						$('.user-higher-bidder').hide();
					}
    			}
  			});
		});

		if($element.length > 0){
            observer.observe($element[0], {
                attributes: true,
          });
        }


		$('.audio-btn').on('click touchend',  function(e){

			e.preventDefault();
			if($(this).hasClass('off')){
				activar_audio();
			}
			else{
				desactivar_audio();
			}
		});

        $('.lot-action_pujar_on_line').on('click', function(e) {
             e.stopPropagation();
				   $.magnificPopup.close();


					if($(e.target)[0].hasAttribute("value")){
						$("#bid_amount").val(parseInt($(e.target).attr("value")));
					}
					else{
						$("#bid_amount").val(parseInt($("#bid_amount_libre").val()));
					}

                   $("#bid_amount_libre").val('');
                   $( ".precio_orden" ).html($("#bid_amount").val());

                   if (typeof cod_licit == 'undefined' || cod_licit == null )
                    {
                        $('#modalLogin').modal('show')
                        $("#insert_msg_title").html("");
                        $("#insert_msg").html(messages.error.mustLogin);
                        //$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);

                        return;
                    }else{
                        //al permitir pujas de escalado libre ya no podemos realizar la comprobación por  < importe_escalado_siguiente, si no por actual_bid
						//menor que el precio actual o igual si no es el precio de salida
						//al gestor permititmos saltarse la comprobación para que pueda hacer pujas por debajo del importe
                    if ( !auction_info.user.is_gestor &&  (isNaN($("#bid_amount").val()) || (parseInt($("#bid_amount").val()) < parseInt(auction_info.lote_actual.actual_bid)) || ( parseInt($("#bid_amount").val()) == parseInt(auction_info.lote_actual.actual_bid) && parseInt(auction_info.lote_actual.actual_bid) != parseInt(auction_info.lote_actual.importe_escalado_siguiente) )))
                        {
                           $("#insert_msg").html(messages.error.bid_small_actual);
                           $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
                        }
                         else{
                           $.magnificPopup.open({items: {src: '#modalPujarFicha'}, type: 'inline'}, 0);
                       }
                    }
        });

         $('.lotlist-orden').on('click', function(e) {
            e.stopPropagation();
            $.magnificPopup.close();
            var precio_lot = $(this).parent().siblings().val();
            var ref = $(this).attr('ref');
            $( ".precio_orden" ).html(precio_lot);
            $( ".ref_orden" ).html(ref);
                $.magnificPopup.open({items: {src: '#modalPujarFicha'}, type: 'inline'}, 0);
		});
	});


    function actionResponseDesign(data){

        if( auction_info.subasta.sub_tiempo_real == 'S'){
            actionResponseDesign_W(data)
        }else{
            actionResponseDesign_O(data)
        }
    }
    function actualize_currency(data, userWinner){
        changeCurrency(data.actual_bid,$("#actual_currency").val(),"impsalexchange-actual");
		changeCurrency(data.siguiente,$("#actual_currency").val(),"impsalexchange-next");
		$('#value-view').html(numeral(data.siguiente).format('0,0' ) + " €");

		if (userWinner){
			$('.title-bid-button').hide();
			$('.input-gestor-content').hide();
			$('.user-higher-bidder').show();
		}
		else{
			$('.title-bid-button').show();
			$('.input-gestor-content').show();
			$('.user-higher-bidder').hide();
		}
    }

    function actionResponseDesign_W(data){

        $('#actual_max_bid').html(data.formatted_actual_bid + " €");

        $('#text_actual_no_bid').addClass('hidden');
        $('#text_actual_max_bid').removeClass('hidden');



        if (typeof auction_info.user != 'undefined' && data.winner == auction_info.user.cod_licit) {
				actualize_currency(data, true);
                $('#tupuja').html(data.formatted_actual_bid);

                if(auction_info.user.cod_licit == data.cod_licit_actual && data.test[0] == "Entramos en OL") {
                        $('#tuorden').html(data.imp_original_formatted);
                }

                $('#actual_max_bid').addClass('mine');
                $('#actual_max_bid').removeClass('other');
                $('#cancelarPujaUser').removeClass('hidden');
                /*console.log('bid 1');*/



        } else {
				actualize_currency(data, false);
                $('#actual_max_bid').addClass('other');
                $('#actual_max_bid').removeClass('mine');
                /*console.log('bid 2');*/

                if(typeof auction_info.user != 'undefined' && auction_info.user.cod_licit == data.cod_licit_actual) {
                        $('#tupuja').html(data.imp_original_formatted);
                }

                /* Si es gestor nunca se oculta el cancelar puja*/
                /* Falta una comprobación para el gestor en caso de que no haya nada que cancelar (una puja)*/
                if(typeof auction_info.user != 'undefined' && auction_info.user.is_gestor) {
                        $('#cancelarPujaUser').removeClass('hidden');
                } else {
                        $('#cancelarPujaUser').addClass('hidden');
                }
        }
   }
   function actionResponseDesign_O(data){

	   $('#actual_max_bid').html(data.formatted_actual_bid + " €");

		//actualizar botones escalado
	   if ( $(".js-lot-action_pujar_escalado").length) {
			reloadPujasButtons(data.pujasAll[0].cod_sub, data.actual_bid);
	  }

       if (typeof auction_info.lote_actual != 'undefined' && typeof auction_info.lote_actual.pujas != 'undefined'){
            $('#text_actual_max_bid').removeClass('hidden');
            $('#actual_max_bid').removeClass('hidden');
            $('#text_actual_no_bid').addClass('hidden');
            $('#impsalexchange-actual').removeClass('hidden');
            actualize_currency(data);
       }

        if (typeof auction_info.user != 'undefined' && cod_licit != null && data.winner == auction_info.user.cod_licit) {

                $('#actual_max_bid').addClass('mine').removeClass('other').removeClass('gold');
                $('.hist_new').removeClass('hidden');

        } else if(cod_licit != null && data.winner != auction_info.user.cod_licit){
            var not_push = false;
            $( data.pujasAll ).each(function( index ) {

              var puja = data.pujasAll[index];

              if(puja.cod_licit == auction_info.user.cod_licit){
                  not_push = true;
                   return false;
              }
            });

            if(not_push){
                     $('#actual_max_bid').addClass('other').removeClass('mine').removeClass('gold');
            }else{
                 $('#actual_max_bid').addClass('gold').removeClass('mine').removeClass('other');
            }
        }else{
             $('#actual_max_bid').addClass('gold');
        }

   }

   function reloadPujasButtons(codSub, actualBid){

	$.ajax({
		type: "GET",
		url: '/api-ajax/calculate_bids/' + actualBid + '/' + actualBid + '?cod_sub=' + codSub,
		success: function( response )
		{
			response = JSON.parse(response);
			//let buttons = $(".js-lot-action_pujar_escalado");

			for (let index = 0; index < 3; index++) {

				$(`[data-escalado-position=${index}]`).attr('value', response[index]);
				$(`[data-escalado-position=${index}] span`).attr('value', response[index]).text(new Intl.NumberFormat("de-DE").format(response[index]));

				/* $(buttons[index]).attr('value', response[index]);
				$(buttons[index].firstElementChild).attr('value', response[index]).text(new Intl.NumberFormat("de-DE").format(response[index])); */
			}
		}
	});

   }


    function reloadPujasList()
    {

        if( auction_info.subasta.sub_tiempo_real == 'S'){
            reloadPujasList_W()
        }else {
            reloadPujasList_O()
        }

    }

    function reloadPujasList_W(){

        var model = $('#type_bid_model').clone();
    	var container = $('.aside.pujas #pujas_list');

    	if (typeof auction_info.lote_actual != 'undefined' && typeof auction_info.lote_actual.pujas != 'undefined' && typeof container != 'undefined' && container.length > 0){

	    	$('.aside.pujas .pujas_model:not(#type_bid_model)').remove();
                var ultima_orden =false;
                var orden_maxima = 0;
                $.each( auction_info.lote_actual.ordenes, function( key_ordenes, value_ordenes ) {
                     if (orden_maxima <  parseInt(value_ordenes.himp_orlic)){
                         orden_maxima = parseInt(value_ordenes.himp_orlic);
                     }

                });
	    	$.each( auction_info.lote_actual.pujas, function( key, value ) {

	    		/* limite de pujas a mostrar*/
	    		if (key >= auction_info.subasta.max_bids_shown && auction_info.subasta.max_bids_shown != -1){
	    			return false;
	    		}

                            var $this = model.clone().removeClass('hidden').removeAttr('id');
                            $('.importePuja .puj_imp', $this).html(value.formatted_imp_asigl1);

                            /*Nombre de los licitadores*/
                            var name_licit = messages.neutral.new_licit;
                            if(typeof licitadores != 'undefined' && typeof licitadores[value.cod_licit] != 'undefined' ){
                                name_licit = licitadores[value.cod_licit];
                            }else if(value.cod_licit == auction_info.subasta.dummy_bidder){
                                name_licit = '-';
                            }
                            /*Fin de nombre de los licitadores*/

                            $('.importePuja .licitadorPuja', $this).html('('+value.cod_licit+')<span style="font-size: 12px;"> '+name_licit+'</span>');

                            $('.tipoPuja p:not(.hidden)', $this).addClass('hidden');
                            $('.tipoPuja p[data-type="'+ value.pujrep_asigl1 +'"]', $this).removeClass('hidden');

                           $.each( auction_info.lote_actual.ordenes, function( key_ordenes, value_ordenes ) {
                               //debe coincidir solo con la orden más alta
                            if( !ultima_orden && value_ordenes.himp_orlic == orden_maxima &&  value.formatted_imp_asigl1 == value_ordenes.himp_orlic_formatted && value_ordenes.cod_licit == value.cod_licit && value.type_asigl1 == 'A'){
                                         $('.ordenes .orden', $this).removeClass('hidden');
                                         ultima_orden = true;
                            }
});
                            container.append($this);
		});

        }
    }

     function reloadPujasList_O(){
         var model = $('#duplicalte_list_pujas').clone();
         var container = $('#pujas_list');
          $('.siguiente_puja').html(auction_info.lote_actual.importe_escalado_siguiente);
    	if (typeof auction_info.lote_actual != 'undefined' && typeof auction_info.lote_actual.pujas != 'undefined'){
                //se borran todos lso contenidos del listado de pujas
                $('div',container).remove();
                $('#pujas-collapse',container).remove();
                $('#historial_pujas').removeClass('hidden');
                $('.num_pujas').html(auction_info.lote_actual.pujas.length);


               var num_lot = 1;
               var cont_licit = 1;
               var licits = new Array();
               var min_price_surpass = false;
               var view_num_pujas = $('#view_num_pujas').val();
               var pujas_licits = auction_info.lote_actual.pujas.slice();
               pujas_licits.reverse();

               $.each( pujas_licits, function( key, licitador ) {
                   //cogemos el primer valor que supere o iguale el importe de reserva
                    if( min_price_surpass == false && parseInt(licitador.imp_asigl1) >= parseInt(auction_info.lote_actual.impres_asigl0)){
                        min_price_surpass = licitador.imp_asigl1;
                    }
                  //obtenemos los valores que identifican lso licitadores, necesitamos ordenar las pujas al reves, por eso se ha hecho un reverse antes
                   if (typeof licits[licitador.cod_licit] == 'undefined'){
                       licits[licitador.cod_licit] = cont_licit;
                       cont_licit++;
                   }
               })

	    	$.each( auction_info.lote_actual.pujas, function( key, puja ) {
                        var $this = model.clone().removeAttr('id').removeClass('hidden');

                    //mostramos todos los lotes si esta activo ver todo o solo lso que cumplen el rango
                        if ( $("#view_all_pujas_active").val() == '1' ||  (num_lot <= view_num_pujas && $("#view_all_pujas_active").val() == '0')){
                            $this.removeClass('hidden');
                        }

                        // si la puja es del licitador ocultamos el numero y se mostrará el YO
                        if(typeof auction_info.user  != 'undefined' &&   typeof auction_info.user.cod_licit != 'undefined' && puja.cod_licit == auction_info.user.cod_licit){
							 $('.uno', $this).addClass('hidden')
							 $('.otro', $this).addClass('hidden')
                        }
                        //Si la puja no es del licitador mostramso el numero del licitador que corresponde
                         else{
                            $('.yo', $this).addClass('hidden');
                            $('.uno', $this).html(licits[puja.cod_licit]);
                        }
                        //si es una sobrepuja debe aparecer la letra A
                        if(puja.type_asigl1 != 'A'){
                            $('.dos', $this).addClass('hidden');
                        }

                        if(parseInt(puja.imp_asigl1) >= parseInt(auction_info.lote_actual.impres_asigl0) ){
                            $('.price', $this).addClass('verde');
                        }else{
                            $('.price', $this).addClass('rojo');
                        }
                        $('.price', $this).html(puja.imp_asigl1 + " EUR");
                        var fecha = new Date(puja.bid_date.replace(/-/g, "/"));
                        var formatted = format_date(fecha)
                        $('.date', $this).html(formatted);
                        container.append($this);
                        //si superamos el valor de reseva
                        if ( parseInt(min_price_surpass) == parseInt(puja.imp_asigl1) ){
                           // se mostrará si esta dentro del rango de numero de pujas visibles o estan todas visibles
                            if ( $("#view_all_pujas_active").val() == '1' ||  (num_lot <= view_num_pujas && $("#view_all_pujas_active").val() == '0')){
                                container.append($("#price_min_surpass").clone().removeClass("hidden"));
                            }else{
                                container.append($("#price_min_surpass").clone());
                            }
                        }
                        num_lot++;
            });
            //mostramso si se ha alcanzado el precio mínimo
            if(min_price_surpass == false){
                $('.precio_minimo_no_alcanzado').removeClass('hidden');
                $('.precio_minimo_alcanzado').addClass('hidden');
            }else{
                 $('.precio_minimo_alcanzado').removeClass('hidden');
                $('.precio_minimo_no_alcanzado').addClass('hidden');
            }
           // mostramos el boton de ver todos los lotrs si es necesario
            if(num_lot > view_num_pujas){
                container.append($("#view_more").clone().removeClass("hidden"));
            }

        }
    }

    /*
    |--------------------------------------------------------------------------
    | END Repinta la caja de pujas
    |--------------------------------------------------------------------------
    */
   function action_success(data){
       //si el la accion del usuario actual ha tenido exito
           if(typeof auction_info.user != 'undefined' && auction_info.user.cod_licit == data.cod_licit_actual){
               if (data.status == 'success') {
                   //Poner codigo de analytics

               }
           }
   }

function view_all_bids(){

    //si estan ocultos los mostramos y cambiamso el texto del boton
    if($("#view_all_pujas_active").val() == '0'){
        $('#pujas_list div').each(function(index){
            $(this).removeClass("hidden");

        });
        $("#view_all_pujas_active").val('1');
        $('#view_more_text').addClass("hidden");
        $('#hide_bids_text').removeClass("hidden");
    }
    //si estan visibles los ocultamos y cambiamso el texto del boton
    else{
        $('#pujas_list div').each(function(index){
            if(index >= $('#view_num_pujas').val()){
             $(this).addClass("hidden");
            }
        });

        $("#view_all_pujas_active").val('0');
        $('#view_more').removeClass("hidden");
        $('#view_more_text').removeClass("hidden");
        $('#hide_bids_text').addClass("hidden");
    }
}

function historicTab(lang, codsub, session ){
    $.ajax({
        type: "GET",
        url: '/'+ lang + '/historicTab/' + codsub + "/" + session,

        success: function( response )
        {
            if(response.status == 'success'){
                 $("#historic_list").html(response.html);
            }else{
                 $("#historic_list").html("");
            }
        }
    });
}

function adjudicadoTab(lang, codsub, session, licit ){
    $.ajax({
        type: "GET",
        url: '/'+ lang + '/adjudicadosTab/' + codsub + "/" + session+ "/" + licit,

        success: function( response )
        {
            if(response.status == 'success'){
                 $("#adjudicaciones_list").html(response.html);
            }else{
                 $("#adjudicaciones_list").html("");
            }
        }
    });
}

function favoriteTab(lang, codsub, licit ){
    $.ajax({
        type: "GET",
        url: '/'+ lang + '/favoritesTab/' + codsub + "/" + licit,

        success: function( response )
        {
            if(response.status == 'success'){
                 $("#favoritos_list").html(response.html);
            }else{
                 $("#favoritos_list").html("");
            }
        }
    });
}


/*
    |--------------------------------------------------------------------------
    | Mostrar alertas
    |--------------------------------------------------------------------------
    */
    function displayAlert(type, msg)
    {
        if( auction_info.subasta.sub_tiempo_real == 'S'){
           displayAlert_W(type, msg);
        }else {
            displayAlert_O(type, msg);
        }
    }

    function displayAlert_W(type, msg)
	{
		if(type == null || typeof type == 'undefined' || !$.isNumeric(type))
			return false;

		/*var type = ''; */
                //(03-10-19: Eloy)Desactivadas notificaciones de sobrepuja (alert) por orden del cliente, var higher_bid_notshow
		switch(type) {
		    case 0:
		        type = 'error';
		        break;
		    case 1:
		        type = 'success';
                        break;
	        case 2:
		        type = 'info';
		        break;
		    case 3:
		        type = 'alert';
		        break;
		}

		playAlert(['notification']);
		var notice = new PNotify({
			title: messages.neutral.notification,
			text: msg,
			type: type,
			shadow: true,
			addclass: 'stack-topleft'
		});
	}

     function displayAlert_O(type, msg)
	{
            $("#insert_msg_title").html("");
            $("#insert_msg").html(msg);
            $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);

        }
	/*
    |--------------------------------------------------------------------------
    | END Mostrar alertas
    |--------------------------------------------------------------------------
    */

/*
|--------------------------------------------------------------------------
| Muesta cuando existen adjudicaciones nuevas
|--------------------------------------------------------------------------
*/
let countAdjudicaciones = 0;
function reloadAdjudicacionesCustom(){

	if (typeof auction_info.user != 'undefined' && typeof auction_info.user.adjudicaciones != 'undefined') {

		let auxAdjudicaciones = auction_info.user.adjudicaciones.length;

		if(auxAdjudicaciones > countAdjudicaciones){
			if ($('#tab-adj').length > 0) {
                if (!$('#tab-adj').hasClass('active')) {
                    $('#tab-adj').addClass('animation');
                    $('#tab-adj').one('webkitAnimationEnd oanimationend msAnimationEnd animationend',
                            function (e) {
                                // code to execute after animation ends
                                $('#tab-adj').removeClass('animation');
                                $('.alert-adj').addClass('pending');
                            });
                }
            }
		}

		countAdjudicaciones = auxAdjudicaciones;
	}
}

function activar_audio(){

	activedAudio(true);

	$('.audio-btn')[0].src = '/img/icons/volume_up.png';
	$('.audio-btn').removeClass('off');
	$('.audio-btn').addClass('on');
}


function desactivar_audio(){

	activedAudio(false);

	$('.audio-btn')[0].src = '/img/icons/volume_off.png';
	$('.audio-btn').removeClass('on');
	$('.audio-btn').addClass('off');
}
