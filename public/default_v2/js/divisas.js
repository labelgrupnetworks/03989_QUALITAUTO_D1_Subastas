$(function() {
	loadDivisa();
	$("#currencyExchange").on("change",function(){
		loadDivisa();
	});

	/*
	$("#bid_modal_pujar").on("keyup", function(){
		showDivisaInLabel($("#bid_modal_pujar").val(),"inputBidExchange_JS");
		console.log("hola keup");
	});
*/










});

function loadDivisa(){
	//comprobamos que exista el selector de divisas, si no es que no tiene contratado el cambi ode divisas
	if(typeof $("#currencyExchange").val() != 'undefined'){
		var currencyExchange = $("#currencyExchange").val();
		changeCurrency(auction_info.lote_actual.impsalhces_asigl0,currencyExchange,"startPriceExchange_JS");
		changeCurrency(auction_info.lote_actual.actual_bid, currencyExchange,"actualBidExchange_JS");
		changeCurrency( auction_info.lote_actual.importe_escalado_siguiente, currencyExchange,"nextBidExchange_JS");

		changeCurrency( auction_info.lote_actual.imptas_asigl0, currencyExchange,"estimateExchange_JS");

		if(typeof  $("#tuorden").html() != 'undefined'){
			changeCurrency( $("#tuorden").html().toString().replaceAll(".", ""), currencyExchange,"yourOrderExchange_JS");
		}
		if(typeof  $("#startPriceDirectSale").val() != 'undefined'){
			changeCurrency( $("#startPriceDirectSale").val(), currencyExchange,"directSaleExchange_JS");
		}

		document.querySelectorAll('.custom-exchange').forEach((element) => {
			changeCurrencyWithElement(element.dataset.value, currencyExchange, element);
		});

	}
}



