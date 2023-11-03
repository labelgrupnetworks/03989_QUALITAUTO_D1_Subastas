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

export async function getOrtsec({ fields, language }) {
	try {
		const response = await fetch(`/api/${language}/getOrtsec`, {
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

export async function getSec({ fields, language }) {
	try {
		const response = await fetch(`/api/${language}/getSec`, {
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

export async function getTallasColores({ fields, language }) {
	try {
		const response = await fetch(`/api/${language}/getTallasColores`, {
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

export async function getMarcas({ fields, language }) {
	try {
		const response = await fetch(`/api/${language}/getMarcas`, {
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

export async function getFamilias({ fields, language }) {
	try {
		const response = await fetch(`/api/${language}/getFamilias`, {
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
