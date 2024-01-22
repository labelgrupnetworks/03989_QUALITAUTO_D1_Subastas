
$(document).on('ready', function () {

	$('#js-deleteSelectedOrders').on('click', deleteSelectedOrders);
	$('#js-selectAllOrders').on('click', selecteAllOrders);

	if($('select[name="idauction"]').length && $('select[name="idauction"]').val() == ''){
		$('select[name="ref"]').prop('disabled', true);
	}

	$('select[name="idauction"]').on('change', function(event) {

		let value = event.target.value;
		$('select[name="ref"]').prop('disabled', !value);

		if(!!value){
			$('select[name="ref"').select2({
				placeholder: '',
				minimumInputLength: 3,
				language: 'es',
				ajax: {
					url: `/admin/subastas/${value}/lotes/select2list`,
					dataType: 'json',
					delay: 250,
					processResults: function (data) {
						return {
							results: $.map(data, function (item) {
								return {
									text: item.id + ' - ' + item.html,
									id: item.id
								}
							})
						};
					},
					cache: true
				}
			});
		}

	});


	$(".js-send_webservice_order").on('click', function (event) {
		event.preventDefault();


		const sub = this.dataset.sub;
		const ref = this.dataset.ref;
		const codcli = this.dataset.codcli;
		const imp = this.dataset.imp;


		bootbox.confirm("¿Estás seguro que quieres enviar esta orden al webservice de la casa de subastas?", function (result) {
			token = $("[name='_token']").val();
			if (result) {
				$.ajax({
					type: "POST",
					url: "/admin/orders/send_ws",
					data: { sub, ref, codcli, imp,  _token: token },
					success: function (response) {
						bootbox.alert('Se ha enviado la orden a través del webservice');
					},
					error: function (error) {
						bootbox.alert('Ha ocurrido un error al enviar la orden a través del webservice');
					}
				});
			}
		});
	});

	$("#js-dropdownOrders").on('show.bs.dropdown', verifyAllSelected);
	$('[name="js-selectAllOrders"').on('click', unselectOrSelectAllInputs);
});

function deleteSelectedOrders(event) {
	event.preventDefault();

	const selectedOrders = Array.from(document.getElementsByName('orders'))
		.filter((element) => element.checked)
		.map((element) => {
			return {
				licit : element.dataset.licit,
				ref: element.value
			}
		});

	if(selectedOrders.length === 0){
		bootbox.alert("Debes seleccionar al menos una orden");
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
				orders: selectedOrders
			})
		})
		.then(handleToJson)
		.then(handleDeleteOrdersResponse)
		.catch(handleFetchingErrorWithBootbox);
	});
}

function handleDeleteOrdersResponse(response) {
	if(!response.success){
		bootbox.alert("Ha ocurrido un error");
		return;
	}

	const errorsMessage = (errors) => `
		<div>
			<p>Se ha producido un error en las siguientes ordenes:</p>
			<ul>
				${errors.map((element) => `<li>Refererencia: ${element.ref}, Licitador: ${element.licit}</li>`).join('')}
			</ul>
		</div>`;

	const whenErrors = response.results.filter((element) => element.status == 'ERROR');
	const responseMessage = whenErrors.length > 0
		? errorsMessage(whenErrors)
		: `<p>Se han eliminado correctamente las ordenes seleccionadas</p>`;

	bootbox.alert(responseMessage);
	location.reload();
}



function selecteAllOrders(event) {
	event.preventDefault();
	const bids = Array.from(document.getElementsByName('orders'));
	bids.forEach((element) => element.checked = true);
}

function deleteOrder(idauction, ref, licit) {

	bootbox.confirm("¿Estas seguro de que quieres eliminar este registro?", function (result) {
        if (result) {

			var token = $("[name='_token']").val();
            $.ajax({
                type: "DELETE",
                url: `/admin/orders/${idauction}`,
                data: {
					_token: token,
					idauction: idauction,
					ref: ref,
					licit: licit
				},
                success: function (response) {

					response = JSON.parse(response);

                    if (response.status == "SUCCESS") {

						location.reload();

                    } else {
                        new PNotify({
                            title: 'Error',
                            text: response.message,
                            type: 'danger'
                        });
                    }
                },
                error: function (response) {
                    new PNotify({
                        title: 'Error',
                        text: 'Se ha producido un error',
                        type: 'danger'
                    });
                }
            });
        }
    });

	return;
}


function makeDataToSendInRemoveOrdersSelecteds(ids) {
	let data = {};
	data['_token'] = $('[name="_token"]').val();
	data['auc_id'] = getValueFromInput('auc_id');
	data['ids'] = ids;
	const searchParams = getSearchParams();
	for (const [key, value] of Object.entries(searchParams)) {
		data[key] = value;
	}
	return data;
}

function removeOrdersSelecteds({ objective, allselected, url, urlwithfilters, title, response }) {

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



