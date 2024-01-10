$(document).ready(function(){

	$('#send_form_ficha').on('click', function(e){

		e.stopPropagation();
		$.magnificPopup.close();

		let maxSize = 2000;
		var formData = new FormData();
		$.each($("input[type='file']")[0].files, function(i, file) {

			let sizeFileInKb = file.size / 1024;
			if(sizeFileInKb > maxSize){
				$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				$("#insert_msg_title").html("");
				$("#insert_msg").html($('.form-authorize small').text());
				throw $('.form-authorize small').text();
			}

			formData.append('file[]', file);
		});

		formData.append('nom',  $('input[name="nom"]').val());
		formData.append('representar', $('select[name="representar"]').val());
		formData.append('nom_rsoc', $('input[name="nom_rsoc"]').val());
		formData.append('cod_sub', $('input[name="cod_sub"]').val());
		formData.append('ref', $('input[name="ref"]').val());

		$.ajax({
			async: true,
			type: "POST",
			dataType: "html",
			contentType: false,
			processData: false,
			url: "/api-ajax/enviar-formulario-pujar",
			data: formData,
			success: function (response) {
				$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				$("#insert_msg_title").html("");
				$("#insert_msg").html(response);

			},
			error: function (error) {
				$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				$("#insert_msg_title").html("");
				$("#insert_msg").html(error.responseText);
			},
		});

	});

});
