
$(document).ready(function () {

	if($('select[name="subasta"]').length && $('select[name="subasta"]').val() == ''){
		$('select[name="lote"]').prop('disabled', true);
	}

	$('select[name="subasta"]').on('change', function(event) {

		let value = event.target.value;
		$('select[name="lote"]').prop('disabled', !value);

		if(!!value){
			$('select[name="lote"').select2({
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


	$('#awardExport').on('click', (e) => exportWithFilters(e, 'table_config_tableAwards', 'awardsFilters'));
	$('#notAwardExport').on('click', (e) => exportWithFilters(e, 'table_config_tableNotAwards', 'notAwardsFilters'));

});

function deleteAward(button) {

	const idauction = $(button).data('idauction');
	const ref = $(button).data('ref');
	const licit = $(button).data('licit');

	bootbox.confirm("¿Estas seguro de que quieres eliminar la adjudicación?", function (result) {
        if (result) {

			var token = $("[name='_token']").val();
            $.ajax({
                type: "POST",
                url: '/admin/award/delete',
                data: {
					_token: token,
					idauction: idauction,
					ref: ref,
					licit: licit
				},
                success: function (response) {

					response = JSON.parse(response);

                    if (response.status == "SUCCESS") {
						$("#awardsFilters form").trigger("submit");
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
