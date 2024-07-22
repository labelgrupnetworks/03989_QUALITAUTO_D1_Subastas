const $divLog = $('#div_log');
const routes = {
	'getLots': '/admin/thumbs/lots',
	'generateThumbs': '/admin/thumbs/generate',
};

let lots = [];

$('form[name="search_lots"]').submit(function (e) {
	e.preventDefault();
	$divLog.html('');
	resetProgressBar();

	const size = $('[name="size"]').val();

	getLots(this).then(response => {
		let message =
			`<p>Se han encontrado ${response.count_images} imagenes de ${response.count_lots} lotes</p>`;
		message +=
			`<p><button class="btn btn-xs btn-success" onclick="generateThumbs(${size})">Generar Miniaturas</button></p>`;
		lots = response.lots;
		$divLog.append(message);
	})
		.catch(error => {
			addLog(error.message, 'text-danger');
		});
});

async function generateThumbs(size) {
	const batchSize = 5; // Tamaño del grupo de peticiones
	const total = lots.length; // Total de lotes
	let completed = 0; // Contador de lotes completados
	resetProgressBar();

	// Divide los lotes en grupos de tamaño batchSize
	const batches = createBatches(lots, batchSize);

	for (const batch of batches) {
		try {
			await processBatch(batch, size);
			completed += batch.length;
			updateProgressBar(completed, total);
		} catch (error) {
			addLog(error.message, 'text-danger');
		}
	}

	addLog('Todas las miniaturas se han generado.');
}

/**
 * Divide un array en lotes de tamaño
 * @param {array} array
 * @param {number} batchSize
 * @returns
 */
function createBatches(array, batchSize) {
	return Array.from({ length: Math.ceil(array.length / batchSize) }, (_, i) =>
		array.slice(i * batchSize, i * batchSize + batchSize)
	);
}

/**
 * Procesa un lote de lotes y genera las miniaturas de cada uno de ellos con el tamaño especificado
 * @param {array} batch
 * @param {string} size
 */
async function processBatch(batch, size) {
	const promises = batch.map(lot => {
		addLog(`Generando miniaturas para el lote numero ${lot.num_hces1} línea ${lot.lin_hces1}`);
		return generateThumb(lot, size);
	});

	const responses = await Promise.all(promises);
	responses.forEach(response => {
		if (response) {
			addLog(response.message, 'text-success');
		}
	});
}

/**
 * Obtiene los lotes que cumplen con los criterios de búsqueda
 * @param {HTMLFormElement} form
 * @throws Error si la petición no es exitosa
 * @returns {Promise}
 */
async function getLots(form) {
	const response = await fetch(routes.getLots, {
		method: 'POST',
		body: new FormData(form)
	});

	if (!response.ok) {
		throw new Error('Error, no se han encontrado lotes');
	}

	return await response.json();
}

/**
 * Genera las miniaturas de un lote con el tamaño especificado
 * @param {object} lot
 * @param {string} size
 * @throws Error si la petición no es exitosa
 * @returns {Promise}
 */
async function generateThumb(lot, size) {
	try {
		const response = await fetch(routes.generateThumbs, {
			method: 'POST',
			body: JSON.stringify({
				numhces: lot.num_hces1,
				linhces: lot.lin_hces1,
				size: size
			}),
			headers: {
				'Content-Type': 'application/json'
			}
		});

		if (!response.ok) {
			throw new Error(
				`Error al generar las miniaturas del lote número ${lot.num_hces1} y línea ${lot.lin_hces1}`);
		}

		return await response.json();
	} catch (error) {
		console.error('Error:', error);
		addLog(error.message, 'text-danger');
	}
}

function addLog(message, className = '') {
	$divLog.append(`<p class="${className}">${message}</p>`);
	$divLog.scrollTop($divLog[0].scrollHeight);
}

function resetProgressBar() {
	$('#progressBarValue').text(`0%`);
	$('#progressBarImg').css('width', `0%`);
}

function updateProgressBar(completed, total) {
	const percent = Math.round((completed / total) * 100);
	$('#progressBarValue').text(`${percent}%`);
	$('#progressBarImg').css('width', `${percent}%`);
}
