
$(function () {
	$('#nfts_table .js-mint-button').on('click', mint);
	$('#nfts_table .js-transfer-button').on('click', transfer);


	getAllStates();
});

function mint(event) {

	const [num, lin] = getRowId(event.target);
	const row = document.querySelector(`#nfts_table tbody tr[data-num="${num}"][data-lin="${lin}"]`);
	const buttonMintElement = row.querySelector(`.js-mint-button`);
	buttonMintElement.setAttribute('disabled', 'disabled');

	iconLoadingButton(buttonMintElement, 'fa-cloud-upload', true);

	fetchinData(`/admin/nfts/mint`, { num, lin }, response => {

		iconLoadingButton(buttonMintElement, 'fa-cloud-upload', false);
		const spanMintElement = row.querySelector(`.mint-result`);
		const isSuccess = response.status == 'success';

		spanMintElement.innerText = response.message;
		spanMintElement.classList.toggle('text-success', isSuccess);
		spanMintElement.classList.toggle('text-danger', !isSuccess);
	});

}

function transfer(event) {

	const [num, lin] = getRowId(event.target);
	const row = document.querySelector(`#nfts_table tbody tr[data-num="${num}"][data-lin="${lin}"]`);
	const buttonTransferElement = row.querySelector(`.js-transfer-button`);

	iconLoadingButton(buttonTransferElement, 'fa-exchange', true);

	fetchinData(`/admin/nfts/transfer`, { num, lin }, response => {

		//iconLoadingButton(buttonTransferElement, 'fa-exchange', false);
		buttonTransferElement.classList.add('hidden');
		const spanTransferElement = row.querySelector(`.transfer-result`);

		const isSuccess = response.status == 'success';

		spanTransferElement.innerText = response.message;
		spanTransferElement.classList.remove('hidden');
		spanTransferElement.classList.toggle('text-success', isSuccess);
		spanTransferElement.classList.toggle('text-success', isSuccess);
	});
}

async function getAllStates() {

	const rows = document.querySelectorAll('#nfts_table tbody tr[data-state=minting]');

	const promises = [...rows].map(row => {
		return new Promise((resolve, reject) => {
			const num = row.dataset.num;
			const lin = row.dataset.lin;
			const url = `/admin/nfts/state`;
			fetch(url, {
				method: 'POST',
				body: JSON.stringify({ num, lin }),
				headers: {
					'Content-Type': 'application/json',
				}
			})
				.then(response => response.json())
				.then(response => {
					resolve(stateResponse(response));
				})
				.catch(error => {
					reject(error);
				});
		})
	});

	const allPromises = await Promise.allSettled(promises);
	document.querySelector('.loader-block')?.classList.add('loading-overlay');
}

/**
 * response: {
    "status": "error",
    "message": "Mint failed: execution reverted: ERC721: That hash is already assigned to other token",
    "data": {
        "num": "8",
        "lin": "3"
    }
}
 */
function stateResponse(response) {

	const {num, lin} = response.data;
	const row = document.querySelector(`#nfts_table tbody tr[data-num="${num}"][data-lin="${lin}"]`);
	const spanMintElement = row.querySelector(`.mint-result`);
	const buttonMintElement = row.querySelector(`.js-mint-button`);
	const buttonTransferElement = row.querySelector(`.js-transfer-button`);

	spanMintElement.innerText = response.message;

	//pendiente
	if (response.status === 'info') {
		spanMintElement.classList.add('text-warning');
		return false;
	}

	//error
	if (response.status == 'error') {
		spanMintElement.classList.add('text-danger');
		buttonMintElement.classList.remove('hidden');
		return false;
	}

	//mintado
	spanMintElement.classList.add('text-success');

	buttonTransferElement.classList.remove('hidden');
	return true;
}

function getRowId(element) {
	const row = element.closest('tr');
	const num = row.dataset.num;
	const lin = row.dataset.lin;
	return [num, lin];
}

function iconLoadingButton(elementButton, originalClass, isLoading) {

	const icon = elementButton.querySelector('i');

	icon.classList.toggle('fa-spinner', isLoading);
	icon.classList.toggle('fa-spin', isLoading);
	icon.classList.toggle('disabled', isLoading);
	icon.classList.toggle(originalClass, !isLoading);
}

function fetchinData(url, data, callback) {
	return fetch(url, {
		method: 'POST',
		body: JSON.stringify(data),
		headers: {
			'Content-Type': 'application/json',
		}
	})
		.then(response => response.json())
		.then(callback);
}


