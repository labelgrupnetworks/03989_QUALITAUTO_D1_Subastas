$(document).ready(function () {
	$(document).off("scroll");

	$('.user-account').click(function () {
		$(this).find('.mega-menu').toggle();
	})

	$('#formGridReference').on('input', (event) => {
		validateReference(event.target);
	});

	$('#formGridDescription').on('input', (event) => {
		$('#form_lotlist input[name="description"]').val(event.target.value);
		checkTextFilters();
	});

	$('#formGridSubmit').on('click', function () {
		$('#form_lotlist').submit();
	});

	$('#searchLot').on('submit', (event) => {
		event.preventDefault();

		const reference = $("[name=reference]").val();
		if (!reference) {
			return;
		}

		let action = event.target.action;
		const goTo = action.replace(":ref", reference.trim())
		window.location = goTo;
	});
});

const LabelStyle = {
	//static function
	toggleContainer: function () {
		const containers = document.querySelectorAll('[data-container-style]');
		containers.forEach(container => {
			const isFluid = container.classList.contains('container-fluid');
			if (isFluid) {
				container.classList.remove('container-fluid');
				container.classList.add('container');
			}
			else {
				container.classList.remove('container');
				container.classList.add('container-fluid');
			}
		});
	}
}

function sendInfoLotRequest() {
	$.ajax({
		type: "POST",
		data: $("#infoLotForm").serialize(),
		url: '/api-ajax/ask-info-lot',
		success: function (res) {

			showMessage("¡Gracias! Hemos sido notificados.  ");
			$("input[name=nombre]").val('');
			$("input[name=email]").val('');
			$("input[name=telefono]").val('');
			$("textarea[name=comentario]").val('');

		},
		error: function (e) {
			showMessage("Ha ocurrido un error y no hemos podido ser notificados");
		}
	});
}

function sendInfoLot() {
	validateCaptchaMiddleware(sendInfoLotRequest);
}

function handleSearch() {

	const $searchInput = $('.input-serach-group input');

	if ($searchInput.hasClass('active') && $searchInput.val() == '') {
		hideInputSearch(event);
	} else if ($searchInput.hasClass('active')) {
		$searchInput.closest('form').submit();
	} else {
		showInputSearch();
	}
}

function showInputSearch() {
	const container = document.querySelector('.input-serach-group');
	const input = container.querySelector('input');
	const button = container.querySelector('button');

	button.classList.remove('btn', 'btn-link');
	input.classList.add('active');

	// Añadir el evento al document
	document.addEventListener('mouseup', handleDocumentClick);
}

function handleDocumentClick(e) {
	var container = document.querySelector('.input-serach-group');
	if (!container.contains(e.target)) {
		hideInputSearch();
	}
}

function hideInputSearch() {
	$('.input-serach-group button').addClass('btn btn-link');
	$('.input-serach-group input').removeClass('active');

	document.removeEventListener('mouseup', handleDocumentClick);
}

function toogleFilters() {
	const filtersElement = document.getElementById('js-filters-col');
	changeFilters(filtersElement.classList.contains('d-none'));
}

changeFilters = function (show) {
	const filtersElement = document.getElementById('js-filters-col');
	const lotsElement = document.getElementById('js-lots-col');

	if (window.matchMedia("(max-width: 992px)").matches) {
		toggleOffCanvasFilters();
		return;
	}

	const colsToLotsElement = calculateLotsGridColumns(filtersElement);
	filtersElement.classList.toggle('d-none', !show);
	lotsElement.classList.toggle(`col-lg-${colsToLotsElement}`, show);
	lotsElement.classList.toggle('col-lg-12', !show);
}

function toggleOffCanvasFilters() {
	const bsOffcanvas = bootstrap.Offcanvas.getOrCreateInstance('#js-filters-col');
	bsOffcanvas.toggle();
}

format_date_large = function(fecha) {

	const options = {
		year: 'numeric', month: 'long', day: 'numeric'
	};

	return new Intl.DateTimeFormat('es-ES', options).format(fecha);
}
