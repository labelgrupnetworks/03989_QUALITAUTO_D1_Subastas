const homeBannersOptions  = [
	{
		breakpoint: 1200,
		settings: {
			slidesToShow: 4,
			slidesToScroll: 4,
			infinite: true,
			rows: 1,
			slidesPerRow: 4,
		}
	},
	{
		breakpoint: 1024,
		settings: {
			slidesToShow: 3,
			slidesToScroll: 3,
			infinite: true,
			rows: 1,
			slidesPerRow: 3,
		}
	},
	{
		breakpoint: 770,
		settings: {
			slidesToShow: 2,
			slidesToScroll: 2,
			rows: 1,
			slidesPerRow: 2,
			arrows: false,
		}
	},
	{
		breakpoint: 480,
		settings: {
			slidesToShow: 1,
			slidesToScroll: 1,
			rows: 1,
			slidesPerRow: 1,
			arrows: false,
		}
	}
];


$(() => {
	document.querySelector('.search-component .icon')?.addEventListener('click', handleClickSearchComponent);
	document.querySelector('.search-button')?.addEventListener('click', handleClickSearchButton);
	document.querySelector('.search-button span[type="reset"]')?.addEventListener('click', handleClickCloseSearchButton);
});

/**
 * @param {Event} event
 */
function handleClickSearchButton(event) {
	const button = event.currentTarget;
	const isOpen = button.classList.contains('open');
	const inputText = button.querySelector('input');

	if (!isOpen) {
		button.classList.add('open');
		inputText.focus();
		return
	}

	const clickElement = event.target;
	const isIconClick = clickElement.classList.contains('icon');
	if (!isIconClick) return

	const searchValue = inputText.value.trim();

	if (searchValue) {
		const form = button.closest('form');
		form.submit();
		/* const url = new URL(window.location.href);
		url.searchParams.append(inputText.name, searchValue);
		window.location.href = url.href; */
		return
	}

	button.classList.remove('open');
}

function handleClickSearchComponent(event) {
	const searchComponent = document.querySelector('.search-component');
	const isOpen = searchComponent.classList.contains('open');
	searchComponent.classList.toggle('open', !isOpen);

	const searchingWrapper = searchComponent.parentNode;
	const isGallerySearch = searchingWrapper.classList.contains('search-gallery-wrapper');
	if (isGallerySearch) {
		searchingWrapper.classList.toggle('open', !isOpen);
	}

	isOpen && searchComponent.querySelector('input').focus();
}

function handleClickCloseSearchButton(event) {
	event.stopPropagation();
	const button = event.currentTarget.closest('button');
	const inputText = button.querySelector('input');

	button.classList.remove('open');
	inputText.value = "";
	return;
}

function toogleMenu(menuButton) {
	const menuHeader = document.querySelector('.menu-header');
	const isOpen = menuButton.getAttribute("aria-expanded") === "true";
	const logo = document.querySelector(".logo-link img");

	menuButton.setAttribute("aria-expanded", !isOpen);

	menuHeader.classList.remove('open-lg');
	menuHeader.classList.toggle('open', !isOpen);
	logo.classList.toggle('d-none', !isOpen);
}

hideFilters = function(event) {
	event.preventDefault();
	$('.filters-auction-content .form-group').toggleClass('d-none');
}
