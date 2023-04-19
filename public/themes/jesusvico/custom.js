project = {
	...project,
	theme: 'jesusvico',
	version: 2
};

$(function () {
	$('#js-show-filters, #js-hide-filters').on('click', resizeGridBanner);

	createObservers();


	const btnToTop = document.querySelector('#btn-to-top');

	window.addEventListener('scroll', () => {
		const scrollHeight = window.pageYOffset;
		const pageHeight = document.documentElement.scrollHeight;
		const thirdHeight = pageHeight / 3;

		if (scrollHeight > thirdHeight) {
			btnToTop.classList.add('show');
		} else {
			btnToTop.classList.remove('show');
		}
	});

	btnToTop.addEventListener('click', () => {
		window.scrollTo({
			top: 0,
			behavior: 'smooth'
		});
	});
})

function createObservers() {

	const observerTypes = ['.observer-animation-bottom', '.observer-animation-left', '.observer-animation-right'];

	const targets = [...document.querySelectorAll(observerTypes.toString())];
	if (targets.length === 0) {
		return;
	}

	const options = {
		rootMargin: '0px',
		threshold: 0.8
	}
	const observer = new IntersectionObserver(handleIntersect, options);
	targets.forEach(target => observer.observe(target));
}

function handleIntersect(entries, observer) {

	const observers = {
		'observer-animation-bottom': 'lb-fadeInBottom',
		'observer-animation-left': 'lb-fadeInLeft',
		'observer-animation-right': 'lb-fadeInRight',
	};

	entries.forEach((entry) => {

		const { isIntersecting, target } = entry;
		const { classList } = target;

		if (isIntersecting && classList.contains('opacity-0')) {
			const animation = [...classList].find((className) => Object.keys(observers).includes(className));
			classList.remove('opacity-0');
			classList.add('lb-fadeIn')
			classList.add(observers[animation]);
		}
	});
}

function scrollToElement(element, miliseconds) {
	const speed = miliseconds ?? 600;
	$('html,body').animate({
		scrollTop: $(element).offset().top - 70
	}, speed);
}


carrousel_molon_new = function (carrousel, newOptions = {}) {
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
	};

	carrousel.slick({ ...options, ...newOptions });
	carrousel.data('hasSlick', true);
}

function resizeGridBanner() {
	const slickBanner = $('.banner_lotes').slick('getSlick');
	slickBanner.refresh();
}
