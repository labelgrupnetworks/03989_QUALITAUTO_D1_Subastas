$(document).ready(function () {


	$('#uploadLotFile').submit(importarExcel);

	$('#select__1__TIPO_SUB').change(tipoSubastaChange);

	tipoSubastaChange();

	$("#img360button").click(function(){
		var frame = prompt("Inserte iframe", "");
		if(frame != null){
			$("#CONTEXTRA_HCES1").val(frame);

		}
	});

	if ($("select[name='PROP_HCES1']").length > 0) {
		$("select[name='PROP_HCES1']").select2({
			placeholder: 'Añade nombre o codigo de cliente',
			minimumInputLength: 3,
			language: "es",
			ajax: {
				url: `/admin/client/list`,
				dataType: 'json',
				delay: 250,
				processResults: function (data) {
					return {
						results: $.map(data, function (item) {
							return {
								text: `${item.cod_cli} - ${item.rsoc_cli}`,
								id: item.cod_cli
							}
						})
					};
				},
				cache: true
			}
		});
	}

});

function tipoSubastaChange(){

	var tipoSubasta = $('#select__1__TIPO_SUB').val();

	if(tipoSubasta == 'O' || tipoSubasta == 'V'){
		$('#fecha__1__DFECORLIC_SUB, #fecha__1__HFECORLIC_SUB, #fecha__1__DHORAORLIC_SUB > input, #fecha__1__HHORAORLIC_SUB > input').prop("disabled", true);
	}
	else{
		$('#fecha__1__DFECORLIC_SUB, #fecha__1__HFECORLIC_SUB, #fecha__1__DHORAORLIC_SUB > input, #fecha__1__HHORAORLIC_SUB > input').prop("disabled", false);
	}

}

function importarExcel(event) {
	event.preventDefault();

	var form = $('#uploadLotFile')[0];
	console.log(form);
	var formData = new FormData();
	formData.append('file', form.file.files[0]);
	formData.append("subasta", form.subasta.value);
	formData.append("_token", form._token);

	$.ajax({
		async: true,
		type: "POST",
		dataType: "html",
		contentType: false,
		processData: false,
		url: form.action,
		data: formData,
		beforeSend: function(data){
			$('.btn.btn-primary').prop('disabled', true);
			$('#div_log').append($('<p>', {'text': 'Cargando lotes...'}));
		},
		success: function (data) {
			saved('datos guardados correctamente');
			$('#div_log').append($('<p>', {'text': 'Lotes cargados'}));
			$('#div_log').append($('<p>', {'text': 'Cargando Imagenes...'}));
			var dataJson = JSON.parse(data);
			imageInBucle(0, dataJson);
		},
		error: function (data) {
			$('.btn.btn-primary').removeAttr('disabled')
			var errors = JSON.parse(data.responseText);
			error(`${errors.message}\n${JSON.stringify(errors.items)}`);
		},
	});
}

function imageInBucle(i, dataJson) {
	var $log = $('#div_log');

	if (dataJson.length == 0 || i >= dataJson.length){

		$('#div_log').append($('<p>', {'text': 'Carga finalizada'}));
		$('.btn.btn-primary').removeAttr('disabled');
		return;
	}

	$.ajax({
		async: true,
		type: 'POST',
		url: '/admin/lote/excelImg',
		data: dataJson[i],
		success: function(data) {
			$log.append(`<p>Imagen del lote ${dataJson[i].idoriginlot} cargada correctamente.</p>`);
		},
		error: function(data) {
			$log.append(`<p class="text-danger">Error al cargar la imagen del lote ${dataJson[i].idoriginlot}<p>`);
		},
		complete: function(data){
			let percent = `${ parseInt((i * 100) / (dataJson.length - 1)) }%`;
			$("#progressBarImg").css("width", percent);
			$("#progressBarValue").text(percent);
			i++;
			newIndex = i;
			imageInBucle(newIndex, dataJson);
		}

	});

	/*for (var i = 0; i < dataJson.length; i++) {
		$.ajax({
			async: false,
			type: 'POST',
			url: '/admin/lote/excelImg',
			data: dataJson[i],
			success: function(data) {
				$(`<span>Imagen ${i} cargada correctamente.</span>`).appendTo($log);
				let percent = `${ (i * 100) / dataJson.length }%`;
				$("#progressBarImg").css("width", percent);
				$("#progressBarValue").text(percent);
			},
			error: function(data) {
				$(`<span>Ha habido un problema inesperado con la imagen ${i}.`).appendTo($log);
				return;
			}
		});
	}*/
}




