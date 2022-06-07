actionResponseDesign_O = function(data) {

	/* sirve para Duran boton directo con valor de puja */
	$("#boton_puja_directa_JS").html(data.siguiente);
	$(".js-lot-action_pujar_escalado").attr("value", data.siguiente);

	pricePerMeter({
		target: document.getElementById('acutalPriceMeter'),
		price: data.actual_bid
	})

	actionResponseDesign_W(data);
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

