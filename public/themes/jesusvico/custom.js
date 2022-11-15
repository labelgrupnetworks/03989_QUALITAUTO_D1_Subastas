project = {
	...project,
	theme: 'jesusvico',
	version: 2
};

$(function () {
	$('#js-show-filters, #js-hide-filters').on('click', resizeGridBanner);

	createObservers('.observer-animation-bottom');
})

function createObservers(elementSelector) {

	const targets = [...document.querySelectorAll(elementSelector)];
	if(targets.length === 0){
		return;
	}

	const options = {
		rootMargin: '0px',
		threshold: 0.8
	}

	const observer = new IntersectionObserver(handleIntersect, options);

	targets.forEach(target => {
		observer.observe(target);
	});
}

function handleIntersect(entries, observer) {
	entries.forEach((entry) => {
		if (entry.isIntersecting && entry.target.classList.contains('opacity-0')) {
			entry.target.classList.remove('opacity-0');
			entry.target.classList.add('lb-fadeIn')
			entry.target.classList.add('lb-fadeInBottom');
		}
	});
}

function scrollToElement(element, miliseconds) {
	const speed = miliseconds ?? 600;
	$('html,body').animate({
		scrollTop: $(element).offset().top - 70
	}, speed);
}


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