function borrarImagenLote(item, url) {

	token = $("[name='_token']").val();
	$.post("/admin/lote/borrarImagenLote", { url: url, _token: token }, function (data) {

		$("#imagen" + item).remove();

	});

}

function borrarSubasta(item) {

	token = $("[name='_token']").val();

	bootbox.confirm("¿Estás seguro que quieres borrar esta subasta?", function (result) {

		if (result) {

			$.post("/admin/subasta/borrarSubasta", { item: item, _token: token }, function (data) {

				if (data == "OK") {
					showMessage("Subasta borrada");
					$("#fila" + item).remove();
				}

			});

		}

	});

}


function borrarLote(subasta, num, lin, ref) {

	token = $("[name='_token']").val();

	bootbox.confirm("¿Estás seguro que quieres borrar este lote?", function (result) {

		if (result) {

			$.post("/admin/lote/borrar/" + subasta + "/" + num + "-" + lin + "-" + ref, { _token: token }, function (data) {

				if (data == "OK") {
					showMessage("Sesión eliminada");
					$("#fila" + num + "-" + lin + "-" + ref).remove();
				}

			});

		}

	});

}


function borrarFichero(subasta, item, element) {

	token = $("[name='_token']").val();

	bootbox.confirm("¿Estás seguro que quieres borrar este fichero?", function (result) {

		if (result) {

			$.post("/admin/subasta/borrarFicherosSubasta", { _token: token, subasta: subasta, item: item }, function (data) {
				if (data == "OK") {
					showMessage("Fichero borrado");
					$("#fila" + element).remove();
				}

			});

		}

	});

}


function borrarPuja(items) {

	token = $("[name='_token']").val();

	bootbox.confirm("¿Estás seguro que quieres borrar esta puja?", function (result) {

		if (result) {

			item = items.split("---");
			var asigl0Aux = item[3];
			var subasta = item[2];
			var lin = item[1];
			var ref = item[0];

			$.post("/admin/subasta/borrarPuja", { _token: token, subasta: subasta, ref: ref, lin: lin, asigl0Aux: asigl0Aux }, function (data) {
				if (data == "OK") {
					showMessage("Puja borrada");
					$("#puja---" + lin + "---" + ref).remove();
					$("#ganador---" + ref).remove();
				}

			});
		}
	});

}


function borrarOrden(items) {

	token = $("[name='_token']").val();

	bootbox.confirm("¿Estás seguro que quieres borrar esta orden?", function (result) {

		if (result) {

			item = items.split("---");

			var subasta = item[2];
			var lin = item[1];
			var ref = item[0];

			$.post("/admin/subasta/borrarOrden", { _token: token, subasta: subasta, ref: ref, lin: lin }, function (data) {
				if (data == "OK") {
					showMessage("Orden borrada");
					$("#orden---" + lin + "---" + ref).remove();
				}

			});
		}

	});

}

function borrarSesion(auction, reference, id_auc_session) {

	token = $("[name='_token']").val();

	bootbox.confirm("¿Estás seguro que quieres borrar esta sesión?", function (result) {

		if (result) {

			$.post("/admin/sesion/borrar/" + auction + "/" + reference, { _token: token }, function (data) {

				if (data == "OK") {
					showMessage("Lote borrado");
					$(`#${id_auc_session}`).remove();
				}

			});
		}
	});
}


function exportWithFilters(event, tableConfig, parentFilters) {

	event.preventDefault();

	const tableClientsConfig = JSON.parse(localStorage.getItem(tableConfig));

	if(!tableClientsConfig){
		alert('Necesita configurar la tabla al menos una vez');
	}

	const selects = tableClientsConfig.columns;

	const route = event.target.href;

	const filters = $(`#${parentFilters} form`).serializeArray().filter((item) => item.value != '').reduce((acc, item) => {
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
