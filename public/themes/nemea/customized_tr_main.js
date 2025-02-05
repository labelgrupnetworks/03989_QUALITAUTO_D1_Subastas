/**
 * Reload block bids in online lots detail
 * @param {array} pujas
 * @param {object} licits
 * @param {object} subasta
 */
reloadHistory = function ({ subasta, pujas, licits, importeReserva, minPriceSurpass }) {

	const importeReservaFormatted = new Intl.NumberFormat("de", { minimumFractionDigits: 0, maximumFractionDigits: 2, style: 'currency', currency: 'EUR' }).format(parseFloat(importeReserva));

	const historyList = document.getElementById('pujas_list');
	historyList.innerHTML = '';

	document.getElementById('historial_pujas').classList.toggle('hidden', pujas.length == 0);
	document.getElementById('num_pujas').innerText = pujas.length;

	const viewAllPujasIsActive = document.getElementById('view_all_pujas_active').value == '1';
	const view_num_pujas = document.getElementById('view_num_pujas').value;

	//traducciones necesarias
	const transTextI = document.getElementById('trans_lot_i').value;
	const transTextAuto = document.getElementById('trans_lot_puja_automatica').value;
	const transMinimalPrice = document.getElementById('trans_minimal_price').value;

	//HTMLElement de la linea
	const iElement = `<span class="yo">${transTextI}</span>`;
	const otherElement = (numLicit) => `<span class="otherLicit hint--top hint--medium" data-hint="${messages.neutral.puja_corresponde} ${numLicit}">${numLicit}</span>`;
	const autoElement = ` <span class="dos hint--top hint--medium" data-hint="${transTextAuto}">A</span>`;
	const reservePriceSurpassElement = `<p class="info">${transMinimalPrice} ${importeReservaFormatted}</p>`;

	pujas.forEach((puja, index) => {

		const numLot = index + 1;

		// si la puja es del licitador ocultamos el numero y se mostrará el YO
		const bidderElement = (auction_info?.user?.cod_licit == puja.cod_licit) ? iElement : otherElement(licits[puja.cod_licit]);

		const date = new Date(puja.bid_date.replace(/-/g, "/"));
		const dateFormatted = format_date(date);

		const priceClass = (parseInt(puja.imp_asigl1) >= parseInt(importeReserva)) ? 'winner' : 'loser';

		const currencySimbol = subasta.currency.symbol;
		let pujaFormatted = puja.imp_asigl1.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
		pujaFormatted = frontCurrencies.includes(currencySimbol) ? `${currencySimbol} ${pujaFormatted}` : `${pujaFormatted} ${currencySimbol}`;

		const licitUnit = puja.cod_licit.toString().slice(-1);

		let bidderFlag = `<img width="35" src="/default/img/icons/flags/es.svg" alt="es"></img>`;
		if (typeof puja.codpais_cli != 'undefined' && puja.codpais_cli != null && puja.codpais_cli != '') {
			bidderFlag = `<img src="/default/img/icons/flags/${puja.codpais_cli.toLowerCase()}.svg" alt="${puja.codpais_cli}"></img>`;
		}

		const line = `<p class="hist_item">
					<span class="bidder">Pujador</span>
					<span class="bidder-identifier-container">
						<span class="bidder-identifier" data-licitunit="${licitUnit}" style="--multiplier:${licitUnit}">
							<span class="semi-colon">(</span>
								${bidderElement}${puja.type_asigl1 != 'A' ? '' : autoElement}
							<span class="semi-colon">)</span>
						</span>
						${bidderFlag}
					</span>
					<span class="date">${dateFormatted}</span>
					<span class="price ${priceClass}">${pujaFormatted}</span>
				</p>`;

		historyList.innerHTML += line;

		if (minPriceSurpass && parseInt(minPriceSurpass) == parseInt(puja.imp_asigl1)) {
			historyList.innerHTML += reservePriceSurpassElement;
		}

	});
}
