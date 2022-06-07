
function bajaDeCliente(cliente, status) {

	token = $("[name='_token']").val();

	if (!status) {

		bootbox.confirm("¿Estás seguro que quieres desactivar este cliente?", function (result) {

			if (result) {

				$.post("/admin/cliente/bajaCliente", { cliente: cliente, _token: token }, function (data) {
					if (data == "OK") {
						document.location = document.location;
					}

				});

			}
		});

	}
	else {

		bootbox.confirm("¿Estás seguro que quieres reactivar a este cliente?", function (result) {

			if (result) {

				$.post("/admin/cliente/reactivarCliente", { cliente: cliente, _token: token }, function (data) {
					if (data == "OK") {
						document.location = document.location;
					}

				});

			}
		});

	}
}

function viewPassword(show) {
	$('input[name="password"]').attr('type', show ? 'text' : 'password');
}

window.onload = function () {


	$('.view_password').on('mousedown', (e) => viewPassword(true));
	$('.view_password').on('mouseup', (e) => viewPassword(false));


	$(".js-delete_cli").on('click', function (event) {
		event.preventDefault();

		const id = this.dataset.idorigin;
		if (typeof id == "undefined" || id == '') {
			return;
		}

		bootbox.confirm("¿Estás seguro que quieres borrar este usuario?", function (result) {

			if (result) {
				$.ajax({
					type: "DELETE",
					url: "clientes/" + id,
					processData: false,
					contentType: false,
					success: function (response) {
						window.location.reload()
						saved(response);
					},
					error: function (error) {
						error(error);
					}
				});
			}
		});
	});

	$('select[name=baja_tmp]').on('change', function (event) {

		token = $("[name='_token']").val();
		var id_cli = event.target.closest("tr").id;
		var baja_tmp = event.target.value;

		bootbox.confirm("¿Estás seguro que quieres cambiar el estado este cliente?", function (result) {

			if (result) {

				$.ajax({
					type: "POST",
					url: "/admin/clientes/baja-tmp-cli",
					data: { id_cli, baja_tmp, _token: token },
					success: function (data) {
						saved(data);
					},
					error: function (data) {
						error(data);
					}
				})

			}
		});
	});

	$('[name="registeredname"]').on('blur', function () {
		if ($('[name="name"]').val() == '') {
			$('[name="name"]').val($('[name="registeredname"]').val());
		}
	});

	$('[name="zipcode"]').on('blur', function () {
		var country = $('[name="country"]').val();
		var zip = $(this).val();
		if (country != '') {
			$.ajax({
				type: "POST",
				data: { zip: zip, country: country },
				url: '/api-ajax/cod-zip',
				success: function (msg) {
					if (msg.status == 'success') {
						$('[name="city"]').val(msg.pob);
						$('[name="province"]').val(msg.des_prv);
					}
				}
			});
		}
	});

	$('[name="zipcodeshipping"]').on('blur', function () {
		var country = $('[name="countryshipping"]').val();
		var zip = $(this).val();
		if (country != '') {
			$.ajax({
				type: "POST",
				data: { zip: zip, country: country },
				url: '/api-ajax/cod-zip',
				success: function (msg) {
					if (msg.status == 'success') {
						$('[name="cityshipping"]').val(msg.pob);
						$('[name="provinceshipping"]').val(msg.des_prv);
					}
				}
			});
		}
	});


	$('#clientesExport').on('click', (e) => exportClients(e));

}


function exportClients(event) {

	event.preventDefault();

	const tableClientsConfig = JSON.parse(localStorage.getItem('table_config_clientes'));

	//const selects = [...new Map(Object.entries(tableClientsConfig.columns))].filter(([key, value]) => value && key != "envcat_cli2").map(([key, value]) => key);
	const selects = tableClientsConfig.columns;

	const route = event.target.href;
	//const formData = new FormData(document.querySelector('#filters form'));
	const filters = $('#filters form').serializeArray().filter((item) => item.value != '').reduce((acc, item) => {
		return { ...acc, [item.name]: item.value }
	}, {});

	const data = {
		method: 'POST',
		headers: { 'Content-Type': 'application/json' },
		body: JSON.stringify({ ...filters, selects })
	};


	fetch(route, data)
		.then(res => {
			if(!res.ok){
				throw new Error('Error descarga de excel');
			}
			return res.blob()
		})
		.then(blob => {

			const blobFile = new Blob([blob], {
				type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
			})

			const file = window.URL.createObjectURL(blobFile);
			window.location.assign(file);
		})
		.catch(error => {
			console.log(error);
		});

}
