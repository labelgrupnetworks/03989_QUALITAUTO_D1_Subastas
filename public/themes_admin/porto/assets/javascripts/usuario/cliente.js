/** prueba para crear url encriptada con filtros, lo mantengo para ver que hago con esto
$(() => {
	document.getElementById('appliFilters')?.addEventListener('click', (event) => {
		const filtersElement = document.querySelectorAll('.filter');

		const filters = [];
		filtersElement.forEach((filterElement) => {
			filters.push({
				field: filterElement.querySelector('[name=field]').value,
				operation: filterElement.querySelector('[name=operation]').value,
				value: filterElement.querySelector('[name=value]').value,
			})
		});

		const filtersJson = JSON.stringify(filters);
		const filtersHash = utf8_to_b64(filtersJson);

		let url = new URL(document.location);
		url.searchParams.set('filters', filtersHash);

		window.location.href = url.href;
	});
})

function utf8_to_b64(str) {
	return window.btoa(unescape(encodeURIComponent(str)));
}
*/

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
			if (!res.ok) {
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

document.getElementById('clientFile').addEventListener('change', updateFiles);
function updateFiles(event) {
	const files = event.target.files;

	//if no files, return
	if (files.length == 0) {
		return;
	}

	const formData = new FormData();
	//append all files to formData
	for (let i = 0; i < files.length; i++) {
		formData.append('files[]', files[i]);
	}

	const route = document.getElementById('store_file_route').value;
	const notify = loadNotify('Subiendo archivos...');

	fetch(route, {
		method: 'POST',
		body: formData
	})
	.then(res => {
		if (!res.ok) {
			throw new Error('Error al subir los archivos');
		}
		return res.json()
	})
	.then(data => {
		if (data.status != 'success') {
			throw new Error(data.message);
		}
		renderTableBody(data.files);

		$(event.target).val('');

		saved('Archivos subidos correctamente');
	})
	.catch(err => error(err))
	.finally(() => notify.remove());
}

function renderTableBody(files){

	$('#bodyTableFile').empty();
	$('#bodyTableFile').append(files.map((file) => {
		const row = $('<tr></tr>')
			.append($('<td></td>').append(
				$('<a></a>')
					.attr('href', file.link)
					.attr('target', '_blank')
					.text(file.name)
			))
			.append($('<td></td>').text(file.size_kb))
			.append($('<td></td>').text(file.last_modified_human))
			.append($('<td></td>').append(
				$('<button></button>')
					.addClass('btn btn-xs btn-danger')
					.attr('type', 'button')
					.text('Eliminar')
					.on('click', () => deleteFile(file.unlink))
			));
		return row;
	}));
}

function deleteFile(route) {

	const notify = loadNotify('Eliminando archivo...');
	fetch(route, {
		method: 'DELETE',
		headers: { 'Content-Type': 'application/json' },
		body: JSON.stringify({
			_token: document.querySelector('[name="_token"]').value
		 })
	})
	.then(res => {
		if (!res.ok) {
			throw new Error('Error al eliminar el archivo');
		}
		return res.json()
	})
	.then(data => {
		if (data.status != 'success') {
			throw new Error(data.message);
		}

		renderTableBody(data.files);
		saved('Archivo eliminado correctamente');
	})
	.catch(err => error(err))
	.finally(() => notify.remove());
}

function loadNotify(message = 'Cargando...') {
	return new PNotify({
		title: message,
		type: 'info',
		textTrusted: true,
		icon: 'fa fa-spin fa-spinner',
		hide: false,
		destroy: true,
		closer: false,
		sticker: false
	});
}
