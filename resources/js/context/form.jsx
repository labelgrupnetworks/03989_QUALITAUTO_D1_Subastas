import { createContext, useEffect, useRef, useState } from 'react'

export const FormContext = createContext(null)

export const FormProvider = ({ children, initialState }) => {

	const [formFields, setFormFields] = useState(initialState);
	const isFirstSearch = useRef(true);

	const updateUrl = (fields) => {
		//construir la url con las keys de los fields utilizando url.searchParams
		const url = new URL(window.location.href);
		const searchParams = new URLSearchParams(url.search);
		Object.keys(fields).forEach(function (key) {
			searchParams.set(key, fields[key]);
		});

		//Si es la primera vez que se realiza la búsqueda, se debe añadir la url actual a la url de la página de artículos.
		const path = isFirstSearch.current ? url.origin + url.pathname : urlArticulos;
		history.pushState(null, "", path + '?' + searchParams.toString());
		isFirstSearch.current = false;
	}

	useEffect(() => {
		updateUrl(formFields);
	}, [formFields])

	return (
		<FormContext.Provider value={{ formFields, setFormFields }}>
			{children}
		</FormContext.Provider>
	)
}
