
$(document).ready(function () {

	//Mostrar tabla de adjudicaciones
	$("#whereLicits").submit(function (event) {
		event.preventDefault();

		$.ajax({
			async: true,
			type: "GET",
			dataType: "json",
			data: $('#whereLicits').serialize(),
			contentType: false,
			processData: false,
			url: $('#whereLicits').attr('action'),
			beforeSend: function (data) {
				$(".submitButton").text("Buscando...");
				$('#tableLicits').dataTable().fnClearTable();
				$('#tableLicits').DataTable().destroy();

			},
			success: function (data) {

				console.log(data);
				$(".submitButton").text("Buscar");
				$('#tableLicits').DataTable({
					data: data,
					columns: [
						{ data: "cli_licit" },
						{ data: "cod2_cli" },
						{ data: "cod_licit" },
						{ data: "rsoc_cli" },
						/* Botón para editar
						{
							data: 'sub_licit cli_licit',
							render: function (data, type, row, meta) {
								data = `<a href="/admin/licit/edit?idauction=${row.sub_licit}&&licit=${row.cli_licit}" class="editor_edit btn btn-success">Edit</a>`;
								return data;
							}
						}
						*/
					]
				});

			},
			error: function (errors) {
				$(".submitButton").text("Buscar");
				console.log(errors);
				alert(errors.responseText);
			},
		});

		return;

	});


});

