import { useEffect, useState } from "react";
import { getMarcas } from "../services/articles.js";

export function useMarcas({ fields }) {

	const [marcasList, setMarcasList] = useState([]);

	useEffect(() => {
		getMarcas({ fields })
			.then((res) => {
				setMarcasList(res);
			})
	}, [fields.search, fields.ortsec, fields.sec, fields.tallaColor, fields.marca, fields.familia])

	return {
		marcasList
	}
}
