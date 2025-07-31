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

        $('.lot-action_pujar_on_line').on('click', function(e) {
             e.stopPropagation();
                   $.magnificPopup.close();
                   $( ".precio_orden" ).html($("#bid_amount").val());

                   if (typeof cod_licit == 'undefined' || cod_licit == null )
                    {

                        $("#insert_msg_title").html("");
                        $("#insert_msg").html(messages.error.mustLogin);
                        $.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);

                        return;
                    }else{

						if (!auction_info.user.is_gestor && ( isNaN(parseInt($("#bid_amount").val())) ||  parseInt($("#bid_amount").val()) < parseInt(auction_info.lote_actual.importe_escalado_siguiente) ) )
                        {
                            $("#insert_msg_title").html($("#bid_amount").val() +  "€ " + messages.error.lower_bid);
                            $("#insert_msg").html(messages.error.your_bid + " " + auction_info.lote_actual.importe_escalado_siguiente + " € " + messages.error.as_minimum);
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


	   if (typeof auction_info != 'undefined' && typeof auction_info.user != 'undefined' && auction_info.user.is_gestor) {

		   reloadAdminCredit();
		   setInterval('reloadAdminCredit()', 10000);

		   $("#clients_credit").on('click', function () {
			   var direccion = ' ';
			   $.ajax({
				   type: "POST",
				   url: '/api-ajax/get_clients_credit',
				   data: { cod_sub: auction_info.lote_actual.sub_hces1 },
				   success: function (data) {
					   $(data).each(function (index) {
						   $("#ClientsCredit .search-loader").hide();
						   direccion += `<tr><td>${this.cod_cli}</td><td>${this.rsoc_cli}</td><td>${this.ries_cli}</td><td>${this.riesmax_cli}</td><td>${this.current_credit}</td><td>${this.fecha_credit}</td></tr>`;
					   });
					   $("#ClientsCredit .insert_msg").html('');
					   $("#ClientsCredit .insert_msg").html(`${auction_info.lote_actual.cod_sub} - ${auction_info.lote_actual.des_sub}`);
					   $(".clientes_credito").html(direccion);
					   $("#ClientsCredit .search-loader").hide();
				   }
			   })
			   $.magnificPopup.close();
			   $.magnificPopup.open({ items: { src: '#ClientsCredit' }, type: 'inline', showCloseBtn: true, enableEscapeKey: false, closeOnBgClick: true }, 0);
		   });
	   }

    });


function reloadAdminCredit() {

	var direccion = ' ';
	$(".search-panel-loader").show();
	$.ajax({
		type: "POST",
		url: '/api-ajax/get_clients_credit',
		data: { cod_sub: auction_info.lote_actual.sub_hces1 },
		success: function (data) {
			$(data).each(function (index) {
				$(".search-panel-loader").hide();
				direccion += `<tr><td>${this.cod_cli}</td><td>${this.rsoc_cli}</td><td>${this.ries_cli}</td><td>${this.riesmax_cli}</td><td>${this.current_credit}</td><td>${this.fecha_credit_forHumans}</td></tr>`;
			});
			$(".panel_clientes_credito").html(direccion);
			$(".search-panel-loader").hide();
		}
	});
}



   function actionResponseDesign(data){

        if( auction_info.subasta.sub_tiempo_real == 'S'){
            actionResponseDesign_W(data)
        }else{
            actionResponseDesign_O(data)
        }
	}


    function actionResponseDesign_W(data){

        $('#actual_max_bid').html(data.formatted_actual_bid + " €");
        $('#text_actual_no_bid').addClass('hidden');
		$('#text_actual_max_bid').removeClass('hidden');

		reloadCredit();

        if (typeof auction_info.user != 'undefined' && data.winner == auction_info.user.cod_licit) {
                $('#tupuja').html(data.formatted_actual_bid);

                if(auction_info.user.cod_licit == data.cod_licit_actual && data.test[0] == "Entramos en OL") {
                        $('#tuorden').html(data.imp_original_formatted);
                }

                $('#actual_max_bid').addClass('mine');
                $('#actual_max_bid').removeClass('other');
                $('#cancelarPujaUser').removeClass('hidden');
                /*console.log('bid 1');*/



        } else {
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
       if (typeof auction_info.lote_actual != 'undefined' && typeof auction_info.lote_actual.pujas != 'undefined'){
            $('#text_actual_max_bid').removeClass('hidden');
            $('#actual_max_bid').removeClass('hidden');
            $('#text_actual_no_bid').addClass('hidden');
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
             $('#actual_max_bid').addClass('vacio');
        }

   }


    function reloadPujasList()
    {
		reloadCredit();
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
                        var $this = model.clone().removeAttr('id');

                    //mostramos todos los lotes si esta activo ver todo o solo lso que cumplen el rango
                        if ( $("#view_all_pujas_active").val() == '1' ||  (num_lot <= view_num_pujas && $("#view_all_pujas_active").val() == '0')){
                            $this.removeClass('hidden');
                        }

                        // si la puja es del licitador ocultamos el numero y se mostrará el YO
                        if(typeof auction_info.user  != 'undefined' &&   typeof auction_info.user.cod_licit != 'undefined' && puja.cod_licit == auction_info.user.cod_licit){
                             $('.uno', $this).addClass('hidden')
                        }
                        //Si la puja no es del licitador mostramso el numero del licitador que corresponde
                         else{
                            $('.yo', $this).addClass('hidden');
                            $('.uno', $this).html(licits[puja.cod_licit]);
                            $('.uno', $this).attr('data-hint', messages.neutral.puja_corresponde+ " " + licits[puja.cod_licit]);
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
       if (auction_info.lote_actual.tipo_sub == 'O' || auction_info.lote_actual.tipo_sub == 'P' ){
           if(typeof auction_info.user != 'undefined' && auction_info.user.cod_licit == data.cod_licit_actual){
               if (data.status == 'success') {
                   //Poner codigo de analytics
               }
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



/*
    |--------------------------------------------------------------------------
    | Mostrar alertas
    |--------------------------------------------------------------------------
    */
    function displayAlert(type, msg)
	{
            if (auction_info.lote_actual.tipo_sub == 'O' || auction_info.lote_actual.tipo_sub == 'P' ){
                 displayAlert_O(type, msg)
            }else if( auction_info.lote_actual.tipo_sub == 'W'){
                 displayAlert_W(type, msg)
            }
        }
    function displayAlert_W(type, msg)
	{
		if(type == null || typeof type == 'undefined' || !$.isNumeric(type))
			return false;

		/*var type = ''; */

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

	function reloadCredit(){

		if(typeof auction_info.user != 'undefined' && typeof auction_info.user.adjudicaciones != 'undefined'){

			let userAdjudicaciones = 0;
			let creditUsed = 0;
			let currentCredit = parseInt($('#current_credit').data('currentCredit'));
			let awards = []

			auction_info.user.adjudicaciones.forEach(function(adjudicacion){
				//cuando se reabren lotes, si el mismo usuario se los vuelve a adjudicar, se duplican las adjudicaciones
				if (awards.indexOf(adjudicacion.ref_asigl1) != -1) {
					return;
				}

				//si la adjudicacion es del lote actual no se tiene en cuenta
				if(auction_info.lote_actual.ref_asigl0 == adjudicacion.ref_asigl1){
					return;
				}

				awards.push(adjudicacion.ref_asigl1);
				userAdjudicaciones += parseInt(adjudicacion.imp_asigl1);
			});

			creditUsed = userAdjudicaciones;

			if(typeof auction_info.user.sum_award_previous_sessions != 'undefined'){
				creditUsed += parseInt(auction_info.user.sum_award_previous_sessions);
			}

			const myMaxBid = auction_info.lote_actual.pujas.find((puja) => {
				return puja.cod_licit == auction_info.user.cod_licit
			});

			if(typeof myMaxBid != 'undefined' && myMaxBid.rn == 1){
				creditUsed += parseInt(myMaxBid.imp_asigl1);
			}

			const formater = new Intl.NumberFormat("de", {});
			$('#credit_used').html(formater.format(creditUsed));
			$("#available_credit").html(formater.format(currentCredit - creditUsed));

		}

	}

	function reloadMainLotInfoCustom() {
		const mark = document.querySelector('#lote_actual_main .lot-itp-mark');
		markWhenIsItp(auction_info.lote_actual, mark);
	}

	function reloadBuscadorCustom() {
		const mark = document.querySelector('.num-lot-search .lot-itp-mark');
		markWhenIsItp(auction_info.buscador, mark);
	}

	/**
	 * Mostrar o no el icono de ITP
	 * @param {*} lot
	 * @param {HTMLElement} element
	 */
	function markWhenIsItp(lot, element) {
		if(!element) return;
		element.classList.toggle('hidden', !lot.isItp);
	}
