
function action_success(data){
	//si el la accion del usuario actual ha tenido exito
	if (auction_info.lote_actual.tipo_sub == 'O' || auction_info.lote_actual.tipo_sub == 'P' ){
		if(typeof auction_info.user != 'undefined' && auction_info.user.cod_licit == data.cod_licit_actual){
			if (data.status == 'success') {
				gtag('event','click',{'event_category':'puja'});
			}
		}
	}else if(auction_info.lote_actual.tipo_sub == 'W'){
		if(typeof auction_info.user != 'undefined' && auction_info.user.cod_licit == data.cod_licit_actual){
			if (data.status == 'success') {
				gtag('event','click',{'event_category':'puja_live'});
			}
		}
	}
}
