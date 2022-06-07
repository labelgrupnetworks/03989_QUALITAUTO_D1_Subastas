ajax_carousel = function ajax_carousel(key, replace) {
	//$( "#"+key ).siblings().removeClass('hidden');
	$.ajax({
		type: "POST",
		url: "/api-ajax/carousel",
		data: { key: key, replace: replace, size: true },
		success: function (result) {


            if(result.size <= 8){
                $("#navs-arrows").addClass('hidden');
			}


			if (result.contents === '') {
				$("#" + key + '-content').hide()
			}
			$("#" + key).siblings('.loader').addClass('hidden');
			$("#" + key).html(result.contents);
			if (key === 'lotes_recomendados') {
				carrousel_molon_new($("#" + key));
			} else {

				setTimeout(function () {
					carrousel_molon($("#" + key));
				}, 100);

			}

			$('.lazy').Lazy({
				// your configuration goes here
				scrollDirection: 'vertical',
				effect: 'fadeIn',
				effectTime: 100,
				visibleOnly: true,
				onError: function (element) {
					console.log('error loading ' + element.data('src'));
				},
				afterLoad: function (element) {
					$('.text-input__loading--line').hide()
				},
			});
			$('[data-countdown]').each(function () {
				$(this).data('ini', new Date().getTime());
				countdown_timer($(this));
			});
		}

	});

};

carrousel_molon = function carrousel_molon(carrousel) {
	if (carrousel.data('hasSlick')) {
		carrousel.slick('unslick');
	}

	var rows = 2;
	if(window.innerWidth < 1024){
		rows = 1;
	}

	carrousel.slick({
		slidesToScroll: 1,
		rows: rows,
		slidesPerRow: 4,
		/*slidesToShow: 4,*/
		arrows: true,
		prevArrow: $('.fa-chevron-left'),
		nextArrow: $('.fa-chevron-right'),
		responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 3,
					infinite: true,
					dots: true,
					rows: 1,
					slidesPerRow: 3,
				}
			},
			{
				breakpoint: 600,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2,
					rows: 1,
					slidesPerRow: 2,

				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					rows: 1,
					slidesPerRow: 1,
				}
			}

		]
	});

	carrousel.data('hasSlick', true);

};
