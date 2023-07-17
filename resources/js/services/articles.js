export async function getArticles({ fields, language }) {
	try {
		const response = await fetch(`/api/${language}/getArticles`, {
			method: 'POST',
			body: JSON.stringify(fields),
			headers: {
				'Content-Type': 'application/json'
			}
		});

		const responseJson = await response.json();
		return responseJson;

	} catch (error) {
		console.log(error);
	}
}

export async function getOrtsec({ fields }) {
	try {
		const response = await fetch('/api/es/getOrtsec', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(fields),
		});

		const responseJson = await response.json();
		return responseJson;

	} catch (error) {
		console.log(error);
	}
}

export async function getSec({ fields }) {
	try {
		const response = await fetch('/api/es/getSec', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(fields),
		});

		const responseJson = await response.json();
		return responseJson;

	} catch (error) {
		console.log(error);
	}
}

export async function getTallasColores({ fields }) {
	try {
		const response = await fetch('/api/es/getTallasColores', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(fields),
		});

		const responseJson = await response.json();
		return Object.values(responseJson);

	} catch (error) {
		console.log(error);
	}
}

export async function getMarcas({ fields }) {
	try {
		const response = await fetch('/api/es/getMarcas', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(fields),
		});

		const responseJson = await response.json();
		return Object.values(responseJson);

	} catch (error) {
		console.log(error);
	}
}

export async function getFamilias({ fields }) {
	try {
		const response = await fetch('/api/es/getFamilias', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(fields),
		});

		const responseJson = await response.json();
		return responseJson;

	} catch (error) {
		console.log(error);
	}
}
