project = {
	...project,
	theme: 'jesusvico',
	version: 2
};

$(function () {
	$('#js-show-filters, #js-hide-filters').on('click', resizeGridBanner);
})

carrousel_molon_new = function (carrousel) {
	if (carrousel.data('hasSlick')) {
		carrousel.slick('unslick');
	}

	const breackPointShow = (number) => {
		return {
			slidesToShow: number,
			slidesToScroll: number,
			slidesPerRow: number,
		}
	};

	const options = {
		slidesToScroll: 1,
		rows: 1,
		slidesToShow: 6,
		arrows: false,
		swipeToSlide: true,
		dots: false,
		infinite: true,
		autoplay: true,
		responsive: [
			{ breakpoint: 1200, settings: breackPointShow(4) },
			{ breakpoint: 1024, settings: breackPointShow(3) },
			{ breakpoint: 600, settings: breackPointShow(2) },
			{ breakpoint: 480, settings: breackPointShow(1) }
		]
	}

	carrousel.slick(options);
	carrousel.data('hasSlick', true);
}

function resizeGridBanner() {
	const slickBanner = $('.banner_lotes').slick('getSlick');
	slickBanner.refresh();
}
