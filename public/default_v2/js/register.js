document.querySelector('[name="clid_cpostal"]').addEventListener('blur', searchCityForSecondAddress);


function searchCityForSecondAddress(event) {

	const country = document.querySelector('[name="clid_pais"]').value;
	const zip = event.target.value;

	if(!Boolean(country.trim()) || !Boolean(zip.trim())){
		return;
	}

	searchCityByCode(country, zip)
		.then((response) => {
			document.querySelector('[name="clid_poblacion"]').value = response.city;
			document.querySelector('[name="clid_provincia"]').value = response.province;
		})
		.catch((error) => console.log(error));
}

async function searchCityByCode(country, zip) {

	const response = await fetch('/api-ajax/cod-zip', {
		method: 'POST',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json'
		},
		body: JSON.stringify({country, zip})
	});

	if(!response.ok) {
		throw new Error(response.status);
	}

	const responseJson = await response.json();

	if(responseJson.status === 'error'){
		throw new Error(responseJson.status);
	}

	return {
		city: responseJson.pob,
		province: responseJson.des_prv
	};
}


/**
 * Elementos a mostrar y su requerimiento cuando seleccionamos entre empresa o particular
 * @param {HTMLSelectElement} select
 */
function changeRegisterType(select) {
	const isParticular = select.value == 'F';
	document.querySelectorAll('.register-particular').forEach((element) => {
		element.classList.toggle('d-none', !isParticular);
	});
	document.querySelectorAll('.register-empresa').forEach((element) => {
		element.classList.toggle('d-none', isParticular);
	});

	const isRequired = isParticular ? 1 : 0;
	document.querySelector("[name='last_name']").id = `texto__${isRequired}__last_name`;
}

/**
 * Elementos a mostrar y su requerimiento cuando seleccionamos dirección de envio
 * @param {HTMLInputElement} checkElement
 */
function handleCheckedAddressShipping(checkElement) {
	const isChecked = checkElement.checked;
	const shippingAddressBlock = document.getElementById('js-shipping_address');

	shippingAddressBlock.classList.toggle('d-none', isChecked);

	const replaceId = (element) => element.id = isChecked
		? element.id.replace('1', '0')
		: element.id.replace('0', '1');

	shippingAddressBlock.querySelectorAll('input').forEach(replaceId);
	shippingAddressBlock.querySelectorAll('select').forEach(replaceId);

	document.getElementById('clid').value = isChecked ? '0' : '1';
}

