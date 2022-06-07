
$(document).on('ready', function () {

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

});



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




