$(document).ready(function () {

	listaItemsBloque();
	$('.jsChangeStatus').click(estadoBanner);


	$(".sortableBanner").sortable({
		items: $(this).data('child'),
		update: function (event, ui) {

			var productOrder = $(this).sortable('toArray');
			var ubicacion = $("#ubicacion").val();

			$.ajax({
				type: "post",
				url: "/admin/newbanner/orderbanner" ,
				data: {order: productOrder,ubicacion: ubicacion},
				success: function (response) {
					if (response.status == "success") {
						new PNotify({
							title: 'Success',
							text: response.message,
							type: 'success'
						});
					} else {
						new PNotify({
							title: 'Error',
							text: 'Se ha producido un error',
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

			/*
			var productOrder = $(this).sortable('toArray');
			var isCategory = $(this)[0].dataset.iscategory;
			saveOrder(productOrder, isCategory);
			*/
		}
	});

});




function listaItemsBloque() {

	$(".bannerItems").each(function (index) {

		id = $("#id").val();
		token = $("#_token").val();


		$.post("/admin/newbanner/listaItemsBloque", { id: id, index: index, _token: token }, function (data) {

			$("#bannerItems" + index).html(data);
		});

	})
}

function nuevoItemBloque(id, index) {

	token = $("#_token").val();

	$.post("/admin/newbanner/nuevoItemBloque", { id: id, index: index, _token: token }, function (data) {
		listaItemsBloque();
	});

}

function editaItemBloque(id) {

	token = $("#_token").val();
	$.post("/admin/newbanner/editaItemBloque", { id: id, _token: token }, function (data) {
		showMessage(data);
		$(".lenguajeES").show(function () {
			$('.summernote').summernote({
				height: 150
			});

			$('#btnGuardarItem').on('click', (e) => {
				e.preventDefault;
				guardaItemBloque();
			})
		});

	});

}

function guardaItemBloque() {

	const formulario = document.getElementById('formLenguaje');

	for (const element of document.querySelectorAll('textarea[name^="texto"]')) {
		if($(element).summernote('isEmpty')){
			element.value = "";
		}
	}

	if (check_form(formulario)) {
		formulario.submit();
		return true;
	}
}

function borraItemBloque(id) {

	token = $("#_token").val();
	$.post("/admin/newbanner/borraItemBloque", { id: id, _token: token }, function (data) {
		listaItemsBloque();
	});

}

function estadoBanner(id) {

	var button = this;
	var activo = 1;
	if (button.getAttribute("estado") == 'on') {
		activo = 0;
	}

	var id = this.id;

	$.ajax({
		url: "/admin/newbanner/activar",
		type: "post",
		data: { "activo": activo, "id": id },
		success: function () {

			if (activo == 0) {
				button.setAttribute("estado", "off");
				button.setAttribute("title", "Activar");
				$(button).removeClass("btn-success");
				$(button).addClass("btn-danger");
			}
			else {
				button.setAttribute("estado", "on");
				button.setAttribute("title", "Desactivar");
				$(button).removeClass("btn-danger");
				$(button).addClass("btn-success");
			}

			saved("Modificado");

		},
		error: function () {
			error("Error en servidor, contacte con equipo técnico");
		}
	});
}

function activaItemBloque(id) {
	estadoItemBloque(id, 1);
}

function desactivaItemBloque(id) {
	estadoItemBloque(id, 0);
}

/**
 * Activa o desactiva in item banner
 * @param {number} id identificador
 * @param {number} estado tinyint activo desactivo
 */
function estadoItemBloque(id, estado) {
	token = $("#_token").val();
	$.post("/admin/newbanner/estadoItemBloque", { id: id, _token: token, activo: estado }, function (data) {
		listaItemsBloque();
	});
}

function editar_run() {

	info = $("#editBanner").serialize();

	$.post("/admin/newbanner/editar_run", info, function (data) {
		if (data == "OK") {
			showMessage("Datos guardados", "");
		} else {
			showMessage("No se han podido guardar los datos", "ERROR");
		}
	});

}

function vista_previa(key) {

	token = $("#_token").val();

	$.post("/admin/newbanner/vistaPrevia", { key: key, _token: token }, function (data) {

		showMessage("", "Vista previa");
		setTimeout(function () {
			$(".modal-body").html(data);
		}, 500);

	});

}

function change_lang(lang) {
	$('.langs').hide();

	$('.lenguaje' + lang).show(function () {

		$('#' + lang + 'button').click(function () {

			$('.lenguaje' + lang).show(function () {

				$('.summernote').summernote({
					tabsize: 1,
					height: 150
				});

			});

		});

	});
}

function saved(text) {
	notify('Saved', text, 'success');
}

function error(text) {
	notify('Error', text, 'error');
}

function notify(title, text, type){
	new PNotify({
		title: title,
		text: text,
		type: type,
		animateSpeed: 'fast',
		delay: 1000
	});
}
