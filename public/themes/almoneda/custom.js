function ajax_lotes_destacados_grid(key, replace, lang) {
	//$( "#"+key ).siblings().removeClass('hidden');
	$.ajax({
		type: "POST",
		url: "/api-ajax/lots-destacados-grid",
		data: { key: key, replace: replace, lang: lang },
		success: function (result) {

			if (result === '') {
				$("#" + key + '-content').hide();
			}
			$("#" + key).siblings('.loader').addClass('hidden');
			$("#" + key).html(result);
			//cargar cuenta atras
			$('[data-countdown]').each(function (event) {

				var countdown = $(this);
				countdown.data('ini', new Date().getTime());
				countdown_timer(countdown);


			});

		}

	});

};
