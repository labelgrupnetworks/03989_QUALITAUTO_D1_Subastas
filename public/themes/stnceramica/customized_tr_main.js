actionResponseDesign_O = function(data) {

	/* sirve para Duran boton directo con valor de puja */
	$("#boton_puja_directa_JS").html(data.siguiente);
	$(".js-lot-action_pujar_escalado").attr("value", data.siguiente);

	pricePerPiece({
		target: document.querySelector('.acutalPricePerpiece'),
		price: data.actual_bid
	})

	pricePerMeter({
		target: document.getElementById('acutalPriceMeter'),
		price: data.actual_bid
	})

	actionResponseDesign_W(data);
}

function pricePerPiece({target, price}) {
	//comprobar que tengamos pieza y exista el elemento en pantalla
	const pieces = parseFloat(auction_info?.lote_actual?.nobj_hces1) || 0;
	if(!pieces || !target){
		return;
	}

	//obtener el valor del elemento original para operar
	const pricePerPiece = price / pieces;

	//introducir el valor fomateado en el elemento a cambiar
	target.innerHTML = formatMoney({
		money: pricePerPiece,
		symbol: ''
	});
}

function pricePerMeter({target, price}){

	//comprobar que tengamos tamaño y exista el elemento en pantalla
	const width = parseFloat(auction_info?.lote_actual?.ancho_hces1) || 0;
	if(!width || !target){
		return;
	}

	//obtener el valor del elemento original para operar
	const pricePerMeter = price / width;

	//introducir el valor fomateado en el elemento a cambiar
	target.innerHTML = formatMoney({
		money: pricePerMeter,
		symbol: ''
	});
}


$(document).ready(function () {
	/**
	 * Observer
	 * Controlamos cuando se actualiza el valor "siguiente puja"
	 * Para actualizar la siguiente por m2
	 */
	 const $element = $(".siguiente_puja");
	 const observer = new MutationObserver(function (mutations) {
		 mutations.forEach(function (mutation) {

			pricePerPiece({
				target: document.querySelector('.siguiente_puja_perpiece'),
				price: parseInt(auction_info.lote_actual.importe_escalado_siguiente)
			})

			pricePerMeter({
				target: document.querySelector('.siguiente_puja_permeter'),
				price: parseInt(auction_info.lote_actual.importe_escalado_siguiente)
			})

		 });
	 });

	 if ($element.length > 0) {
		 observer.observe($element[0], {
			childList: true
		 });
	 }
});

