carrousel_molon = function (carrousel) {
	if (carrousel.data('hasSlick')) {
		carrousel.slick('unslick');
	}

	var rows = 1;
	/*Si se añaden más de una fila, estas no cambian al reducir pantalla
	//Establecer desde el inicio
	if(window.innerWidth < 1024){
		rows = 1;
	}*/

	/**
	 * Si se utilizan más de un row, se tiene en cuenta slidesPerRow
	 * En caso de usar un solo row, se utiliza slidesToShow
	 * Utilizar los dos, crea conflictos...
	 */

	carrousel.slick({
		slidesToScroll: 1,
		rows: rows,
		/*slidesPerRow: 4,*/
		slidesToShow: 5,
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
}
