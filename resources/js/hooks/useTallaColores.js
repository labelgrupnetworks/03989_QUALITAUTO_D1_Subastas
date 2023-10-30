import { useEffect, useState } from "react";
import { getTallasColores } from "../services/articles"

export function useTallaColores({ fields }) {
	const [tallasColoresList, setTallasColoresList] = useState([])
	useEffect(() => {
		getTallasColores({ fields, language })
			.then((res) => {
				setTallasColoresList(res);
			})
	}, [fields.search, fields.ortsec, fields.sec, fields.tallaColor, fields.marca, fields.familia])

	return {
		tallasColoresList
	}
}
