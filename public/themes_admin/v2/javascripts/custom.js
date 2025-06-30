$(function () {
	$("#accerder-user-form").on('submit', handleSubmitLoginForm);
});


function LabelUtils() {

	this.numberFormat = number => {

		if (isNaN(number)) return 0;

		return (number !== undefined) ? new Intl.NumberFormat().format(number) : '0';
	}

	this.sumArrayValues = (totalValue, value) => this.intVal(totalValue) + this.intVal(value);

	this.intVal = (i) => {
		const isString = typeof i === 'string';
		if (isString) {
			return i.replace(/[\$,]/g, '') * 1;
		}

		return typeof i === 'number' ? i : 0;
	};

	this.months = {
		1: 'Enero',
		2: 'Febrero',
		3: 'Marzo',
		4: 'Abril',
		5: 'Mayo',
		6: 'Junio',
		7: 'Julio',
		8: 'Agosto',
		9: 'Septiembre',
		10: 'Octubre',
		11: 'Noviembre',
		12: 'Diciembre'
	};
}


function TableConfig() {

	this.addTable = function (tableId, params) {

		document.getElementById(`table_config_${tableId}`).addEventListener('submit', e => this.saveConfig(e, tableId, params));

		/**
		 * Para la version 2
		 */
		document.getElementById(`table_config_${tableId}`).querySelectorAll('input[type=checkbox]').forEach(input => {
			input.addEventListener('change', e => this.changeInputConfig(e, tableId, params));
		})

		let configTable;
		if ((configTable = localStorage.getItem(`table_config_${tableId}`)) != null) {
			this.reloadTable(JSON.parse(configTable));
		}

	}
	/**
	* Para la version 2
	*/
	this.changeInputConfig = function (event, id, params) {
		event.preventDefault();

		let container = event.target.closest('form');
		let eventTable = id;
		let result = {
			"table": eventTable,
			"modal": container.id,
			"columns": {}
		};

		for (const param of params) {

			let checked = $(container).find(`input[name=check_${param}]`).prop('checked');
			result.columns[param] = checked;

			if (checked) {
				$(`#${eventTable} .${param}`).show();
			}
			else {
				$(`#${eventTable} .${param}`).hide();
				$(`input[name=${param}]`).val('');
			}
		}

		localStorage.setItem(container.id, JSON.stringify(result));
	}

	this.saveConfig = function (event, id, params) {

		event.preventDefault();
		let eventModal = event.target.id || event.target.closest('form').id;
		let eventTable = id;
		let result = {
			"table": eventTable,
			"modal": eventModal,
			"columns": {}
		};

		for (const param of params) {

			let checked = $(event.target).find(`input[name=check_${param}]`).prop('checked');
			result.columns[param] = checked;

			if (checked) {
				$(`#${eventTable} .${param}`).show();
			}
			else {
				$(`#${eventTable} .${param}`).hide();
				$(`input[name=${param}]`).val('');
			}
		}

		localStorage.setItem(eventModal, JSON.stringify(result));
	}

	this.reloadTable = function (parameters) {

		for (const key in parameters.columns) {
			if (parameters.columns[key]) {

				$(`#${parameters.table} .${key}`).show();
				$(`#${parameters.modal}`).find(`input[name=check_${key}]`).prop('checked', true);

			}
			else {

				$(`#${parameters.table} .${key}`).hide();
				$(`#${parameters.modal}`).find(`input[name=check_${key}]`).prop('checked', false);

			}
		}
	}

	this.orderTable = function (e) {

		if (!e.target.dataset.order) {
			return;
		}

		let orderName = e.target.closest('table').dataset.orderName;

		let url = new URL(window.location.href);
		url.searchParams.set(orderName, e.target.dataset.order);
		url.searchParams.set(`${orderName}_dir`, 'asc');

		if (e.target.querySelector('i.fa-arrow-up') !== null) {
			url.searchParams.set(`${orderName}_dir`, 'desc');
		}
		window.location.href = url;

	}

}

const tableConfig = new TableConfig();


/**
 * @param {Event} event
 */
function handleSubmitLoginForm(event) {
	event.preventDefault();

	const button = document.getElementById('accerder-user');
	if (button) {
		button.classList.toggle('loading', true);
	}

	showSpinner(true)

	$.ajax({
		type: "POST",
		url: '/login_post_ajax',
		data: $('#accerder-user-form').serialize(),
		success: successLoginForm,
		complete: () => {
			if (button) {
				button.classList.toggle('loading', false);
			}
			showSpinner(false)
		}
	});
}

function showSpinner(visible = true) {
	const spinner = document.querySelector('.spinner-border');
	if (spinner) {
		if (visible) {
			spinner.classList.remove('d-none');
		} else {
			spinner.classList.add('d-none');
		}
	}
}

function successLoginForm(response) {

	if (response.status == 'success') {
		window.location.href = response.redirect;
	} else {
		$(".message-error-log").removeClass('d-none').text('').append("Correo o contraseña incorrectos");

	}
}
