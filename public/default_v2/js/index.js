

document.querySelectorAll('.js-auction-files').forEach(el => {
	el.addEventListener('click', showAuctionsFiles);
});

function showAuctionsFiles(event) {

	const modalElement = document.getElementById('documentsModal');
	modalElement.querySelector('.modal-body').innerHTML = '';

	const myModal = new bootstrap.Modal(modalElement);

	const element = event.target.classList.contains('js-auction-files')
		? event.target
		: event.target.parentElement;

	const reference = element.dataset.reference;
	const auction = element.dataset.auction;

	auctionFiles(auction, reference)
		.then(data => {
			console.log(data);
			modalElement.querySelector('.modal-body').innerHTML = data.html;
			myModal.show();
		});
}

async function auctionFiles(auction, reference) {
	const response = await fetch('/api-ajax/sessions/files', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify({ auction, reference })
	});
	return await response.json();
}


// onclick="auctionFiles('001', 'LABELD')"
