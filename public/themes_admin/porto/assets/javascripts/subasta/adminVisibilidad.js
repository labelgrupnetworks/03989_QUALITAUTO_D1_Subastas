
$(document).ready(function () {

	if($('select[name="sub_visibilidad"]').length && $('select[name="sub_visibilidad"]').val() == ''){
		$('[name="ref_visibilidad"]').prop('disabled', true);
	}
	else{
		$('[name="ref_visibilidad"]').prop('disabled', false);
	}

	$('select[name="sub_visibilidad"]').on('change', event => {
		const value = event.target.value;
		$('[name="ref_visibilidad"]').prop('disabled', !value);
	})

	/* $('select[name="sub_visibilidad"]').on('change', function(event) {

		let value = event.target.value;
		$('select[name="ref_visibilidad"]').prop('disabled', !value);

		if(!!value){
			$('select[name="ref_visibilidad"').select2({
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

	}); */

});
