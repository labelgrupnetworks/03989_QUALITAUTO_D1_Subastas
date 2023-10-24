
$(document).on('ready', function () {

	$('[name=starthour], [name=endhour]').on('blur', function (e) {
		let element = e.target;

		if (element.value.length == '5') {
			element.value = element.value + ':00';
		}

	});

	//Convertir imagenes de iphone a jpg
	$('[name="images[]"]').on('change', e => convertHeicToJpg(e.target));

	$('[name=startprice]').on('input', e => document.getElementById('biddercommission_importe').value = calculateCommision(document.querySelector('[name=biddercommission]'), OPERATION_TYPES.PERCENTATGE));
	$('[name=biddercommission]').on('input', e => document.getElementById('biddercommission_importe').value = calculateCommision(e.target, OPERATION_TYPES.PERCENTATGE));
	$('#biddercommission_importe').on('input', e => document.querySelector('[name=biddercommission]').value = calculateCommision(e.target, OPERATION_TYPES.PERCENTATGE_INVERSE));

	$('[name=ownercommission]').on('input', (e) => document.getElementById('ownercommission_importe').value = calculateCommision(e.target, OPERATION_TYPES.PERCENTATGE));
	$('#ownercommission_importe').on('input', (e) => document.querySelector('[name=ownercommission]').value = calculateCommision(e.target, OPERATION_TYPES.PERCENTATGE_INVERSE))

	$('#js-nft-unpublish').on('click', unpublishNft);

	$('.js-actionSelectedLots').on('click', actionSelectedLots);
	$('#js-selectAllLots').on('click', selectedAllLots);


	$(".js-send_webservice_close_lot").on('click', function (event) {
		event.preventDefault();


		const sub = this.dataset.sub;
		const ref = this.dataset.ref;



		bootbox.confirm("¿Estás seguro que quieres enviar el lote cómo finalizado a la casa de subastas?", function (result) {
			token = $("[name='_token']").val();
			if (result) {
				$.ajax({
					type: "POST",
					url: "/admin/lote/send_end_lot_ws",
					data: { sub, ref,  _token: token },
					success: function (response) {
						bootbox.alert('Se ha enviado el lote cómo finalizado  a través del webservice');
					},
					error: function (error) {
						bootbox.alert('Ha ocurrido un error al enviar el lote como finalizado a través del webservice');
					}
				});
			}
		});
	});

});

const OPERATION_TYPES = {
	PERCENTATGE: (a, b) => a * b * 0.01,
	PERCENTATGE_INVERSE: (a, b) => (a * 100) / b
}

/**
 * @param {HTMLElement} thisElement
 * @param {Function} operationType
 * @returns {number}
 */
const calculateCommision = (thisElement, operationType) => {

	const initialValue = parseInt(thisElement.value);
	const startPrice = parseInt(document.querySelector('[name=startprice]').value);

	if (isNaN(initialValue) || isNaN(startPrice)) {
		return 0;
	}

	return parseInt(operationType(initialValue, startPrice));
}

/**
 * Esta sera la funcion buena
 * @param {HTMLElement} element
 */
const convertHeicToJpg = async (element) => {

	//obtenemos el array de archivos
	const files = [...element.files];

	//Filtramos solo los de tipo heic
	const filesHeic = files.filter((file) => file.name.toUpperCase().includes(".HEIC"));

	//Si no existen, no tenemos que hacer nada
	if (!filesHeic.length) {
		return;
	}

	try {

		//Inicio spinner
		$('#loadMe').data('bs.modal', null);
		$("#loadMe").modal({
			backdrop: 'static', //remove ability to close modal with click
			keyboard: false, //remove option to close with keyboard
			show: true //Display loader!
		});

		//Creamos dataTransder para envolver file
		const container = new DataTransfer();

		for (const file of filesHeic) {

			//convertimos imagen a jpg
			let conversionResult = await heic2any({ blob: file, toType: "image/jpg" });

			//Lo envolvemos en objeto File
			let newFile = new File([conversionResult], `${file.name.slice(0, -5)}.jpg`, { type: "image/jpeg", lastModified: new Date().getTime() });

			//Añadimos los files al dataTransfer
			container.items.add(newFile);
		}

		element.files = container.files;

	} catch (error) {
		console.log(error)
	}

	//fin spinner
	$("#loadMe").modal('hide');
}

function unpublishNft(event) {

	event.preventDefault();

	bootbox.confirm("¿Estás seguro que quieres despublicar el NFT?", function (result) {
		if(result){
			document.location = event.target.href;
		}
	});
}

function actionSelectedLots(event) {
	event.preventDefault();

	const lots = selectedCheckItemsByName('lote');

	if(lots.length === 0){
		bootbox.alert("Debes seleccionar al menos un lote");
		return;
	}

	bootbox.confirm(event.target.dataset.title, function (result) {
		if(!result) return;

		fetch(event.target.href, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({lots})
		})
		.then(handleToJson)
		.then(data => {

			if(data.success){
				bootbox.alert(event.target.dataset.respuesta);
				location.reload();
			}
			else if(data.status === 'success'){
				console.log(data);
				bootbox.alert(data.message);
			}
			else{
				bootbox.alert("Ha ocurrido un error");
			}
		})
		.catch(handleFetchingErrorWithBootbox);
	});

}
/*
function removeStockSelectedLots(event) {
	event.preventDefault();

	const lots = selectedCheckItemsByName('lote');

	if(lots.length === 0){
		bootbox.alert("Debes seleccionar al menos un lote");
		return;
	}

	bootbox.confirm("¿Estás seguro de quitar el stock en todos los lotes seleccionados", function (result) {
		if(!result) return;

		fetch(event.target.href, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({lots})
		})
		.then(handleToJson)
		.then(data => {

			if(data.success){
				bootbox.alert("Se ha quitado el stock a los lotes seleccionados");
				location.reload();
			}
			else{
				bootbox.alert("Ha ocurrido un error");
			}
		})
		.catch(handleFetchingErrorWithBootbox);
	});

}
*/
function selectedAllLots(event) {
	event.preventDefault();
	const lots = Array.from(document.getElementsByName('lote'));

	lots.forEach((element) => element.checked = true);
}
