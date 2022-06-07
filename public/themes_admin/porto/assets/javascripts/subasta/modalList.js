let lists = {
	'idauction': [],
	'ref' : [],
	'licit': []
};
const idModal = 'list_modal';
let idTarget = '';

$(document).ready(function () {

	$(`[id$='_delete']`).each(function(){

		$(this).click(function(){
			let id = this.id.split("_")[0];
			$(`[id$='${id}']`).val('');
			$(`[id$='${id}_name']`).val('');
		})
	});

	$(`#${idModal}`).on('show.bs.modal', function (e) {

		$(`#${idModal}_select`).empty();

		id = $(e.relatedTarget).data("list");

		if (lists[id].length != 0) {
			listOptions(lists[id]);
			return;
		}

		cargarSelect(id);

		//comportamiento al seleccionar y guardar dentro del modal
		$(`#${idModal} #seleccionar`).click(function (e) {
			e.preventDefault();

			if(id == 'idauction'){

				lists['ref'] = [];
				lists['licit'] = [];

				$(`[id$='ref']`).val('');
				$(`[id$='ref_name']`).val('');

				$(`[id$='licit']`).val('');
				$(`[id$='licit_name']`).val('');
			}

			var text = $(`#${idModal}_select`)[0].selectedOptions[0].text;
			var val = $(`#${idModal}_select`).val();

			$(`[id$='${id}']`).val(val);
			$(`[id$='${id}_name']`).val(text);

			$(`#${idModal}`).modal("hide");
		});


	});

});

function cargarSelect(id) {

	$('.loader-spinner').hide();

	var options = listData(id);

	if (options === false) {
		alert("Es necesario seleccionar antes una subasta");
		return;
	}

	$.ajax({
		async: true,
		type: "GET",
		dataType: "json",
		contentType: false,
		processData: false,
		url: `/admin/${options['recurso']}/list?${options['data']}`,
		beforeSend: function (data) {
			$('.loader-spinner').show();
		},
		success: function (data) {
			listOptions(data);
			lists[id] = data;
			$('.loader-spinner').hide();
		},
		error: function (errors) {
			alert(errors);
			$('.loader-spinner').hide();
		},
	});
}


function listData(recurso) {

	var options = [];
	options['recurso'] = '';
	options['data'] = '';


	switch (recurso) {
		case 'idauction':
			options['recurso'] = 'subasta';
			return options;

		case 'ref':

			var idauction = $(`#texto__1__idauction`).val();
			if (idauction.trim() == '') {
				return false;
			}
			options['recurso'] = 'lote';
			options['data'] = `idauction=${idauction}`;
			return options

		case 'licit':

			var idauction = $(`#texto__1__idauction`).val();
			if (idauction.trim() == '') {
				return false;
			}
			options['recurso'] = 'licit';
			options['data'] = `idauction=${idauction}`;
			return options

		default:
			return false;
	}
}

function listOptions(data) {

	var options = $();

	for (const value of data) {
		options = options.add(
			$('<option>', {
				'value': value.id,
				'html': value.html
			})
		);
	}

	$(`#${idModal}_select`).append(options);
}

