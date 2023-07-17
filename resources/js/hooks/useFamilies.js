import { useEffect, useState } from "react";
import { getFamilias } from "../services/articles";

export function useFamilies({ fields }) {

	const [familiasList, setFamiliasList] = useState([]);

	useEffect(() => {
		getFamilias({ fields })
			.then((res) => {
				setFamiliasList(Object.values(res));
			});
	}, [fields.search, fields.ortsec, fields.sec, fields.tallaColor, fields.marca])

	return {
		familiasList
	}
}
