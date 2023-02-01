$(document).ready(function () {

	listaItemsBloque();
	$('.jsChangeStatus').on('click', estadoBanner);


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

	const request = {
		_token: $("#_token").val(),
		key: $("[name=nombre]").val(),
		id,
		index
	}

	$.post("/admin/newbanner/nuevoItemBloque", request, (response) => listaItemsBloque());
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

	const request = {
		_token: $("#_token").val(),
		key: $("[name=nombre]").val(),
		id
	}

	$.post("/admin/newbanner/borraItemBloque", request, (response) => listaItemsBloque());
}

/**
 * @param {Event} event
 * @returns
 */
function estadoBanner(event) {

	const button = event.currentTarget;
	const id = button.dataset.id;
	const key = button.dataset.key;
	const isActive = button.getAttribute("estado") === 'on' ? 0 : 1;

	const request = {
		activo: isActive,
		id,
		key
	};

	$.ajax({
		url: "/admin/newbanner/activar",
		type: "post",
		data: request,
		success: () => {
			button.setAttribute('estado', isActive ? 'on' : 'off');
			button.setAttribute('title', isActive ? 'Desactivar' : 'Activar');
			button.classList.toggle('btn-danger', !isActive);
			button.classList.toggle('btn-success', isActive);

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

	const request = {
		_token: $("#_token").val(),
		key: $("[name=nombre]").val(),
		activo: estado,
		id
	}

	$.post("/admin/newbanner/estadoItemBloque", request, (response) => listaItemsBloque());
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
