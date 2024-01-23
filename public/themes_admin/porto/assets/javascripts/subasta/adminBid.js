$(document).on('ready', function () {

	$('#js-deleteSelectedBids').on('click', deleteSelectedBids);
	$('#js-selectAllBids').on('click', selecteAllBids);

	$("#js-dropdownBids").on('show.bs.dropdown', verifyAllSelected);
	$('[name="js-selectAllBids"').on('click', unselectOrSelectAllInputs);
});

function selecteAllBids(event) {
	event.preventDefault();
	const bids = Array.from(document.getElementsByName('bids'));
	bids.forEach((element) => element.checked = true);
}

function deleteSelectedBids(event) {
	event.preventDefault();

	const selectedBids = Array.from(document.getElementsByName('bids'))
		.filter((element) => element.checked)
		.map((element) => {
			return {
				lin : element.value,
				ref: element.dataset.ref,
				instance: element.dataset.instance,
			}
		});

	if(selectedBids.length === 0){
		bootbox.alert("Debes seleccionar al menos una puja");
		return;
	}

	bootbox.confirm("¿Estás seguro de eliminar todas las pujas seleccionadas", function (result) {
		if(!result) return;

		fetch(event.target.href, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({
				bids: selectedBids
			})
		})
		.then(handleToJson)
		.then(data => {
			if(data.success){
				bootbox.alert("Se han eliminado las pujas seleccionadas");
				location.reload();
			}
			else{
				bootbox.alert("Ha ocurrido un error");
			}
		})
		.catch(handleFetchingErrorWithBootbox);
	});
}

function removeBidsSelecteds({ objective, allselected, url, urlwithfilters, title, response }) {

	const valueAllSelected = getValueFromInput(allselected);
	const urlAjax = valueAllSelected ? urlwithfilters : url;
	const ids = !valueAllSelected ? selectedCheckItemsByName(objective) : '';

	bootbox.confirm(title, function (result) {
		if(!result) return;

		$.ajax({
			url: urlAjax,
			type: "post",
			data: makeDataToSendInRemoveOrdersSelecteds(ids),
			success: function(result) {
				saved(result.message);
				location.reload(true);
			},
			error: function(result) {
				error(result.responseJSON.message);
			}
		});

	});
}
