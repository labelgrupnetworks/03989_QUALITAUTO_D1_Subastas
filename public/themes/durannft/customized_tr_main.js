reloadPujasList_O = function () {


	var model = $('#duplicalte_list_pujas').clone();
	var container = $('#pujas_list');
	$('.siguiente_puja').html(new Intl.NumberFormat("de", {}).format(auction_info.lote_actual.importe_escalado_siguiente));
	if (typeof auction_info.lote_actual != 'undefined' && typeof auction_info.lote_actual.pujas != 'undefined') {
		//se borran todos lso contenidos del listado de pujas
		$('div', container).remove();
		$('#pujas-collapse', container).remove();
		$('#historial_pujas').removeClass('hidden');
		$('.num_pujas').html(auction_info.lote_actual.pujas.length);


		var num_lot = 1;
		var cont_licit = 1;
		var licits = new Array();
		var min_price_surpass = false;
		var view_num_pujas = $('#view_num_pujas').val();
		var pujas_licits = auction_info.lote_actual.pujas.slice();
		pujas_licits.reverse();

		$.each(pujas_licits, function (key, licitador) {
			//cogemos el primer valor que supere o iguale el importe de reserva
			if (min_price_surpass == false && parseInt(licitador.imp_asigl1) >= parseInt(auction_info.lote_actual.impres_asigl0)) {
				min_price_surpass = licitador.imp_asigl1;
			}
			//obtenemos los valores que identifican lso licitadores, necesitamos ordenar las pujas al reves, por eso se ha hecho un reverse antes
			if (typeof licits[licitador.cod_licit] == 'undefined') {
				licits[licitador.cod_licit] = cont_licit;
				cont_licit++;
			}
		})

		let pujaPosition = auction_info.lote_actual.pujas.length;

		$.each(auction_info.lote_actual.pujas, function (key, puja) {
			var $this = model.clone().removeAttr('id');


			//mostramos todos los lotes si esta activo ver todo o solo lso que cumplen el rango
			if ($("#view_all_pujas_active").val() == '1' || (num_lot <= view_num_pujas && $("#view_all_pujas_active").val() == '0')) {
				$this.removeClass('hidden');
			}

			$('.num-puja', $this).html(pujaPosition--);

			// si la puja es del licitador ocultamos el numero y se mostrará el YO
			if (typeof auction_info.user != 'undefined' && typeof auction_info.user.cod_licit != 'undefined' && puja.cod_licit == auction_info.user.cod_licit) {
				$('.uno', $this).addClass('hidden')
			}
			//Si la puja no es del licitador mostramso el numero del licitador que corresponde
			else {
				$('.yo', $this).addClass('hidden');
				$('.uno', $this).html(licits[puja.cod_licit]);
				$('.uno', $this).attr('data-hint', messages.neutral.puja_corresponde + " " + licits[puja.cod_licit]);
			}
			//si es una sobrepuja debe aparecer la letra A
			if (puja.type_asigl1 != 'A') {
				$('.dos', $this).addClass('hidden');
			}

			if (parseInt(puja.imp_asigl1) >= parseInt(auction_info.lote_actual.impres_asigl0)) {
				$('.price', $this).addClass('verde');
			} else {
				$('.price', $this).addClass('rojo');
			}
			var impPuja = puja.imp_asigl1.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");

			if(frontCurrencies.includes(auction_info.subasta.currency.symbol)){
				$('.price', $this).html(auction_info.subasta.currency.symbol + impPuja );
			}else{
				$('.price', $this).html(impPuja + " " + auction_info.subasta.currency.symbol );
			}
			var fecha = new Date(puja.bid_date.replace(/-/g, "/"));
			var formatted = format_date(fecha)
			$('.date', $this).html(formatted);
			container.append($this);
			//si superamos el valor de reseva
			if (parseInt(min_price_surpass) == parseInt(puja.imp_asigl1)) {
				// se mostrará si esta dentro del rango de numero de pujas visibles o estan todas visibles
				if ($("#view_all_pujas_active").val() == '1' || (num_lot <= view_num_pujas && $("#view_all_pujas_active").val() == '0')) {
					container.append($("#price_min_surpass").clone().removeClass("hidden"));
				} else {
					container.append($("#price_min_surpass").clone());
				}
			}
			num_lot++;
		});
		//mostramso si se ha alcanzado el precio mínimo
		if (min_price_surpass == false) {
			$('.precio_minimo_no_alcanzado').removeClass('hidden');
			$('.precio_minimo_alcanzado').addClass('hidden');
		} else {
			$('.precio_minimo_alcanzado').removeClass('hidden');
			$('.precio_minimo_no_alcanzado').addClass('hidden');
		}
		// mostramos el boton de ver todos los lotrs si es necesario, num_lot siempre lleva un ode mas
		if ((num_lot - 1) > view_num_pujas) {
			container.append($("#view_more").clone().removeClass("hidden"));
			//como al recargar el listado se perdia el valor actual, modifico el valor y simulo una llamada
			if ($("#view_all_pujas_active").val() == '0') {
				$("#view_all_pujas_active").val('1');
			}else{
				$("#view_all_pujas_active").val('0');
			}
			view_all_bids();
		}


	}
}


function action_success(data){
	//si el la accion del usuario actual ha tenido exito
	if (auction_info.lote_actual.tipo_sub == 'O' || auction_info.lote_actual.tipo_sub == 'P' ){
		if(typeof auction_info.user != 'undefined' && auction_info.user.cod_licit == data.cod_licit_actual){
			if (data.status == 'success') {
				gtag('event','click',{'event_category':'puja_nft'});
			}
		}
	}else if(auction_info.lote_actual.tipo_sub == 'W'){
		if(typeof auction_info.user != 'undefined' && auction_info.user.cod_licit == data.cod_licit_actual){
			if (data.status == 'success') {
				gtag('event','click',{'event_category':'puja_nft'});
			}
		}
	}
}
